CREATE SCHEMA projekt;
CREATE SEQUENCE projekt.pojazd_id_pojazdu_seq;

CREATE TABLE projekt.Pojazd (
                Id_Pojazdu INTEGER NOT NULL DEFAULT nextval('projekt.pojazd_id_pojazdu_seq'),
                Opis VARCHAR NOT NULL,
                CONSTRAINT pojazd_pk PRIMARY KEY (Id_Pojazdu)
);


ALTER SEQUENCE projekt.pojazd_id_pojazdu_seq OWNED BY projekt.Pojazd.Id_Pojazdu;

CREATE SEQUENCE projekt.restauracja_id_restauracja_seq;

CREATE TABLE projekt.Restauracja (
                Id_Restauracja INTEGER NOT NULL DEFAULT nextval('projekt.restauracja_id_restauracja_seq'),
                Miasto VARCHAR(30) NOT NULL,
                Nr_domu INTEGER NOT NULL,
                Ulica VARCHAR(30) NOT NULL,
                Zakonczenie_pracy TIME NOT NULL,
                Rozpoczecie_pracy TIME NOT NULL,
                Nazwa VARCHAR NOT NULL,
                CONSTRAINT restauracja_pk PRIMARY KEY (Id_Restauracja)
);


ALTER SEQUENCE projekt.restauracja_id_restauracja_seq OWNED BY projekt.Restauracja.Id_Restauracja;

CREATE SEQUENCE projekt.dostawca_id_dostawca_seq;

CREATE TABLE projekt.Dostawca (
                Id_Dostawca INTEGER NOT NULL DEFAULT nextval('projekt.dostawca_id_dostawca_seq'),
                Imie VARCHAR(30) NOT NULL,
                Nazwisko VARCHAR(30) NOT NULL,
                Rozpoczecie_pracy TIME NOT NULL,
                Zakonczenie_pracy TIME NOT NULL,
                Id_Pojazdu INTEGER NOT NULL,
                CONSTRAINT dostawca_pk PRIMARY KEY (Id_Dostawca)
);


ALTER SEQUENCE projekt.dostawca_id_dostawca_seq OWNED BY projekt.Dostawca.Id_Dostawca;

CREATE SEQUENCE projekt.manager_id_manager_seq;

CREATE TABLE projekt.Manager (
                Id_Manager INTEGER NOT NULL DEFAULT nextval('projekt.manager_id_manager_seq'),
                Imie VARCHAR(30) NOT NULL,
                Email VARCHAR(100) NOT NULL,
                Nazwisko VARCHAR(30) NOT NULL,
                Id_Restauracja INTEGER NOT NULL,
                CONSTRAINT manager_pk PRIMARY KEY (Id_Manager)
);


ALTER SEQUENCE projekt.manager_id_manager_seq OWNED BY projekt.Manager.Id_Manager;

CREATE SEQUENCE projekt.produkt_kategoria_id_kategoria_seq;

CREATE TABLE projekt.Produkt_kategoria (
                Id_Kategoria INTEGER NOT NULL DEFAULT nextval('projekt.produkt_kategoria_id_kategoria_seq'),
                Opis VARCHAR NOT NULL,
                CONSTRAINT produkt_kategoria_pk PRIMARY KEY (Id_Kategoria)
);


ALTER SEQUENCE projekt.produkt_kategoria_id_kategoria_seq OWNED BY projekt.Produkt_kategoria.Id_Kategoria;

CREATE SEQUENCE projekt.produkt_id_produkt_seq;

CREATE TABLE projekt.Produkt (
                Id_Produkt INTEGER NOT NULL DEFAULT nextval('projekt.produkt_id_produkt_seq'),
                Cena INTEGER NOT NULL,
                Nazwa VARCHAR(40) NOT NULL,
                Id_Kategoria INTEGER NOT NULL,
                Id_Restauracja INTEGER NOT NULL,
                CONSTRAINT produkt_pk PRIMARY KEY (Id_Produkt)
);


ALTER SEQUENCE projekt.produkt_id_produkt_seq OWNED BY projekt.Produkt.Id_Produkt;

CREATE SEQUENCE projekt.zamowienie_id_zamowienie_seq;

CREATE TABLE projekt.Zamowienie (
                Id_Zamowienie INTEGER NOT NULL DEFAULT nextval('projekt.zamowienie_id_zamowienie_seq'),
                Godzina_zamowienia TIME NOT NULL,
                Data_zamowienia DATE NOT NULL,
                Miasto VARCHAR NOT NULL,
                Nr_domu INTEGER NOT NULL,
                Ulica VARCHAR NOT NULL,
                Id_Restauracja INTEGER NOT NULL,
                CONSTRAINT zamowienie_pk PRIMARY KEY (Id_Zamowienie)
);


ALTER SEQUENCE projekt.zamowienie_id_zamowienie_seq OWNED BY projekt.Zamowienie.Id_Zamowienie;

CREATE TABLE projekt.Zamowiony_Produkt (
                Id_Zamowienie INTEGER NOT NULL,
                Id_Produkt INTEGER NOT NULL,
                Ilosc INTEGER NOT NULL,
                CONSTRAINT zamowiony_produkt_pk PRIMARY KEY (Id_Zamowienie, Id_Produkt)
);


CREATE TABLE projekt.Dostawa (
                Id_Zamowienie INTEGER NOT NULL,
                Id_Dostawca INTEGER NOT NULL,
                CONSTRAINT dostawa_pk PRIMARY KEY (Id_Zamowienie, Id_Dostawca)
);


