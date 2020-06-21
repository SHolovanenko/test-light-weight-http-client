<?php

namespace Client;

use Client\HttpResponse;
use Exception;

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

    private function parseResponseCode($headers) {
        preg_match('/\d{3}/', $headers[0], $matches);
        $code = intval($matches[0]);

        switch ($code) {
            case 400: throw new Exception('Bad Request', $code); break;
            case 401: throw new Exception('Unauthorized', $code); break;
            case 402: throw new Exception('Payment Required', $code); break;
            case 403: throw new Exception('Forbidden', $code); break;
            case 404: throw new Exception('Not Found', $code); break;
            case 405: throw new Exception('Method Not Allowed', $code); break;
            case 406: throw new Exception('Not Acceptable', $code); break;
            case 407: throw new Exception('Proxy Authentication Required', $code); break;
            case 408: throw new Exception('Request Time-out', $code); break;
            case 409: throw new Exception('Conflict', $code); break;
            case 410: throw new Exception('Gone', $code); break;
            case 411: throw new Exception('Length Required', $code); break;
            case 412: throw new Exception('Precondition Failed', $code); break;
            case 413: throw new Exception('Request Entity Too Large', $code); break;
            case 414: throw new Exception('Request-URI Too Large', $code); break;
            case 415: throw new Exception('Unsupported Media Type', $code); break;
            case 500: throw new Exception('Internal Server Error', $code); break;
            case 501: throw new Exception('Not Implemented', $code); break;
            case 502: throw new Exception('Bad Gateway', $code); break;
            case 503: throw new Exception('Service Unavailable', $code); break;
            case 504: throw new Exception('Gateway Time-out', $code); break;
            case 505: throw new Exception('HTTP Version not supported', $code); break;
            default:
                return $code;
            break;
        }
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
        try {
            $headers = isset($params['headers']) ? $$params['headers'] : [];

            $opts = [
                'http' => [
                    'method'  => 'GET',
                    'header'  => $this->getHeaders($headers)
                ]
            ];
            $context  = stream_context_create($opts);
            $result = file_get_contents($this->uri($relativeUri), false, $context);
            $this->parseResponseCode($http_response_header);
    
            $response = new HttpResponse($http_response_header, $result);
    
            return $response->asArray();

        } catch (Exception $e) {
            throw $e;
        }
    }

    public function post($relativeUri, array $params = []) {
        try{
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
            $this->parseResponseCode($http_response_header);

            $response = new HttpResponse($http_response_header, $result);

            return $response->asArray();

        } catch (Exception $e) {
            throw $e;
        }
    }

    public function options($relativeUri, array $params = []) {
        try {
            $headers = isset($params['headers']) ? $$params['headers'] : [];

            $opts = [
                'http' => [
                    'method'  => 'OPTIONS',
                    'header'  => $this->getHeaders($headers)
                ]
            ];
            $context  = stream_context_create($opts);
            $result = file_get_contents($this->uri($relativeUri), false, $context);
            $this->parseResponseCode($http_response_header);
    
            $response = new HttpResponse($http_response_header, $result);
    
            return $response->asArray();

        } catch (Exception $e) {
            throw $e;
        }
    }

    public function delete() {
        //TODO
    }

    public function head() {
        //TODO
    }

    public function patch() {
        //TODO
    }

    public function put() {
        //TODO
    }
}
