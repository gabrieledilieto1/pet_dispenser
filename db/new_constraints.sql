-- 1. Rimuovi autoincremento
ALTER TABLE animals ALTER COLUMN id DROP DEFAULT;

-- 2. Impedisci più animali per utente
ALTER TABLE animals ADD CONSTRAINT unique_user_per_animal UNIQUE (user_id);