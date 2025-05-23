-- Table: public.dispenser_logs

-- DROP TABLE IF EXISTS public.dispenser_logs;

CREATE TABLE IF NOT EXISTS public.dispenser_logs
(
    id integer NOT NULL DEFAULT nextval('dispenser_logs_id_seq'::regclass),
    animal_id integer NOT NULL,
    grams integer NOT NULL,
    delivered_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT dispenser_logs_pkey PRIMARY KEY (id),
    CONSTRAINT dispenser_logs_animal_id_fkey FOREIGN KEY (animal_id)
        REFERENCES public.animals (id) MATCH SIMPLE
        ON UPDATE NO ACTION
        ON DELETE CASCADE
)

TABLESPACE pg_default;

ALTER TABLE IF EXISTS public.dispenser_logs
    OWNER to postgres;

REVOKE ALL ON TABLE public.dispenser_logs FROM www;

GRANT ALL ON TABLE public.dispenser_logs TO postgres;

GRANT DELETE, UPDATE, INSERT, SELECT ON TABLE public.dispenser_logs TO www;