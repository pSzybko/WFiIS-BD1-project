<?php
    session_start();
    $dbconn =pg_connect("host=localhost dbname=u9sipko user=u9sipko password=9sipko") or die ("Nie mozna polaczyc sie z serwerem\n");
    $query = <<<SQL
    DELETE FROM projekt.produkt WHERE id_produkt = {$_POST['nazwaProdukt']};
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
        <p>Pomyślnie usunięto produkt!</p>
        <form method="POST" action="manager_menu.php">
            <input type="hidden" id="manager" name="manager"
                <?php
                    echo 'value="'.$_SESSION["manager"].'">';
                ?>
            <input type="submit" value="Powrót do strony głównej" />
        </form>
    </body>
</html>