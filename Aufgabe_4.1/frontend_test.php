<!DOCTYPE html>
<html>
<head>
    <title>DB Form</title>
</head>
<body>


    <pre id="output"></pre>

    <script>

    </script>
    <?php
        // Datenbankverbindungsparameter
        $servername = "localhost:8888"; 
        $username = "elias.bachmann@siga.swiss";
        $password = "NiMaBe!!6103";
        $dbname = "Skiturnier";

        // Verbindung herstellen
        $conn = new mysqli($servername, $username, $password, $dbname);

        // Verbindung überprüfen
        if ($conn->connect_error) {
            die("Verbindung fehlgeschlagen: " . $conn->connect_error);
        }

        // Beispielabfrage
        $sql = "SELECT * FROM Teilnehmer";
        $result = $conn->query($sql);

        // Ergebnisse verarbeiten
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "ID: " . $row["Teilnehmer_ID"] . " - Name: " . $row["Vorname"] . " " . $row["Nachname"] . "<br>";
            }
        } else {
            echo "0 Ergebnisse";
        }

        // Verbindung schließen
        $conn->close();
    ?>
</body>
</html>
