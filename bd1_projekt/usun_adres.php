<?php
    session_start();
    $dbconn =pg_connect("host=localhost dbname=u9sipko user=u9sipko password=9sipko") or die ("Nie mozna polaczyc sie z serwerem\n");
    $query = <<<SQL
    DELETE FROM projekt.Adres WHERE id_adres={$_POST['selAdresUsun']};
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
        <p>Pomyślnie usunięto adres!</p>
        <form method="POST" action="logged_menu.php">
            <input type="hidden" id="uzyt" name="uzyt"
                <?php
                    echo 'value="'.$_SESSION["imie"].':'.$_SESSION["nazwisko"].':'.$_SESSION["id"].'">';
                ?>
            <input type="submit" value="Powrót do strony głównej" />
        </form>
    </body>
</html>