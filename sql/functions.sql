CREATE OR REPLACE FUNCTION projekt.produkt_z_kategoria( id_res INTEGER, id_kat INTEGER)
    RETURNS TABLE(cena INTEGER, nazwa varchar, Id_Kategoria INTEGER, Id_Restauracja INTEGER) AS
$$
BEGIN
    RETURN QUERY SELECT produkt.cena, produkt.nazwa, produkt.Id_Kategoria, produkt.Id_Restauracja
    FROM projekt.produkt
    JOIN projekt.Produkt_kategoria ON produkt.Id_Kategoria = Produkt_kategoria.Id_Kategoria
    JOIN projekt.Restauracja ON produkt.Id_Restauracja = Restauracja.Id_Restauracja
    WHERE produkt.Id_Kategoria = id_kat AND produkt.Id_Restauracja = id_res;
END;
$$
LANGUAGE plpgsql;

CREATE OR REPLACE FUNCTION projekt.kategorie_z_restauracja( id_res INTEGER)
    RETURNS TABLE(opis_kategoria VARCHAR ,Id_Kategoria INTEGER, Id_Restauracja INTEGER) AS
$$
BEGIN
    RETURN QUERY SELECT Produkt_kategoria.opis ,produkt.Id_Kategoria, produkt.Id_Restauracja
    FROM projekt.produkt
    JOIN projekt.Produkt_kategoria ON produkt.Id_Kategoria = Produkt_kategoria.Id_Kategoria
    JOIN projekt.Restauracja ON produkt.Id_Restauracja = Restauracja.Id_Restauracja
    WHERE produkt.Id_Restauracja = id_res
    GROUP BY Produkt_kategoria.opis ,produkt.Id_Kategoria, produkt.Id_Restauracja
    ORDER BY Produkt_kategoria.opis;
END;
$$
LANGUAGE plpgsql;

CREATE OR REPLACE FUNCTION projekt.wypisz_produkt(Id_res INTEGER)
    RETURNS TABLE(Nazwa VARCHAR, Cena INTEGER, Id_Kategoria INTEGER, Id_produkt INTEGER, Opis VARCHAR) AS
$$
BEGIN
    RETURN QUERY SELECT PRO.Nazwa, PRO.Cena, KAT.Id_Kategoria, PRO.Id_produkt, KAT.opis FROM projekt.Produkt PRO
    JOIN projekt.Produkt_kategoria KAT USING (Id_Kategoria)
    WHERE Id_Restauracja=Id_res
    GROUP BY KAT.Id_Kategoria, KAT.opis, PRO.Nazwa, PRO.Cena, PRO.Id_produkt
    ORDER BY KAT.opis, PRO.Id_produkt;
END;
$$
LANGUAGE plpgsql;

CREATE OR REPLACE FUNCTION projekt.wypisz_odwiedzone_restauracja(Id_uzy INTEGER)
    RETURNS TABLE(Id_Restauracja INTEGER, Nazwa VARCHAR) AS
$$
BEGIN
    RETURN QUERY SELECT Restauracja.Id_Restauracja, Restauracja.Nazwa FROM projekt.Restauracja
    WHERE Restauracja.Id_Restauracja IN (
        SELECT Zamowienie.Id_Restauracja FROM projekt.Zamowienie
        WHERE Zamowienie.Id_Zamowienie IN (
            SELECT Zamawiajacy.Id_Zamowienie FROM projekt.Zamawiajacy
            WHERE Zamawiajacy.Id_Uzytkownik=Id_uzy
        )
    );
END;
$$
LANGUAGE plpgsql;

