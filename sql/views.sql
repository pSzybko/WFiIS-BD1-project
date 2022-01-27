CREATE OR REPLACE VIEW projekt.zamowienia_uzytkownika AS
SELECT Zamowienie.Id_Zamowienie, Zamowienie.Data_zamowienia, Restauracja.Nazwa, Restauracja.Miasto, Restauracja.Id_Restauracja, Uzytkownik.Id_Uzytkownik FROM projekt.Zamowienie
JOIN projekt.Restauracja ON Restauracja.Id_Restauracja = Zamowienie.Id_Restauracja
JOIN projekt.Zamawiajacy ON Zamowienie.Id_Zamowienie = Zamawiajacy.Id_Zamowienie
JOIN projekt.Uzytkownik ON Zamawiajacy.Id_Uzytkownik = Uzytkownik.Id_Uzytkownik
ORDER BY Zamowienie.Data_zamowienia DESC, Zamowienie.Id_Zamowienie DESC;

-- Restauracje zawsze wypisujemy względem wybranego adresu(tym samym miasta) dlatego postanowiłem stworzyć dwa odrębne widoki restauracje_krakow i restauracje_warszawa dla zwiększenia przejrzystości
CREATE OR REPLACE VIEW projekt.restauracje_krakow AS
SELECT  RES.Nazwa, RES.Rozpoczecie_pracy, RES.Zakonczenie_pracy, RES.Miasto, RES.Ulica, RES.Nr_domu, SUM(CAST(REC.Ocena AS decimal))/COUNT(REC.Ocena) srednia, RES.Id_Restauracja FROM projekt.Restauracja RES 
LEFT JOIN projekt.Recenzja REC ON RES.Id_Restauracja=REC.Id_Restauracja 
WHERE RES.Miasto='Kraków'
GROUP BY RES.Nazwa, RES.Id_Restauracja, RES.Rozpoczecie_pracy, RES.Zakonczenie_pracy, RES.Miasto, RES.Ulica, RES.Nr_domu;

CREATE OR REPLACE VIEW projekt.restauracje_warszawa AS
SELECT  RES.Nazwa, RES.Rozpoczecie_pracy, RES.Zakonczenie_pracy, RES.Miasto, RES.Ulica, RES.Nr_domu, SUM(CAST(REC.Ocena AS decimal))/COUNT(REC.Ocena) srednia, RES.Id_Restauracja FROM projekt.Restauracja RES 
LEFT JOIN projekt.Recenzja REC ON RES.Id_Restauracja=REC.Id_Restauracja 
WHERE RES.Miasto='Warszawa'
GROUP BY RES.Nazwa, RES.Id_Restauracja, RES.Rozpoczecie_pracy, RES.Zakonczenie_pracy, RES.Miasto, RES.Ulica, RES.Nr_domu;

CREATE OR REPLACE VIEW projekt.recenzje_uzytkownik AS
SELECT UZY.Id_Uzytkownik, UZY.Imie, UZY.Nazwisko, REC.Id_Restauracja, REC.Ocena, REC.Tresc, RES.Nazwa FROM projekt.Uzytkownik UZY
JOIN projekt.Recenzja REC ON UZY.Id_Uzytkownik = REC.Id_Uzytkownik
JOIN projekt.Restauracja RES ON REC.Id_Restauracja = RES.Id_Restauracja;

CREATE OR REPLACE VIEW projekt.recenzje_restauracja AS
SELECT UZY.email, REC.Ocena, REC.Tresc, RES.Nazwa, RES.Id_Restauracja, UZY.imie FROM projekt.Restauracja RES
JOIN projekt.Recenzja REC ON RES.Id_Restauracja = REC.Id_Restauracja
JOIN projekt.Uzytkownik UZY ON REC.Id_Uzytkownik = UZY.Id_Uzytkownik;

CREATE OR REPLACE VIEW projekt.srednia_uzytkownika AS
SELECT UZY.Id_Uzytkownik, SUM(CAST(REC.Ocena AS decimal))/COUNT(REC.Ocena) srednia FROM projekt.Uzytkownik UZY
JOIN projekt.Recenzja REC ON UZY.Id_Uzytkownik = REC.Id_Uzytkownik
GROUP BY UZY.Id_Uzytkownik;

CREATE OR REPLACE VIEW projekt.srednia_restauracji AS
SELECT RES.Id_Restauracja, SUM(CAST(REC.Ocena AS decimal))/COUNT(REC.Ocena) srednia FROM projekt.Restauracja RES
JOIN projekt.Recenzja REC ON RES.Id_Restauracja = REC.Id_Restauracja
GROUP BY RES.Id_Restauracja;

CREATE OR REPLACE VIEW projekt.odwiedzone_restauracje AS
SELECT UZY.Id_Uzytkownik, RES.Id_Restauracja, RES.Nazwa FROM projekt.Restauracja RES
JOIN projekt.Zamowienie ZAM on RES.Id_Restauracja = ZAM.Id_Restauracja
JOIN projekt.Zamawiajacy UZY ON ZAM.Id_Zamowienie = UZY.Id_Zamowienie
GROUP BY RES.Id_Restauracja, RES.Nazwa, UZY.Id_Uzytkownik;

CREATE OR REPLACE VIEW projekt.manager_restauracji AS 
SELECT MAN.Id_Manager, MAN.Imie, MAN.Nazwisko, MAN.Email, RES.Id_Restauracja, RES.Nazwa, RES.Miasto, RES.Ulica, RES.Nr_domu, RES.Rozpoczecie_pracy, RES.Zakonczenie_pracy FROM projekt.Manager MAN
JOIN projekt.Restauracja RES ON MAN.Id_Restauracja = RES.Id_Restauracja; 

CREATE OR REPLACE VIEW projekt.tygodniowe_zamowienia AS
SELECT Id_Restauracja, count(Id_Zamowienie) tygodniowe_zamowienia FROM projekt.Zamowienie
WHERE data_zamowienia >= NOW()::DATE-EXTRACT(DOW FROM NOW())::INTEGER-7 
GROUP BY Id_Restauracja;

CREATE OR REPLACE VIEW projekt.produkty_restauracji AS
SELECT Cena, Produkt.Nazwa, Produkt.Id_produkt, Produkt_kategoria.Opis, Restauracja.Id_Restauracja FROM projekt.Produkt
JOIN projekt.Produkt_kategoria ON Produkt.Id_Kategoria = Produkt_kategoria.Id_Kategoria
JOIN projekt.Restauracja ON Produkt.Id_Restauracja = Restauracja.Id_Restauracja
ORDER BY produkt.Id_Kategoria;
