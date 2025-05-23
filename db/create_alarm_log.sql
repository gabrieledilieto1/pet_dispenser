-- Table: public.alarm_log

-- DROP TABLE IF EXISTS public.alarm_log;

CREATE TABLE IF NOT EXISTS public.alarm_log
(
    id integer NOT NULL DEFAULT nextval('alarm_log_id_seq'::regclass),
    animal_id integer,
    alarm_type character varying(50) COLLATE pg_catalog."default" NOT NULL,
    "timestamp" timestamp without time zone DEFAULT CURRENT_TIMESTAMP,
    handled boolean DEFAULT false,
    notes text COLLATE pg_catalog."default",
    CONSTRAINT alarm_log_pkey PRIMARY KEY (id),
    CONSTRAINT alarm_log_animal_id_fkey FOREIGN KEY (animal_id)
        REFERENCES public.animals (id) MATCH SIMPLE
        ON UPDATE NO ACTION
        ON DELETE NO ACTION
)

TABLESPACE pg_default;

ALTER TABLE IF EXISTS public.alarm_log
    OWNER to postgres;

REVOKE ALL ON TABLE public.alarm_log FROM www;

GRANT ALL ON TABLE public.alarm_log TO postgres;

GRANT DELETE, UPDATE, INSERT, SELECT ON TABLE public.alarm_log TO www;