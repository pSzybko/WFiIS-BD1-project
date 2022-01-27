<?php
    session_start();
    $dbconn =pg_connect("host=localhost dbname=u9sipko user=u9sipko password=9sipko") or die ("Nie mozna polaczyc sie z serwerem\n");
    $id_res = intval($_POST['id_restauracja']);
    $query = <<<SQL
    SELECT projekt.dodaj_manager('{$_POST["imie"]}', '{$_POST["nazwisko"]}', '{$_POST["email"]}', {$id_res});
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
        <p>Pomyślnie dodano nowego managera!</p>
        <form method="POST" action="manager_login.php">
            <input type="submit" value="Powrót do strony głównej" />
        </form>
    </body>
</html>