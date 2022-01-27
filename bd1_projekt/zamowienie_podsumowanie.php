<?php
    session_start();
    $dbconn = pg_connect("host=localhost dbname=u9sipko user=u9sipko password=9sipko") or die ("Nie mozna polaczyc sie z serwerem\n");
    $query = <<<SQL
    SELECT * FROM projekt.wypisz_produkt({$_SESSION['rest']});
    SQL;
    $query2 = <<<SQL
    INSERT INTO projekt.Zamowienie(
                    Data_zamowienia ,
                    Miasto ,
                    Ulica ,
                    Nr_domu ,
                    Id_Restauracja
    )values
    (CURRENT_DATE, '{$_SESSION["miasto_z"]}', '{$_SESSION["ulica_z"]}', {$_SESSION['numer_z']}, {$_SESSION['rest']})
    RETURNING Id_Zamowienie;
    SQL;
    $result2 = pg_query($dbconn, $query2) or die('Query failed2: ' . pg_last_error());
    $zamowienie_last_id = pg_fetch_row($result2);
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <link rel="StyleSheet" href="zad.css" type="text/css">
        <script src="main.js"></script>
    </head>
    <body>
        <table>
            <thead>
                <tr>
                    <th colspan="2">Podsumowanie</th>
                </tr>
            </thead>
            <tbody>
                <tr><th>Produkt</th><th>Ilość</th></tr>
                <?php
                    $result = pg_query($dbconn, $query) or die('Query failed: ' .$_SESSION['rest']. pg_last_error());
                    $sum=0;
                    for($i=0; $i<$_SESSION['ilosc']; $i++){
                        $line = pg_fetch_array($result, null, PGSQL_ASSOC);
                        if($_POST['produkt'.$i]!=0){
                            echo "<tr><td>".$line['nazwa']."</td><td>".$_POST['produkt'.$i]."</td></tr>";
                            $sum+=$line['cena']*$_POST['produkt'.$i];
                            $produkt_id=$line['id_produkt'];
                            $ilosc=$_POST['produkt'.$i];
                            $query3 = <<<SQL
                            INSERT INTO projekt.Zamowiony_Produkt(
                                            Id_Zamowienie ,
                                            Id_Produkt ,
                                            Ilosc
                            )values
                            ({$zamowienie_last_id[0]}, {$produkt_id}, {$ilosc});
                            SQL;
                            $result3=pg_query($dbconn, $query3) or die('Query failed3: ' . pg_last_error());
                        }
                    }
                    $query4 = <<<SQL
                            INSERT INTO projekt.Zamawiajacy(
                                            Id_Uzytkownik ,
                                            Id_Zamowienie
                            )values
                            ({$_SESSION['id']}, {$zamowienie_last_id[0]});
                            SQL;
                    $result4=pg_query($dbconn, $query4) or die('Query failed4: ' . pg_last_error());
                ?>
            </tbody>
            <tfoot>
                <tr>
                    <td></td>
                    <?php
                        echo "<td>Łącznie: ".$sum." zł</td>";
                    ?>   
                </tr>
            </tfoot>
        </table>
        
        <form method="POST" action="logged_menu.php">
            <input type="hidden" id="uzyt" name="uzyt"
                <?php
                    echo 'value="'.$_SESSION["imie"].':'.$_SESSION["nazwisko"].':'.$_SESSION["id"].'">';
                ?>
            <input type="submit" value="Powrót do menu głównego" />
        </form>
    </body>
</html>