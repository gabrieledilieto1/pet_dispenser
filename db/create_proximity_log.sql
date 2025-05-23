-- Table: public.proximity_log

-- DROP TABLE IF EXISTS public.proximity_log;

CREATE TABLE IF NOT EXISTS public.proximity_log
(
    id integer NOT NULL DEFAULT nextval('proximity_log_id_seq'::regclass),
    animal_id integer NOT NULL,
    detected boolean NOT NULL,
    "timestamp" timestamp without time zone DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT proximity_log_pkey PRIMARY KEY (id),
    CONSTRAINT proximity_log_animal_id_fkey FOREIGN KEY (animal_id)
        REFERENCES public.animals (id) MATCH SIMPLE
        ON UPDATE NO ACTION
        ON DELETE CASCADE
)

TABLESPACE pg_default;

ALTER TABLE IF EXISTS public.proximity_log
    OWNER to postgres;

REVOKE ALL ON TABLE public.proximity_log FROM www;

GRANT ALL ON TABLE public.proximity_log TO postgres;

GRANT DELETE, UPDATE, INSERT, SELECT ON TABLE public.proximity_log TO www;