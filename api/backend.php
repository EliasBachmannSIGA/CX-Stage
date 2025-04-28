<?php

$method = $_SERVER['REQUEST_METHOD'];
$addresses = json_decode(file_get_contents('data.json'));

switch($method) {
    case 'GET': 
        $data = $addresses;
        
        header("Content-Type: application/json");
        echo json_encode($data); 
        exit;

    case 'POST':
        $foundAddressKey = null;
        foreach ($addresses as $key => $address) {
            print_r($address->id . ' ? ' . $_POST['id']); echo '<br>';
            // we found the address with given id, use this one
            if($address->id == $_POST['id']) {
                $foundAddressKey = $key;
            }
        }

        if(!$foundAddressKey) {
            // we not have an adress with given id, create one
            $newAddress = [
                'prename' => $_POST['prename'],
                'lastname' => $_POST['lastname'],
                'id' => $_POST['id']
            ];
            $addresses[] = $newAddress;

            file_put_contents('data.json', json_encode($addresses));
            
            header("Content-Type: application/json");
            echo json_encode($newAddress);
            exit;
        }
        else {
            // we have an address found fron line 12ff --> set new values
            $address->prename = $_POST['prename'];
            $address->lastname = $_POST['lastname'];

            
            $addresses[$foundAddressKey] = $address;
            file_put_contents('data.json', json_encode($addresses));

            header("Content-Type: application/json");
            echo json_encode($address);
            exit;
        }

    case 'PUT':     
    case 'PATCH':     
        // Get the input data
        parse_str(file_get_contents('php://input'), $_PUT_PATCH);

        // Initialize a flag to check if the address already exists
        $foundAddressKey = null;
        
        // Check if the prename and lastname already exist in the data
        foreach ($addresses as $key => $address) {
            if ($address->id == $_PUT_PATCH['id']) {
                $foundAddressKey = $key;
                break;
            }
        }

        if ($foundAddressKey == null) {
            http_response_code(400);
            echo json_encode(["message" => "Address doesn't exist"]);
            exit;
        }

        $changedAdress = [
            'prename' => $_PUT_PATCH['prename'],
            'lastname' => $_PUT_PATCH['lastname'],
            'id' => $_PUT_PATCH['id']
        ];

        $addresses[$foundAddressKey] = $changedAdress;

        // Save the updated addresses back to the data.json
        file_put_contents('data.json', json_encode($addresses));

        // Send the new address as the response
        header("Content-Type: application/json");
        echo json_encode($changedAdress);
        exit;                
    case 'DELETE':
    
        break; 
    default:
        die('unknown http method requested');

}
 