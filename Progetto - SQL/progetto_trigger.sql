-------------

CREATE OR REPLACE FUNCTION insert_root_categories() RETURNS TRIGGER AS $$

BEGIN
	INSERT INTO categoria VALUES ('Spesa', '-', NEW.mail, NULL, NULL);
	INSERT INTO categoria VALUES ('Entrata', '+', NEW.mail, NULL, NULL);
	RETURN NEW;
END;

$$ LANGUAGE 'plpgsql';

CREATE TRIGGER create_root_categories AFTER INSERT ON utente FOR EACH ROW EXECUTE PROCEDURE insert_root_categories();

-------------

CREATE TRIGGER rinnova_bilancio WHEN 