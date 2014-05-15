CREATE OR REPLACE FUNCTION create_conto_deposito(iban varchar(32), mail varchar(100), disponibilita numeric(12, 2)) RETURNS VOID AS $$
	BEGIN
		INSERT INTO conto_deposito VALUES (iban, mail, disponibilita);
	END;
	
$$LANGUAGE 'plpgsql';


CREATE OR REPLACE FUNCTION get_disponibilita(varchar(32)) RETURNS numeric(12, 2) AS $$
	DECLARE
		disp conto_deposito.disponibilita%TYPE;
		tipo conto.tipo_conto%TYPE;
	BEGIN
		SELECT tipo_conto INTO tipo FROM conto WHERE conto.iban = $1;
		IF(tipo = 'deposito') THEN
			SELECT disponibilita INTO disp FROM conto_deposito WHERE iban = $1;
		ELSE
			SELECT disponibilita INTO disp FROM conto_credito WHERE iban = $1;
		END IF;
		RETURN disp;
	END;
	
$$LANGUAGE 'plpgsql';


CREATE OR REPLACE FUNCTION create_conto_credito(iban varchar(32), iban_2 varchar(32), tetto_max numeric(12, 2)) RETURNS VOID AS $$
	DECLARE
		disp conto_deposito.disponibilita%TYPE;
		my_mail conto_deposito.mail%TYPE;
	BEGIN
		SELECT disponibilita INTO disp FROM conto_deposito WHERE conto_deposito.iban = iban_2;
		SELECT mail INTO my_mail FROM conto_deposito WHERE conto_deposito.iban = iban_2;
		IF(disp >= tetto_max) THEN
			disp = tetto_max;
		END IF;
		INSERT INTO conto_credito VALUES (iban, iban_2, my_mail, tetto_max, disp);
	END;
	
$$LANGUAGE 'plpgsql';


CREATE OR REPLACE FUNCTION add_conto_from_deposito() RETURNS TRIGGER AS $$
	BEGIN
		INSERT INTO conto VALUES (NEW.iban, NEW.mail, 'deposito', 'aperto');
		RETURN NEW;
	END;

$$LANGUAGE 'plpgsql';

CREATE OR REPLACE FUNCTION add_conto_from_credito() RETURNS TRIGGER AS $$
	BEGIN
		INSERT INTO conto VALUES (NEW.iban, NEW.mail, 'credito', 'aperto');
		RETURN NEW;
	END;

$$LANGUAGE 'plpgsql';


CREATE OR REPLACE FUNCTION delete_conto() RETURNS TRIGGER AS $$
	BEGIN
		UPDATE conto SET stato_conto = 'chiuso' WHERE conto.iban = OLD.iban;
		RETURN NEW;
	END;
$$LANGUAGE 'plpgsql';


CREATE OR REPLACE FUNCTION get_mail(varchar(32)) RETURNS varchar(100) AS $$
	DECLARE 
		my_mail conto.mail%TYPE;
	BEGIN
		SELECT mail INTO my_mail FROM conto WHERE iban = $1;
		
		RETURN my_mail;
	END;
$$LANGUAGE 'plpgsql';

CREATE OR REPLACE FUNCTION get_tipo_conto(varchar(32)) RETURNS varchar(100) AS $$
	DECLARE 
		my_tipo_conto conto.tipo_conto%TYPE;
	BEGIN
		SELECT tipo_conto INTO my_tipo_conto FROM conto WHERE iban = $1;
		
		RETURN my_tipo_conto;
	END;
$$LANGUAGE 'plpgsql';

CREATE OR REPLACE FUNCTION get_stato_conto(varchar(32)) RETURNS varchar(6) AS $$
	DECLARE 
		my_stato_conto conto.stato_conto%TYPE;
	BEGIN
		SELECT stato_conto INTO my_stato_conto FROM conto WHERE iban = $1;
		
		RETURN my_stato_conto;
	END;
$$LANGUAGE 'plpgsql';


CREATE OR REPLACE FUNCTION get_categoria_padre(varchar(32)) RETURNS varchar(50) AS $$
	DECLARE
		my_categoria_padre spesa.categoria%TYPE;
	BEGIN
		SELECT categoria_padre INTO my_categoria_padre FROM bilancio WHERE categoria = get_categoria($1) AND iban = $1;
	RETURN my_categoria_padre;
	END;
$$LANGUAGE 'plpgsql';


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


CREATE OR REPLACE FUNCTION effettua_entrata() RETURNS TRIGGER AS $$
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
			IF(my_tipo_conto = 'credito') THEN
				RAISE EXCEPTION 'Impossibile effettuare su conti di credito.';
			ELSE
				UPDATE conto_deposito SET disponibilita = disponibilita + my_entita_economica WHERE conto_deposito.iban = my_iban;
			END IF;
		ELSE
			RAISE EXCEPTION 'Il conto è stato chiuso.';
		
		END IF;
		RETURN NEW;
		END;
$$LANGUAGE 'plpgsql';

/*CREATE OR REPLACE FUNCTION set_cat_base() RETURNS TRIGGER AS $$
	BEGIN	
		INSERT INTO categoria VALUES ('Spesa', 'Spesa', NEW.mail);
		INSERT INTO categoria VALUES ('Entrata', 'Entrata', NEW.mail);
		RETURN NEW;
	END;
$$LANGUAGE 'plpgsql';*/	

CREATE OR REPLACE FUNCTION get_bilanci(varchar(32)) RETURNS VOID AS $$
	BEGIN
		SELECT * FROM bilancio 	WHERE bilancio.iban = $1;
	END;
$$LANGUAGE 'plpgsql';

	


