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

# Friendly output for evaluation
try:
    logging.info("Starting the script to analyze 'etat' (valide, invalide, en cours).")

    # Load the data
    logging.info("Loading data from 'opportunities_data.csv'.")
    data = pd.read_csv('storage/app/opportunities_data.csv')
    logging.info("Data loaded successfully.")
    logging.info(f"Columns available: {list(data.columns)}")

    # Check for 'etat' column
    if 'etat' not in data.columns:
        raise ValueError("La colonne 'etat' n'existe pas dans le dataset.")

    # Map 'etat' to numeric: "en cours" → 0, "invalide" → -1, "valide" → 1
    etat_mapping = {
        'en cours': 0,
        'invalide': -1,
        'valide': 1
    }
    data['etat_numeric'] = data['etat'].apply(
        lambda x: etat_mapping[x.strip().lower()] if isinstance(x, str) else 0
    )

    # Handle commissions: if empty, set to 0
    data['commissions'] = pd.to_numeric(data['commissions'], errors='coerce').fillna(0.0)

    # Convert created_at and date_validation to datetime
    data['created_at'] = pd.to_datetime(data['created_at'], errors='coerce')
    data['date_validation'] = pd.to_datetime(data['date_validation'], errors='coerce')

    # Remove timezone information (if any)
    data['created_at'] = data['created_at'].dt.tz_localize(None)
    data['date_validation'] = data['date_validation'].dt.tz_localize(None)

    # Compute duration in days between created_at and date_validation
    data['duration_days'] = (data['date_validation'] - data['created_at']).dt.total_seconds() / (3600 * 24)
    data['duration_days'] = data['duration_days'].fillna(0.0)

    # Features: commissions and duration_days
    X = data[['commissions', 'duration_days']]
    y = data['etat_numeric']

    logging.info("Features used: commissions, duration_days")
    logging.info("Target: etat_numeric ('en cours'=0, 'invalide'=-1, 'valide'=1)")

    # Split data
    logging.info("Splitting data into training and test sets.")
    X_train, X_test, y_train, y_test = train_test_split(X, y, test_size=0.2, random_state=42)
    logging.info(f"Data split complete: {len(X_train)} training samples and {len(X_test)} test samples.")

    # Train Logistic Regression
    logging.info("Training the Logistic Regression model.")
    model = LogisticRegression(max_iter=1000, multi_class='multinomial', solver='lbfgs')
    model.fit(X_train, y_train)
    logging.info("Model trained successfully.")

    # Predictions
    logging.info("Making predictions on the test set.")
    y_pred = model.predict(X_test)
    logging.info("Predictions complete.")

    # Evaluate model
    accuracy = accuracy_score(y_test, y_pred)
    logging.info(f"Model accuracy: {accuracy:.2f}")

    # Generate classification report
    class_names = ["En cours (0)", "Invalide (-1)", "Valide (1)"]
    report = classification_report(y_test, y_pred, target_names=class_names, output_dict=True)
    logging.info("Classification report generated.")

    # Confusion Matrix
    cm = confusion_matrix(y_test, y_pred)
    logging.info(f"Confusion Matrix:\n{cm}")

    # Friendly Summary
    summary = f"""
    -------- Model Evaluation --------
    Accuracy: {accuracy:.2f}

    Classification Report:
    - En cours (0): Precision={report['En cours (0)']['precision']:.2f}, Recall={report['En cours (0)']['recall']:.2f}, F1 Score={report['En cours (0)']['f1-score']:.2f}
    - Invalide (-1): Precision={report['Invalide (-1)']['precision']:.2f}, Recall={report['Invalide (-1)']['recall']:.2f}, F1 Score={report['Invalide (-1)']['f1-score']:.2f}
    - Valide (1): Precision={report['Valide (1)']['precision']:.2f}, Recall={report['Valide (1)']['recall']:.2f}, F1 Score={report['Valide (1)']['f1-score']:.2f}
    
    Confusion Matrix:
            Predicted ->  En cours (0)   Invalide (-1)   Valide (1)
    Actual
    En cours (0)       {cm[0, 0]}             {cm[0, 1]}             {cm[0, 2]}
    Invalide (-1)      {cm[1, 0]}             {cm[1, 1]}             {cm[1, 2]}
    Valide (1)         {cm[2, 0]}             {cm[2, 1]}             {cm[2, 2]}
    """
    print(summary)

    # Save to a text file for user reference
    with open('model_evaluation_summary.txt', 'w') as f:
        f.write(summary)
    logging.info("Model evaluation summary saved as 'model_evaluation_summary.txt'.")

    # Save confusion matrix image
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

except Exception as e:
    logging.error(f"An error occurred: {str(e)}")
