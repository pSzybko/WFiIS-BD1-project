<?php
    session_start();
    $dbconn =pg_connect("host=localhost dbname=u9sipko user=u9sipko password=9sipko") or die ("Nie mozna polaczyc sie z serwerem\n");
    $query = <<<SQL
    SELECT * FROM projekt.Manager;
    SQL;
    $result = pg_query($dbconn, $query) or die('Query failed: ' . pg_last_error());
    $query2 = <<<SQL
    SELECT * FROM projekt.Restauracja;
    SQL;
    $result2 = pg_query($dbconn, $query2) or die('Query failed: ' . pg_last_error());
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <link rel="StyleSheet" href="zad.css" type="text/css">
        <script src="main.js"></script>
    </head>
    <body>
        <div id="manager_login">
        <form method="POST" action="manager_menu.php">
            <label for="manager">Wybierz istniejące konto</label><br>
            <select id="manager" name="manager" onchange="showButtonForSelect('manager', 'MyDivElement')" autocomplete="off">
                <option value="wybierz">Wybierz uzytkownika</option>
                <?php
                    while($line = pg_fetch_array($result, null, PGSQL_ASSOC)){
                       echo '<option value="'.$line["id_manager"].'">'.$line['imie'].' '.$line['nazwisko'].'</option>';
                    }
                ?>
            </select>
            <div id="MyDivElement"></div>
        </form>
        <p>lub</p>
        <input type="button" value="Dodaj nowego managera" onclick="getForm('managerForm')" />
        <div id="managerForm" hidden>
            <form method="POST" action="dodaj_managera.php">
                <label for="imie">Imię:</label>
                <input type="text" id="imie" name="imie" required/><br>
                <label for="nazwisko">Nazwisko:</label>
                <input type="text" id="nazwisko" name="nazwisko" required/><br>
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required/><br>
                <label for="id_restauracja">Restauracja:</label>
                <select id="id_restauracja" name="id_restauracja" required>
                    <option value="">Wybierz resturację</option>
                    <?php
                        while($line = pg_fetch_array($result2, null, PGSQL_ASSOC)){
                            echo '<option value="'.$line["id_restauracja"].'">'.$line["nazwa"].'</option>';
                        }
                    ?>
                </select><br>
                <input type="submit" value="Dalej" />
            </form>
        </div>
        </div>
        <div id="powrot">
        <form action="main.php" method="POST" >
            <input type="submit" value="Powrót do strony głównej" />
        </form>
        </div>
    </body>
</html>