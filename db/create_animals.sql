-- Table: public.animals

-- DROP TABLE IF EXISTS public.animals;

CREATE TABLE IF NOT EXISTS public.animals
(
    id integer NOT NULL DEFAULT nextval('animals_id_seq'::regclass),
    user_id integer NOT NULL,
    name character varying(100) COLLATE pg_catalog."default" NOT NULL,
    age integer,
    weight numeric(5,2),
    breed character varying(100) COLLATE pg_catalog."default",
    photo_path character varying(255) COLLATE pg_catalog."default",
    created_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT animals_pkey PRIMARY KEY (id),
    CONSTRAINT animals_user_id_fkey FOREIGN KEY (user_id)
        REFERENCES public.account (id) MATCH SIMPLE
        ON UPDATE NO ACTION
        ON DELETE CASCADE
)

TABLESPACE pg_default;

ALTER TABLE IF EXISTS public.animals
    OWNER to postgres;

REVOKE ALL ON TABLE public.animals FROM www;

GRANT ALL ON TABLE public.animals TO postgres;

GRANT DELETE, UPDATE, INSERT, SELECT ON TABLE public.animals TO www;