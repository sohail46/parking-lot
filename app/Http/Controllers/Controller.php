<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{
    protected $_request;

    public function jsonResponse($data, $message = '')
    {
        $this->_request = app('request');
        if (!empty($data)) {
            $responseData['data'] = $data;
        }
        $response = '';
        $responseCode = 200;
        $this->utf8_encode_deep($responseData);

        switch (strtoupper($this->_request->method())) {
            case 'POST':
                $responseCode = 201;
                break;

            default:
            case 'PUT':
            case 'GET':
                $responseCode = 200;
                break;

            case 'DELETE':
                $responseCode = 200;
                break;
        }
        $responseData['status'] = $responseCode;
        $responseData['success'] = 1;
        $responseData['message'] = $message;
        $response = response()->json($responseData, $responseCode, array(), JSON_PRETTY_PRINT);
        return $response;
    }

    public function utf8_encode_deep(&$input)
    {
        if (is_string($input)) {
            $str = iconv('UTF-8', 'UTF-8//IGNORE', $input);
            if ($str) {
                $input = $str;
            } else {
                $input = utf8_encode($input);
            }
        } else if (is_array($input)) {
            foreach ($input as &$value) {
                self::utf8_encode_deep($value);
            }

            unset($value);
        } else if (is_object($input)) {
            $vars = array_keys(get_object_vars($input));

            foreach ($vars as $var) {
                self::utf8_encode_deep($input->$var);
            }
        }
    }
}
