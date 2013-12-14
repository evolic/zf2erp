CREATE TABLE companies (
  id SERIAL NOT NULL,
  country_id INT DEFAULT NULL,

  name VARCHAR(127) NOT NULL,
  city VARCHAR(63) NOT NULL,
  postcode VARCHAR(7) NOT NULL,
  address VARCHAR(63) NOT NULL,
  vatin VARCHAR(31) NOT NULL,
  ein VARCHAR(31) NOT NULL,
  bein VARCHAR(31) DEFAULT NULL,

  PRIMARY KEY(id)
);
CREATE INDEX IDX_8244AA3AF92F3E70 ON companies (country_id);

CREATE TABLE countries (
  id SERIAL NOT NULL,

  name VARCHAR(63) NOT NULL,

  PRIMARY KEY(id)
);

CREATE TABLE countries_translations (
  id INT NOT NULL,

  locale VARCHAR(8) NOT NULL,
  object_class VARCHAR(255) NOT NULL,
  field VARCHAR(32) NOT NULL,
  foreign_key INT NOT NULL,
  content TEXT DEFAULT NULL,

  PRIMARY KEY(id)
);
CREATE INDEX countries_translations_idx ON countries_translations (locale, object_class, field, foreign_key);

CREATE TABLE product_categories (
  id SERIAL NOT NULL,

  name VARCHAR(63) NOT NULL,

  PRIMARY KEY(id)
);

CREATE TABLE units (
  id SERIAL NOT NULL,

  name VARCHAR(15) NOT NULL,
  description VARCHAR(63) DEFAULT NULL,

  PRIMARY KEY(id)
);

CREATE TABLE vat_rates (
  id SERIAL NOT NULL,
  value DOUBLE PRECISION NOT NULL,

  PRIMARY KEY(id)
);

CREATE SEQUENCE countries_translations_id_seq INCREMENT BY 1 MINVALUE 1 START 1;

ALTER TABLE companies ADD CONSTRAINT FK_8244AA3AF92F3E70 FOREIGN KEY (country_id) REFERENCES countries (id) NOT DEFERRABLE INITIALLY IMMEDIATE;
