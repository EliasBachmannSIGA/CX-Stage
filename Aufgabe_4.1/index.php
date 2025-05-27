<?php
$parsedUrl = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$table     = basename($parsedUrl);
$table_ID = $table.'_ID';

$host  = 'localhost';
$db    = 'Skiturnier';
$user  = 'Elias';
$pass  = 'NiMaBe!!6103';

$allowedTables = [
  'Adresse','Team','Teilnehmer','Teilnehmer_Wertung',
  'Turnier','Turnier_Teilnehmer','Turnier_Zuschauer',
  'Turnier_Wertung','Wertung','Zuschauer'
];

if (!in_array($table, $allowedTables)) {
  http_response_code(404);
  echo 'Tabelle nicht gefunden';
  exit;
}

try {
  $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
  http_response_code(500);
  echo 'DB-Verbindung fehlgeschlagen: ' . $e->getMessage();
  exit;
}

switch ($_SERVER['REQUEST_METHOD']) {
  case 'GET':
    $stmt = $pdo->query("SELECT * FROM `$table`");
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($rows, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
    break;

  case 'POST':
    $input = json_decode(file_get_contents('php://input'), true);

    // Hash (Aus Vorname, Nachname und Email)
    if (isset($input['Vorname'], $input['Nachname'], $input['Email'])) {
      $combined = strtolower(trim($input['Vorname'] . $input['Nachname'] . $input['Email']));
      $hash = md5($combined);

      $checkStmt = $pdo->prepare("
        SELECT COUNT(*) FROM Teilnehmer
        WHERE MD5(LOWER(CONCAT(Vorname, Nachname, Email))) = :hash
      ");
      $checkStmt->bindValue(':hash', $hash, PDO::PARAM_STR);
      $checkStmt->execute();
      $exists = $checkStmt->fetchColumn();

      // Response ändern
      if ($exists > 0) {
        http_response_code(409);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode([
          'success' => false,
          'message' => 'Teilnehmer existiert bereits'
        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        exit;
      }
    }


    if (!is_array($input) || empty($input)) {
      http_response_code(400);
      header('Content-Type: application/json; charset=utf-8');
      echo json_encode(['error'=>'Ungültiges oder leeres JSON-Objekt.']);
      exit;
    }

    $data = [];
    foreach ($input as $key => $value) {
      if ($key == $table_ID) {continue;}
      $data[$key] = $value;
    }

    $colList = implode(', ', array_keys($data));
    $phList = ':' . implode(', :', array_keys($data));
    $sql  = "INSERT INTO `$table` ($colList) VALUES ($phList)";
    $stmt = $pdo->prepare($sql);

    foreach ($data as $col => $val) {
      if (ctype_digit(strval($val))) {
        $stmt->bindValue(":$col", (int)$val, PDO::PARAM_INT);
      } else {
        $stmt->bindValue(":$col", $val, PDO::PARAM_STR);
      }
    }

    try {
      $stmt->execute();
      $id = $pdo->lastInsertId();

      http_response_code(201);
      header('Content-Type: application/json; charset=utf-8');
      echo json_encode(['success'=>true,'insert_id'=>$id,'table'=>$table], JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
    } catch (PDOException $e) {
      http_response_code(500);
      header('Content-Type: application/json; charset=utf-8');
      echo json_encode(['error'=>'Einfügen fehlgeschlagen','message'=>$e->getMessage()], JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
    }
    break;
    case 'PUT':
    case 'PATCH': 
        $input = json_decode(file_get_contents('php://input'), true);
        if (!is_array($input) || empty($input)) {
          http_response_code(400);
          header('Content-Type: application/json; charset=utf-8');
          echo json_encode(['error'=>'Ungültiges oder leeres JSON-Objekt.']);
          exit;
        }
    
        $idColumn = $table . '_ID';
        if (!isset($input[$idColumn]) || !ctype_digit(strval($input[$idColumn]))) {
          http_response_code(400);
          header('Content-Type: application/json; charset=utf-8');
          echo json_encode(['error'=>"Fehlende oder ungültige $idColumn."]);
          exit;
        }
        $idValue = (int)$input[$idColumn];
        unset($input[$idColumn]);
        
        $cols     = array_keys($input);
        $setParts = array_map(fn($c)=> "`$c` = :$c", $cols);
        $setList  = implode(', ', $setParts);
    
        $sql  = "UPDATE `$table` SET $setList WHERE `$idColumn` = :id";
        $stmt = $pdo->prepare($sql);
    
        foreach ($input as $col => $val) {
          if (ctype_digit(strval($val))) {
            $stmt->bindValue(":$col", (int)$val, PDO::PARAM_INT);
          } else {
            $stmt->bindValue(":$col", $val, PDO::PARAM_STR);
          }
        }
        $stmt->bindValue(':id', $idValue, PDO::PARAM_INT);
    
        try {
          $stmt->execute();
          http_response_code(200);
          header('Content-Type: application/json; charset=utf-8');
          echo json_encode([
            'success'   => true,
            'updated_id'=> $idValue,
            'table'     => $table
          ], JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
        } catch (PDOException $e) {
          http_response_code(500);
          header('Content-Type: application/json; charset=utf-8');
          echo json_encode([
            'error'   => 'Update fehlgeschlagen',
            'message' => $e->getMessage()
          ], JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
        }
    break;
    
    //DELETE
    default:
    http_response_code(405);
    header('Allow: GET, POST, PUT');
    echo 'Method Not Allowed. Nur  sind erlaubt.';
    break;
}
?>
