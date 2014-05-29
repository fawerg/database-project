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

