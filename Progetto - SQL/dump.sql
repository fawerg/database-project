--
-- PostgreSQL database dump
--

SET statement_timeout = 0;
SET lock_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SET check_function_bodies = false;
SET client_min_messages = warning;

--
-- Name: progetto_db; Type: SCHEMA; Schema: -; Owner: postgres
--

CREATE SCHEMA progetto_db;


ALTER SCHEMA progetto_db OWNER TO postgres;

--
-- Name: plpgsql; Type: EXTENSION; Schema: -; Owner: 
--

CREATE EXTENSION IF NOT EXISTS plpgsql WITH SCHEMA pg_catalog;


--
-- Name: EXTENSION plpgsql; Type: COMMENT; Schema: -; Owner: 
--

COMMENT ON EXTENSION plpgsql IS 'PL/pgSQL procedural language';


SET search_path = progetto_db, pg_catalog;

--
-- Name: tipologia_conto; Type: DOMAIN; Schema: progetto_db; Owner: postgres
--

CREATE DOMAIN tipologia_conto AS text
	CONSTRAINT tipologia_conto_check CHECK (((VALUE ~ 'Deposito'::text) OR (VALUE ~ 'Credito'::text)));


ALTER DOMAIN progetto_db.tipologia_conto OWNER TO postgres;

--
-- Name: insert_conto_credito(); Type: FUNCTION; Schema: progetto_db; Owner: postgres
--

CREATE FUNCTION insert_conto_credito() RETURNS trigger
    LANGUAGE plpgsql
    AS $$

BEGIN
	INSERT INTO conto VALUES (NEW.iban, 'Credito', NEW.mail);
	RETURN NEW;
END;

$$;


ALTER FUNCTION progetto_db.insert_conto_credito() OWNER TO postgres;

--
-- Name: insert_conto_deposito(); Type: FUNCTION; Schema: progetto_db; Owner: postgres
--

CREATE FUNCTION insert_conto_deposito() RETURNS trigger
    LANGUAGE plpgsql
    AS $$

BEGIN
	INSERT INTO conto VALUES (NEW.iban, 'Deposito', NEW.mail);
	RETURN NEW;
END;

$$;


ALTER FUNCTION progetto_db.insert_conto_deposito() OWNER TO postgres;

SET default_tablespace = '';

SET default_with_oids = false;

--
-- Name: bilancio; Type: TABLE; Schema: progetto_db; Owner: postgres; Tablespace: 
--

CREATE TABLE bilancio (
    id character varying(8) NOT NULL,
    quantita_denaro numeric(12,2) NOT NULL,
    valore_iniziale numeric(12,2) NOT NULL,
    data_creazione date DEFAULT now(),
    data_scadenza date DEFAULT (now() + '1 mon'::interval),
    mail character varying(100) NOT NULL
);


ALTER TABLE progetto_db.bilancio OWNER TO postgres;

--
-- Name: bilancio_conto; Type: TABLE; Schema: progetto_db; Owner: postgres; Tablespace: 
--

CREATE TABLE bilancio_conto (
    id_bilancio character varying(8) NOT NULL,
    iban_deposito character varying(32) NOT NULL,
    iban_credito character varying(32) NOT NULL,
    CONSTRAINT bilancio_conto_check CHECK ((((iban_deposito IS NULL) OR (iban_credito IS NULL)) AND ((iban_deposito IS NOT NULL) AND (iban_credito IS NOT NULL))))
);


ALTER TABLE progetto_db.bilancio_conto OWNER TO postgres;

--
-- Name: bilancio_spesa; Type: TABLE; Schema: progetto_db; Owner: postgres; Tablespace: 
--

CREATE TABLE bilancio_spesa (
    id_bilancio character varying(8) NOT NULL,
    data_spesa timestamp without time zone NOT NULL
);


ALTER TABLE progetto_db.bilancio_spesa OWNER TO postgres;

--
-- Name: conto; Type: TABLE; Schema: progetto_db; Owner: postgres; Tablespace: 
--

CREATE TABLE conto (
    iban character varying(32) NOT NULL,
    tipologia tipologia_conto NOT NULL,
    mail character varying(100) NOT NULL
);


ALTER TABLE progetto_db.conto OWNER TO postgres;

--
-- Name: conto_credito; Type: TABLE; Schema: progetto_db; Owner: postgres; Tablespace: 
--

