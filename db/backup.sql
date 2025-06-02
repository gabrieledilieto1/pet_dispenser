--
-- PostgreSQL database dump
--

-- Dumped from database version 17.5
-- Dumped by pg_dump version 17.5

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET transaction_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

SET default_tablespace = '';

SET default_table_access_method = heap;

--
-- Name: account; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.account (
    id integer NOT NULL,
    nome character varying(50) NOT NULL,
    cognome character varying(50) NOT NULL,
    username character varying(50) NOT NULL,
    email character varying(100) NOT NULL,
    password character varying(255) NOT NULL,
    created_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP
);


ALTER TABLE public.account OWNER TO postgres;

--
-- Name: account_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.account_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.account_id_seq OWNER TO postgres;

--
-- Name: account_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.account_id_seq OWNED BY public.account.id;


--
-- Name: alarm_log; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.alarm_log (
    id integer NOT NULL,
    animal_id integer,
    alarm_type character varying(50) NOT NULL,
    "timestamp" timestamp without time zone DEFAULT CURRENT_TIMESTAMP,
    handled boolean DEFAULT false,
    notes text
);


ALTER TABLE public.alarm_log OWNER TO postgres;

--
-- Name: alarm_log_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.alarm_log_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.alarm_log_id_seq OWNER TO postgres;

--
-- Name: alarm_log_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.alarm_log_id_seq OWNED BY public.alarm_log.id;


--
-- Name: animals; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.animals (
    id integer NOT NULL,
    user_id integer NOT NULL,
    name character varying(50) NOT NULL,
    age integer,
    weight numeric(5,2),
    breed character varying(100),
    photo_path character varying(255),
    created_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP
);


ALTER TABLE public.animals OWNER TO postgres;

--
-- Name: animals_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.animals_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.animals_id_seq OWNER TO postgres;

--
-- Name: animals_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.animals_id_seq OWNED BY public.animals.id;


--
-- Name: dispenser_logs; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.dispenser_logs (
    id integer NOT NULL,
    animal_id integer NOT NULL,
    grams integer NOT NULL,
    delivered_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP
);


ALTER TABLE public.dispenser_logs OWNER TO postgres;

--
-- Name: dispenser_logs_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.dispenser_logs_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.dispenser_logs_id_seq OWNER TO postgres;

--
-- Name: dispenser_logs_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.dispenser_logs_id_seq OWNED BY public.dispenser_logs.id;


--
-- Name: dispenser_schedules; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.dispenser_schedules (
    id integer NOT NULL,
    animal_id integer NOT NULL,
    schedule_time time without time zone NOT NULL,
    portion_grams integer NOT NULL,
    proximity_enabled boolean DEFAULT false,
    manual_mode boolean DEFAULT false,
    created_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP,
    active boolean DEFAULT true
);


ALTER TABLE public.dispenser_schedules OWNER TO postgres;

--
-- Name: dispenser_schedules_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.dispenser_schedules_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.dispenser_schedules_id_seq OWNER TO postgres;

--
-- Name: dispenser_schedules_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.dispenser_schedules_id_seq OWNED BY public.dispenser_schedules.id;


--
-- Name: proximity_log; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.proximity_log (
    id integer NOT NULL,
    animal_id integer NOT NULL,
    detected boolean NOT NULL,
    "timestamp" timestamp without time zone DEFAULT CURRENT_TIMESTAMP
);


ALTER TABLE public.proximity_log OWNER TO postgres;

--
-- Name: proximity_log_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.proximity_log_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.proximity_log_id_seq OWNER TO postgres;

--
-- Name: proximity_log_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.proximity_log_id_seq OWNED BY public.proximity_log.id;


--
-- Name: account id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.account ALTER COLUMN id SET DEFAULT nextval('public.account_id_seq'::regclass);


--
-- Name: alarm_log id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.alarm_log ALTER COLUMN id SET DEFAULT nextval('public.alarm_log_id_seq'::regclass);


--
-- Name: dispenser_logs id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.dispenser_logs ALTER COLUMN id SET DEFAULT nextval('public.dispenser_logs_id_seq'::regclass);


