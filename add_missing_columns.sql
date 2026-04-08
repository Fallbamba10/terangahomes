-- Ajout des colonnes manquantes dans la table annonces
ALTER TABLE annonces 
ADD COLUMN etage INT DEFAULT 0 AFTER superficie,
ADD COLUMN terrasse BOOLEAN DEFAULT FALSE AFTER piscine,
ADD COLUMN jardin BOOLEAN DEFAULT FALSE AFTER terrasse,
ADD COLUMN garage BOOLEAN DEFAULT FALSE AFTER jardin,
ADD COLUMN duree_minimale INT NULL AFTER garage,
ADD COLUMN type_location ENUM('jour', 'semaine', 'mois', 'annee') NULL AFTER duree_minimale,
ADD COLUMN services_complementaires JSON NULL AFTER type_location;