CREATE TABLE conto_credito (
    iban character varying(32) NOT NULL,
    disponibilita_denaro numeric(12,2) DEFAULT 0.00 NOT NULL,
    tetto_massimo numeric(12,2) DEFAULT 0.00 NOT NULL,
    mail character varying(100) NOT NULL,
    iban_deposito character varying(32) NOT NULL,
    CONSTRAINT conto_credito_check CHECK ((disponibilita_denaro <= tetto_massimo)),
    CONSTRAINT conto_credito_disponibilita_denaro_check CHECK ((disponibilita_denaro >= 0.00))
);


ALTER TABLE progetto_db.conto_credito OWNER TO postgres;

--
-- Name: conto_deposito; Type: TABLE; Schema: progetto_db; Owner: postgres; Tablespace: 
--

CREATE TABLE conto_deposito (
    iban character varying(32) NOT NULL,
    disponibilita_denaro numeric(12,2) DEFAULT 0.00 NOT NULL,
    mail character varying(100) NOT NULL,
    CONSTRAINT conto_deposito_disponibilita_denaro_check CHECK ((disponibilita_denaro >= 0.00))
);


ALTER TABLE progetto_db.conto_deposito OWNER TO postgres;

--
-- Name: entrata; Type: TABLE; Schema: progetto_db; Owner: postgres; Tablespace: 
--

CREATE TABLE entrata (
    data_movimento timestamp without time zone NOT NULL,
    entita_economica numeric(12,2) NOT NULL,
    descrizione character varying(50) DEFAULT 'Entrata generica'::character varying,
    mail character varying(100),
    iban character varying(32) NOT NULL
);


ALTER TABLE progetto_db.entrata OWNER TO postgres;

--
-- Name: spesa; Type: TABLE; Schema: progetto_db; Owner: postgres; Tablespace: 
--

CREATE TABLE spesa (
    data_movimento timestamp without time zone NOT NULL,
    entita_economica numeric(12,2) NOT NULL,
    descrizione character varying(50) DEFAULT 'Spesa generica'::character varying,
    mail character varying(100),
    iban character varying(32) NOT NULL
);


ALTER TABLE progetto_db.spesa OWNER TO postgres;

--
-- Name: utente; Type: TABLE; Schema: progetto_db; Owner: postgres; Tablespace: 
--

CREATE TABLE utente (
    mail character varying(100) NOT NULL,
    nome character varying(30) NOT NULL,
    cognome character varying(30) NOT NULL,
    indirizzo character varying(100) NOT NULL,
    pwd character varying(32) NOT NULL,
    CONSTRAINT utente_pwd_check CHECK ((length((pwd)::text) > 7))
);


ALTER TABLE progetto_db.utente OWNER TO postgres;

--
-- Data for Name: bilancio; Type: TABLE DATA; Schema: progetto_db; Owner: postgres
--



--
-- Data for Name: bilancio_conto; Type: TABLE DATA; Schema: progetto_db; Owner: postgres
--



--
-- Data for Name: bilancio_spesa; Type: TABLE DATA; Schema: progetto_db; Owner: postgres
--



--
-- Data for Name: conto; Type: TABLE DATA; Schema: progetto_db; Owner: postgres
--

INSERT INTO conto VALUES ('IT223400009834562345', 'Deposito', 'm.taddei92@gmail.com');
INSERT INTO conto VALUES ('IT171000000012345678', 'Credito', 'm.taddei92@gmail.com');


--
-- Data for Name: conto_credito; Type: TABLE DATA; Schema: progetto_db; Owner: postgres
--

INSERT INTO conto_credito VALUES ('IT223400009834562345', 750.00, 750.00, 'm.taddei92@gmail.com', 'IT171000000012345678');


--
-- Data for Name: conto_deposito; Type: TABLE DATA; Schema: progetto_db; Owner: postgres
--

INSERT INTO conto_deposito VALUES ('IT171000000012345678', 5000.00, 'm.taddei92@gmail.com');


--
-- Data for Name: entrata; Type: TABLE DATA; Schema: progetto_db; Owner: postgres
--



--
-- Data for Name: spesa; Type: TABLE DATA; Schema: progetto_db; Owner: postgres
--



--
-- Data for Name: utente; Type: TABLE DATA; Schema: progetto_db; Owner: postgres
--

