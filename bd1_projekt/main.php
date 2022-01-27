<?php
    session_start();
    $dbconn =pg_connect("host=localhost dbname=u9sipko user=u9sipko password=9sipko") or die ("Nie mozna polaczyc sie z serwerem\n");
    $query = 'SELECT * FROM projekt.Uzytkownik';
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
        <div id="main_user">
        <form method="POST" action="logged_menu.php">
            <label for="uzyt">Kto dzisiaj zamawia?</label><br>
            <select id="uzyt" name="uzyt" onchange="showButtonForSelect('uzyt', 'MyDivElement')" autocomplete="off">
                <option value="wybierz">Wybierz uzytkownika</option>
                <?php
                    while ($line = pg_fetch_array($result, null, PGSQL_ASSOC))  {
                        echo '<option value="'.$line["imie"].':'.$line["nazwisko"].':'.$line["id_uzytkownik"].'">'.$line["imie"].' '.$line["nazwisko"].'</option>';
                    }
                ?>
            </select>
            <div id="MyDivElement"></div>
        </form>
        <p>lub dodaj nowego użytkownika</p>
        <input type="button" value="Dodaj" onclick="getForm('uzytkownikForm')"/>
        <div id="uzytkownikForm" hidden>
            <form method="POST" action="dodaj_uzytkownika.php">
                <table>
                    <thead>
                        <tr><th colspan="2">Nowy użytkownik</th></tr>
                    </thead>
                    <tbody>
                        <tr><td><label for="imie">Imię:</label></td><td><input type="text" id="imie" name="imie" required/></td></tr>
                        <tr><td><label for="nazwisko">Nazwisko:</label></td><td><input type="text" id="nazwisko" name="nazwisko" required/></td></tr>
                        <tr><td><label for="email">Email:</label></td><td><input type="email" id="email" name="email" required/></td></tr>
                    </tbody>
                    <tfoot>
                        <tr><td colspan="2"><input type="submit" value="Dalej" /></td></tr>
                    </tfoot>
                </table>
                <!-- <label for="imie">Imię:</label>
                <input type="text" id="imie" name="imie" required/><br>
                <label for="nazwisko">Nazwisko:</label>
                <input type="text" id="nazwisko" name="nazwisko" required/><br>
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required/><br>
                <input type="submit" value="Dalej" /> -->
            </form>
        </div>
        </div>
        <div id="main_manager">
        <p>Jesteś managerem restauracji? Tutaj możesz zarządzać stroną swojej restauracji</p>
        <form action="manager_login.php" method="POST">
            <input type="submit" value="Zaloguj jako manager" />
        </form>
        </div>
    </body>
</html>