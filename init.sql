CREATE TABLE Categorie (
    id INT PRIMARY KEY,
    titre VARCHAR(100)
);

CREATE TABLE Article (
    id SERIAL PRIMARY KEY,
    titre VARCHAR(100),
    contenu TEXT,
    dat TIMESTAMP,
    categorie INT,
    CONSTRAINT fk_categorie FOREIGN KEY(categorie) REFERENCES Categorie(id)
);


INSERT INTO Categorie(id, titre) VALUES (1, 'Première catégorie'),(2, 'Seconde catégorie');

INSERT INTO Article(titre, contenu, dat, categorie) VALUES 
('Premier article', 'Contenu du premier article', '2024-08-20 12:15:32', 1),
('Second article', 'Contenu du second article', '2024-08-21 12:15:32', 1),
('Troisième article', 'Contenu du troisième article', '2024-08-20 12:15:32', 2);
