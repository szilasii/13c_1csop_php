
<?php
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods : GET,POST");
header("Access-Control-Allow-Headers : Content-Type");

$method = $_SERVER['REQUEST_METHOD'];
if ($_SERVER['REQUEST_URI'] === '/api/users') {
switch ($method) {
    case 'GET':
        $response = [
            "status" => "success",
            "message" => "GET request received",
            "code" => 201,
            "data" => [
                "id" => 1,
                "name" => "John Doe",
                "email" => "john.doe@example.com"
            ]
        ];
        echo json_encode($response);
        break;
    case 'POST':
        $input = json_decode(file_get_contents("php://input"), true);
         $response = [
            "status" => "success",
            "message" => "POST request received",
            "code" => 200,
            "data" => $input
        ];


        echo json_encode($response);
        break;
    default:
        echo json_encode(["message" => "Unsupported request method"]);
        break;
}
} else {
    echo json_encode(["message" => "Endpoint not found"]);
}




?>    

