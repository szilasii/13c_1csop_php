<?php
class Controller {
    protected function json($data,$status = 200) {
        http_response_code($status);
       
         if ($data === null) {
            return;
        }
        header("Content-Type: application/json; charset=UTF-8");
        echo json_encode($data);
    }
    
    protected function getInput() {
        return json_decode(file_get_contents("php://input"), true);
    }
}