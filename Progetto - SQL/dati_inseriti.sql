INSERT INTO final_db.utente VALUES ('mariorossi@gmail.it', 'Mario', 'Rossi', 'via Botticelli 31, Milano', 'abcdefgh');
INSERT INTO final_db.utente VALUES ('carloverdi@libero.it', 'Carlo', 'Verdi', 'Piazza San Marco 5, Monza', '11111111');
INSERT INTO final_db.utente VALUES ('franconeri1990@gmail.it', 'Franco', 'Neri', 'via Golgi 3, Milano', '12345678');
INSERT INTO final_db.utente VALUES ('francesmera@gmail.com' , 'Francesco', 'Merati', 'via guido guarini matteucci 1, Milano', 'ghirlande');

INSERT INTO final_db.conto VALUES ('IT240834582870105450756518472284', 'mariorossi@gmail.it', 'deposito', 12000);
INSERT INTO final_db.conto VALUES ('IT535007746124574129532243390948', 'franconeri1990@gmail.it','deposito', 9000);
INSERT INTO final_db.conto VALUES ('IT410490417899136751944426761926', 'carloverdi@libero.it','deposito', 3000);
INSERT INTO final_db.conto VALUES ('IT445040250997749818210557972942', 'francesmera@gmail.com','deposito', 20000);
INSERT INTO final_db.conto VALUES ('IT240834582870105450756518472284', 'francesmera@gmail.com','deposito', '10000');
INSERT INTO final_db.conto VALUES ('IT535007746124574129532243390948', 'francesmera@gmail.com','credito', '2000');
INSERT INTO final_db.conto VALUES ('IT535007746124574129532243390947', 'francesmera@gmail.com','credito', '1000');

INSERT INTO final_db.conto_credito VALUES ('IT535007746124574129532243390948', '2000', 'IT445040250997749818210557972942');
INSERT INTO final_db.conto_credito VALUES ('IT535007746124574129532243390947', '1000', 'IT240834582870105450756518472284');

INSERT INTO final_db.categoria VALUES 

