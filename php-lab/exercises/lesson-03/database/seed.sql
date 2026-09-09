INSERT INTO autori (nome) VALUES
    ('Autore Alpha'),
    ('Autore Beta'),
    ('Autore Gamma'),
    ('Autore Delta');

INSERT INTO generi (nome) VALUES
    ('Pop'),
    ('Rock'),
    ('Jazz');

INSERT INTO brani
    (titolo, autore_id, genere_id, durata_minuti, anno)
VALUES
    ('Aurora', 1, 1, 3.45, 1992),
    ('Blue River', 2, 3, 4.12, 1998),
    ('City Lights', 3, 1, 3.58, 2001),
    ('Dream Road', 4, 2, 4.31, 2005),
    ('Echoes', 1, 2, 5.02, 1989),
    ('Falling Star', 2, 1, 3.27, 2010),
    ('Golden Day', 3, 3, 4.44, 1995),
    ('Hidden Moon', 4, NULL, 3.51, NULL),
    ('Into the Night', 1, 2, 4.08, 2003),
    ('January Rain', 2, 1, 3.39, 1999),
    ('Kind of Morning', 3, 3, 5.11, 1987),
    ('Long Way Home', 4, 2, 4.25, 2007),
    ('Morning Sun', 1, 1, 3.33, 2012),
    ('Northern Sky', 2, NULL, 4.17, 1994),
    ('Open Road', 3, 2, 3.56, 2000);
