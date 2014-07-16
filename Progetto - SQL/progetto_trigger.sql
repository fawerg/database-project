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
	my_mail final_db.conto.mail%TYPE;

BEGIN
	SELECT mail INTO my_mail FROM final_db.conto WHERE iban = New.iban;
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
	SELECT ammontare, mail INTO my_ammontare, my_mail FROM final_db.conto WHERE iban = NEW.deposito_riferimento;
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

-------------------------------------------------------------
CREATE OR REPLACE FUNCTION effettua_transazione() RETURNS TRIGGER AS $$

DECLARE
	my_tipo final_db.categoria.tipo%TYPE;
	my_ammontare final_db.conto.ammontare%TYPE;
	my_id final_db.bilancio.id%TYPE;
	my_disponibilita final_db.bilancio.disponibilita%TYPE;
	
BEGIN
	IF(NEW.tipologia='n') THEN
		SELECT tipo INTO my_tipo FROM final_db.categoria WHERE nome = NEW.nome;
		SELECT ammontare INTO my_ammontare FROM final_db.conto WHERE iban = NEW.iban;
		IF(my_tipo ='-') THEN
			SELECT id, disponibilita INTO my_id, my_disponibilita FROM final_db.bilancio NATURAL JOIN final_db.categoria_bilancio WHERE mail = NEW.mail AND nome = NEW.nome;
			IF((my_id IS NULL) OR (my_disponibilita - NEW.entita_economica < 0)) THEN
				IF(my_ammontare - NEW.entita_economica < 0) THEN
					RAISE EXCEPTION 'Errore: fondi insufficienti per effettuare la spesa.';
				ELSE
					UPDATE final_db.conto SET ammontare = ammontare - NEW.entita_economica WHERE iban = NEW.iban;
				END IF;
			ELSE
				UPDATE final_db.bilancio SET disponibilita = disponibilita - NEW.entita_economica WHERE id = my_id;
			END IF;
		ELSE
			UPDATE final_db.conto SET ammontare = ammontare + NEW.entita_economica WHERE iban = NEW.iban;
		END IF;
	END IF;
	RETURN NEW;
END;

$$ LANGUAGE 'plpgsql';

CREATE TRIGGER conto_transazione BEFORE INSERT ON transazione FOR EACH ROW EXECUTE PROCEDURE effettua_transazione();

---------------------------------------------------------------------
CREATE OR REPLACE FUNCTION update_conti_credito() RETURNS TRIGGER AS $$

DECLARE
	my_mese final_db.scheduler.mese%TYPE;
	my_credito final_db.conto.iban%TYPE;
	my_ammontare final_db.conto.ammontare%TYPE;
	my_tetto final_db.conto_credito.tetto_max%TYPE;
	my_deposito final_db.conto_credito.deposito_riferimento%TYPE;
BEGIN
	SELECT mese INTO my_mese FROM final_db.scheduler WHERE id = '0';
	IF(my_mese < NEW.mese) THEN
		FOR my_credito, my_ammontare, my_tetto, my_deposito IN SELECT iban, ammontare, tetto_max, deposito_riferimento FROM final_db.conto NATURAL JOIN final_db.conto_credito
		LOOP
			UPDATE final_db.conto SET ammontare = ammontare - my_tetto - my_ammontare WHERE iban = my_deposito;
			UPDATE final_db.conto SET ammontare = my_tetto WHERE iban = my_credito;
		END LOOP;
	END IF;
	RETURN NEW;
END;

$$ LANGUAGE 'plpgsql';

CREATE TRIGGER update_conti AFTER INSERT ON scheduler FOR EACH ROW EXECUTE PROCEDURE update_conti_credito();

----------------------------------------------------------------------
CREATE OR REPLACE FUNCTION delete_conti_credito() RETURNS TRIGGER AS $$

DECLARE
	my_mese final_db.scheduler.mese%TYPE;
	my_credito final_db.conto.iban%TYPE;
	my_ammontare final_db.conto.ammontare%TYPE;
	my_tetto final_db.conto_credito.tetto_max%TYPE;
	my_deposito final_db.conto_credito.deposito_riferimento%TYPE;
BEGIN
	SELECT mese INTO my_mese FROM final_db.scheduler WHERE id = '0';
	IF(my_mese < NEW.mese) THEN
		FOR my_credito, my_ammontare, my_tetto, my_deposito IN SELECT iban, ammontare, tetto_max, deposito_riferimento FROM final_db.conto NATURAL JOIN final_db.conto_credito
		LOOP
			UPDATE final_db.conto SET ammontare = ammontare - my_tetto - my_ammontare WHERE iban = my_deposito;
			UPDATE final_db.conto SET ammontare = my_tetto WHERE iban = my_credito;
		END LOOP;
	END IF;
	RETURN NEW;
END;

$$ LANGUAGE 'plpgsql';

CREATE TRIGGER update_conti AFTER INSERT ON scheduler FOR EACH ROW EXECUTE PROCEDURE delete_conti_credito();