-- CREATE OR REPLACE FUNCTION projekt.wypisz_zamowienie(Id_zam INTEGER)
--     RETURNS TABLE(Miasto VARCHAR, Data_zamowienia DATE, Id_Restauracja INTEGER, Ilosc INTEGER, Id_Produkt INTEGER, Cena INTEGER, Nazwa VARCHAR, Id_Kategoria INTEGER) AS
-- $$
-- BEGIN
--     RETURN QUERY SELECT(Zamowienie.miasto, Zamowienie.data_zamowienia, Zamowienie.Nr_domu, Zamowienie.Id_Restauracja, Zamowiony_Produkt.Ilosc, Produkt.Id_Produkt, Produkt.Cena, Produkt.Nazwa, Produkt.Id_Kategoria) FROM projekt.Zamowienie 
--     JOIN projekt.Zamowiony_Produkt USING (Id_Zamowienie)
--     JOIN projekt.Produkt USING (Id_Produkt)
--     WHERE id_zamowienie = Id_zam;
-- END;
-- $$
-- LANGUAGE plpgsql;

CREATE OR REPLACE FUNCTION projekt.wypisz_zamowienie(Id_zam INTEGER)
    RETURNS TABLE(Miasto VARCHAR, Data_zamowienia DATE, Id_Restauracja INTEGER, Ilosc INTEGER, Id_Produkt INTEGER, Cena INTEGER, Nazwa VARCHAR, Id_Kategoria INTEGER) AS
$$
BEGIN
    RETURN QUERY SELECT Zamowienie.miasto, Zamowienie.data_zamowienia, Zamowienie.Id_Restauracja, Zamowiony_Produkt.Ilosc, Produkt.Id_Produkt, Produkt.Cena, Produkt.Nazwa, Produkt.Id_Kategoria FROM projekt.Zamowienie
    JOIN projekt.Zamowiony_Produkt USING (Id_Zamowienie)
    JOIN projekt.Produkt USING (Id_Produkt)
    WHERE id_zamowienie = Id_zam;
END;
$$
LANGUAGE plpgsql;

CREATE OR REPLACE FUNCTION projekt.wypisz_zamowienia_restauracja(Id_res INTEGER)
    RETURNS TABLE(Id_Zamowienie INTEGER, Data_zamowienia DATE, sum BIGINT) AS
$$
BEGIN
    RETURN QUERY SELECT Zamowienie.Id_Zamowienie, Zamowienie.Data_zamowienia, SUM(cena * ilosc) FROM projekt.Zamowienie
    JOIN projekt.Zamowiony_Produkt USING (Id_Zamowienie)
    JOIN projekt.Produkt USING (Id_Produkt)
    WHERE zamowienie.Id_restauracja = Id_res
    group by Zamowienie.Id_Zamowienie, Zamowienie.Data_zamowienia
    order by Zamowienie.Data_zamowienia, Zamowienie.Id_Zamowienie DESC;
END;
$$
LANGUAGE plpgsql;

CREATE OR REPLACE FUNCTION projekt.dodaj_adres(Id_uzy INTEGER, Mias VARCHAR, Uli VARCHAR, Nr INTEGER)
    RETURNS VOID AS
$$
BEGIN
    INSERT INTO projekt.Adres (Id_Uzytkownik, Miasto, Ulica, Nr_domu)
    VALUES(Id_uzy, Mias, Uli, Nr);
END;
$$
LANGUAGE plpgsql;

CREATE OR REPLACE FUNCTION projekt.dodaj_produkt(Cen INTEGER, Naz  VARCHAR, Id_kat INTEGER, Id_res INTEGER)
    RETURNS VOID AS
$$
BEGIN
    INSERT INTO projekt.Produkt (Cena, Nazwa, Id_Kategoria, Id_Restauracja)
    VALUES(Cen, Naz, Id_kat, Id_res);
END;
$$
LANGUAGE plpgsql;


CREATE OR REPLACE FUNCTION projekt.dodaj_recenzja(Oce INTEGER, Tre VARCHAR, Id_uzy INTEGER, Id_res INTEGER)
    RETURNS VOID AS
$$
BEGIN
    INSERT INTO projekt.Recenzja (Ocena, Tresc, Id_Uzytkownik, Id_Restauracja) 
    VALUES (Oce, Tre, Id_uzy, id_res);
