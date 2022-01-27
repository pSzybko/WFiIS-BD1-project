<?php
    session_start();
    $dbconn =pg_connect("host=localhost dbname=u9sipko user=u9sipko password=9sipko") or die ("Nie mozna polaczyc sie z serwerem\n");
    $_SESSION['manager']=$_POST['manager'];
    $query = <<<SQL
        SELECT * FROM projekt.manager_restauracji WHERE id_manager = {$_POST['manager']};
    SQL;
    $result = pg_query($dbconn, $query) or die('Query failed: ' . pg_last_error());
    $line = pg_fetch_array($result, null, PGSQL_ASSOC);
    $_SESSION['restauracja']=$line['id_restauracja'];
    $query2 = <<<SQL
        SELECT * FROM projekt.recenzje_restauracja
        WHERE id_restauracja IN (
            SELECT id_restauracja FROM projekt.manager_restauracji
            WHERE id_manager = {$_POST['manager']}
        );
    SQL;
    $result2 = pg_query($dbconn, $query2) or die('Query failed: ' . pg_last_error());
    $query3 = <<<SQL
        SELECT * FROM projekt.manager_restauracji
        WHERE id_manager = {$_POST['manager']};
    SQL;
    $result3 = pg_query($dbconn, $query3) or die('Query failed: ' . pg_last_error());
    $query4 = <<<SQL
        SELECT Id_Kategoria, opis_kategoria FROM projekt.kategorie_z_restauracja({$_SESSION['restauracja']}) GROUP BY Id_Kategoria, opis_kategoria;
    SQL;
    $query5 = <<<SQL
        SELECT * FROM projekt.produkty_restauracji WHERE Id_Restauracja = {$_SESSION['restauracja']};
    SQL;
    $result5 = pg_query($dbconn, $query5) or die('Query failed: ' . pg_last_error());
    $query6 = <<<SQL
        SELECT * FROM projekt.Produkt_kategoria;
    SQL;
    $result6 = pg_query($dbconn, $query6) or die('Query failed: ' . pg_last_error());
    $query7 = <<<SQL
        SELECT * FROM projekt.wypisz_zamowienia_restauracja({$_SESSION['restauracja']});
    SQL;
    $result7 = pg_query($dbconn, $query7) or die('Query failed: ' . pg_last_error());
    $query8 = <<<SQL
        SELECT * FROM projekt.srednia_restauracji WHERE Id_Restauracja={$_SESSION['restauracja']};
    SQL;
    $result8 = pg_query($dbconn, $query8) or die('Query failed: ' . pg_last_error());
    $query9 = <<<SQL
        SELECT * FROM projekt.tygodniowe_zamowienia WHERE Id_Restauracja={$_SESSION['restauracja']};
    SQL;
    $result9 = pg_query($dbconn, $query9) or die('Query failed: ' . pg_last_error());
    $query10 = <<<SQL
        SELECT * FROM projekt.tygodniowe_duze_zamowienia({$_SESSION['restauracja']});
    SQL;
    $result10 = pg_query($dbconn, $query10) or die('Query failed: ' . pg_last_error());
    $query11 = <<<SQL
        SELECT * FROM projekt.suma_tygodniowych_zamowien({$_SESSION['restauracja']});
    SQL;
    $result11 = pg_query($dbconn, $query11) or die('Query failed: ' . pg_last_error());
    $query12 = <<<SQL
        SELECT * FROM projekt.ilosc_klientow({$_SESSION['restauracja']});
    SQL;
    $result12 = pg_query($dbconn, $query12) or die('Query failed: ' . pg_last_error());
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <link rel="StyleSheet" href="zad.css" type="text/css">
        <script src="main.js"></script>
    </head>
    <body>
        <header>
            <div id="mainHeader">
                <nav>
                    <ul>
                        <li><a onclick="return showContent(event, 'historia');">Historia zamówień</a></li>
                        <li><a onclick="return showContent(event, 'recenzje');">Recenzje restauracji</a></li>
                        <li><a onclick="return showContent(event, 'oferta');">Oferta Restauracji</a></li>
                        <li><a onclick="return showContent(event, 'dane_res');">Dane restauracji</a></li>
                        <li><a onclick="return showContent(event, 'dane');">Moje dane</a></li>
                        <li><a href="manager_login.php">Wyloguj</a></li>
                    </ul>
                </nav>
            </div>
        </header>
        <article>
            <div class="MyDivElement" id="MyHomeScreen">
                <div id="HomeScreen">
                    <p>
                        Witaj w panelu managera
                    </p>
                </div>
            </div>
            <div class="MyDivElement" id="historia" hidden>
                <table>
                    <thead><tr><th colspan="3">Zamówienia z restauracji</th></tr></thead>
                    <tbody>
                        <tr><th>Data</th><th>Kwota</th><th>Szczegóły</th></tr>
                        <?php
                            while ($line = pg_fetch_array($result7, null, PGSQL_ASSOC))  {
                                echo '<tr><td>'.$line["data_zamowienia"].'</td><td>'.$line["sum"].'</td><td><form method="POST" action="zamowienie_szegoly.php"><input type="hidden" name="id_zamowienie" id="id_zamowienie" value='.$line["id_zamowienie"].'><input type=hidden id="suma" name="suma" value='.$line["sum"].'><input type="submit" value="Szczegóły" /></form></td></tr>';
                            }
                        ?>
                    </tbody>
                </table>
            </div>
            <div class="MyDivElement" id="recenzje" hidden>
                <table>
                    <thead>
                        <tr><th colspan="3">Recenzje restauracji <?php $line = pg_fetch_array($result, null, PGSQL_ASSOC); echo $line['nazwa']; ?></th></tr>
                    </thead>
                    <tbody>
                        <tr><th>Email</th><th>Ocena</th><th>Tresc</th></tr>
                        <?php
                            while ($line = pg_fetch_array($result2, null, PGSQL_ASSOC))  {
                                echo '<tr><td>'.$line["email"].'</td><td>'.$line["ocena"].'</td><td>'.$line["tresc"].'</td></tr>';
                            }
                        ?>
                    </tbody>
                    <tfoot>
                        <tr><td colspan="2">Srednia:</td><td>
                            <?php
                                $line = pg_fetch_array($result8, null, PGSQL_ASSOC);
                                echo number_format($line["srednia"], 2);
                            ?>
                        </td></tr>
                    </tfoot>
                </table>
            </div>
            <div class="MyDivElement" id="oferta" hidden>
                
                <input type="button" value="Dodaj kategorię" onclick="showForm('kategoria')"/>
                <input type="button" value="Dodaj produkt" onclick="showForm('dodajProdukt')"/>
                <input type="button" value="Usuń produkt" onclick="showForm('usunProdukt')"/>
                <div id="kategoriaForm" hidden>
                    <form method="POST" action="dodaj_kategoria.php">
                        <label for="nazwaKategoria">Nazwa kategorii: </label>
                        <input type="text" id="nazwaKategoria" name="nazwaKategoria" required /><br>
                        <input type="submit" value="Dalej" /> 
                    </form>
                </div>
                <div id="produktForm" hidden>
                    <form method="POST" action="dodaj_produkt.php">
                        <label for="nazwaProdukt">Nazwa produktu: </label>
                        <input type="text" id="nazwaProdukt" name="nazwaProdukt" required />
                        <label for="cenaProdukt">Cena produktu: </label>
                        <input type="number" id="cenaProdukt" name="cenaProdukt" required />
                        <label for="kategoriaProdukt">Kategoria produktu: </label>
                        <select id="kategoriaProdukt" name="kategoriaProdukt" required>
                            <option value="">Wybierz kategorię: </option>
                            <?php
                                while($line = pg_fetch_array($result6, null, PGSQL_ASSOC)){
                                    echo '<option value="'.$line["id_kategoria"].'">'.$line["opis"]."</option>";
                                }
                            ?>
                        </select><br>
                        <input type="submit" value="Dalej" /> 
                    </form>
                </div>
                <div id="produktUsun" hidden>
                    <form method="POST" action="usun_produkt.php">
                        <label for="nazwaProdukt">Produkt do usunięcia: </label>
                        <select id="nazwaProdukt" name="nazwaProdukt" required>
                            <option value="">Wybierz produkt</option>
                            <?php
                                while($line = pg_fetch_array($result5, null, PGSQL_ASSOC)){
                                    echo '<option value="'.$line["id_produkt"].'">'.$line["nazwa"]."</option>";
                                }
                            ?>
                        </select><br>
                        <input type="submit" value="Dalej" /> 
                    </form>
                </div>
                <table>
                    <thead><tr><th colspan="2">Oferta restauracji</th></tr></thead>
                    <tbody>
                        <?php
                            $result4 = pg_query($dbconn, $query4) or die('Query failed: ' . pg_last_error());
                            while($line = pg_fetch_array($result4, null, PGSQL_ASSOC)){
                                echo '<tr><th colspan="2">'.$line['opis_kategoria']."</th></tr>";
                                $temp_query = <<<SQL
                                    SELECT * FROM projekt.produkt_z_kategoria({$_SESSION['restauracja']}, {$line['id_kategoria']});
                                SQL;
                                $temp_result = pg_query($dbconn, $temp_query) or die('Query failed: ' . pg_last_error());
                                while($line2 = pg_fetch_array($temp_result, null, PGSQL_ASSOC))
                                    echo '<tr><td>'.$line2['nazwa']."</td><td>".$line2['cena']."</td></tr>";
                            } 
                        ?>
                    </tbody>
                </table>
            </div>
            <div class="MyDivElement" id="dane_res" hidden>
                <table>
                    <thead>
                        <tr><th colspan="2">Dane restauracji </th></tr>
                    </thead>
                    <tbody>
                        <?php
                        $line = pg_fetch_array($result3, null, PGSQL_ASSOC);
                        $code = <<<HTML
                        <tr><td>Nazwa: </td><td>{$line['nazwa']}</td></tr>
                        <tr><td>Adres: </td><td>{$line['miasto']} {$line['ulica']} {$line['nr_domu']}</td></tr>
                        <tr><td>Godziny pracy: </td><td>{$line['rozpoczecie_pracy']} - {$line['zakonczenie_pracy']} </td></tr>
                        HTML;
                        echo $code;
                        ?>        
                    </tbody>
                </table>
                <input type="button" value="Edytuj dane" onclick="showForm('dane')">
                <div id="restDaneForm" name="restDaneForm" hidden>
                    <form method="POST" action="restauracje_dane.php">
                        <table>
                            <thead>
                                <tr><th colspan="3">Wprowadź nowe dane</th></tr>
                            </thead>
                            <tbody>
                                <tr><td><label for="Nazwa">Nazwa: </label></td><td><input type="text" id="Nazwa" name="Nazwa" value="<?php echo $line['nazwa']; ?>" required/></td><td></td></tr>
                                <tr><td><label for="Ulica">Ulica i numer budynku :</label></td><td><input type="text" id="Ulica" name="Ulica" value="<?php echo $line['ulica']; ?>" required/></td><td><input type="number" id="Numer" name="Numer" value="<?php echo $line['nr_domu']; ?>" required/></td></tr>
                                <tr><td><label for="Rozp">Godziny pracy: </label></td>
                                <td><input type="time" id="Rozp" name="Rozp" value="<?php echo $line['rozpoczecie_pracy']; ?>" required/></td>
                                <td><input type="time" id="Zako" name="Zako" value="<?php echo $line['zakonczenie_pracy']; ?>" required/></td></tr>
                            </tbody>
                            <tfoot>
                                <tr><td colspan="3"><input type="submit" value="Dalej"></td></tr>
                            </tfoot>
                        </table>
                    </form>
                </div>
                <table>
                    <thead>
                        <tr><th colspan="2">Tygodniowe statystyki restauracji</th></tr>
                    </thead>
                    <tbody>
                        <tr><td>Ilość zamówień</td><td>
                            <?php
                                $line = pg_fetch_array($result9, null, PGSQL_ASSOC);
                                echo $line['tygodniowe_zamowienia'];
                            ?>
                        </td></tr>      
                        <tr><td>Ilość dużych zamówień</td><td>
                            <?php
                                $line = pg_fetch_array($result10, null, PGSQL_ASSOC);
                                echo $line['tygodniowe_duze_zamowienia'];
                            ?>
                        </td></tr>
                        <tr>
                            <td>Łączna wartość zamówień</td>
                            <td>
                                <?php
                                    $line = pg_fetch_array($result11, null, PGSQL_ASSOC);
                                    echo $line['suma_tygodniowych_zamowien'];
                                ?>
                            </td>
                        </tr>    
                        <tr>
                            <td>Ilość klientów</td>
                            <td>
                                <?php
                                    $line = pg_fetch_array($result12, null, PGSQL_ASSOC);
                                    echo $line['ilosc_klientow'];
                                ?>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="MyDivElement" id="dane" hidden>
                <?php
                    $result = pg_query($dbconn, $query) or die('Query failed: ' . pg_last_error());
                    $line = pg_fetch_array($result, null, PGSQL_ASSOC);
                    $code = <<<HTML
                    <table>
                        <thead>
                            <tr><th colspan="2">Dane managera</th></tr>
                        </thead>
                        <tbody>
                            <tr><td>Imię: </td><td>{$line['imie']}</td></tr>
                            <tr><td>Nazwisko: </td><td>{$line['nazwisko']}</td></tr>
                            <tr><td>Email: </td><td>{$line['email']}</td></tr>
                            <tr><td>Restauracja: </td><td>{$line['nazwa']}</td></tr>
                        </tbody>
                    </table>
                    HTML;
                    echo $code;
                ?>
            </div>
        </article>
    </body>
</html>