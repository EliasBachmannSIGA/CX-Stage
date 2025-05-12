<?php

$method = $_SERVER['REQUEST_METHOD'];
$file = 'data.json';

// Lade JSON-Daten
$addresses = file_exists($file) ? json_decode(file_get_contents($file), true) : [];

switch ($method) {
    case 'GET':
        header('Content-Type: application/json');
        echo json_encode($addresses, JSON_PRETTY_PRINT);
        break;

    case 'POST':
        $newAddress = [
            'prename' => $_POST['prename'],
            'lastname' => $_POST['lastname'],
            'id' => $_POST['id']
        ];

        $addresses[] = $newAddress;
        file_put_contents($file, json_encode($addresses, JSON_PRETTY_PRINT));

        header('Content-Type: application/json');
        echo json_encode($newAddress);
        break;

    case 'PUT':
    case 'PATCH':
        parse_str(file_get_contents("php://input"), $putData);
        
        if (empty($addresses)) {
            http_response_code(400);
            echo json_encode(["message" => "Keine Daten vorhanden zum Ändern."]);
            exit;
        }

        // Ändere die letzte Adresse
        $addresses[count($addresses) - 1] = [
            'prename' => $putData['prename'],
            'lastname' => $putData['lastname'],
            'id' => $putData['id']
        ];

        file_put_contents($file, json_encode($addresses, JSON_PRETTY_PRINT));

        header('Content-Type: application/json');
        echo json_encode($addresses[count($addresses) - 1]);
        break;

    case 'DELETE':
        if (empty($addresses)) {
            http_response_code(400);
            echo json_encode(["message" => "Keine Daten vorhanden zum Löschen."]);
            exit;
        }

        $deleted = array_pop($addresses);
        file_put_contents($file, json_encode($addresses, JSON_PRETTY_PRINT));

        header('Content-Type: application/json');
        echo json_encode($deleted);
        break;

    default:
        http_response_code(405);
        echo json_encode(["message" => "HTTP-Methode nicht unterstützt."]);
        break;
}
