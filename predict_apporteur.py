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

# Map 'etat' to numeric: "valide" → 10, "en cours" → 2, "non valide" → -5
etat_mapping = {
    'valide': 5,         # Highest weight
    'en cours': 2,        # Moderate weight
    'non valide': 0      # Penalizing weight
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

    # Aggregate predictions by user_id
    predictions_by_user = data.groupby('user_id').agg(
        total_opportunities=('etat_numeric', 'count'),
        validated_count=('etat_numeric', lambda x: (x == 5).sum()),  # Count of "valide"
        en_cours_count=('etat_numeric', lambda x: (x == 2).sum()),    # Count of "en cours"
        non_valide_count=('etat_numeric', lambda x: (x == 0).sum())  # Count of "non valide"
    )

    # Introduce scoring logic with strong weights/penalties
    predictions_by_user['adjusted_score'] = (
        5 * predictions_by_user['validated_count'] +     # Weight for "valide"
        2 * predictions_by_user['en_cours_count'] +       # Weight for "en cours"
        0 * predictions_by_user['non_valide_count']      # Penalty for "non valide"
    )

    # Calculate validation percentage based on adjusted score
    predictions_by_user['validation_percentage'] = (
        predictions_by_user['adjusted_score'] /
        (predictions_by_user['total_opportunities'] * 10) * 100  # Normalize by maximum possible score
    )

    # Debug: Check predictions
    logging.info(f"Predictions by user with adjusted scores:\n{predictions_by_user}")

    # Sort predictions_by_user by validation percentage in descending order
    predictions_by_user = predictions_by_user.sort_values(by='validation_percentage', ascending=False)

    # Visualization: Bar chart of adjusted validation percentages by user_id (sorted)
    plt.figure(figsize=(12, 6))
    plt.bar(
        predictions_by_user.index.astype(str),  # Convert user_id to string explicitly
        predictions_by_user['validation_percentage'],
        color='skyblue'
    )
    plt.xlabel('User ID')
    plt.ylabel('Validation Percentage (%)')
    plt.title('Adjusted Validation Percentage by User ID (Ordered)')
    plt.xticks(rotation=45)
    plt.ylim(0, 100)
    plt.tight_layout()

    # Save the sorted plot to PNG
    plt.savefig('adjusted_validation_percentage_by_user_ordered.png', dpi=300)
    logging.info("Bar chart saved as 'adjusted_validation_percentage_by_user_ordered.png'.")
    plt.show()

except Exception as e:
    logging.error(f"An error occurred: {str(e)}")