INSERT INTO utente VALUES ('m.taddei92@gmail.com', 'Marco', 'Taddei', 'Via Crescenzago 26, milano', 'kjgkxbmnzf');
INSERT INTO utente VALUES ('jzsuperstar@gmail.com', 'Jacopo', 'Zemella', 'Via Copernico 31, Cologno Monzese', 'imasuperstar');
INSERT INTO utente VALUES ('giorgio.kill@gmail.com', 'Giorgio', 'Romani', 'Via Calendula 7', 'fxhfjjhg');
INSERT INTO utente VALUES ('jellyfish@yahoo.com', 'Giacomo', 'Roverti', 'Via Stipulti 8', 'dtujgugj');
INSERT INTO utente VALUES ('marcotaddei@fastwebnet.it', 'Marco', 'Taddei', 'Via Crescenzago 26', 'lhdbldsnbicdsb ');
INSERT INTO utente VALUES ('francesmera@gmail.com', 'Francesco', 'Merati', 'Via Guido Guarini Matteucci 1', 'ghirlande');


--
-- Name: bilancio_conto_pkey; Type: CONSTRAINT; Schema: progetto_db; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY bilancio_conto
    ADD CONSTRAINT bilancio_conto_pkey PRIMARY KEY (id_bilancio, iban_deposito, iban_credito);


--
-- Name: bilancio_pkey; Type: CONSTRAINT; Schema: progetto_db; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY bilancio
    ADD CONSTRAINT bilancio_pkey PRIMARY KEY (id);


--
-- Name: bilancio_spesa_pkey; Type: CONSTRAINT; Schema: progetto_db; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY bilancio_spesa
    ADD CONSTRAINT bilancio_spesa_pkey PRIMARY KEY (id_bilancio, data_spesa);


--
-- Name: conto_credito_pkey; Type: CONSTRAINT; Schema: progetto_db; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY conto_credito
    ADD CONSTRAINT conto_credito_pkey PRIMARY KEY (iban);


--
-- Name: conto_deposito_pkey; Type: CONSTRAINT; Schema: progetto_db; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY conto_deposito
    ADD CONSTRAINT conto_deposito_pkey PRIMARY KEY (iban);


--
-- Name: conto_pkey; Type: CONSTRAINT; Schema: progetto_db; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY conto
    ADD CONSTRAINT conto_pkey PRIMARY KEY (iban);


--
-- Name: entrata_pkey; Type: CONSTRAINT; Schema: progetto_db; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY entrata
    ADD CONSTRAINT entrata_pkey PRIMARY KEY (data_movimento);


--
-- Name: spesa_pkey; Type: CONSTRAINT; Schema: progetto_db; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY spesa
    ADD CONSTRAINT spesa_pkey PRIMARY KEY (data_movimento);


--
-- Name: utente_pkey; Type: CONSTRAINT; Schema: progetto_db; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY utente
    ADD CONSTRAINT utente_pkey PRIMARY KEY (mail);


--
-- Name: assegna_conto_credito; Type: TRIGGER; Schema: progetto_db; Owner: postgres
--

CREATE TRIGGER assegna_conto_credito BEFORE INSERT ON conto_credito FOR EACH ROW EXECUTE PROCEDURE insert_conto_credito();


--
-- Name: assegna_conto_deposito; Type: TRIGGER; Schema: progetto_db; Owner: postgres
--

CREATE TRIGGER assegna_conto_deposito BEFORE INSERT ON conto_deposito FOR EACH ROW EXECUTE PROCEDURE insert_conto_deposito();


--
-- Name: bilancio_conto_iban_credito_fkey; Type: FK CONSTRAINT; Schema: progetto_db; Owner: postgres
--

ALTER TABLE ONLY bilancio_conto
    ADD CONSTRAINT bilancio_conto_iban_credito_fkey FOREIGN KEY (iban_credito) REFERENCES conto_credito(iban) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: bilancio_conto_iban_deposito_fkey; Type: FK CONSTRAINT; Schema: progetto_db; Owner: postgres
--

ALTER TABLE ONLY bilancio_conto
    ADD CONSTRAINT bilancio_conto_iban_deposito_fkey FOREIGN KEY (iban_deposito) REFERENCES conto_deposito(iban) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: bilancio_conto_id_bilancio_fkey; Type: FK CONSTRAINT; Schema: progetto_db; Owner: postgres
--

