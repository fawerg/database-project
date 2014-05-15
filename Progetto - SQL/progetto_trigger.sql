-----------------------------------------------------------------------------------
CREATE OR REPLACE FUNCTION insert_conto_deposito() RETURNS TRIGGER AS $$

BEGIN
	INSERT INTO conto VALUES (NEW.iban, 'Deposito', NEW.mail);
	RETURN NEW;
END;

$$LANGUAGE 'plpgsql';

CREATE TRIGGER assegna_conto_deposito BEFORE INSERT ON conto_deposito FOR EACH ROW EXECUTE PROCEDURE insert_conto_deposito();

-------------
CREATE OR REPLACE FUNCTION insert_conto_credito() RETURNS TRIGGER AS $$

BEGIN
	INSERT INTO conto VALUES (NEW.iban, 'Credito', NEW.mail);
	RETURN NEW;
END;

$$LANGUAGE 'plpgsql';

CREATE TRIGGER assegna_conto_credito BEFORE INSERT ON conto_credito FOR EACH ROW EXECUTE PROCEDURE insert_conto_credito();