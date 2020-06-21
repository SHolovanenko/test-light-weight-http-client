<?php 

namespace Client;

class HttpResponse {
    private $headers;
    private $body;

    public function __construct($headers, $body)
    {
        $this->headers = $headers;
        $this->body = $body;
    }

    public function getHeaders() {
        return $this->headers;
    }

    public function getBody() {
        foreach ($this->headers as $header) {
            if (strpos($header, 'Content-Type: application/json') !== false)
                return json_decode($this->body, true);
        }

        return $this->body;
    }

    public function asArray() {
        return [
            'headers' => $this->getHeaders(),
            'body' => $this->getBody()
        ];
    }
}