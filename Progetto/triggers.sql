CREATE TRIGGER create_conto_1 BEFORE INSERT ON conto_deposito FOR EACH ROW EXECUTE PROCEDURE add_conto_from_deposito();

CREATE TRIGGER create_conto_2 BEFORE INSERT ON conto_credito FOR EACH ROW EXECUTE PROCEDURE add_conto_from_credito();

CREATE TRIGGER delete_conto_1 AFTER DELETE ON conto_deposito FOR EACH ROW EXECUTE PROCEDURE delete_conto();

CREATE TRIGGER delete_conto_2 AFTER DELETE ON conto_credito FOR EACH ROW EXECUTE PROCEDURE delete_conto();

CREATE TRIGGER check_spesa BEFORE INSERT ON spesa FOR EACH ROW EXECUTE PROCEDURE effettua_spesa();

CREATE TRIGGER set_entrata BEFORE INSERT ON entrata FOR EACH ROW EXECUTE PROCEDURE effettua_entrata();

--CREATE TRIGGER cat_base AFTER INSERT ON utente FOR EACH ROW EXECUTE PROCEDURE set_cat_base();