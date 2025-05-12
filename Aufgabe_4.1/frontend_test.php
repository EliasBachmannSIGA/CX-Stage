<!DOCTYPE html>
<html>
<head>
    <title>DB Form</title>
</head>
<body>
<!--    <h1>Teilnehmerverwaltung</h1>

    <h2>Teilnehmer hinzufügen</h2>
    <form id="addForm">
        <input type="text" placeholder="Vorname" name="Vorname" required>
        <input type="text" placeholder="Nachname" name="Nachname" required>
        <input type="number" placeholder="Team_ID" name="Team_ID" required>
        <input type="email" placeholder="Email" name="Email" required>
        <input type="text" placeholder="Telefonnummer" name="Telefonnummer" required>
        <input type="number" placeholder="Adresse_ID" name="Adresse_ID" required>
        <button type="submit">Hinzufügen</button>
    </form>

    <h2>Teilnehmer aktualisieren</h2>
    <form id="updateForm">
        <input type="number" placeholder="Teilnehmer_ID" name="Teilnehmer_ID" required>
        <input type="text" placeholder="Vorname" name="Vorname" required>
        <input type="text" placeholder="Nachname" name="Nachname" required>
        <input type="number" placeholder="Team_ID" name="Team_ID" required>
        <input type="email" placeholder="Email" name="Email" required>
        <input type="text" placeholder="Telefonnummer" name="Telefonnummer" required>
        <input type="number" placeholder="Adresse_ID" name="Adresse_ID" required>
        <button type="submit">Aktualisieren</button>
    </form>

    <h2>Teilnehmer löschen</h2>
    <form id="deleteForm">
        <input type="number" placeholder="Teilnehmer_ID" name="Teilnehmer_ID" required>
        <button type="submit">Löschen</button>
    </form>

    <h2>Alle Teilnehmer anzeigen</h2>
    <button onclick="loadTeilnehmer()">Laden</button>
    <pre id="output"></pre>

    <script>
        const apiUrl = 'http://localhost:8888/phpMyAdmin5/index.php?route=/database/structure&db=Skiturnier';

        document.getElementById('addForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const form = new FormData(this);
            fetch(apiUrl, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(Object.fromEntries(form))
            }).then(res => res.json()).then(data => alert(JSON.stringify(data)));
        });

        document.getElementById('updateForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const form = new FormData(this);
            const id = form.get('Teilnehmer_ID');
            form.delete('Teilnehmer_ID');
            fetch(`${apiUrl}/${id}`, {
                method: 'PUT',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(Object.fromEntries(form))
            }).then(res => res.json()).then(data => alert(JSON.stringify(data)));
        });

        document.getElementById('deleteForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const id = new FormData(this).get('Teilnehmer_ID');
            fetch(`${apiUrl}/${id}`, {
                method: 'DELETE'
            }).then(res => res.json()).then(data => alert(JSON.stringify(data)));
        });

        function loadTeilnehmer() {
            fetch(apiUrl)
                .then(res => res.json())
                .then(data => {
                    document.getElementById('output').textContent = JSON.stringify(data, null, 2);
                });
        }
    </script> -->
    <?php

    // Header für JSON-Antwort und CORS
    header('Content-Type: application/json');
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
    header('Access-Control-Allow-Headers: Content-Type');

    // Datenbankverbindungsparameter
    $servername = "localhost"; 
    $username = "Elias";
    $password = "NiMaBe!!6103";
    $dbname = "Skiturnier";

    // Verbindung herstellen
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Verbindung überprüfen
    if ($conn->connect_error) {
        die("Verbindung fehlgeschlagen: " . $conn->connect_error);
    }

    // Request-Methode ermitteln
    $method = $_SERVER['REQUEST_METHOD'];

    // Request-Pfad auslesen
    $path = isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : '/';
    $pathSegments = explode('/', ltrim($path, '/'));
    $resource = isset($pathSegments[0]) ? $pathSegments[0] : '';
    $id = isset($pathSegments[1]) ? $pathSegments[1] : null;

    // API-Logik basierend auf Methode und Ressource
    switch($method) {
    case 'GET':
        if ($resource == 'teilnehmer') {
            if ($id) {
                // Einzelnen Teilnehmer abfragen
                $sql = "SELECT * FROM Teilnehmer WHERE Teilnehmer_ID = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("i", $id);
                $stmt->execute();
                $result = $stmt->get_result();
                $data = $result->fetch_assoc();
                echo json_encode($data);
            } else {
                // Alle Teilnehmer abfragen
                $sql = "SELECT * FROM Teilnehmer";
                $result = $conn->query($sql);
                $data = [];
                while($row = $result->fetch_assoc()) {
                    $data[] = $row;
                }
                echo json_encode($data);
            }
        }
        break;
    case 'POST':
        if ($resource == 'teilnehmer') {
            // Neuen Teilnehmer erstellen
            $data = json_decode(file_get_contents('php://input'), true);
            $sql = "INSERT INTO Teilnehmer (Vorname, Nachname, Team_ID, Email, Telefonnummer, Adresse_ID) 
                    VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssissi", 
                $data['Vorname'], 
                $data['Nachname'], 
                $data['Team_ID'], 
                $data['Email'], 
                $data['Telefonnummer'], 
                $data['Adresse_ID']
            );
            if ($stmt->execute()) {
                echo json_encode(['message' => 'Teilnehmer erstellt', 'id' => $conn->insert_id]);
            } else {
                echo json_encode(['error' => 'Fehler beim Erstellen: ' . $stmt->error]);
            }
        }
        break;
        case 'PUT':
            case 'PATCH':
                if ($resource == 'teilnehmer' && $id) {
                    // Teilnehmer aktualisieren
                    $data = json_decode(file_get_contents('php://input'), true);
                    $sql = "UPDATE Teilnehmer SET Vorname = ?, Nachname = ?, Team_ID = ?, Email = ?, Telefonnummer = ?, Adresse_ID = ?
                            WHERE Teilnehmer_ID = ?";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("ssissii", 
                        $data['Vorname'], 
                        $data['Nachname'], 
                        $data['Team_ID'], 
                        $data['Email'], 
                        $data['Telefonnummer'], 
                        $data['Adresse_ID'],
                        $id
                    );
                    if ($stmt->execute()) {
                        echo json_encode(['message' => 'Teilnehmer aktualisiert']);
                    } else {
                        echo json_encode(['error' => 'Fehler beim Aktualisieren: ' . $stmt->error]);
                    }
                }
                break;
        
            case 'DELETE':
                if ($resource == 'teilnehmer' && $id) {
                    // Teilnehmer löschen
                    $sql = "DELETE FROM Teilnehmer WHERE Teilnehmer_ID = ?";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("i", $id);
                    if ($stmt->execute()) {
                        echo json_encode(['message' => 'Teilnehmer gelöscht']);
                    } else {
                        echo json_encode(['error' => 'Fehler beim Löschen: ' . $stmt->error]);
                    }
                }
                break;        
}

    // Verbindung schließen
    $conn->close();
    ?>
</body>
</html>
