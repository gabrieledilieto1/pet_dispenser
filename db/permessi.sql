-- Assicurati che l'utente 'www' esista, altrimenti crealo prima:
-- CREATE USER www WITH PASSWORD 'tw2024';

-- Concedi accesso completo al database
GRANT CONNECT ON DATABASE pet_feeder TO www;

-- Passa allo schema pubblico (default)
\c pet_feeder
GRANT USAGE ON SCHEMA public TO www;

-- Concedi tutti i permessi sulle tabelle esistenti
GRANT ALL PRIVILEGES ON ALL TABLES IN SCHEMA public TO www;

-- Concedi permessi anche su sequenze (necessario per SERIAL)
GRANT ALL PRIVILEGES ON ALL SEQUENCES IN SCHEMA public TO www;

-- Applica i permessi anche alle future tabelle e sequenze
ALTER DEFAULT PRIVILEGES IN SCHEMA public
GRANT ALL ON TABLES TO www;

ALTER DEFAULT PRIVILEGES IN SCHEMA public
GRANT ALL ON SEQUENCES TO www;
