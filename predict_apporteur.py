import pandas as pd
import matplotlib.pyplot as plt
from sklearn.model_selection import train_test_split
from sklearn.linear_model import LogisticRegression
from sklearn.preprocessing import LabelEncoder
from sklearn.metrics import accuracy_score, classification_report
import logging

# Configure logging
logging.basicConfig(level=logging.INFO)

# Load the data
data = pd.read_csv('storage/app/opportunities_data.csv')

# Debug: Print initial data
logging.info(f"Initial data:\n{data.head()}")

# Map 'etat' to numeric: "valide" → 20, "en cours" → 6, "non valide" → 0
etat_mapping = {
    'valide': 20,         # Highest weight
    'en cours': 6,        # Moderate weight
    'non valide': 0       # Neutral weight
}
data['etat_numeric'] = data['etat'].apply(
    lambda x: etat_mapping.get(x.strip().lower(), None) if isinstance(x, str) else None
)

try:
    # Debug: Check mapped values
    logging.info(f"Mapped etat values:\n{data[['etat', 'etat_numeric']].drop_duplicates()}")

    # Handle missing values for commissions
    data['commissions'] = pd.to_numeric(data['commissions'], errors='coerce').fillna(0.0)

    # Convert created_at and date_validation to datetime
    data['created_at'] = pd.to_datetime(data['created_at'], errors='coerce')
    data['date_validation'] = pd.to_datetime(data['date_validation'], errors='coerce')

    # Remove timezone information
    data['created_at'] = data['created_at'].dt.tz_localize(None)
    data['date_validation'] = data['date_validation'].dt.tz_localize(None)

    # Compute duration in days
    data['duration_days'] = (data['date_validation'] - data['created_at']).dt.total_seconds() / (3600 * 24)
    data['duration_days'] = data['duration_days'].fillna(0.0)

    # Encode user_id as a numeric feature
    user_encoder = LabelEncoder()
    data['user_id_encoded'] = user_encoder.fit_transform(data['user_id'])

    # Features and target
    X = data[['commissions', 'duration_days', 'user_id_encoded']]
    y = data['etat_numeric']

    # Debug: Check features and target distribution
    logging.info(f"Features sample:\n{X.head()}")
    logging.info(f"Target distribution:\n{y.value_counts()}")

    # Drop rows with missing target values
    data = data.dropna(subset=['etat_numeric'])
    X = X.loc[data.index]
    y = y.loc[data.index]

    # Split data into training and test sets
    X_train, X_test, y_train, y_test = train_test_split(X, y, test_size=0.2, random_state=42)

    # Train Logistic Regression with class balance
    model = LogisticRegression(max_iter=1000, multi_class='multinomial', solver='lbfgs', class_weight='balanced')
    model.fit(X_train, y_train)

    # Evaluate the model
    y_pred = model.predict(X_test)
    accuracy = accuracy_score(y_test, y_pred)
    logging.info(f"Model trained successfully. Accuracy: {accuracy:.2f}")
    logging.info("Classification Report:")
    logging.info("\n" + classification_report(y_test, y_pred))

    # Create simulated 2024 data based on 2024 patterns
    data_2024 = data.copy()
    data_2024['created_at'] = pd.to_datetime("2024-01-01")  # Start of 2024
    data_2024['duration_days'] = (pd.to_datetime("2024-12-31") - data_2024['created_at']).dt.total_seconds() / (3600 * 24)
    X_2024 = data_2024[['commissions', 'duration_days', 'user_id_encoded']]

    # Predict outcomes for 2024
    data_2024['predicted_etat'] = model.predict(X_2024)

    # Aggregate 2024 predictions
    predictions_2024 = data_2024.groupby('user_id').agg(
        total_opportunities=('predicted_etat', 'count'),
        validated_count=('predicted_etat', lambda x: (x == 20).sum())
    )
    predictions_2024['validation_percentage'] = (
        predictions_2024['validated_count'] / predictions_2024['total_opportunities'] * 100
    )

    # Debug: Check predictions for 2024
    logging.info(f"Predictions for 2024:\n{predictions_2024}")

    # Visualization: Merged chart with two subplots
    fig, axes = plt.subplots(2, 1, figsize=(12, 10))
    
    # Subplot 1: Number of valid opportunities
    axes[0].bar(
        predictions_2024.index.astype(str),
        predictions_2024['validated_count'],
        color='skyblue'
    )
    axes[0].set_xlabel('User ID')
    axes[0].set_ylabel('Number of Validated Opportunities')
    axes[0].set_title('Predicted Validated Opportunities by User for 2024')
    axes[0].tick_params(axis='x', rotation=45)

    # Subplot 2: Percentage of valid opportunities
    axes[1].bar(
        predictions_2024.index.astype(str),
        predictions_2024['validation_percentage'],
        color='skyblue'
    )
    axes[1].set_xlabel('User ID')
    axes[1].set_ylabel('Validation Percentage (%)')
    axes[1].set_title('Predicted Validation Percentage by User for 2024')
    axes[1].set_ylim(0, 100)
    axes[1].tick_params(axis='x', rotation=45)

    # Adjust layout and save
    plt.tight_layout()
    plt.savefig('predicted_opportunities_2024.png', dpi=300)
    logging.info("Merged chart saved as 'predicted_opportunities_2024.png'.")
    plt.show()

except Exception as e:
    logging.error(f"An error occurred: {str(e)}")
