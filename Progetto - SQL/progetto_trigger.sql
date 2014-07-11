-------------
CREATE OR REPLACE FUNCTION insert_root_categories() RETURNS TRIGGER AS $$

BEGIN
	INSERT INTO final_db.categoria VALUES ('Spesa', '-', NEW.mail, NULL, NULL);
	INSERT INTO final_db.categoria VALUES ('Entrata', '+', NEW.mail, NULL, NULL);
	RETURN NEW;
END;

$$ LANGUAGE 'plpgsql';

CREATE TRIGGER create_root_categories AFTER INSERT ON utente FOR EACH ROW EXECUTE PROCEDURE insert_root_categories();

-------------
CREATE OR REPLACE FUNCTION check_bilancio_user_iban() RETURNS TRIGGER AS $$

DECLARE
	my_mail conto.mail%TYPE;

BEGIN
	SELECT mail INTO my_mail FROM conto WHERE iban = New.iban;
	IF(my_mail != NEW.mail) THEN
		RAISE EXCEPTION 'Conto non associato all utente specificato.';
	END IF;
	RETURN NEW;
END;

$$ LANGUAGE 'plpgsql';

CREATE TRIGGER check_info_bilancio BEFORE INSERT ON bilancio FOR EACH ROW EXECUTE PROCEDURE check_bilancio_user_iban();

-------------
CREATE OR REPLACE FUNCTION fill_cred_from_dep() RETURNS TRIGGER AS $$

DECLARE
	my_ammontare conto.ammontare%TYPE;
	my_mail conto.mail%TYPE;

BEGIN
	SELECT ammontare, mail INTO my_ammontare, my_mail FROM conto WHERE iban = NEW.deposito_riferimento;
	IF(my_ammontare - NEW.tetto_max < 0) THEN
		RAISE EXCEPTION 'Errore: fondi insufficienti per creare il conto di credito.';
	ELSE
		UPDATE conto SET ammontare = ammontare - NEW.tetto_max WHERE iban = NEW.deposito_riferimento;
		INSERT INTO conto VALUES (NEW.iban, NEW.tetto_max, 'Credito', my_mail);
	END IF;
	RETURN NEW;
END;

$$ LANGUAGE 'plpgsql';

CREATE TRIGGER cred_dep BEFORE INSERT ON conto_credito FOR EACH ROW EXECUTE PROCEDURE fill_cred_from_dep();