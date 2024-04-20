<?php

namespace App\Core\Http;

use App\Controller\TaskController;

class Router
{
    private array $routes = [
        'GET' => [
            '/' => [
                'controller' => TaskController::class,
                'method' => 'index'
            ],
        ],
        'POST' => [
            '/tasks' => [
                'controller' => TaskController::class,
                'method' => 'store'
            ],
            '/edit-task' => [
                'controller' => TaskController::class,
                'method' => 'edit'
            ],
            '/update-task' => [
                'controller' => TaskController::class,
                'method' => 'update'
            ],
            '/delete-task' => [
                'controller' => TaskController::class,
                'method' => 'delete'
            ]
        ]
    ];

    public function __construct(private readonly Request $request){}

    public function route(): void
    {
        $path = $this->request->getUri();
        $method = $this->request->getMethod();
        $availableRoutes = $this->routes[$method];
        if (isset($availableRoutes[$path]))
        {
            $controller = new $availableRoutes[$path]['controller']($this->request);
            $action = $availableRoutes[$path]['method'];
            $reflection = new \ReflectionMethod($controller, $action);
            $params = $reflection->getParameters();
            $instances = [];
            foreach ($params as $param)
            {
                $instances []= new ($param->getType()->getName());
            }

            $reflection->invokeArgs($controller, $instances);
        }
        else
        {
            header('HTTP/1.1 404 Not Found');
        }
    }
}