CREATE SEQUENCE projekt.uzytkownik_id_uzytkownik_seq;

CREATE TABLE projekt.Uzytkownik (
                Id_Uzytkownik INTEGER NOT NULL DEFAULT nextval('projekt.uzytkownik_id_uzytkownik_seq'),
                Email VARCHAR(100) NOT NULL,
                Imie VARCHAR(30) NOT NULL,
                Nazwisko VARCHAR(30) NOT NULL,
                CONSTRAINT uzytkownik_pk PRIMARY KEY (Id_Uzytkownik)
);


ALTER SEQUENCE projekt.uzytkownik_id_uzytkownik_seq OWNED BY projekt.Uzytkownik.Id_Uzytkownik;

CREATE TABLE projekt.Zamawiajacy (
                Id_Uzytkownik INTEGER NOT NULL,
                Id_Zamowienie INTEGER NOT NULL,
                CONSTRAINT zamawiajacy_pk PRIMARY KEY (Id_Uzytkownik, Id_Zamowienie)
);


CREATE SEQUENCE projekt.recenzja_id_recenzja_seq;

CREATE TABLE projekt.Recenzja (
                Id_Recenzja INTEGER NOT NULL DEFAULT nextval('projekt.recenzja_id_recenzja_seq'),
                Ocena INTEGER NOT NULL,
                Tresc VARCHAR(500) NOT NULL,
                Id_Uzytkownik INTEGER NOT NULL,
                Id_Restauracja INTEGER NOT NULL,
                CONSTRAINT recenzja_pk PRIMARY KEY (Id_Recenzja)
);


ALTER SEQUENCE projekt.recenzja_id_recenzja_seq OWNED BY projekt.Recenzja.Id_Recenzja;

CREATE SEQUENCE projekt.adres_id_adres_seq;

CREATE TABLE projekt.Adres (
                Id_Adres INTEGER NOT NULL DEFAULT nextval('projekt.adres_id_adres_seq'),
                Miasto VARCHAR(30) NOT NULL,
                Nr_domu INTEGER NOT NULL,
                Ulica VARCHAR(30) NOT NULL,
                Id_Uzytkownik INTEGER NOT NULL,
                CONSTRAINT adres_pk PRIMARY KEY (Id_Adres)
);


ALTER SEQUENCE projekt.adres_id_adres_seq OWNED BY projekt.Adres.Id_Adres;

ALTER TABLE projekt.Dostawca ADD CONSTRAINT pojazd_dostawca_fk
FOREIGN KEY (Id_Pojazdu)
REFERENCES projekt.Pojazd (Id_Pojazdu)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

ALTER TABLE projekt.Zamowienie ADD CONSTRAINT restauracja_zamowienie_fk
FOREIGN KEY (Id_Restauracja)
REFERENCES projekt.Restauracja (Id_Restauracja)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

ALTER TABLE projekt.Recenzja ADD CONSTRAINT restauracja_recenzja_fk
FOREIGN KEY (Id_Restauracja)
REFERENCES projekt.Restauracja (Id_Restauracja)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

ALTER TABLE projekt.Manager ADD CONSTRAINT restauracja_manager_fk
FOREIGN KEY (Id_Restauracja)
REFERENCES projekt.Restauracja (Id_Restauracja)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

ALTER TABLE projekt.Produkt ADD CONSTRAINT restauracja_produkt_fk
FOREIGN KEY (Id_Restauracja)
REFERENCES projekt.Restauracja (Id_Restauracja)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

ALTER TABLE projekt.Dostawa ADD CONSTRAINT dostawca_dostawa_fk
FOREIGN KEY (Id_Dostawca)
REFERENCES projekt.Dostawca (Id_Dostawca)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

ALTER TABLE projekt.Produkt ADD CONSTRAINT produkt_kategoria_produkt_fk
FOREIGN KEY (Id_Kategoria)
REFERENCES projekt.Produkt_kategoria (Id_Kategoria)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

ALTER TABLE projekt.Zamowiony_Produkt ADD CONSTRAINT produkt_zamowiony_produkt_fk
FOREIGN KEY (Id_Produkt)
REFERENCES projekt.Produkt (Id_Produkt)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

ALTER TABLE projekt.Dostawa ADD CONSTRAINT zamowienie_dostawa_fk
FOREIGN KEY (Id_Zamowienie)
REFERENCES projekt.Zamowienie (Id_Zamowienie)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

ALTER TABLE projekt.Zamowiony_Produkt ADD CONSTRAINT zamowienie_zamowiony_produkt_fk
FOREIGN KEY (Id_Zamowienie)
REFERENCES projekt.Zamowienie (Id_Zamowienie)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

ALTER TABLE projekt.Zamawiajacy ADD CONSTRAINT zamowienie_zamawiajacy_fk
FOREIGN KEY (Id_Zamowienie)
REFERENCES projekt.Zamowienie (Id_Zamowienie)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

ALTER TABLE projekt.Adres ADD CONSTRAINT uzytkownik_adres_fk
FOREIGN KEY (Id_Uzytkownik)
REFERENCES projekt.Uzytkownik (Id_Uzytkownik)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

ALTER TABLE projekt.Recenzja ADD CONSTRAINT uzytkownik_recenzja_fk
FOREIGN KEY (Id_Uzytkownik)
REFERENCES projekt.Uzytkownik (Id_Uzytkownik)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

ALTER TABLE projekt.Zamawiajacy ADD CONSTRAINT uzytkownik_zamawiajacy_fk
FOREIGN KEY (Id_Uzytkownik)
REFERENCES projekt.Uzytkownik (Id_Uzytkownik)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;