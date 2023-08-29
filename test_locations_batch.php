<?php

$url = "https://crm.smart-pages.ru/json_rpc_test/index.php";    

$content = json_encode(
    [
        [
            'jsonrpc' => '2.0',
            'method' => 'locations',
            'params' => '3,21',
            'id' => 100
        ],
        [
            'jsonrpc' => '2.0',
            'method' => 'error',
            'params' => '4,7',
            'id' => 100
        ],
        [
            'jsonrpc' => '2.0',
            'method' => 'locations',
            'params' => '4,7',
            'id' => 100
        ]
    ]
);

$curl = curl_init($url);
curl_setopt($curl, CURLOPT_HEADER, false);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_HTTPHEADER,
        array("Content-type: application/json"));
curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl, CURLOPT_POSTFIELDS, $content);

$json_response = curl_exec($curl);

$status = curl_getinfo($curl, CURLINFO_HTTP_CODE);

// if ( $status != 201 ) {
//     die("Error: call to URL $url failed with status $status, response $json_response, curl_error " . curl_error($curl) . ", curl_errno " . curl_errno($curl));
// }

curl_close($curl);

$response = json_decode($json_response, true);

echo '<pre>';
var_dump($response);