END;
$$
LANGUAGE plpgsql;

CREATE OR REPLACE FUNCTION projekt.dodaj_uzytkownik(Mail VARCHAR, Imi VARCHAR, Nazw VARCHAR)
    RETURNS VOID AS
$$
BEGIN
    INSERT INTO projekt.Uzytkownik (Email, Imie, Nazwisko) 
    VALUES (Mail, Imi, Nazw);
END;
$$
LANGUAGE plpgsql;

CREATE OR REPLACE FUNCTION projekt.dodaj_manager(Imi VARCHAR, Nazw VARCHAR, Mail VARCHAR, Id_res INTEGER)
    RETURNS VOID AS
$$
BEGIN
    INSERT INTO projekt.Manager (Email, Imie, Nazwisko, Id_Restauracja) 
    VALUES (Mail, Imi, Nazw, Id_res);
END;
$$
LANGUAGE plpgsql;

CREATE OR REPLACE FUNCTION projekt.aktualizuj_restauracja(Nazw VARCHAR, Uli VARCHAR, Nr INTEGER, Rozp TIME, Zako TIME, Id_res INTEGER)
    RETURNS VOID AS
$$
BEGIN
    UPDATE projekt.Restauracja
    SET nazwa=Nazw, ulica=Uli, nr_domu=Nr, rozpoczecie_pracy=Rozp, zakonczenie_pracy=Zako
    WHERE Id_Restauracja=Id_res;
END;
$$
LANGUAGE plpgsql;

CREATE OR REPLACE FUNCTION projekt.tygodniowe_duze_zamowienia(Id_res INTEGER)
    RETURNS INTEGER AS
$$
BEGIN
    RETURN (SELECT  COUNT(*) FROM (
    SELECT Id_Zamowienie, Zamowienie.Id_Restauracja,SUM(ilosc * cena) 
    FROM projekt.Zamowienie
    JOIN projekt.Zamowiony_Produkt USING(Id_Zamowienie)
    JOIN projekt.Produkt USING (Id_Produkt)
    WHERE data_zamowienia >= NOW()::DATE-EXTRACT(DOW FROM NOW())::INTEGER-7 
    AND Zamowienie.Id_Restauracja=Id_res
    GROUP BY id_zamowienie
    HAVING SUM(ilosc * cena) >150
    ) src);
END;
$$
LANGUAGE plpgsql;

CREATE OR REPLACE FUNCTION projekt.suma_tygodniowych_zamowien(Id_res INTEGER)
    RETURNS INTEGER AS
$$
BEGIN
    RETURN (SELECT SUM(suma_pojedyncza) FROM(
    SELECT Zamowienie.Id_Zamowienie, Zamowienie.Data_zamowienia, SUM(cena * ilosc) as suma_pojedyncza FROM projekt.Zamowienie
    JOIN projekt.Zamowiony_Produkt USING (Id_Zamowienie)
    JOIN projekt.Produkt USING (Id_Produkt)
    WHERE zamowienie.Id_restauracja = Id_res AND
    data_zamowienia >= NOW()::DATE-EXTRACT(DOW FROM NOW())::INTEGER-7 
    group by Zamowienie.Id_Zamowienie, Zamowienie.Data_zamowienia
    order by Zamowienie.Data_zamowienia, Zamowienie.Id_Zamowienie
    )sum);
END;
$$
LANGUAGE plpgsql;


CREATE OR REPLACE FUNCTION projekt.ilosc_klientow(Id_res INTEGER)
    RETURNS INTEGER AS
$$
BEGIN
    RETURN (SELECT count(*) FROM(
    SELECT Id_Uzytkownik FROM
    projekt.Zamowienie 
    JOIN projekt.Zamawiajacy USING(Id_Zamowienie)
    WHERE Id_restauracja=Id_res
    GROUP BY Id_Uzytkownik
    )ilosc);
END;
$$
LANGUAGE plpgsql;