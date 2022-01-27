<?php
    session_start();
    $dbconn =pg_connect("host=localhost dbname=u9sipko user=u9sipko password=9sipko") or die ("Nie mozna polaczyc sie z serwerem\n");
    $query = <<<SQL
    SELECT projekt.aktualizuj_restauracja('{$_POST["Nazwa"]}', '{$_POST["Ulica"]}', '{$_POST["Numer"]}', '{$_POST["Rozp"]}', '{$_POST["Zako"]}', {$_SESSION['restauracja']})
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
        <p>Pomyślnie zaktualizowano dane restauracji!</p>
        <form method="POST" action="manager_menu.php">
            <input type="hidden" id="manager" name="manager"
                <?php
                    echo 'value="'.$_SESSION["manager"].'">';
                ?>
            <input type="submit" value="Powrót do strony głównej" />
        </form>
    </body>
</html>