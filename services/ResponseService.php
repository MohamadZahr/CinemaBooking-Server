<?php

class ResponseService {

    public static function success_response( int $code = 200 , $payload ) {
        
        echo json_encode([
            "status" => $code,
            "payload" => $payload
        ]);
    }

    public static function error_response( int $code = 500 , $message ) {
        echo json_encode([
            "status" => $code,
            "error" => $message
        ]);
    }
}
