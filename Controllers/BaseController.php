<?php
require_once(__DIR__ . "/../services/ResponseService.php");

abstract class BaseController {
    protected static function success($data, int $code = 200) {
        ResponseService::success_response($code, $data);
    }

    protected static function error($message, int $code = 500) {
        ResponseService::error_response($code, $message);
    }
}
?>