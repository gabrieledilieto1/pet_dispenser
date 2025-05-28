-- PostgreSQL-compatible schema for Pet Feeder
-- Switch to the appropriate database manually in pgAdmin if needed

-- Utenti del sistema
CREATE TABLE account (
    id SERIAL PRIMARY KEY,
    nome VARCHAR(50) NOT NULL,
    cognome VARCHAR(50) NOT NULL,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Animali registrati da ciascun utente
CREATE TABLE animals (
    id SERIAL PRIMARY KEY,
    user_id INT NOT NULL,
    name VARCHAR(50) NOT NULL,
    age INT,
    weight NUMERIC(5,2),
    breed VARCHAR(100),
    photo_path VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES account(id) ON DELETE CASCADE
);

-- Programmazione erogazione cibo
CREATE TABLE dispenser_schedules (
    id SERIAL PRIMARY KEY,
    animal_id INT NOT NULL,
    schedule_time TIME NOT NULL,
    portion_grams INT NOT NULL,
    proximity_enabled BOOLEAN DEFAULT FALSE,
    manual_mode BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    active BOOLEAN DEFAULT TRUE,
    FOREIGN KEY (animal_id) REFERENCES animals(id) ON DELETE CASCADE
);

-- Log di ogni erogazione
CREATE TABLE dispenser_logs (
    id SERIAL PRIMARY KEY,
    animal_id INT NOT NULL,
    grams INT NOT NULL,
    delivered_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (animal_id) REFERENCES animals(id) ON DELETE CASCADE
);

-- Log di eventi di prossimità
CREATE TABLE proximity_log (
    id SERIAL PRIMARY KEY,
    animal_id INT NOT NULL,
    detected BOOLEAN NOT NULL,
    timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (animal_id) REFERENCES animals(id) ON DELETE CASCADE
);

-- Log degli allarmi (es. accumulo cibo)
CREATE TABLE alarm_log (
    id SERIAL PRIMARY KEY,
    animal_id INT,
    alarm_type VARCHAR(50) NOT NULL,
    timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    handled BOOLEAN DEFAULT FALSE,
    notes TEXT,
    FOREIGN KEY (animal_id) REFERENCES animals(id) ON DELETE SET NULL
);