--
-- Name: dispenser_schedules id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.dispenser_schedules ALTER COLUMN id SET DEFAULT nextval('public.dispenser_schedules_id_seq'::regclass);


--
-- Name: proximity_log id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.proximity_log ALTER COLUMN id SET DEFAULT nextval('public.proximity_log_id_seq'::regclass);


--
-- Data for Name: account; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.account (id, nome, cognome, username, email, password, created_at) FROM stdin;
1	Giorgio	Gaber	GGaber	giorgio@gmail.com	$2y$10$NK8efDjwpa/vH3BFpfnJROkxY/N.LZVTLKfmMcTS4pb2POGHiISiq	2025-05-28 18:08:54.977203
\.


--
-- Data for Name: alarm_log; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.alarm_log (id, animal_id, alarm_type, "timestamp", handled, notes) FROM stdin;
1	1	overflow	2025-06-03 01:15:52.834663	f	Sensore rileva accumulo cibo.
2	1	manual_override	2025-06-02 03:32:52.834663	t	Utente ha attivato manualmente.
3	1	proximity_alert	2025-06-01 00:52:52.834663	f	Presenza rilevata poco prima dell’orario.
4	1	overflow	2025-05-31 02:23:52.834663	t	Contenitore pieno.
5	1	manual_override	2025-05-30 02:00:52.834663	t	Dispenser forzato.
6	1	proximity_alert	2025-05-29 00:40:52.834663	f	Richiesta anticipata registrata.
7	1	overflow	2025-05-28 03:05:52.834663	f	Ostruzione rilevata.
8	1	manual_override	2025-05-27 04:50:52.834663	t	Avvio da interfaccia web.
9	1	proximity_alert	2025-05-25 23:55:52.834663	f	Movimento rilevato in prossimità.
10	1	overflow	2025-05-25 01:20:52.834663	t	Dispenser controllato manualmente.
\.


--
-- Data for Name: animals; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.animals (id, user_id, name, age, weight, breed, photo_path, created_at) FROM stdin;
1	1	Dom	22	2.00	Maine Coon	uploads/1748875636_PHOTO-2025-05-16-12-26-49.jpg	2025-06-02 16:47:16.43608
\.


--
-- Data for Name: dispenser_logs; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.dispenser_logs (id, animal_id, grams, delivered_at) FROM stdin;
135	1	100	2025-06-03 01:03:49.76458
136	1	100	2025-06-02 05:03:49.76458
137	1	100	2025-06-01 11:03:49.76458
138	1	100	2025-05-31 01:03:49.76458
139	1	100	2025-05-30 05:03:49.76458
140	1	100	2025-05-29 11:03:49.76458
141	1	100	2025-05-28 01:03:49.76458
142	1	100	2025-05-27 05:03:49.76458
143	1	100	2025-05-26 11:03:49.76458
144	1	100	2025-05-25 01:03:49.76458
145	1	100	2025-06-02 17:06:06.59932
\.


--
-- Data for Name: dispenser_schedules; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.dispenser_schedules (id, animal_id, schedule_time, portion_grams, proximity_enabled, manual_mode, created_at, active) FROM stdin;
7	1	08:30:00	100	t	f	2025-06-02 16:51:39.27893	t
8	1	13:00:00	100	t	f	2025-06-02 16:51:56.842872	t
9	1	20:00:00	100	t	f	2025-06-02 16:52:08.114568	t
\.


