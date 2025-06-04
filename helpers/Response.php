<?php

class Response
{
    public static function json($data, $status = 200)
    {
        if (ob_get_length() > 0) {
            ob_clean();
        }
        header('Content-Type: application/json');
        http_response_code($status);

        $res = json_encode([
            'data' => $data,
            'status' => $status
        ]);
        echo $res;
        exit;
    }

    public static function error($message, $status = 400)
    {
        return json_encode([
            'error' => true,
            'message' => $message
        ], $status);
    }
}