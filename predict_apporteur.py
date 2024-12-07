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

    # Create simulated 2025 data based on 2024 patterns
    data_2025 = data.copy()
    data_2025['created_at'] = pd.to_datetime("2025-01-01")  # Start of 2025
    data_2025['duration_days'] = (pd.to_datetime("2025-12-31") - data_2025['created_at']).dt.total_seconds() / (3600 * 24)
    X_2025 = data_2025[['commissions', 'duration_days', 'user_id_encoded']]

    # Predict outcomes for 2025
    data_2025['predicted_etat'] = model.predict(X_2025)

    # Aggregate 2025 predictions
    predictions_2025 = data_2025.groupby('user_id').agg(
        total_opportunities=('predicted_etat', 'count'),
        validated_count=('predicted_etat', lambda x: (x == 20).sum())
    )
    predictions_2025['validation_percentage'] = (
        predictions_2025['validated_count'] / predictions_2025['total_opportunities'] * 100
    )

    # Debug: Check predictions for 2025
    logging.info(f"Predictions for 2025:\n{predictions_2025}")

    # Visualization: Number of valid opportunities in 2025
    plt.figure(figsize=(12, 6))
    plt.bar(
        predictions_2025.index.astype(str),  # Convert user_id to string explicitly
        predictions_2025['validated_count'],
        color='skyblue'
    )
    plt.xlabel('User ID')
    plt.ylabel('Number of Validated Opportunities')
    plt.title('Predicted Validated Opportunities by User for 2025')
    plt.xticks(rotation=45)
    plt.tight_layout()

    # Save the chart
    plt.savefig('predicted_valid_opportunities_2025.png', dpi=300)
    logging.info("Bar chart for valid opportunities saved as 'predicted_valid_opportunities_2025.png'.")
    plt.show()

    # Visualization: Percentage of validated opportunities in 2025
    plt.figure(figsize=(12, 6))
    plt.bar(
        predictions_2025.index.astype(str),  # Convert user_id to string explicitly
        predictions_2025['validation_percentage'],
        color='skyblue'
    )
    plt.xlabel('User ID')
    plt.ylabel('Validation Percentage (%)')
    plt.title('Predicted Validation Percentage by User for 2025')
    plt.xticks(rotation=45)
    plt.ylim(0, 100)
    plt.tight_layout()

    # Save the chart
    plt.savefig('predicted_validation_percentage_2025.png', dpi=300)
    logging.info("Bar chart for validation percentage saved as 'predicted_validation_percentage_2025.png'.")
    plt.show()

except Exception as e:
    logging.error(f"An error occurred: {str(e)}")
