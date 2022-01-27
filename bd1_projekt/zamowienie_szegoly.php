<?php
    session_start();
    $dbconn =pg_connect("host=localhost dbname=u9sipko user=u9sipko password=9sipko") or die ("Nie mozna polaczyc sie z serwerem\n");
    $query = <<<SQL
    SELECT * FROM projekt.wypisz_zamowienie({$_POST['id_zamowienie']});
    SQL;
    $result = pg_query($dbconn, $query) or die('Query failed: ' . pg_last_error());
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <link rel="StyleSheet" href="zad.css" type="text/css">
    </head>
    <body>
        <table>
            <thead>
                <tr><th colspan="3">Szczegóły zamówienia</th></tr>
            </thead>
            <tbody>
                <tr><th>Produkt</th><th>Ilosc</th><th>Cena</th></tr>
        <?php
            while($line = pg_fetch_array($result, null, PGSQL_ASSOC)){
                echo '<tr><td>'.$line["nazwa"].'</td><td>'.$line["ilosc"].'</td><td>'.$line["cena"].'</td></tr>';
            }
        ?>
            </tbody>
            <tfoot>
                <tr><td colspan="2">Łącznie: </td><td><?php echo $_POST['suma'] ?></td></tr>
            </tfoot>
        </table>
        <form method="POST" action="manager_menu.php">
            <input type="hidden" id="manager" name="manager"
                <?php
                    echo 'value="'.$_SESSION["manager"].'">';
                ?>
            <input type="submit" value="Powrót do strony głównej" />
        </form>
    </body>
</html>