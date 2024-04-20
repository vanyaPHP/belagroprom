<?php

namespace App\Core;

use App\Core\Http\Request;
use App\Core\Http\Router;

class App
{
    private Router $router;
    public function __construct()
    {
        $this->router = new Router(new Request());
    }

    public function run(): void
    {
        $this->router->route();
    }
}