--
-- Data for Name: proximity_log; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.proximity_log (id, animal_id, detected, "timestamp") FROM stdin;
140	1	f	2025-06-02 17:06:06.599484
141	1	t	2025-06-03 00:38:52.119781
142	1	t	2025-06-02 01:23:52.119781
143	1	t	2025-06-01 02:08:52.119781
144	1	t	2025-05-31 03:08:52.119781
145	1	t	2025-05-30 04:53:52.119781
146	1	t	2025-05-29 00:38:52.119781
147	1	t	2025-05-28 01:23:52.119781
148	1	t	2025-05-27 02:08:52.119781
149	1	t	2025-05-26 03:08:52.119781
150	1	t	2025-05-25 04:53:52.119781
151	1	t	2025-05-24 00:38:52.119781
152	1	t	2025-05-23 01:23:52.119781
153	1	t	2025-05-22 02:08:52.119781
154	1	t	2025-05-21 03:08:52.119781
155	1	t	2025-05-20 04:53:52.119781
156	1	t	2025-05-19 00:38:52.119781
157	1	t	2025-05-18 01:23:52.119781
158	1	t	2025-05-17 02:08:52.119781
159	1	t	2025-05-16 03:08:52.119781
160	1	t	2025-05-15 04:53:52.119781
161	1	t	2025-06-03 01:37:32.894323
162	1	t	2025-06-03 06:10:32.894323
163	1	t	2025-06-03 13:09:32.894323
164	1	t	2025-06-02 01:43:32.894323
165	1	t	2025-06-02 06:13:32.894323
166	1	t	2025-06-02 13:08:32.894323
167	1	t	2025-06-01 01:36:32.894323
168	1	t	2025-06-01 06:07:32.894323
169	1	t	2025-06-01 13:14:32.894323
170	1	t	2025-05-31 01:41:32.894323
171	1	t	2025-05-31 06:06:32.894323
172	1	t	2025-05-31 13:05:32.894323
173	1	t	2025-05-30 01:42:32.894323
174	1	t	2025-05-30 06:09:32.894323
175	1	t	2025-05-30 13:07:32.894323
176	1	t	2025-05-29 01:38:32.894323
177	1	t	2025-05-29 06:11:32.894323
178	1	t	2025-05-29 13:10:32.894323
179	1	t	2025-05-28 01:44:32.894323
180	1	t	2025-05-28 06:14:32.894323
181	1	t	2025-05-28 13:13:32.894323
182	1	t	2025-05-27 01:39:32.894323
183	1	t	2025-05-27 06:05:32.894323
184	1	t	2025-05-27 13:06:32.894323
185	1	t	2025-05-26 01:40:32.894323
186	1	t	2025-05-26 06:08:32.894323
187	1	t	2025-05-26 13:11:32.894323
188	1	t	2025-05-25 01:35:32.894323
189	1	t	2025-05-25 06:12:32.894323
190	1	t	2025-05-25 13:12:32.894323
191	1	t	2025-06-02 19:59:00
192	1	t	2025-06-02 12:59:00
193	1	t	2025-06-02 11:24:00
194	1	t	2025-06-02 08:27:00
195	1	t	2025-06-01 19:53:00
196	1	t	2025-06-01 12:56:00
197	1	t	2025-06-01 08:24:00
198	1	t	2025-05-31 19:54:00
199	1	t	2025-05-31 12:53:00
200	1	t	2025-05-31 08:28:00
201	1	t	2025-05-30 19:57:00
202	1	t	2025-05-30 12:59:00
203	1	t	2025-05-30 08:20:00
204	1	t	2025-05-30 01:42:00
205	1	t	2025-05-29 19:59:00
206	1	t	2025-05-29 12:51:00
207	1	t	2025-05-29 08:23:00
208	1	t	2025-05-28 19:54:00
209	1	t	2025-05-28 12:53:00
210	1	t	2025-05-28 08:28:00
211	1	t	2025-05-27 19:50:00
212	1	t	2025-05-27 12:50:00
213	1	t	2025-05-27 08:20:00
214	1	t	2025-05-26 19:55:00
215	1	t	2025-05-26 12:59:00
216	1	t	2025-05-26 08:28:00
217	1	t	2025-05-25 19:53:00
218	1	t	2025-05-25 12:50:00
219	1	t	2025-05-25 08:22:00
220	1	t	2025-05-24 19:51:00
221	1	t	2025-05-24 12:56:00
222	1	t	2025-05-24 08:28:00
223	1	t	2025-05-23 19:58:00
224	1	t	2025-05-23 12:55:00
225	1	t	2025-05-23 08:22:00
226	1	t	2025-05-22 19:57:00
227	1	t	2025-05-22 12:51:00
228	1	t	2025-05-22 08:24:00
229	1	t	2025-05-21 19:51:00
230	1	t	2025-05-21 12:55:00
231	1	t	2025-05-21 08:22:00
232	1	t	2025-05-20 19:51:00
233	1	t	2025-05-20 12:59:00
234	1	t	2025-05-20 08:24:00
235	1	t	2025-05-19 19:51:00
236	1	t	2025-05-19 12:53:00
237	1	t	2025-05-19 10:40:00
238	1	t	2025-05-19 08:20:00
239	1	t	2025-05-18 19:50:00
240	1	t	2025-05-18 12:57:00
241	1	t	2025-05-18 08:24:00
242	1	t	2025-05-17 19:50:00
243	1	t	2025-05-17 12:56:00
244	1	t	2025-05-17 08:20:00
245	1	t	2025-05-16 19:59:00
246	1	t	2025-05-16 12:53:00
247	1	t	2025-05-16 08:23:00
248	1	t	2025-05-15 19:52:00
249	1	t	2025-05-15 12:54:00
250	1	t	2025-05-15 08:24:00
251	1	t	2025-05-14 19:55:00
252	1	t	2025-05-14 12:51:00
253	1	t	2025-05-14 12:45:00
254	1	t	2025-05-14 08:24:00
255	1	t	2025-05-13 19:55:00
256	1	t	2025-05-13 12:50:00
257	1	t	2025-05-13 08:20:00
258	1	t	2025-05-12 19:51:00
259	1	t	2025-05-12 12:57:00
260	1	t	2025-05-12 08:26:00
261	1	t	2025-05-11 19:59:00
262	1	t	2025-05-11 12:50:00
263	1	t	2025-05-11 08:20:00
264	1	t	2025-05-10 19:58:00
265	1	t	2025-05-10 12:57:00
266	1	t	2025-05-10 08:29:00
267	1	t	2025-05-09 19:56:00
268	1	t	2025-05-09 12:54:00
269	1	t	2025-05-09 08:25:00
270	1	t	2025-05-08 19:50:00
271	1	t	2025-05-08 12:51:00
272	1	t	2025-05-08 08:24:00
273	1	t	2025-05-07 19:55:00
274	1	t	2025-05-07 12:55:00
275	1	t	2025-05-07 08:20:00
276	1	t	2025-05-06 19:58:00
277	1	t	2025-05-06 12:56:00
278	1	t	2025-05-06 08:20:00
279	1	t	2025-05-05 19:58:00
280	1	t	2025-05-05 12:58:00
281	1	t	2025-05-05 08:22:00
282	1	t	2025-05-04 19:59:00
283	1	t	2025-05-04 12:52:00
284	1	t	2025-05-04 08:29:00
285	1	t	2025-05-03 19:59:00
286	1	t	2025-05-03 17:18:00
287	1	t	2025-05-03 12:58:00
288	1	t	2025-05-03 08:29:00
289	1	t	2025-05-02 19:51:00
290	1	t	2025-05-02 12:54:00
291	1	t	2025-05-02 08:29:00
292	1	t	2025-05-01 19:55:00
293	1	t	2025-05-01 12:55:00
294	1	t	2025-05-01 08:27:00
\.


