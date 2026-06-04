USE syanfraud;

-- Comptes de démonstration
-- Admin : username admin / mot de passe admin123
-- Client : username clientdemo / mot de passe client123 / PIN 1234
INSERT INTO users (nom, username, email, mot_de_passe, pin_hash, role, solde_compte, date_creation) VALUES
('Administrateur SyAnFraud','admin','admin@syanfraud.local','$2y$12$BnyWW22gLF3mupa8wryVBOWpq5NdfJXAtk8K.y/U0BMR7xQvPYUx6',NULL,'admin',50000,NOW()),
('Client Démo','clientdemo','client@syanfraud.local','$2y$12$8.NxKjGvqODK3aQQcC9l3uRCvI9a5fAwt6OjsfrLG1xJzgheKUSzy','$2y$12$yUYKmQ70XmxUzuB2U8qxwuJc45nZ.4YvDqSyb1onI6uz7ZO9gNbc2','client',18000,DATE_SUB(NOW(), INTERVAL 180 DAY));

INSERT INTO transactions (user_id,montant,type_transaction,lieu,beneficiaire,heure,frequence,temps_validation,solde_compte,anciennete_compte,montant_moyen,ecart_montant,tentatives_pin,beneficiaire_nouveau,jour_semaine,probabilite,resultat,explication,date_transaction) VALUES
(2,9839.64,'PAYMENT','Kin','M1979787155',10,1,7,18000,180,2500,3.94,1,1,1,0.42,'validée','montant élevé',DATE_SUB(NOW(), INTERVAL 6 DAY)),
(2,1864.28,'PAYMENT','Kin','M2044282225',12,1,6,18000,180,2500,0.75,1,1,2,0.16,'validée','profil transactionnel normal',DATE_SUB(NOW(), INTERVAL 5 DAY)),
(2,12000.00,'TRANSFER','Autre','C553264065',2,4,31,18000,180,2200,5.45,3,1,3,0.87,'bloquée','montant élevé, heure inhabituelle, plusieurs tentatives PIN',DATE_SUB(NOW(), INTERVAL 4 DAY)),
(2,181.00,'CASH_OUT','Kin','C38997010',15,1,5,18000,180,2500,0.07,1,1,4,0.13,'validée','profil transactionnel normal',DATE_SUB(NOW(), INTERVAL 3 DAY)),
(2,11668.14,'PAYMENT','Autre','M1230701703',23,3,28,18000,180,2400,4.86,2,1,5,0.72,'bloquée','montant élevé, lieu inhabituel, fréquence élevée',DATE_SUB(NOW(), INTERVAL 2 DAY));

INSERT INTO fraud_logs (transaction_id,niveau_risque,message,statut,date_alerte) VALUES
(3,0.87,'montant élevé, heure inhabituelle, plusieurs tentatives PIN','non traitée',DATE_SUB(NOW(), INTERVAL 4 DAY)),
(5,0.72,'montant élevé, lieu inhabituel, fréquence élevée','non traitée',DATE_SUB(NOW(), INTERVAL 2 DAY));
