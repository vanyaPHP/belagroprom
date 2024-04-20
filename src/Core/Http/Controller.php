<?php

namespace App\Core\Http;

class Controller
{
    public function __construct(protected readonly Request $request)
    {
    }

    protected function query(string $key = null)
    {
        return $this->request->query($key);
    }

    protected function body(string $key = null)
    {
        return $this->request->body($key);
    }

    protected function getUri()
    {
        return $this->request->getUri();
    }

    protected function getMethod()
    {
        return $this->request->getMethod();
    }
}