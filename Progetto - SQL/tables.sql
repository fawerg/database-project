CREATE TABLE utente(
	mail varchar(100) PRIMARY KEY,
	nome varchar(30) NOT NULL,
	cognome varchar(30) NOT NULL,
	indirizzo varchar(100) NOT NULL,
	password varchar(20) NOT NULL,
	CHECK (length(password) > 7)
);

CREATE TABLE categoria(
	nome varchar(20) NOT NULL,
	tipo char(1) NOT NULL,
	mail varchar(100) NOT NULL REFERENCES utente(mail) ON UPDATE CASCADE ON DELETE CASCADE,
	nome_padre varchar(20),
	mail_padre varchar(100),
	FOREIGN KEY (nome_padre, mail_padre) REFERENCES categoria(nome, mail) ON UPDATE CASCADE ON DELETE CASCADE,
	PRIMARY KEY(nome, mail),
	CHECK (tipo IN ('+', '-'))
);

CREATE TABLE conto(
	iban varchar(32) PRIMARY KEY,
	ammontare numeric(12, 2) NOT NULL,
	tipologia varchar(8) NOT  NULL,
	mail varchar(100) NOT NULL REFERENCES utente(mail) ON UPDATE CASCADE ON DELETE NO ACTION,
	CHECK (ammontare >= 0.00),
	CHECK (tipologia IN ('Deposito', 'Credito'))
);

CREATE TABLE conto_credito(
	iban varchar(32) PRIMARY KEY REFERENCES conto(iban) ON UPDATE CASCADE ON DELETE CASCADE,
	tetto_max numeric(12,2) NOT NULL,
	deposito_riferimento varchar(32) REFERENCES conto(iban) ON UPDATE CASCADE ON DELETE NO ACTION
);

CREATE TABLE transazione(
	data_transazione timestamp,
	descrizione varchar(100),
	entita_economica numeric(12, 2) NOT NULL,
	iban varchar(32) NOT NULL REFERENCES conto(iban) ON UPDATE CASCADE ON DELETE CASCADE,
	mail varchar(100) NOT NULL,
	nome varchar(20) NOT NULL,
	FOREIGN KEY (mail, nome) REFERENCES categoria(mail, nome) ON UPDATE CASCADE ON DELETE NO ACTION,
	PRIMARY KEY(data_transazione, iban),
	CHECK (entita_economica >= 0.00)
);

CREATE TABLE bilancio(
	id varchar(8) PRIMARY KEY,
	disponibilita numeric(12, 2) NOT NULL,
	valore_iniziale numeric(12, 2) NOT NULL,
	data_inizio date DEFAULT now(),
	data_scadenza date NOT NULL,
	iban varchar(32) NOT NULL REFERENCES conto(iban) ON UPDATE CASCADE ON DELETE NO ACTION,
	mail varchar(100) NOT NULL REFERENCES utente(mail) ON UPDATE CASCADE ON DELETE NO ACTION
	CHECK (valore_iniziale >= 0.00)
);

CREATE TABLE categoria_bilancio(
	id varchar(8) NOT NULL REFERENCES bilancio(id) ON UPDATE CASCADE ON DELETE CASCADE,
	mail varchar(100) NOT NULL,
	nome varchar(20) NOT NULL,
	FOREIGN KEY (mail, nome) REFERENCES categoria(mail, nome) ON UPDATE CASCADE ON DELETE CASCADE,
	PRIMARY KEY(id, mail, nome)
);
---------------------------------------------------------------------------------------------------------

CREATE TABLE utente(
	mail varchar(100) PRIMARY KEY,
	nome varchar(30) NOT NULL,
	cognome varchar(30) NOT NULL,
	indirizzo varchar(100) NOT NULL,
	password varchar(20) NOT NULL,
	CHECK (length(password) > 7)
);

CREATE TABLE categoria(
	nome varchar(50) NOT NULL,
	padre varchar(50) NOT NULL,
	utente varchar(100) NOT NULL REFERENCES utente(mail) ON DELETE CASCADE ON UPDATE CASCADE,
	PRIMARY KEY(nome, utente)
);

