<?php

namespace App\Core\Http;

class Request
{
    public function getMethod()
    {
        return $_SERVER["REQUEST_METHOD"];
    }

    public function getUri()
    {
        $uri = $_SERVER["REQUEST_URI"];
        $queryParamsStartIndex = strpos($uri, '?');
        if (!$queryParamsStartIndex)
        {
            return $uri;
        }

        return substr($uri, 0, $queryParamsStartIndex);
    }

    public function query(string $key = null)
    {
        return ($key != null) ? ($_GET[$key] ?? null) : $_GET;
    }

    public function body(string $key = null)
    {
        return ($key != null) ? ($_POST[$key] ?? null) : $_POST;
    }
}