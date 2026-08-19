
CREATE TABLE IF NOT EXISTS produits (
    id             SERIAL PRIMARY KEY,
    nom            VARCHAR(150) NOT NULL,
    description    TEXT NOT NULL DEFAULT '',
    prix           NUMERIC(10, 2) NOT NULL DEFAULT 0,
    quantite       INTEGER NOT NULL DEFAULT 0,
    image          BYTEA,
    image_type     VARCHAR(50),
    date_creation  TIMESTAMP NOT NULL DEFAULT NOW()
);
