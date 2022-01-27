<?php
    session_start();
    $tab=explode(':', $_POST['uzyt']);
    $_SESSION['imie']= $tab[0];
    $_SESSION['nazwisko']= $tab[1];
    $_SESSION['id']= $tab[2];
    $dbconn =pg_connect("host=localhost dbname=u9sipko user=u9sipko password=9sipko") or die ("Nie mozna polaczyc sie z serwerem\n");
    $query = 'SELECT * FROM projekt.Adres WHERE Id_Uzytkownik='.$_SESSION['id'];
    $result = pg_query($dbconn, $query) or die('Query failed: ' . pg_last_error());
    $query2 = <<<SQL
    SELECT * FROM projekt.zamowienia_uzytkownika
    WHERE Id_Uzytkownik = {$_SESSION['id']};
    SQL;
    $query3 = <<<SQL
    SELECT * FROM projekt.wypisz_odwiedzone_restauracja({$_SESSION['id']});
    SQL;
    $query4 = <<<SQL
    SELECT nazwa, tresc, ocena FROM projekt.recenzje_uzytkownik WHERE id_uzytkownik={$_SESSION['id']};
    SQL;
    $result4 = pg_query($dbconn, $query4) or die('Query failed: ' . pg_last_error());
    $query5 = <<<SQL
    SELECT * FROM projekt.restauracje_krakow;
    SQL;
    $result5 = pg_query($dbconn, $query5) or die('Query failed: ' . pg_last_error());
    $query6 = <<<SQL
    SELECT * FROM projekt.restauracje_warszawa;
    SQL;
    $result6 = pg_query($dbconn, $query6) or die('Query failed: ' . pg_last_error());
    $query7 = <<<SQL
    SELECT * FROM projekt.srednia_uzytkownika WHERE id_uzytkownik={$_SESSION['id']};
    SQL;
    $result7 = pg_query($dbconn, $query7) or die('Query failed: ' . pg_last_error());
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
                        <li><a onclick="return showContent(event, 'zamow');">Złóż zamówienie</a></li>
                        <li><a onclick="return showContent(event, 'historia');">Historia zamówień</a></li>
                        <li><a onclick="return showContent(event, 'recenzje');">Moje recenzje</a></li>
                        <li><a onclick="return showContent(event, 'dane');">Moje adresy </a></li>
                        <li><a onclick="return showContent(event, 'restauracje');">Przeglądaj restauracje</a></li>
                        <li><a href="main.php">Wyloguj</a></li>
                    </ul>
                </nav>
            </div>
        </header>
        <article>
            <div class="MyDivElement" id="MyHomeScreen">
                <div id="HomeScreen">
                    <p>
                        Witaj z powrotem
                        <?php  echo $_SESSION['imie']; echo ' '; echo $_SESSION['nazwisko'];?>
                    </p>
                </div>
            </div>
            <div class="MyDivElement" id="zamow" hidden>
                <h3>Nowe zamówienie</h3>
                <label for="selAdres">Adres zamówienia</label>
                <form method="POST" action="zamowienie.php">
                    <select id="selAdres" name="selAdres" autocomplete="off" onchange="showButtonForSelect('selAdres', 'wyborRest');">
                        <option value="wybierz">Wybierz adres</option>
                        <?php
                        $result = pg_query($dbconn, $query) or die('Query failed: ' . pg_last_error());
                        while ($line = pg_fetch_array($result, null, PGSQL_ASSOC))  {
                            echo '<option value="'.$line["miasto"].':'.$line["ulica"].':'.$line["nr_domu"].'">'.$line["miasto"].' '.$line["ulica"].' '.$line["nr_domu"].'</option>';
                        }
                    ?>
                    </select>
                    <div id="wyborRest"></div>
                </form>
            </div>
            <div class="MyDivElement" id="historia" hidden>
                <table>
                    <thead><tr><th colspan="4">Historia zamówień użytkownika <?php  echo $_SESSION['imie']; echo ' '; echo $_SESSION['nazwisko'];?></th></tr></thead>
                    <tbody>
                        <tr><th>Data</th><th>Restauracja</th><th>Miasto</th></tr>
                <?php
                    $result2 = pg_query($dbconn, $query2) or die('Query failed: ' . pg_last_error());
                    while ($line = pg_fetch_array($result2, null, PGSQL_ASSOC))  {
                        echo '<tr><td>'.$line["data_zamowienia"].'</td><td>'.$line["nazwa"].'</td><td>'.$line["miasto"].'</td></tr>';
                    }
                ?>
                </tbody>
                </table>
            </div>
            <div class="MyDivElement" id="recenzje" hidden>
                <input type="button" value="Dodaj nową recenzję" onclick="showForm('dodajRec')"/>
                <input type="button" value="Przeglądaj moje recenzje" onclick="showForm('usunRec')"/>
                <div id="recenzjaForm" hidden>
                    <form method="POST" action="recenzja.php">
                        <label for="mojeRest">Restauracja: </label>
                        <select id="mojeRest" name="mojeRest" autocomplete="off" onchange="showButtonRecenzja()">
                            <option value="wybierz">Wybierz restaurację</option>
                            <?php
                                $result3 = pg_query($dbconn, $query3) or die('Query failed: ' . pg_last_error());
                                while ($line = pg_fetch_array($result3, null, PGSQL_ASSOC)) {
                                    echo '<option value="'.$line["id_restauracja"].'">'.$line["nazwa"].'</option>';
                                }
                            ?>
                        </select><br>
                        <label for="ocena">Ocena: </label>
                        <input type="number" id="ocena" name="ocena" min="1" max="5" autocomplete="off" value="" onchange="showButtonRecenzja()"><br>
                        <label for="tresc">Dodaj komentarz: </label><br>
                        <textarea id="tresc" name="tresc" rows="4" cols="50" placeholder="Napisz coś o Twoich wrażeniach"></textarea>
                        <div id="wyslijRecenzja"></div>
                    </form>
                </div>
                <div id="recenzjaUsun" hidden>
                    <table>
                        <thead><tr><th colspan="3">Recenzje użytkownika <?php  echo $_SESSION['imie']; echo ' '; echo $_SESSION['nazwisko'];?></th></tr></thead>
                        <tbody>
                            <tr><th>Restauracja</th><th>Tresc</th><th>Ocena</th></tr>
                            <?php
                                while ($line = pg_fetch_array($result4, null, PGSQL_ASSOC))  {
                                    echo '<tr><td>'.$line["nazwa"].'</td><td>'.$line["tresc"].'</td><td>'.$line["ocena"].'</td></tr>';
                                }
                            ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="2">Średnia ocen użytkownika: </th>
                                <td>
                                    <?php
                                        while ($line = pg_fetch_array($result7, null, PGSQL_ASSOC))  {
                                            echo number_format($line["srednia"], 2);
                                        }
                                    ?>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            <div class="MyDivElement" id="dane" hidden>
                <h3>Dodane adresy użytkownika <?php  echo $_SESSION['imie']; echo ' '; echo $_SESSION['nazwisko'];?></h3>
                <ul>
                <?php
                    $result = pg_query($dbconn, $query) or die('Query failed: ' . pg_last_error());
                    while ($line = pg_fetch_array($result, null, PGSQL_ASSOC))  {
                        echo '<li>'.$line["miasto"].' '.$line["ulica"].' '.$line["nr_domu"].'</li>';
                    }
                ?>
                </ul>
                <input type="button" value="Dodaj nowy" onclick="showForm('dodaj')">
                <input type="button" value="Usun adres" onclick="showForm('usun')">
                <div id="formularzAdres" hidden>
                    <form method="POST" action="dodaj_adres.php">
                        <table>
                            <thead>
                                <tr><th colspan="2">Dodaj nowy adres</th></tr>
                            </thead>
                            <tbody>
                                <tr><td><label for="selMiasto">Wybierz miasto</label></td><td>
                                    <select id="selMiasto" name="selMiasto" required>
                                        <option value="">Wybierz miasto</option>
                                        <option value="Kraków">Kraków</option>
                                        <option value="Warszawa">Warszawa</option>
                                    </select><br></td></tr>
                                <tr><td><label for="inputUlica">Ulica:</label></td><td><input type="text" id="inputUlica" name="inputUlica" value="" required></td></tr>
                                <tr><td><label for="inputNumer">Numer domu:</label></td><td><input type="number" id="inputNumer" name="inputNumer" value="" required></td></tr>
                            </tbody>
                            <tfoot>
                                <tr><td colspan="2"><input type="submit" value="Dodaj"></td></tr>
                            </tfoot>
                        </table>
                    </form>
                </div>
                <div id="formularzUsun" hidden>
                    <form method="POST" action="usun_adres.php">
                        <label for="selAdresUsun">Wybierz adres</label>
                        <select id="selAdresUsun" name="selAdresUsun" autocomplete="off" onchange="showButtonForSelect('selAdresUsun', 'usunAdres');">
                            <option value="wybierz">Wybierz adres</option>
                            <?php
                                $result = pg_query($dbconn, $query) or die('Query failed: ' . pg_last_error());
                                while ($line = pg_fetch_array($result, null, PGSQL_ASSOC))  {
                                    echo '<option value="'.$line["id_adres"].'">'.$line["miasto"].' '.$line["ulica"].' '.$line["nr_domu"].'</option>';
                                }
                            ?>
                        </select><br>
                        <div id="usunAdres"></div>
                    </form>
                </div>
            </div>
            <div class="MyDivElement" id="restauracje" hidden>
                <label for="selMiasto2">Wybierz miasto</label>
                <select id="selMiasto2" name="selMiasto2" autocomplete="off" onchange="showButtonForSelect2('selMiasto2', 'showRest')">
                    <option value="wybierz">Wybierz miasto</option>
                    <option value="Kraków">Kraków</option>
                    <option value="Warszawa">Warszawa</option>
                </select><br>
                <input type="button" id="showRest" value="Dalej" onclick="showForm('miasto')" hidden/>
                <div id="restKrakow" name="restKrakow" hidden>
                    <table>
                        <thead>
                            <tr><th colspan="5">Restauracje w Krakowie</th></tr>
                        </thead>
                        <tbody>
                            <tr><th>Nazwa Restauracji</th><th>Godziny otwarcia</th><th>Adres</th><th>Ocena</th><th>Recenzje</th></tr>
                            <?php
                                while ($line = pg_fetch_array($result5, null, PGSQL_ASSOC))  {
                                    echo '<tr><td>'.$line["nazwa"].'</td><td>'.$line["rozpoczecie_pracy"].' - '.$line["zakonczenie_pracy"].'</td><td>'.$line["miasto"].' '.$line["ulica"].' '.$line["nr_domu"].'</td><td>'.number_format($line["srednia"], 2).'</td><td><form method="POST" action="restauracja_recenzje.php" method=""><input type="hidden" id="id_restauracja" name="id_restauracja" value='.$line["id_restauracja"].'><input type="submit" value="Przeglądaj"></form></td></tr>';
                                }
                            ?>
                        </tbody>
                    </table>
                </div>
                <div id="restWarszawa" name="restWarszawa" hidden>
                    <table>
                        <thead>
                            <tr><th colspan="5">Restauracje w Warszawie</th></tr>
                        </thead>
                        <tbody>
                            <tr><th>Nazwa Restauracji</th><th>Godziny otwarcia</th><th>Adres</th><th>Ocena</th><th>Recenzje</th></tr>
                            <?php
                                while ($line = pg_fetch_array($result6, null, PGSQL_ASSOC))  {
                                    echo '<tr><td>'.$line["nazwa"].'</td><td>'.$line["rozpoczecie_pracy"].' - '.$line["zakonczenie_pracy"].'</td><td>'.$line["miasto"].' '.$line["ulica"].' '.$line["nr_domu"].'</td><td>'.number_format($line["srednia"], 2).'</td><td><form method="POST" action="restauracja_recenzje.php" method=""><input type="hidden" id="id_restauracja" name="id_restauracja" value='.$line["id_restauracja"].'><input type="submit" value="Przeglądaj"></form></td></tr>';
                                }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </article>
        <footer>
        <h3>Paweł Sipko<br>WFiIS AGH 2022</h3>
        </footer>
    </body>
</html>