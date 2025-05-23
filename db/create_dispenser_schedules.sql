-- Table: public.dispenser_schedules

-- DROP TABLE IF EXISTS public.dispenser_schedules;

CREATE TABLE IF NOT EXISTS public.dispenser_schedules
(
    id integer NOT NULL DEFAULT nextval('dispenser_schedules_id_seq'::regclass),
    animal_id integer NOT NULL,
    schedule_time time without time zone NOT NULL,
    portion_grams integer NOT NULL,
    proximity_enabled boolean DEFAULT true,
    manual_mode boolean DEFAULT false,
    created_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP,
    active boolean DEFAULT true,
    CONSTRAINT dispenser_schedules_pkey PRIMARY KEY (id),
    CONSTRAINT dispenser_schedules_animal_id_fkey FOREIGN KEY (animal_id)
        REFERENCES public.animals (id) MATCH SIMPLE
        ON UPDATE NO ACTION
        ON DELETE CASCADE
)

TABLESPACE pg_default;

ALTER TABLE IF EXISTS public.dispenser_schedules
    OWNER to postgres;

REVOKE ALL ON TABLE public.dispenser_schedules FROM www;

GRANT ALL ON TABLE public.dispenser_schedules TO postgres;

GRANT DELETE, UPDATE, INSERT, SELECT ON TABLE public.dispenser_schedules TO www;