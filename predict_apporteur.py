import pandas as pd
from sklearn.model_selection import train_test_split
from sklearn.linear_model import LinearRegression
from sklearn.metrics import mean_squared_error
import pickle
import matplotlib.pyplot as plt
import logging

# Configure logging
logging.basicConfig(
    filename='predict_apporteur.log',
    level=logging.INFO,
    format='%(asctime)s - %(levelname)s - %(message)s'
)

try:
    # Log the start of the script
    logging.info("Script started")

    # Charger les données depuis le fichier CSV
    logging.info("Loading data from 'apporteurs_data.csv'")
    data = pd.read_csv('apporteurs_data.csv')
    logging.info("Data loaded successfully. First few rows:")
    logging.info("\n%s", data.head())

    # Examiner les premières lignes pour comprendre les données
    logging.info("Examining data columns")
    logging.info("Columns found: %s", list(data.columns))

    # Sélectionner les colonnes pertinentes pour la régression
    logging.info("Selecting relevant columns for regression")
    X = data[['total_commission', 'opportunities_validated', 'avg_opportunity_duration']]
    y = data['performance']
    logging.info("Features selected: %s", list(X.columns))
    logging.info("Target selected: 'performance'")

    # Diviser les données en ensemble d'entraînement et de test
    logging.info("Splitting data into training and testing sets")
    X_train, X_test, y_train, y_test = train_test_split(X, y, test_size=0.2, random_state=42)
    logging.info("Data split completed: %d training samples, %d testing samples", len(X_train), len(X_test))

    # Créer et entraîner le modèle de régression linéaire
    logging.info("Creating and training the Linear Regression model")
    model = LinearRegression()
    model.fit(X_train, y_train)
    logging.info("Model trained successfully")

    # Faire des prédictions
    logging.info("Making predictions on the test data")
    y_pred = model.predict(X_test)
    logging.info("Predictions completed")

    # Calculer l'erreur quadratique moyenne (MSE)
    logging.info("Calculating Mean Squared Error (MSE)")
    mse = mean_squared_error(y_test, y_pred)
    logging.info("Mean Squared Error: %f", mse)

    # Sauvegarder le modèle pour une utilisation future
    logging.info("Saving the trained model to 'regression_model.pkl'")
    with open('regression_model.pkl', 'wb') as file:
        pickle.dump(model, file)
    logging.info("Model saved successfully")

    # Visualisation des résultats
    logging.info("Generating scatter plot for predictions vs reality")
    plt.scatter(y_test, y_pred)
    plt.xlabel('Valeurs réelles')
    plt.ylabel('Prédictions')
    plt.title('Prédictions vs Réalité')
    plt.savefig('predictions_vs_reality.png')  # Save the plot as a file
    logging.info("Scatter plot saved as 'predictions_vs_reality.png'")
    plt.show()

    # Log the end of the script
    logging.info("Script finished successfully")

except Exception as e:
    logging.error("An error occurred: %s", e, exc_info=True)
