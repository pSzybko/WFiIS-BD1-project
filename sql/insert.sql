INSERT INTO  projekt.Restauracja (
                Miasto ,
                Nr_domu ,
                Ulica ,
                Nazwa,
                Rozpoczecie_pracy,
                Zakonczenie_pracy
)VALUES
('Kraków', 24, 'Długa', 'Bistro', '8:00:00', '20:00:00'),
('Kraków', 12, 'Główna', 'Bar studencki', '12:00:00', '17:00:00'),
('Kraków', 5, 'Główna', 'Nóż i widelec','12:00:00', '24:00:00'),
('Kraków', 46, 'Polna', 'Chata Staropolska', '11:00:00', '21:00:00'),
('Warszawa', 2, 'Ogrodowa', 'I Love Pasta', '8:00:00', '22:00:00'),
('Warszawa', 4, 'Lipowa', 'Magia Lubczyku', '10:00:00', '23:00:00'),
('Warszawa', 17, 'Brzozowa', 'Morski Zając', '16:00:00', '23:00:00');

INSERT INTO projekt.Manager (
                Imie ,
                Email ,
                Nazwisko ,
                Id_Restauracja 
)VALUES
('Jan', 'flisikowski@mail.com', 'Flisikowski', 1),
('Piotr', 'flisikowski@mail.com', 'Płochocki', 2),
('Krzysztof', 'stachyra@mail.com', 'Stachyra', 3),
('Stanisław', 'sztuka@mail.com', 'Sztuka', 4),
('Tomasz', 'sosin@mail.com', 'Sosin', 5),
('Paweł', 'glowala@mail.com', 'Głowala', 6),
('Józef', 'dwojak@mail.com', 'Dwojak', 7);

INSERT INTO projekt.Uzytkownik (
                Email ,
                Imie ,
                Nazwisko 
)VALUES
('abacki@mail.com', 'Adam', 'Abacki'),
('babacka@mail.com', 'Ewa', 'Babacka'),
('cabacki@mail.com', 'Edward', 'Cabacki');

INSERT INTO  projekt.Adres (
                Miasto ,
                Nr_domu ,
                Ulica ,
                Id_Uzytkownik 
)VALUES
('Kraków', 3, 'Łąkowa', 1),
('Warszawa', 12 , 'Polna', 1),
('Warszawa', 3 , 'Długa', 2),
('Kraków', 11 , 'Słoneczna', 3);

INSERT INTO projekt.Produkt_kategoria(
    Opis
)VALUES
('Wegetariańskie'),
('Wegańskie'),
('Mięsne'),
('Zupy'),
('Owoce morza'),
('Wegetariańskie'),
('Przystawki'),
('Burgery'),
('Makarony'),
('Pierogi');

INSERT INTO projekt.Produkt(
                Id_Restauracja, 
                Id_Kategoria, 
                Nazwa, 
                Cena
)VALUES
(1, 7, 'Krewetki', 33), 
(1, 7, 'Tatar wołowy', 34), 
(1, 7, 'Sałatka Cezar', 31), 
(1, 7, 'Tatar z łososia', 33), 
(1, 4, 'Zupa dnia', 12), 
(1, 4, 'Rosół', 15), 
(1, 4, 'Krem pomidorowy', 17), 
(1, 8, 'Cheese burger', 35), 
(1, 8, 'HOT burger', 36), 
(1, 8, 'Chicken burger', 34), 
(1, 8, 'Szef burger', 39),
(1, 10, 'Pierogi ruskie', 31),
(1, 10, 'Pierogi z mięsem', 32),
(1, 10, 'Pierogi ze śliwką i cynamonem', 30),
(2, 1, 'Placki ziemniaczane', 10), 
(2, 1, 'Naleśniki z twarogiem', 13), 
(2, 1, 'Naleśniki z dżemem', 9), 
(2, 2, 'Kopytka', 13), 
(2, 2, 'Kluski śląskie', 12), 
(2, 2, 'Pierogi z kapustą i grzybami', 15), 
(2, 4, 'Pomidorowa', 6), 
(2, 4, 'Ogórkowa', 6), 
(2, 4, 'Żurek', 9), 
(2, 4, 'Jarzynowa', 6), 
(2, 10, 'Pierogi ruskie', 15),
(2, 10, 'Pierogi z mięsem', 17),
(2, 10, 'Pierogi ze szpinakiem', 14),
(3, 3, 'Schab z grilla', 25), 
(3, 3, 'Eskalopki', 25),
(3, 3, 'Rumsztyk wieprzowy', 25),
(3, 10, 'Pierogi ruskie', 20),
(3, 10, 'Pierogi z mięsem', 20),
(3, 4, 'Pomidorowa z makaronem', 9),
(3, 4, 'Żurek', 9),
(3, 7, 'Naleśniki z serem', 18),
(3, 7, 'Naleśniki z jabłkami', 18),
(3, 7, 'Naleśniki z jabłkami', 18),
(4, 7, 'Bajgiel z szarpaną wołowiną', 21),
(4, 7, 'Pieczywo czosnkowe', 14),
(4, 7, 'Śledź w śmietanie', 14),
(4, 4, 'Krem z borowików', 14),
(4, 3, 'Sezonowany antrykot', 78),
(4, 3, 'Udko z królika', 49),
(4, 2, 'Burger wege sojowy', 29),
(4, 10, 'Pierogi z mięsem', 23),
(5, 7, 'Sałatka szefa', 38),
(5, 7, 'Carpaccio di manzo', 44),
(5, 7, 'Carpaccio di polpo', 48),
(5, 9, 'Spaghetti alla carbonara', 49),
(5, 9, 'Spaghetti al nero di seppia', 48),
(5, 9, 'Tagliatelle ai frutti di mare', 48),
(5, 9, 'Anolini di carne', 42),
(5, 9, 'Carbonara di mare', 58),
(6, 7, 'Tatar wołowy z polędwicy', 52),
(6, 7, 'Selekcja sałat', 38),
(6, 7, 'Paprykarz Dźwirzyński', 32),
(6, 4, 'Zupa ogórkowa', 29),
(6, 4, 'Zupa rybna', 28),
(6, 4, 'Krem z brukwi', 28),
(6, 3, 'Stek z antrykotu', 92),
(6, 3, 'Stek z rostbefu', 95),
(6, 3, 'De volaille', 52),
(7, 7, 'Carpaccio z polędwicy wołowej', 39),
(7, 7, 'Tatar z łososia', 38),
(7, 7, 'Bagietka z matjasem', 28),
(7, 2, 'Tofu w sezamie', 34),
(7, 4, 'Zupa morska', 32),
(7, 4, 'Rosół', 19),
(7, 5, 'Paella z owocami morza', 58),
(7, 5, 'Homar z grilla', 150),
(7, 3, 'Wołowina konfitowana', 64),
(7, 3, 'Schab z kością', 39);