ALTER TABLE ONLY bilancio_conto
    ADD CONSTRAINT bilancio_conto_id_bilancio_fkey FOREIGN KEY (id_bilancio) REFERENCES bilancio(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: bilancio_mail_fkey; Type: FK CONSTRAINT; Schema: progetto_db; Owner: postgres
--

ALTER TABLE ONLY bilancio
    ADD CONSTRAINT bilancio_mail_fkey FOREIGN KEY (mail) REFERENCES utente(mail) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: bilancio_spesa_data_spesa_fkey; Type: FK CONSTRAINT; Schema: progetto_db; Owner: postgres
--

ALTER TABLE ONLY bilancio_spesa
    ADD CONSTRAINT bilancio_spesa_data_spesa_fkey FOREIGN KEY (data_spesa) REFERENCES spesa(data_movimento) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: bilancio_spesa_id_bilancio_fkey; Type: FK CONSTRAINT; Schema: progetto_db; Owner: postgres
--

ALTER TABLE ONLY bilancio_spesa
    ADD CONSTRAINT bilancio_spesa_id_bilancio_fkey FOREIGN KEY (id_bilancio) REFERENCES bilancio(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: conto_credito_iban_deposito_fkey; Type: FK CONSTRAINT; Schema: progetto_db; Owner: postgres
--

ALTER TABLE ONLY conto_credito
    ADD CONSTRAINT conto_credito_iban_deposito_fkey FOREIGN KEY (iban_deposito) REFERENCES conto_deposito(iban) ON UPDATE CASCADE;


--
-- Name: conto_credito_iban_fkey; Type: FK CONSTRAINT; Schema: progetto_db; Owner: postgres
--

ALTER TABLE ONLY conto_credito
    ADD CONSTRAINT conto_credito_iban_fkey FOREIGN KEY (iban) REFERENCES conto(iban) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: conto_credito_mail_fkey; Type: FK CONSTRAINT; Schema: progetto_db; Owner: postgres
--

ALTER TABLE ONLY conto_credito
    ADD CONSTRAINT conto_credito_mail_fkey FOREIGN KEY (mail) REFERENCES utente(mail) ON UPDATE CASCADE;


--
-- Name: conto_deposito_iban_fkey; Type: FK CONSTRAINT; Schema: progetto_db; Owner: postgres
--

ALTER TABLE ONLY conto_deposito
    ADD CONSTRAINT conto_deposito_iban_fkey FOREIGN KEY (iban) REFERENCES conto(iban) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: conto_deposito_mail_fkey; Type: FK CONSTRAINT; Schema: progetto_db; Owner: postgres
--

ALTER TABLE ONLY conto_deposito
    ADD CONSTRAINT conto_deposito_mail_fkey FOREIGN KEY (mail) REFERENCES utente(mail) ON UPDATE CASCADE;


--
-- Name: conto_mail_fkey; Type: FK CONSTRAINT; Schema: progetto_db; Owner: postgres
--

ALTER TABLE ONLY conto
    ADD CONSTRAINT conto_mail_fkey FOREIGN KEY (mail) REFERENCES utente(mail) ON UPDATE CASCADE;


--
-- Name: entrata_iban_fkey; Type: FK CONSTRAINT; Schema: progetto_db; Owner: postgres
--

ALTER TABLE ONLY entrata
    ADD CONSTRAINT entrata_iban_fkey FOREIGN KEY (iban) REFERENCES conto(iban) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: entrata_mail_fkey; Type: FK CONSTRAINT; Schema: progetto_db; Owner: postgres
--

ALTER TABLE ONLY entrata
    ADD CONSTRAINT entrata_mail_fkey FOREIGN KEY (mail) REFERENCES utente(mail) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: spesa_mail_fkey; Type: FK CONSTRAINT; Schema: progetto_db; Owner: postgres
--

ALTER TABLE ONLY spesa
    ADD CONSTRAINT spesa_mail_fkey FOREIGN KEY (mail) REFERENCES utente(mail) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: public; Type: ACL; Schema: -; Owner: postgres
--

REVOKE ALL ON SCHEMA public FROM PUBLIC;
REVOKE ALL ON SCHEMA public FROM postgres;
GRANT ALL ON SCHEMA public TO postgres;
GRANT ALL ON SCHEMA public TO PUBLIC;


--
-- PostgreSQL database dump complete
--

