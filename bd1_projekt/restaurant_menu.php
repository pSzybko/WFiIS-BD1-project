<?php
    session_start();
    $dbconn =pg_connect("host=localhost dbname=u9sipko user=u9sipko password=9sipko") or die ("Nie mozna polaczyc sie z serwerem\n");
    $query = <<<SQL
    -- SELECT * FROM projekt.wypisz_produkt({$_POST['check']});
    SELECT Id_Kategoria, opis_kategoria FROM projekt.kategorie_z_restauracja({$_POST['check']});

    SQL;
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <link rel="StyleSheet" href="zad.css" type="text/css">
        <script src="main.js"></script>
    </head>
    <body>
        <form method="POST" action="zamowienie_podsumowanie.php">
            <?php
                $query2 = <<<SQL
                SELECT count(*) as ilosc FROM projekt.Produkt
                WHERE Produkt.Id_Restauracja={$_POST['check']}
                SQL;
                $result2 = pg_query($dbconn, $query2) or die('Query failed: ' . pg_last_error());
                $line = pg_fetch_array($result2, null, PGSQL_ASSOC);
                $_SESSION['ilosc']=$line['ilosc'];
                $_SESSION['rest']=$_POST['check'];
            ?>
            <table>
                <thead>
                    <tr>
                        <th colspan="3">Menu</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <th>Nazwa</th>
                        <th>Cena</th>
                        <th>Ilosc</th>
                    </tr>
                    <?php
                        $result = pg_query($dbconn, $query) or die('Query failed: ' . pg_last_error());
                        $licznik=0;
                        while($line = pg_fetch_array($result, null, PGSQL_ASSOC)){
                            echo '<tr><th colspan="3">'.$line['opis_kategoria']."</th></tr>";
                            $temp_query = <<<SQL
                                SELECT * FROM projekt.produkt_z_kategoria({$_POST['check']}, {$line['id_kategoria']});
                            SQL;
                            $temp_result = pg_query($dbconn, $temp_query) or die('Query failed: ' . pg_last_error());
                            while($line2 = pg_fetch_array($temp_result, null, PGSQL_ASSOC)){
                                echo '<tr><td>'.$line2["nazwa"].'</td><td>'.$line2["cena"].'</td><td><input type="number" id="produkt'.$licznik.'" name="produkt'.$licznik.'" value="0" min="0" max="5" autocomplete="off" /></td></tr>';
                                $licznik++;
                            }
                        }
                        // $result = pg_query($dbconn, $query) or die('Query failed: ' . pg_last_error());
                        // $licznik=0;
                        // while($line = pg_fetch_array($result, null, PGSQL_ASSOC)){
                        //     echo '<tr><td>'.$line["nazwa"].'</td><td>'.$line["cena"].'</td><td><input type="number" id="produkt'.$licznik.'" name="produkt'.$licznik.'" value="0" min="0" max="5" autocomplete="off" /></td></tr>';
                        //     $licznik++;
                        // }
                    ?>
                </tbody>
                <tfoot>
                    <tr><td colspan="3"><input type="submit" value="Dalej"/></td></tr>
                </tfoot>
            </table>
        </form>
        <form method="POST" action="logged_menu.php">
            <input type="hidden" id="uzyt" name="uzyt"
                <?php
                    echo 'value="'.$_SESSION["imie"].':'.$_SESSION["nazwisko"].':'.$_SESSION["id"].'">';
                ?>
            <input type="submit" value="Anuluj zamówienie" />
        </form>
    </body>
</html>