CREATE TABLE utente(
	mail varchar(100) PRIMARY KEY,
	nome varchar(30) NOT NULL,
	cognome varchar(30) NOT NULL,
	indirizzo varchar(100) NOT NULL
);

CREATE TABLE conto_deposito(
	iban varchar(32) PRIMARY KEY,
	disponibilita numeric(12 ,2) NOT NULL DEFAULT 0.00,
	mail varchar(100)  NOT NULL REFERENCES utente(mail) ON DELETE NO ACTION ON UPDATE CASCADE
);

CREATE TABLE conto_credito(
	iban varchar(32) PRIMARY KEY,
	disponibilita numeric(12 ,2) NOT NULL DEFAULT 0.00,
	tetto_max numeric(12, 2) NOT NULL DEFAULT 0.00,
	mail varchar(100)  NOT NULL REFERENCES utente(mail) ON DELETE CASCADE ON UPDATE CASCADE,
	iban_deposito varchar(32) NOT NULL REFERENCES conto_deposito(iban) ON DELETE NO ACTION ON UPDATE CASCADE,
	CHECK (disponibilita <= tetto_max)
);

CREATE TABLE entrata(
	data_entrata timestamp PRIMARY KEY,
	entita_economica numeric(12, 2) NOT NULL,
	descrizione varchar(100) DEFAULT 'Nessuna informazione disponibile',
	iban_deposito varchar(32) REFERENCES conto_deposito(iban) ON DELETE NO ACTION ON UPDATE CASCADE,
	iban_credito varchar(32) REFERENCES conto_credito(iban) ON DELETE NO ACTION ON UPDATE CASCADE,
	mail varchar(100) NOT NULL REFERENCES utente(mail) ON DELETE CASCADE ON UPDATE CASCADE,
	CHECK ((iban_deposito IS NULL OR iban_credito IS NULL) AND (iban_deposito IS NOT NULL AND iban_credito IS NOT NULL))
);

CREATE TABLE spesa(
	data_spesa timestamp PRIMARY KEY,
	entita_economica numeric(12, 2) NOT NULL,
	descrizione varchar(100) DEFAULT 'Nessuna informazione disponibile',
	iban_deposito varchar(32) REFERENCES conto_deposito(iban) ON DELETE NO ACTION ON UPDATE CASCADE,
	iban_credito varchar(32) REFERENCES conto_credito(iban) ON DELETE NO ACTION ON UPDATE CASCADE,
	mail varchar(100) NOT NULL REFERENCES utente(mail) ON DELETE CASCADE ON UPDATE CASCADE,
	CHECK ((iban_deposito IS NULL OR iban_credito IS NULL) AND (iban_deposito IS NOT NULL AND iban_credito IS NOT NULL))
);

CREATE TABLE bilancio(
	id_bilancio varchar(8) PRIMARY KEY,
	quantita numeric(12, 2) DEFAULT 0.00,
	valore_iniziale numeric(12, 2) DEFAULT 0.00,
	scandenza date DEFAULT now(),
	periodo interval NOT NULL,
	mail varchar(100) NOT NULL REFERENCES utente(mail) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE spesa_bilancio(
	data_spesa timestamp NOT NULL REFERENCES spesa(data_spesa),
	id_bilancio varchar(8) NOT NULL REFERENCES bilancio(id_bilancio),
	PRIMARY KEY(data_spesa, id_bilancio)
);

CREATE TABLE conto_bilancio(
	id_bilancio varchar(8) NOT NULL REFERENCES bilancio(id_bilancio),
	iban_deposito varchar(32) REFERENCES conto_deposito(iban) ON DELETE NO ACTION ON UPDATE CASCADE,
	iban_credito varchar(32) REFERENCES conto_credito(iban) ON DELETE NO ACTION ON UPDATE CASCADE,
	CHECK ((iban_deposito IS NULL OR iban_credito IS NULL) AND (iban_deposito IS NOT NULL AND iban_credito IS NOT NULL)),
	PRIMARY KEY(id_bilancio, iban_deposito, iban_credito)
);
	




