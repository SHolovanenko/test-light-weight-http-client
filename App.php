<?php

use Client\LightWeightHttpClient;

require_once('./config.php');
require_once('./HttpResponse.php');
require_once('./LightWeightHttpClient.php');

(function($client) {
    $client->setHeaders([
        'Accept: application/json',
        'Authorization: Bearer '.BEARER_TOKEN
    ]);

    print_r($client->get('posts'));

    print_r($client->post('posts', [
        'json' => [
            'user_id' => 1,
            'title' => 'my title',
            'body' => 'my text'
        ]
    ]));

}) (new LightWeightHttpClient('https://gorest.co.in/public-api'));