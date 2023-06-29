<?php

namespace App;

class Request
{
    private array $headers;
    private array $postParams;
    private array $getParams;
    private array $queryParams;
    private array $files;
    private array $server;
    private array $session;
    private array $data;

    public function __construct()
    {
        $this->headers = $this->parseRequestHeaders();
        $this->queryParams = $this->parseQueryParams();
        $this->data = $this->parseJsonData();
        $this->postParams = $_POST ?? [];
        $this->getParams = $_GET ?? [];
        $this->files = $_FILES ?? [];
        $this->server = $_SERVER ?? [];
        $this->session = $_SESSION ?? [];

    }

    private function parseRequestHeaders(): array
    {
        $headers = [];
        foreach ($_SERVER as $key => $value) {
            if (str_starts_with($key, 'HTTP_')) {
                $headerKey = str_replace('HTTP_', '', $key);
                $headerKey = str_replace('_', '-', $headerKey);
                $headers[$headerKey] = $value;
            }
        }
        return $headers;
    }

    private function parseQueryParams()
    {
        parse_str($_SERVER['QUERY_STRING'] ?? "", $queryParams);
        return $queryParams;
    }

    private function parseJsonData(): array
    {
        $contentType = $this->getHeader('Content-Type');
        if ($contentType === 'application/json') {
            $json = file_get_contents('php://input');
            return json_decode($json, true);
        }
        return [];
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function getHeader($name)
    {
        return $this->headers[$name] ?? null;
    }

    public function getPostParams(): array
    {
        return $this->postParams;
    }

    public function getPostParam(string $param)
    {
        return $this->postParams[$param] ?? null;
    }

    public function getQueryParams(): array
    {
        return $this->queryParams;
    }

    public function getQueryParam($param)
    {
        return $this->queryParams[$param] ?? null;
    }

    public function getRequestParams(): array
    {
        return array_merge($this->queryParams, $this->postParams);
    }

    public function getRequestParam($param)
    {
        $requestParams = $this->getRequestParams();
        return $requestParams[$param] ?? null;
    }

    public function getJsonParams(): array
    {
        return $this->data;
    }

    public function getJsonParam($param)
    {
        return $this->data[$param] ?? null;
    }

    public function getMethod()
    {
        return $this->server['REQUEST_METHOD'];
    }

    public function getUri()
    {
        return $this->server['REQUEST_URI'];
    }


}
