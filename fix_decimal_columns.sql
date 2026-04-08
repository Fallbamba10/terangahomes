-- Autoriser NULL pour les champs décimaux problématiques
ALTER TABLE annonces 
MODIFY latitude DECIMAL(10,8) NULL,
MODIFY longitude DECIMAL(11,8) NULL,
MODIFY superficie DECIMAL(8,2) NULL;

-- Autoriser NULL pour le prix aussi (au cas où)
ALTER TABLE annonces 
MODIFY prix DECIMAL(10,2) NULL;