CREATE TABLE conto(
	iban varchar(32) PRIMARY KEY,
	mail varchar(100) NOT NULL REFERENCES utente (mail) ON DELETE CASCADE ON UPDATE CASCADE,
	tipo_conto varchar(8) NOT NULL,
	stato_conto varchar(6) NOT NULL,
	CHECK (tipo_conto IN ('deposito', 'credito')),
	CHECK (stato_conto IN ('aperto', 'chiuso'))
);

CREATE TABLE conto_deposito(
	iban varchar(32) PRIMARY KEY REFERENCES conto(iban) ON UPDATE CASCADE ON DELETE CASCADE,
	mail varchar(100) NOT NULL REFERENCES utente(mail) ON UPDATE CASCADE ON DELETE CASCADE,
	disponibilita numeric(12, 2) NOT NULL DEFAULT 0.00,
	CHECK (disponibilita >= 0.00)
);

CREATE TABLE conto_credito(
	iban varchar(32) PRIMARY KEY REFERENCES conto(iban) ON UPDATE CASCADE ON DELETE CASCADE,
	iban_deposito varchar(32) REFERENCES conto_deposito(iban) ON UPDATE CASCADE ON DELETE CASCADE,
	mail varchar(100)  NOT NULL REFERENCES utente(mail) ON UPDATE CASCADE ON DELETE CASCADE,
	tetto_max numeric(12, 2) NOT NULL DEFAULT 0.00,
	disponibilita numeric(12, 2) NOT NULL DEFAULT 0.00,
	CHECK ((disponibilita BETWEEN 0.00 AND tetto_max) AND (tetto_max > 0.00))
);

CREATE TABLE entrata(
	data_entrata timestamp PRIMARY KEY,
	entita_economica numeric(12, 2) NOT NULL,
	descrizione varchar(100) DEFAULT 'Nessuna informazione disponibile',
	iban varchar(32) REFERENCES conto(iban) ON UPDATE CASCADE ON DELETE CASCADE,
	categoria varchar(50) DEFAULT 'Entrata',
	CHECK (entita_economica >= 0.00)
);


CREATE TABLE spesa(
	data_spesa timestamp PRIMARY KEY,
	entita_economica numeric(12, 2) NOT NULL,
	iban varchar(32) REFERENCES conto(iban) ON UPDATE CASCADE ON DELETE CASCADE,
	categoria varchar(50) DEFAULT 'Spesa',
	mail varchar(100) NOT NULL,
	FOREIGN KEY (categoria, mail) REFERENCES categoria(nome, mail) ON DELETE SET NULL ON UPDATE CASCADE,
	CHECK (entita_economica >= 0.00)
);

CREATE TABLE bilancio(
	id varchar(8) PRIMARY KEY,
	ammontare numeric(12, 2) DEFAULT 0.00,
	valore_iniziale numeric(12, 2) DEFAULT 0.00,
	descrizione varchar(100) DEFAULT 'Nessuna informazione disponibile',
	scandenza date DEFAULT now(),
	periodo interval NOT NULL,
	CHECK (valore_iniziale >= 0.00)
);

CREATE TABLE conto_bilancio(
	id_bilancio varchar(8) REFERENCES bilancio(id) ON DELETE CASCADE ON UPDATE CASCADE,
	iban varchar(32) REFERENCES conto(iban) ON DELETE CASCADE ON UPDATE CASCADE,
	PRIMARY KEY(id_bilancio, iban)
);

CREATE TABLE bilancio_categoria(
	id_bilancio varchar(8) NOT NULL REFERENCES bilancio(id) ON DELETE CASCADE ON UPDATE CASCADE,
	categoria varchar(50) NOT NULL,
	mail varchar(100) NOT NULL,
	FOREIGN KEY (categoria, mail) REFERENCES categoria(nome, mail) ON DELETE SET NULL ON UPDATE CASCADE,
	PRIMARY KEY(id_bilancio, categoria, mail)
);




