<?php
    session_start();
    $dbconn =pg_connect("host=localhost dbname=u9sipko user=u9sipko password=9sipko") or die ("Nie mozna polaczyc sie z serwerem\n");
    $tab=explode(':', $_POST['selAdres']);
    $_SESSION['miasto_z']= $tab[0];
    $_SESSION['ulica_z']= $tab[1];
    $_SESSION['numer_z']= $tab[2];
    if(strcmp($_SESSION["miasto_z"], "Kraków") !== 0){
        $query = <<<SQL
            SELECT * FROM projekt.restauracje_warszawa;
        SQL;
    }
    else{
        $query = <<<SQL
            SELECT * FROM projekt.restauracje_krakow;
        SQL;
    }
    $result = pg_query($dbconn, $query) or die('Query failed: ' . pg_last_error());
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <link rel="StyleSheet" href="zad.css" type="text/css">
        <script src="main.js"></script>
    </head>
    <body>
        <form method="POST" action="restaurant_menu.php">
            <table>
                <thead>
                    <tr><th colspan="5">Lista obecnie dostępnych restauracji w podanej lokalizacji:</th></tr>
                </thead>
                <tbody>
                    <tr><th>Nazwa Restauracji</th><th>Godzina otwarcia</th><th>Godzina zamknięcia</th><th>Ocena</th><th></th></tr>
                    <?php
                        $result = pg_query($dbconn, $query) or die('Query failed: ' . pg_last_error());
                        while ($line = pg_fetch_array($result, null, PGSQL_ASSOC))  {
                            echo '<tr><td>'.$line["nazwa"].'</td><td>'.$line["rozpoczecie_pracy"].'</td><td>'.$line["zakonczenie_pracy"].'</td><td>'.number_format($line["srednia"], 2).'</td><td><input type="checkbox" autocomplete="off" name="check" id="check" value='.$line["id_restauracja"].' onclick="onlyOne(this)"></td></tr>';
                        }
                    ?>
                </tbody>
                <tfoot>
                    <tr ><td colspan="5"><input type="submit" id="res_dalej" value="Dalej" hidden/></td></tr>
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