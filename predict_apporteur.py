import pandas as pd
from sklearn.model_selection import train_test_split
from sklearn.linear_model import LogisticRegression
from sklearn.metrics import accuracy_score, classification_report, confusion_matrix
import pickle
import matplotlib.pyplot as plt
import logging

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
    logging.info("Starting the script to analyze 'etat' (valide/invalide).")

    # Chargement des données
    logging.info("Loading data from 'apporteurs_data.csv'.")
    data = pd.read_csv('apporteurs_data.csv')
    logging.info("Data loaded successfully.")

    # Analyser les colonnes disponibles
    logging.info(f"Columns available: {list(data.columns)}")

    # Sélection des features
    # On suppose que ces 3 colonnes restent utiles pour prédire l'état
    logging.info("Preparing data for classification.")
    X = data[['total_commission', 'opportunities_validated', 'avg_opportunity_duration']]

    # Cible : on prédit l'état valide/invalide
    # On suppose que la colonne 'etat' contient des valeurs "valide" ou "invalide"
    if 'etat' not in data.columns:
        raise ValueError("La colonne 'etat' n'existe pas dans le dataset.")

    # Conversion texte -> numérique : valide = 1, invalide = 0
    data['etat_numeric'] = data['etat'].apply(lambda x: 1 if x.strip().lower() == 'valide' else 0)
    y = data['etat_numeric']

    logging.info("Features: total_commission, opportunities_validated, avg_opportunity_duration")
    logging.info("Target: etat (valide/invalide)")

    # Division en sets d'entraînement et de test
    logging.info("Splitting data into training and test sets.")
    X_train, X_test, y_train, y_test = train_test_split(X, y, test_size=0.2, random_state=42)
    logging.info(f"Data split complete: {len(X_train)} training samples and {len(X_test)} test samples.")

    # Modèle de régression logistique pour classification
    logging.info("Training the Logistic Regression model.")
    model = LogisticRegression(max_iter=1000)
    model.fit(X_train, y_train)
    logging.info("Model trained successfully.")

    # Prédictions sur le test set
    logging.info("Making predictions on the test set.")
    y_pred = model.predict(X_test)
    logging.info("Predictions complete.")

    # Évaluer le modèle par l'accuracy
    logging.info("Evaluating model performance.")
    accuracy = accuracy_score(y_test, y_pred)
    logging.info(f"Model accuracy: {accuracy:.2f}")

    # Afficher un rapport de classification
    logging.info("Classification report:")
    logging.info("\n" + classification_report(y_test, y_pred))

    # Matrice de confusion pour visualiser le résultat
    cm = confusion_matrix(y_test, y_pred)
    logging.info(f"Confusion Matrix:\n{cm}")

    # Sauvegarder le modèle entraîné
    logging.info("Saving the trained model.")
    with open('classification_model.pkl', 'wb') as file:
        pickle.dump(model, file)
    logging.info("Model saved successfully as 'classification_model.pkl'.")

    # Optionnel : Visualisation simple (par exemple bar chart du cm)
    # On peut afficher la matrice de confusion à l'aide de matplotlib
    fig, ax = plt.subplots()
    cax = ax.matshow(cm, cmap='Blues')
    plt.title('Confusion Matrix')
    plt.colorbar(cax)
    plt.xlabel('Predicted')
    plt.ylabel('Actual')
    for (i, j), z in np.ndenumerate(cm):
        ax.text(j, i, str(z), ha='center', va='center')
    plt.savefig('confusion_matrix.png')
    plt.show()
    logging.info("Confusion matrix saved as 'confusion_matrix.png'.")

    logging.info("Script completed successfully.")

except Exception as e:
    logging.error(f"An error occurred: {str(e)}")
