<?php
    session_start();
    $dbconn =pg_connect("host=localhost dbname=u9sipko user=u9sipko password=9sipko") or die ("Nie mozna polaczyc sie z serwerem\n");
    $query = <<<SQL
    SELECT projekt.dodaj_uzytkownik('{$_POST["email"]}', '{$_POST["imie"]}' , '{$_POST["nazwisko"]}');
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
        <p>Pomyślnie dodano nowego użytkownika!</p>
        <form action="main.php" method="POST" >
            <input type="submit" value="Powrót do strony głównej" />
        </form>
    </body>
</html>