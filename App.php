<?php

use Client\LightWeightHttpClient;

require_once('./config.php');
require_once('./HttpResponse.php');
require_once('./LightWeightHttpClient.php');

(function($client) {
    try {
        $response = $client->options('assessment-endpoint.php');
        $token = $response['body'];

        $client->setHeaders([
            'Accept: application/json',
            'Authorization: Bearer '.$token
        ]);
    
        $response = $client->post('posts', [
            'json' => [
                'name' => 'Serhii Holovanenko',
                'email' => 'serhii.holovanenko@gmail.com',
                'url' => 'https://github.com/SHolovanenko/test-light-weight-http-client'
            ]
        ]);

        print_r($response);

    } catch (Exception $e) {
        print_r($e->message);
    }
}) (new LightWeightHttpClient('https://www.coredna.com'));