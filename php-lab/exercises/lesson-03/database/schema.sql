CREATE TABLE autori (
    autore_id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL
);

CREATE TABLE generi (
    genere_id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(50) NOT NULL
);

CREATE TABLE brani (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titolo VARCHAR(150) NOT NULL,
    autore_id INT NOT NULL,
    genere_id INT NULL,
    durata_minuti DECIMAL(4,2) NULL,
    anno INT NULL,
    CONSTRAINT fk_brani_autori
        FOREIGN KEY (autore_id) REFERENCES autori(autore_id),
    CONSTRAINT fk_brani_generi
        FOREIGN KEY (genere_id) REFERENCES generi(genere_id)
);
