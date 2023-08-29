<?php

class Base
{
    
    function fetchUrl($url) {
        $handle = curl_init();
    
        curl_setopt($handle, CURLOPT_URL, $url);
        curl_setopt($handle, CURLOPT_POST, false);
        curl_setopt($handle, CURLOPT_BINARYTRANSFER, false);
        curl_setopt($handle, CURLOPT_HEADER, true);
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($handle, CURLOPT_CONNECTTIMEOUT, 10);
    
        $response = curl_exec($handle);

        file_put_contents($_SERVER['DOCUMENT_ROOT'].'/err_log.txt',json_encode($response)."\r\n", FILE_APPEND);
        $hlength  = curl_getinfo($handle, CURLINFO_HEADER_SIZE);
        $httpCode = curl_getinfo($handle, CURLINFO_HTTP_CODE);
        $body     = substr($response, $hlength);
    
        //file_put_contents($_SERVER['DOCUMENT_ROOT'].'/err_log.txt',json_encode($httpCode)."\r\n", FILE_APPEND);

        //var_dump($httpCode);
        //die();

        // If HTTP response is not 200, throw exception
        if ($httpCode != 200) {
            return false;
        }
    
        return $body;
    } 
}