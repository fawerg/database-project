INSERT INTO utente VALUES ('mariorossi@gmail.it', 'Mario', 'Rossi', 'via Botticelli 31, Milano', 'abcd');
INSERT INTO utente VALUES ('carloverdi@libero.it', 'Carlo', 'Verdi', 'Piazza San Marco 5, Monza', '1111');
INSERT INTO utente VALUES ('franconeri1990@gmail.it', 'Franco', 'Neri', 'via Golgi 3, Milano', '1234');
INSERT INTO conto_deposito VALUES ('abcgderop', 'mariorossi@gmail.it', 12000);
INSERT INTO conto_deposito VALUES ('12340002', 'franconeri1990@gmail.it', 9000);
INSERT INTO conto_deposito VALUES ('123de3s2', 'carloverdi@libero.it', 3000);
INSERT INTO conto_deposito VALUES ('0TR33jde3s2', 'carloverdi@libero.it', 2000);
SELECT create_conto_deposito('1RRRRde3s2', 'carloverdi@libero.it', 9000);
SELECT create_conto_credito('T00000123OE4', '123de3s2', 4000);
INSERT INTO spesa VALUES (now(), 200, 'Vacanza', '0TR33jde3s2');
INSERT INTO entrata VALUES (now(), 223456, 'Stipendio', '0TR33jde3s2');
--INSERT INTO bilancio VALUES ('00000000', 2000, 20000, '2020-12-3', 2 mounth, '123de3s2');
DELETE FROM conto_deposito WHERE iban = '12340002';