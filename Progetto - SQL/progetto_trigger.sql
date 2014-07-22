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
		UPDATE final_db.conto SET ammontare = ammontare - NEW.tetto_max WHERE iban = NEW.deposito_riferimento;
		INSERT INTO final_db.conto VALUES (NEW.iban, NEW.tetto_max, 'Credito', my_mail);
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

CREATE TRIGGER conto_transazione BEFORE INSERT OR UPDATE ON transazione FOR EACH ROW EXECUTE PROCEDURE effettua_transazione();
---------------------------------------------------------------------

CREATE OR REPLACE FUNCTION effettua_transazione_programmata() RETURNS VOID AS $$
DECLARE
	data date;
	my_data final_db.transazione.data_transazione%TYPE;

BEGIN
	data=current_date;
	FOR my_data IN SELECT data_transazione FROM final_db.transazione_programmata NATURAL JOIN final_db.transazione WHERE data_operativa=data
	LOOP
	       IF(my_data IS NOT NULL) THEN
	       		DELETE FROM final_db.transazione_programmata WHERE data_transazione=my_data;
		    	UPDATE final_db.transazione SET data_transazione= localtimestamp , tipologia='n' WHERE my_data=data_transazione;
		    
	        END IF;
        END LOOP;
END;
$$ LANGUAGE 'plpgsql';
---------------------------------------------------------------------
CREATE OR REPLACE FUNCTION update_conti_credito() RETURNS VOID AS $$

DECLARE
	my_credito final_db.conto.iban%TYPE;
	my_ammontare2 final_db.conto.ammontare%TYPE;
	my_ammontare final_db.conto.ammontare%TYPE;
	my_tetto final_db.conto_credito.tetto_max%TYPE;
	my_deposito final_db.conto_credito.deposito_riferimento%TYPE;
BEGIN
	FOR my_credito, my_ammontare, my_tetto, my_deposito IN SELECT iban, ammontare, tetto_max, deposito_riferimento FROM final_db.conto NATURAL JOIN final_db.conto_credito
	LOOP
		SELECT ammontare INTO my_ammontare2 FROM final_db.conto WHERE iban=my_deposito;
		UPDATE final_db.conto SET ammontare = my_ammontare2 - (my_tetto - my_ammontare) WHERE iban = my_deposito;
		UPDATE final_db.conto SET ammontare = my_tetto WHERE iban = my_credito;
	END LOOP;
	
END;

$$ LANGUAGE 'plpgsql';
----------------------------------------------------------------------
CREATE OR REPLACE FUNCTION refill_bilancio() RETURNS VOID AS $$
DECLARE
	data date;
	my_date final_db.bilancio.data_scadenza%TYPE;
	my_id final_db.bilancio.id%TYPE;
	my_inizio final_db.bilancio.data_inizio%TYPE;
	my_iban final_db.bilancio.iban%TYPE;
	my_ammontare final_db.conto.ammontare%TYPE;
	my_val1 final_db.bilancio.valore_iniziale%TYPE;
	my_val2 final_db.bilancio.disponibilita%TYPE;
BEGIN
	FOR my_date, my_id, my_inizio , my_iban, my_val1, my_val2 IN SELECT data_scadenza, id , data_inizio, iban, valore_iniziale, disponibilita  FROM final_db.bilancio 
	LOOP
		IF(my_date=data) THEN
			UPDATE final_db.bilancio SET data_inizio=localtimestamp, data_scadenza=my_date+(my_date-my_inizio) WHERE id=my_id;
			SELECT ammontare INTO my_ammontare FROM final_db.conto WHERE iban=my_iban;
			IF(my_ammontare < my_val1-my_val2) THEN
				RAISE EXCEPTION 'Errore: fondi insufficienti per rimpinguare il bilancio.';
			
			ELSE
				UPDATE final_db.conto SET ammontare =  my_ammontare -(my_val1- my_val2);
				UPDATE final_db.bilancio SET disponibilita = my_val1 WHERE id=my_id;
			END IF;
		END IF;
	END LOOP;
END;

$$ LANGUAGE 'plpgsql'
----------------------------------------------------------------------
CREATE OR REPLACE FUNCTION delete_bilancio() RETURNS TRIGGER AS $$

DECLARE
	my_ammontare final_db.conto.ammontare%TYPE;
BEGIN
	
	IF(OLD.disponibilita != 0) THEN
		SELECT ammontare INTO my_ammontare FROM final_db.conto WHERE iban=OLD.iban;
		UPDATE final_db.conto SET ammontare=my_ammontare + OLD.disponibilita WHERE iban=OLD.iban;
	END IF;
	
END;

$$ LANGUAGE 'plpgsql';

CREATE TRIGGER delete_bilancio AFTER DELETE ON final_db.bilancio FOR EACH ROW EXECUTE PROCEDURE delete_bilancio();
-------------------------------------------------------------------------

CREATE OR REPLACE FUNCTION fill_bilancio() RETURNS TRIGGER AS $$

DECLARE
	my_ammontare final_db.conto.ammontare%TYPE;
BEGIN
	SELECT ammontare INTO my_ammontare FROM final_db.conto WHERE iban = NEW.iban;
	UPDATE final_db.conto SET ammontare = my_ammontare - NEW.disponibilita WHERE iban=NEW.iban;
	RETURN NEW;
END;

$$ LANGUAGE 'plpgsql';

CREATE TRIGGER fill_bilancio BEFORE INSERT ON final_db.bilancio FOR EACH ROW EXECUTE PROCEDURE fill_bilancio();
------------------------------------------------------------------------
CREATE OR REPLACE FUNCTION delete_conti_credito() RETURNS TRIGGER AS $$

DECLARE
	my_id final_db.bilancio.id%TYPE;
	
	
	my_ammontare final_db.conto.ammontare%TYPE;
	my_ammontare2 final_db.conto.ammontare%TYPE;
	my_deposito final_db.conto_credito.deposito_riferimento%TYPE;
	my_iban final_db.conto.iban%TYPE;
BEGIN	
	SELECT iban into my_iban FROM final_db.conto_credito WHERE iban=OLD.iban;
	IF(my_iban IS NOT NULL) THEN
		FOR my_id IN SELECT id FROM final_db.bilancio WHERE iban=OLD.iban 
		LOOP
			DELETE FROM final_db.bilancio WHERE id=my_id;
		END LOOP;
		SELECT ammontare, deposito_riferimento INTO my_ammontare, my_deposito FROM final_db.conto NATURAL JOIN final_db.conto_credito WHERE iban=OLD.iban;
		IF(my_ammontare >=0) THEN
			SELECT  ammontare INTO my_ammontare2 FROM final_db.conto WHERE iban=my_deposito;
			UPDATE final_db.conto SET ammontare=my_ammontare2 + my_ammontare WHERE iban=my_deposito;
		END IF;
	END IF;
	RETURN NEW;
END;

$$ LANGUAGE 'plpgsql';

CREATE TRIGGER delete_conto_credito BEFORE DELETE ON final_db.conto FOR EACH ROW EXECUTE PROCEDURE delete_conti_credito();

