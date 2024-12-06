import pandas as pd
from sklearn.model_selection import train_test_split
from sklearn.linear_model import LogisticRegression
from sklearn.metrics import accuracy_score, classification_report, confusion_matrix
import pickle
import matplotlib.pyplot as plt
import logging
import numpy as np

# Configure logging
logging.basicConfig(
    level=logging.INFO,
    format='%(message)s',
    handlers=[
        logging.FileHandler('predict_apporteur.log'),
        logging.StreamHandler()
    ]
)

try:
    logging.info("Starting the script to analyze 'etat' (valide / non-valide).")

    # Load the data
    logging.info("Loading data from 'opportunities_data.csv'.")
    data = pd.read_csv('storage/app/opportunities_data.csv')
    logging.info("Data loaded successfully.")
    logging.info(f"Columns available: {list(data.columns)}")

    # Check for 'etat' column
    if 'etat' not in data.columns:
        raise ValueError("La colonne 'etat' n'existe pas dans le dataset.")

    # Convert etat to numeric: valide = 1, else = 0
    data['etat_numeric'] = data['etat'].apply(lambda x: 1 if (isinstance(x, str) and x.strip().lower() == 'valide') else 0)

    # Handle commissions: if empty, set to 0
    data['commissions'] = pd.to_numeric(data['commissions'], errors='coerce').fillna(0.0)

    # Convert created_at and date_validation to datetime
    data['created_at'] = pd.to_datetime(data['created_at'], errors='coerce')
    data['date_validation'] = pd.to_datetime(data['date_validation'], errors='coerce')

    # Remove timezone information (if any) to avoid tz-naive and tz-aware subtraction issues
    data['created_at'] = data['created_at'].dt.tz_localize(None)
    data['date_validation'] = data['date_validation'].dt.tz_localize(None)

    # Compute duration in days between created_at and date_validation
    # If date_validation is NaN, we set duration to 0
    data['duration_days'] = (data['date_validation'] - data['created_at']).dt.total_seconds() / (3600 * 24)
    data['duration_days'] = data['duration_days'].fillna(0.0)

    # Features: commissions and duration_days
    # In this dataset we have individual opportunities, so these features help guess if it's valide or not
    X = data[['commissions', 'duration_days']]
    y = data['etat_numeric']

    logging.info("Features used: commissions, duration_days")
    logging.info("Target: etat_numeric (valide=1, else=0)")

    # Split data
    logging.info("Splitting data into training and test sets.")
    X_train, X_test, y_train, y_test = train_test_split(X, y, test_size=0.2, random_state=42)
    logging.info(f"Data split complete: {len(X_train)} training samples and {len(X_test)} test samples.")

    # Train Logistic Regression
    logging.info("Training the Logistic Regression model.")
    model = LogisticRegression(max_iter=1000)
    model.fit(X_train, y_train)
    logging.info("Model trained successfully.")

    # Predictions
    logging.info("Making predictions on the test set.")
    y_pred = model.predict(X_test)
    logging.info("Predictions complete.")

    # Evaluate model
    logging.info("Evaluating model performance.")
    accuracy = accuracy_score(y_test, y_pred)
    logging.info(f"Model accuracy: {accuracy:.2f}")

    logging.info("Classification report:")
    logging.info("\n" + classification_report(y_test, y_pred))

    cm = confusion_matrix(y_test, y_pred)
    logging.info(f"Confusion Matrix:\n{cm}")

    # Save model
    logging.info("Saving the trained model.")
    with open('classification_model.pkl', 'wb') as file:
        pickle.dump(model, file)
    logging.info("Model saved successfully as 'classification_model.pkl'.")

    # Visualize confusion matrix
    fig, ax = plt.subplots()
    cax = ax.matshow(cm, cmap='Blues')
    plt.title('Confusion Matrix')
    plt.colorbar(cax)
    plt.xlabel('Predicted')
    plt.ylabel('Actual')

    # Add counts to each cell
    for (i, j), z in np.ndenumerate(cm):
        ax.text(j, i, str(z), ha='center', va='center')

    plt.savefig('confusion_matrix.png')
    plt.show()
    logging.info("Confusion matrix saved as 'confusion_matrix.png'.")

    logging.info("Script completed successfully.")

except Exception as e:
    logging.error(f"An error occurred: {str(e)}")
