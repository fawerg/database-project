select * from utente;
select * from conto;
select * from conto_credito;
select * from conto_deposito;
delete * from conto_credito;
CHECK entrata


CREATE OR REPLACE FUNCTION effettua_spesa() RETURNS TRIGGER AS $$
	DECLARE
		my_iban conto.iban%TYPE;
		my_tipo_conto conto.tipo_conto%TYPE;
		my_stato_conto conto.stato_conto%TYPE;
		my_entita_economica spesa.entita_economica%TYPE;
		
	BEGIN
		my_iban = NEW.iban;
		my_tipo_conto = get_tipo_conto(my_iban);
		my_stato_conto = get_stato_conto(my_iban);
		my_entita_economica = NEW.entita_economica;
		
		IF(get_disponibilita(my_iban) < my_entita_economica) THEN
			RAISE EXCEPTION 'La spesa è eccessiva.';
		ELSE
			IF(my_stato_conto = 'aperto') THEN
				IF(my_tipo_conto = 'deposito') THEN
					UPDATE conto_deposito SET disponibilita = disponibilita - my_entita_economica WHERE conto_deposito.iban = my_iban;
				ELSE
					UPDATE conto_credito SET disponibilita = disponibilita - my_entita_economica WHERE conto_credito.iban = my_iban;
				END IF;
			ELSE
				RAISE EXCEPTION 'Il conto è stato chiuso.';
			END IF;
		END IF;
		RETURN NEW;
	END;
$$LANGUAGE 'plpgsql';