-- Table: public.account

-- DROP TABLE IF EXISTS public.account;

CREATE TABLE IF NOT EXISTS public.account
(
    id integer NOT NULL DEFAULT nextval('account_id_seq'::regclass),
    nome character varying(100) COLLATE pg_catalog."default" NOT NULL,
    cognome character varying(100) COLLATE pg_catalog."default" NOT NULL,
    username character varying(100) COLLATE pg_catalog."default" NOT NULL,
    email character varying(255) COLLATE pg_catalog."default" NOT NULL,
    password character varying(255) COLLATE pg_catalog."default" NOT NULL,
    created_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT account_pkey PRIMARY KEY (id),
    CONSTRAINT account_email_key UNIQUE (email),
    CONSTRAINT account_username_key UNIQUE (username)
)

TABLESPACE pg_default;

ALTER TABLE IF EXISTS public.account
    OWNER to postgres;

REVOKE ALL ON TABLE public.account FROM www;

GRANT ALL ON TABLE public.account TO postgres;

GRANT DELETE, UPDATE, INSERT, SELECT ON TABLE public.account TO www;