--
-- Name: account_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.account_id_seq', 1, true);


--
-- Name: alarm_log_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.alarm_log_id_seq', 10, true);


--
-- Name: animals_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.animals_id_seq', 1, true);


--
-- Name: dispenser_logs_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.dispenser_logs_id_seq', 145, true);


--
-- Name: dispenser_schedules_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.dispenser_schedules_id_seq', 9, true);


--
-- Name: proximity_log_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.proximity_log_id_seq', 294, true);


--
-- Name: account account_email_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.account
    ADD CONSTRAINT account_email_key UNIQUE (email);


--
-- Name: account account_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.account
    ADD CONSTRAINT account_pkey PRIMARY KEY (id);


--
-- Name: account account_username_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.account
    ADD CONSTRAINT account_username_key UNIQUE (username);


--
-- Name: alarm_log alarm_log_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.alarm_log
    ADD CONSTRAINT alarm_log_pkey PRIMARY KEY (id);


--
-- Name: animals animals_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.animals
    ADD CONSTRAINT animals_pkey PRIMARY KEY (id);


--
-- Name: dispenser_logs dispenser_logs_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.dispenser_logs
    ADD CONSTRAINT dispenser_logs_pkey PRIMARY KEY (id);


--
-- Name: dispenser_schedules dispenser_schedules_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.dispenser_schedules
    ADD CONSTRAINT dispenser_schedules_pkey PRIMARY KEY (id);


