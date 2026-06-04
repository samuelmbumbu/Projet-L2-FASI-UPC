from flask import Flask, request, jsonify
import pandas as pd
import joblib
import os

app = Flask(__name__)

# Chemins des fichiers sauvegardés
BASE_DIR = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
MODEL_PATH = os.path.join(BASE_DIR, "model", "model.pkl")
SCALER_PATH = os.path.join(BASE_DIR, "model", "scaler.pkl")
FEATURES_PATH = os.path.join(BASE_DIR, "model", "features.pkl")

# Chargement du modèle, scaler et colonnes attendues
model = joblib.load(MODEL_PATH)
scaler = joblib.load(SCALER_PATH)
features = joblib.load(FEATURES_PATH)


@app.route("/", methods=["GET"])
def home():
    return jsonify({
        "message": "API ML SyAnFraud opérationnelle",
        "route_prediction": "/predict"
    })


@app.route("/predict", methods=["POST"])
def predict():
    try:
        data = request.get_json()

        if not data:
            return jsonify({"error": "Aucune donnée reçue"}), 400

        # Données reçues depuis PHP
        montant = float(data.get("montant", 0))
        type_transaction = data.get("type_transaction", "Online")
        lieu = data.get("lieu", "Kin")
        solde_compte = float(data.get("solde_compte", 0))
        frequence_24h = int(data.get("frequence_24h", 0))
        montant_moyen = float(data.get("montant_moyen", 0))
        tentatives_pin = int(data.get("tentatives_pin", 0))
        heure = int(data.get("heure", 12))

        # Variable calculée
        ecart_montant = montant - montant_moyen

        # Mapping SyAnFraud -> valeurs connues du dataset
        if lieu == "Kin":
            lieu_model = "Mumbai"
        else:
            lieu_model = "New York"

        # Construction des données selon les noms utilisés à l'entraînement
        input_data = {
            "montant": montant,
            "type_transaction": type_transaction,
            "lieu": lieu_model,
            "solde_compte": solde_compte,
            "frequence_24h": frequence_24h,
            "montant_moyen": montant_moyen,
            "tentatives_pin": tentatives_pin,
            "heure": heure,
            "ecart_montant": ecart_montant
        }

        df = pd.DataFrame([input_data])

        # Encodage comme dans le notebook
        df_encoded = pd.get_dummies(df)

        # Ajouter les colonnes manquantes attendues par le modèle
        for col in features:
            if col not in df_encoded.columns:
                df_encoded[col] = 0

        # Garder uniquement les colonnes du modèle, dans le bon ordre
        df_encoded = df_encoded[features]
        
        # Standardisation
        X_scaled = scaler.transform(df_encoded)

        # Prédiction
        prediction = model.predict(X_scaled)[0]
        probability = model.predict_proba(X_scaled)[0][1]

        risk_percent = round(float(probability) * 100, 2)

        decision = "bloquée" if risk_percent >= 70 else "validée"

        return jsonify({
            "risk": round(float(probability), 4),
            "risk_percent": risk_percent,
            "prediction": int(prediction),
            "decision": decision,
            "message": "Analyse ML effectuée avec succès"
        })

    except Exception as e:
        return jsonify({
            "error": str(e),
            "risk": 0,
            "risk_percent": 0,
            "decision": "erreur"
        }), 500


if __name__ == "__main__":
    app.run(debug=True, port=5001)
    