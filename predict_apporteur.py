import pandas as pd
from sklearn.model_selection import train_test_split
from sklearn.linear_model import LinearRegression
from sklearn.metrics import mean_squared_error
import pickle
import matplotlib.pyplot as plt

# # Charger les données depuis le fichier CSV
# data = pd.read_csv('apporteurs_data.csv')

# # Examiner les premières lignes pour comprendre les données
# print(data.head())

# # Sélectionner les colonnes pertinentes pour la régression
# X = data[['total_commission', 'opportunities_validated', 'avg_opportunity_duration']]  # Variables explicatives
# y = data['performance']  # Cible : La performance des apporteurs d'affaires (ex : score ou classement)

# # Diviser les données en ensemble d'entraînement et de test
# X_train, X_test, y_train, y_test = train_test_split(X, y, test_size=0.2, random_state=42)

# # Créer et entraîner le modèle de régression linéaire
# model = LinearRegression()
# model.fit(X_train, y_train)

# # Faire des prédictions
# y_pred = model.predict(X_test)

# # Calculer l'erreur quadratique moyenne (MSE)
# mse = mean_squared_error(y_test, y_pred)
# print(f'Mean Squared Error: {mse}')

# # Sauvegarder le modèle pour une utilisation future
# with open('regression_model.pkl', 'wb') as file:
#     pickle.dump(model, file)

# # Visualisation des résultats
# plt.scatter(y_test, y_pred)
# plt.xlabel('Valeurs réelles')
# plt.ylabel('Prédictions')
# plt.title('Prédictions vs Réalité')
# plt.show()