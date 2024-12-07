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

# Map 'etat' to numeric: "valide" → 1, "en cours" → 0, "non valide" → -1
etat_mapping = {
    'valide': 1,
    'en cours': 0,
    'non valide': -1
}
data['etat_numeric'] = data['etat'].apply(
    lambda x: etat_mapping[x.strip().lower()] if isinstance(x, str) else None
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

    # Predictions
    data['validation_probability'] = model.predict_proba(X)[:, 1]  # Probability of 'valide'

    # Aggregate predictions by user_id
    predictions_by_user = data.groupby('user_id').agg(
        total_opportunities=('etat_numeric', 'count'),
        predicted_validated=('validation_probability', 'sum'),
    )
    predictions_by_user['validation_percentage'] = (
        predictions_by_user['predicted_validated'] / predictions_by_user['total_opportunities'] * 100
    )
    predictions_by_user = predictions_by_user.sort_values(by='validation_percentage', ascending=False)

    # Debug: Check predictions
    logging.info(f"Predictions by user:\n{predictions_by_user}")

    # Visualization: Bar chart of validation percentages by user_id
    plt.figure(figsize=(12, 6))
    plt.bar(
        predictions_by_user.index.astype(str),  # Convert user_id to string explicitly
        predictions_by_user['validation_percentage'],
        color='skyblue'
    )
    plt.xlabel('User ID')
    plt.ylabel('Validation Percentage (%)')
    plt.title('Predicted Validation Percentage by User ID')
    plt.xticks(rotation=45)
    plt.ylim(0, 100)
    plt.tight_layout()

    # Save the plot to PNG
    plt.savefig('validation_percentage_by_user.png', dpi=300)
    logging.info("Bar chart saved as 'validation_percentage_by_user.png'.")
    plt.show()
    
except Exception as e:
    logging.error(f"An error occurred: {str(e)}")