--
-- Name: proximity_log proximity_log_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.proximity_log
    ADD CONSTRAINT proximity_log_pkey PRIMARY KEY (id);


--
-- Name: animals unique_user_per_animal; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.animals
    ADD CONSTRAINT unique_user_per_animal UNIQUE (user_id);


--
-- Name: alarm_log alarm_log_animal_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.alarm_log
    ADD CONSTRAINT alarm_log_animal_id_fkey FOREIGN KEY (animal_id) REFERENCES public.animals(id) ON DELETE SET NULL;


--
-- Name: animals animals_user_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.animals
    ADD CONSTRAINT animals_user_id_fkey FOREIGN KEY (user_id) REFERENCES public.account(id) ON DELETE CASCADE;


--
-- Name: dispenser_logs dispenser_logs_animal_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.dispenser_logs
    ADD CONSTRAINT dispenser_logs_animal_id_fkey FOREIGN KEY (animal_id) REFERENCES public.animals(id) ON DELETE CASCADE;


--
-- Name: dispenser_schedules dispenser_schedules_animal_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.dispenser_schedules
    ADD CONSTRAINT dispenser_schedules_animal_id_fkey FOREIGN KEY (animal_id) REFERENCES public.animals(id) ON DELETE CASCADE;


--
-- Name: proximity_log proximity_log_animal_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.proximity_log
    ADD CONSTRAINT proximity_log_animal_id_fkey FOREIGN KEY (animal_id) REFERENCES public.animals(id) ON DELETE CASCADE;


--
-- Name: SCHEMA public; Type: ACL; Schema: -; Owner: pg_database_owner
--

GRANT USAGE ON SCHEMA public TO www;


--
-- Name: TABLE account; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.account TO www;


--
-- Name: SEQUENCE account_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.account_id_seq TO www;


--
-- Name: TABLE alarm_log; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.alarm_log TO www;


--
-- Name: SEQUENCE alarm_log_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.alarm_log_id_seq TO www;


--
-- Name: TABLE animals; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.animals TO www;


--
-- Name: SEQUENCE animals_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.animals_id_seq TO www;


--
-- Name: TABLE dispenser_logs; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.dispenser_logs TO www;


--
-- Name: SEQUENCE dispenser_logs_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.dispenser_logs_id_seq TO www;


--
-- Name: TABLE dispenser_schedules; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.dispenser_schedules TO www;


--
-- Name: SEQUENCE dispenser_schedules_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.dispenser_schedules_id_seq TO www;


--
-- Name: TABLE proximity_log; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.proximity_log TO www;


--
-- Name: SEQUENCE proximity_log_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.proximity_log_id_seq TO www;


--
-- Name: DEFAULT PRIVILEGES FOR SEQUENCES; Type: DEFAULT ACL; Schema: public; Owner: postgres
--

ALTER DEFAULT PRIVILEGES FOR ROLE postgres IN SCHEMA public GRANT ALL ON SEQUENCES TO www;


--
-- Name: DEFAULT PRIVILEGES FOR TABLES; Type: DEFAULT ACL; Schema: public; Owner: postgres
--

ALTER DEFAULT PRIVILEGES FOR ROLE postgres IN SCHEMA public GRANT ALL ON TABLES TO www;


--
-- PostgreSQL database dump complete
--

