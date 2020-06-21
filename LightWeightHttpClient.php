<?php

namespace Client;

use Client\HttpResponse;

class LightWeightHttpClient 
{
    private $baseUri;
    private $headers;
    
    public function __construct($baseUri, $headers = [])
    {
        $this->baseUri = $baseUri;
        $this->headers = $headers;
    }

    private function uri($relativeUri) {
        $result = strpos($relativeUri, '/');
        if ($result === 0)
            return $this->baseUri . $relativeUri;
        
        return $this->baseUri . '/' . $relativeUri;
    }

    public function addHeader($header) {
        $this->headers[] = $header;
    }

    public function setHeaders(array $headers) {
        $this->headers = $headers;
    }

    public function getHeaders(array $headers = []) {
        $headers = empty($headers) ? $this->headers : $headers;
        $result = '';
        
        foreach ($this->headers as $header) {
            $result .= $header . "\r\n";
        }

        return $result;
    }

    public function get($relativeUri, array $params = []) {
        $headers = isset($params['headers']) ? $$params['headers'] : [];

        $opts = [
            'http' => [
                'method'  => 'GET',
                'header'  => $this->getHeaders($headers)
            ]
        ];
        $context  = stream_context_create($opts);
        $result = file_get_contents($this->uri($relativeUri), false, $context);

        $response = new HttpResponse($http_response_header, $result);

        return $response->asArray();
    }

    public function post($relativeUri, array $params = []) {
        $headers = isset($params['headers']) ? $$params['headers'] : [];
        
        if (isset($params['json'])) {
            $postdata = json_encode($params['json']);
            $this->addHeader('Content-Type: application/json;');
        } else if (isset($params['body'])) {
            $postdata = $params['body'];
            $this->addHeader('Content-type: application/x-www-form-urlencoded;');
        } else {
            $postdata = '';
        }

        $opts = [
            'http' => [
                'method'  => 'POST',
                'header'  => $this->getHeaders($headers),
                'content' => $postdata
            ]
        ];
        $context  = stream_context_create($opts);
        $result = file_get_contents($this->uri($relativeUri), false, $context);

        $response = new HttpResponse($http_response_header, $result);

        return $response->asArray();
    }

    public function delete() {

    }

    public function head() {

    }

    public function options() {

    }

    public function patch() {

    }

    public function put() {

    }
}
