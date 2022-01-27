<?php
    session_start();
    $dbconn =pg_connect("host=localhost dbname=u9sipko user=u9sipko password=9sipko") or die ("Nie mozna polaczyc sie z serwerem\n");
    $query = <<<SQL
    SELECT * FROM projekt.Restauracja WHERE Id_Restauracja={$_POST['id_restauracja']}
    SQL;
    $result = pg_query($dbconn, $query) or die('Query failed: ' . pg_last_error());
    $query2 = <<<SQL
    SELECT * FROM projekt.recenzje_restauracja WHERE Id_Restauracja={$_POST['id_restauracja']}
    SQL;
    $result2 = pg_query($dbconn, $query2) or die('Query failed: ' . pg_last_error());
    $query3 = <<<SQL
    SELECT * FROM projekt.restauracje_krakow WHERE Id_Restauracja={$_POST['id_restauracja']};
    SQL;
    $result3 = pg_query($dbconn, $query3) or die('Query failed: ' . pg_last_error());
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <link rel="StyleSheet" href="zad.css" type="text/css">
    </head>
    <body>
        <header>
            <h3>
                <?php
                    $line = pg_fetch_array($result, null, PGSQL_ASSOC);
                    echo 'Restauracja '.$line['nazwa'];
                ?>
            </h3>
        </header>
        <article>
            <table>
                <thead>
                    <tr><th colspan="3">Recenzje naszych użytkowników o 
                        <?php
                            echo 'Restauracja '.$line['nazwa'];
                        ?>
                    </th></tr>
                </thead>
                <tbody>
                    <tr><th>Użytkownik</th><th>Ocena</th><th>Tresc</th></tr>
                    <?php
                        while ($line = pg_fetch_array($result2, null, PGSQL_ASSOC))  {
                            echo '<tr><td>'.$line['imie'].'</td><td>'.$line['ocena'].'</td><td>'.$line['tresc'].'</td></tr>';
                        }
                    ?>
                </tbody>
                <tfoot>
                    <tr><td>Średnia ocena: </td><td colspan="2">
                        <?php
                            $line = pg_fetch_array($result3, null, PGSQL_ASSOC);
                            echo number_format($line["srednia"], 2);
                        ?>
                    </td></tr>
                </tfoot>
            </table>
            <form method="POST" action="logged_menu.php">
                <input type="hidden" id="uzyt" name="uzyt"
                    <?php
                        echo 'value="'.$_SESSION["imie"].':'.$_SESSION["nazwisko"].':'.$_SESSION["id"].'">';
                    ?>
                <input type="submit" value="Powrót do strony głównej" />
            </form>
        </article>
    </body>
</html>