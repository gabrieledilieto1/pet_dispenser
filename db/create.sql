DROP TABLE IF EXISTS account CASCADE;
DROP TABLE IF EXISTS animals CASCADE;
DROP TABLE IF EXISTS dispenser_schedules CASCADE;
DROP TABLE IF EXISTS dispenser_logs CASCADE;
DROP TABLE IF EXISTS proximity_log CASCADE;
DROP TABLE IF EXISTS alarm_log CASCADE;
-- Database: pet_dispenser

-- Utenti del sistema
CREATE TABLE account (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(50) NOT NULL,
    cognome VARCHAR(50) NOT NULL,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Animali registrati da ciascun utente
CREATE TABLE animals (
    id INT AUTO_INCREMENT PRIMARY KEY,
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
    id INT AUTO_INCREMENT PRIMARY KEY,
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
    id INT AUTO_INCREMENT PRIMARY KEY,
    animal_id INT NOT NULL,
    grams INT NOT NULL,
    delivered_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (animal_id) REFERENCES animals(id) ON DELETE CASCADE
);

-- Log di eventi di prossimità
CREATE TABLE proximity_log (
    id INT AUTO_INCREMENT PRIMARY KEY,
    animal_id INT NOT NULL,
    detected BOOLEAN NOT NULL,
    timestamp DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (animal_id) REFERENCES animals(id) ON DELETE CASCADE
);

-- Log degli allarmi (es. accumulo cibo)
CREATE TABLE alarm_log (
    id INT AUTO_INCREMENT PRIMARY KEY,
    animal_id INT,
    alarm_type VARCHAR(50) NOT NULL,
    timestamp DATETIME DEFAULT CURRENT_TIMESTAMP,
    handled BOOLEAN DEFAULT FALSE,
    notes TEXT,
    FOREIGN KEY (animal_id) REFERENCES animals(id) ON DELETE SET NULL
);
