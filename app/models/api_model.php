<?php
class ApiModel extends Controller
{
    public function doAPI($data)
    {
        $api = PREFIX.$data->api.DNS;
        unset($data->api);
        $postData = json_encode($data);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $api);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FRESH_CONNECT, TRUE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, self::getHeader($postData));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);

        $res = curl_exec($ch);
        if(isset($res)){
            return $res; 
        }
        curl_close($ch);
    }

    public function createPersonProfile($input)
    {
        error_log('show input: '.print_r($input, 1));
        $data = new stdClass();
        $data->api = 'database';
        $data->connection = 'CORE';
        $data->procedure = __FUNCTION__;
        $data->params = $input;
        $res = self::responseObject(self::doAPI($data));
        return $res[0];
    }

    public function updatePersonProfile($input)
    {
        error_log('show input updatePersonProfile: '.print_r($input, 1));
        $data = new stdClass();
        $data->api = 'database';
        $data->connection = 'CORE';
        $data->procedure = __FUNCTION__;
        $data->params = $input;
        $res = self::responseObject(self::doAPI($data));
        return $res[0];
    }

    public function getProfile($input)
    {
        $data = new stdClass();
        $data->api = 'database';
        $data->connection = 'CORE';
        $data->procedure = __FUNCTION__;
        $data->params->userId = $input;
        $res = self::responseObject(self::doAPI($data));
        return $res[0];
    }

    public function responseObject($data)
    {
        $resObj = json_decode($data);
        return $resObj;
    }

    public function getHeader($data)
    {
        $signature = base64_encode(hash_hmac('sha256', $data, SIGNATURE, true));
        $header = array('Content-Type:application/json', 'APP-SECURITY-AUTH:'.$signature);
        return $header;
    }
}