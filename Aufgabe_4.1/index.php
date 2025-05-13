index <?php
phpinfo();
/*
if (isset($_GET['resource'])) {
    // JSON-Header & CORS
    header('Content-Type: application/json; charset=UTF-8');
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET, POST, PUT, PATCH, DELETE, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type');

    // Preflight sofort beenden
    if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
        exit;
    }

    // PHP-Fehler nicht in HTML, sondern nur intern
    ini_set('display_errors', 0);
    ini_set('display_startup_errors', 0);
    ini_set('html_errors', 0);
    error_reporting(E_ALL);

    // Routing-Variablen
    $method   = $_SERVER['REQUEST_METHOD'];
    $resource = $_GET['resource'];
    $id       = isset($_GET['id']) ? (int)$_GET['id'] : null;

    // DB-Verbindung
    $servername = "localhost";
    $username   = "Elias";
    $password   = "NiMaBe!!6103";
    $dbname     = "Skiturnier";
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        http_response_code(500);
        echo json_encode(['error' => 'DB-Verbindung fehlgeschlagen: ' . $conn->connect_error]);
        exit;
    }

    if ($resource === 'teilnehmer') {
        switch ($method) {
            case 'GET':
                $sql = "SELECT Teilnehmer_ID, Vorname, Nachname, Team_ID, Email, Telefonnr AS Telefonnummer
                        FROM Teilnehmer";
                $res = $conn->query($sql);
                if (!$res) {
                    http_response_code(500);
                    echo json_encode(['error' => 'DB-Query fehlgeschlagen: ' . $conn->error]);
                    exit;
                }
                $arr = [];
                while ($row = $res->fetch_assoc()) {
                    $arr[] = $row;
                }
                echo json_encode($arr);
                exit;

            case 'POST':
                $data = json_decode(file_get_contents('php://input'), true);
                $sql  = "INSERT INTO Teilnehmer (Vorname, Nachname, Team_ID, Email, Telefonnr, Adresse_ID)
                         VALUES (?, ?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);
                if (!$stmt) {
                    http_response_code(500);
                    echo json_encode(['error' => 'Prepare fehlgeschlagen: ' . $conn->error]);
                    exit;
                }
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
                    http_response_code(500);
                    echo json_encode(['error' => 'Fehler beim Erstellen: ' . $stmt->error]);
                }
                exit;

            case 'PUT':
            case 'PATCH':
                if (!$id) {
                    http_response_code(400);
                    echo json_encode(['error' => 'ID fehlt']);
                    exit;
                }
                $data = json_decode(file_get_contents('php://input'), true);
                $sql  = "UPDATE Teilnehmer
                         SET Vorname = ?, Nachname = ?, Team_ID = ?, Email = ?, Telefonnr = ?, Adresse_ID = ?
                         WHERE Teilnehmer_ID = ?";
                $stmt = $conn->prepare($sql);
                if (!$stmt) {
                    http_response_code(500);
                    echo json_encode(['error' => 'Prepare fehlgeschlagen: ' . $conn->error]);
                    exit;
                }
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
                    http_response_code(500);
                    echo json_encode(['error' => 'Fehler beim Aktualisieren: ' . $stmt->error]);
                }
                exit;

            case 'DELETE':
                if (!$id) {
                    http_response_code(400);
                    echo json_encode(['error' => 'ID fehlt']);
                    exit;
                }
                $sql  = "DELETE FROM Teilnehmer WHERE Teilnehmer_ID = ?";
                $stmt = $conn->prepare($sql);
                if (!$stmt) {
                    http_response_code(500);
                    echo json_encode(['error' => 'Prepare fehlgeschlagen: ' . $conn->error]);
                    exit;
                }
                $stmt->bind_param("i", $id);
                if ($stmt->execute()) {
                    echo json_encode(['message' => 'Teilnehmer gelöscht']);
                } else {
                    http_response_code(500);
                    echo json_encode(['error' => 'Fehler beim Löschen: ' . $stmt->error]);
                }
                exit;
        }
    }

    // wenn wir hier landen, gab's keine passende Ressource
    http_response_code(404);
    echo json_encode(['error' => 'Ressource nicht gefunden']);
    exit;
}
?>
<!DOCTYPE html>
<html lang="de">
<head>
  <meta charset="UTF-8">
  <title>Teilnehmer anzeigen</title>
  <style>
    body { font-family: sans-serif; padding: 1em; }
    button { padding: .5em 1em; font-size: 1em; }
    pre { background: #f4f4f4; padding: 1em; margin-top: 1em; }
  </style>
</head>
<body>

  <button id="showPartie">Alle Teilnehmer anzeigen</button>
  <pre id="output"></pre>

  <script>
    document
      .getElementById('showPartie')
      .addEventListener('click', async () => {
        const out = document.getElementById('output');
        out.textContent = 'Lade…';
        try {
          const res = await fetch('index.php?resource=teilnehmer');
          if (!res.ok) throw new Error(`Server-Fehler: ${res.status}`);
          const teilnehmer = await res.json();
          if (teilnehmer.length === 0) {
            out.textContent = 'Keine Teilnehmer gefunden.';
            return;
          }
          const lines = teilnehmer.map(t =>
            `#${t.Teilnehmer_ID}: ${t.Vorname} ${t.Nachname} — Team ${t.Team_ID}, E-Mail: ${t.Email}, Tel: ${t.Telefonnummer}`
          );
          out.textContent = lines.join('\n');
        } catch (err) {
          out.textContent = 'Fehler: ' + err.message;
        }
      });
  </script>

</body>
</html>
