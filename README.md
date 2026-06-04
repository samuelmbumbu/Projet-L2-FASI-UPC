# 🛡️ SyAnFraud

## Système Intelligent de Détection de Fraude Financière

SyAnFraud est une plateforme web de détection de fraude financière développée dans le cadre d'un projet académique en informatique.

Le système utilise une approche hybride combinant le Machine Learning et des règles métier afin d'identifier les transactions suspectes, d'évaluer leur niveau de risque et d'assister la prise de décision.

---

## 🎯 Objectif du projet

Les institutions financières sont confrontées à une augmentation constante des fraudes bancaires et des transactions suspectes.

L'objectif de SyAnFraud est de :

- détecter les comportements anormaux ;

- évaluer le risque de fraude ;

- bloquer ou signaler les transactions suspectes ;

- fournir des tableaux de bord analytiques ;

- améliorer la sécurité des opérations financières.

---

## 🏗️ Architecture du système

Le projet repose sur une architecture hybride :

Transaction

↓

Collecte des données

↓

Prétraitement

↓

Machine Learning

↓

Règles métier

↓

Score de risque

↓

Décision finale

↓

Alertes et tableaux de bord

---

## 🚀 Fonctionnalités principales

### Authentification sécurisée

- Connexion utilisateur

- Gestion des sessions PHP

- Déconnexion sécurisée

### Analyse des transactions

- Montant

- Type de transaction

- Localisation

- Fréquence des opérations

- Historique comportemental

### Détection intelligente

- Machine Learning

- Scoring de risque

- Détection d'anomalies

- Analyse comportementale

### Règles métier

Exemples :

- montant inhabituel ;

- fréquence excessive ;

- localisation suspecte ;

- multiples tentatives PIN ;

- comportement incohérent.

### Gestion des alertes

- Transactions bloquées

- Transactions validées

- Alertes de fraude

- Historique des décisions

### Tableau de bord

- Nombre total de transactions

- Fraudes détectées

- Taux de fraude

- Graphiques d'évolution

- Statistiques décisionnelles

---

## 🧠 Approche hybride

SyAnFraud combine :

### Machine Learning

Le modèle apprend à distinguer les transactions normales des transactions frauduleuses à partir de données historiques.

### Règles métier

Les règles métier permettent d'intégrer l'expertise bancaire afin de renforcer les décisions du modèle d'intelligence artificielle.

Cette approche améliore la précision tout en réduisant les faux positifs.

---

## ⚙️ Technologies utilisées

### Frontend

- HTML5

- CSS3

- JavaScript

- Chart.js

- Font Awesome

### Backend

- PHP

### Base de données

- MySQL

### Intelligence Artificielle

- Python

- Flask

- Pandas

- NumPy

- Scikit-Learn

### Gestion de version

- Git

- GitHub

---

## 📂 Structure du projet

```text

SyAnFraud/

│

├── frontend/

│   ├── auth/

│   ├── pages/

│   ├── css/

│   ├── js/

│   └── components/

│

├── ml-model/

│   ├── api/

│   ├── dataset/

│   ├── notebooks/

│   └── model/

│

├── database/

│

└── README.md

```

## 📊 Variables utilisées par le modèle

Le modèle prend en compte notamment :

- montant ;

- type de transaction ;

- lieu ;

- solde du compte ;

- fréquence des transactions sur 24h ;

- montant moyen ;

- heure de la transaction ;

- écart entre le montant actuel et le montant moyen.

---

## 🔐 Sécurité

Le système intègre :

- gestion des sessions PHP ;

- protection des pages privées ;

- contrôle d'accès ;

- validation des entrées utilisateur ;

- journalisation des alertes.

---

## 🔮 Perspectives d'amélioration

- Réentraînement automatique du modèle

- Détection en temps réel

- Géolocalisation avancée

- Authentification biométrique

- Notifications SMS et Email

- Explainable AI (XAI)

- API bancaire

- Application mobile

---

## 👨‍💻 Auteur

Samuel MBUMBU

Étudiant en Informatique

Université Protestante au Congo (UPC)

Projet académique de Data Science et Développement Web

---

## 📜 Licence

Projet académique réalisé à des fins pédagogiques et de recherche.