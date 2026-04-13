<?php
    namespace Core;

    class App {
        private Router $router;
        private array $config;

        public function __construct() {
            $this->config = require BASE_PATH . '/config/app.php';
            date_default_timezone_set($this->config['timezone']);

            if ($this->config['debug']) {
                ini_set('display_errors', 1);
                error_reporting(E_ALL);
            }
            Session::start();
            $this->router = new Router();
        }

        public function run(): void {
            $router = $this->router;
            require_once BASE_PATH . '/routes.php';

            $request = new Request();
            $response = new Response();

            try {
                $this->router->dispatch($request, $response);
            } catch(\Exception $e) {
                $this->handleException($e, $request, $response);
            }
        }

        private function handleException(\Exception $e, Request $request, Response $response): void {
            $code = $e->getCode() ?: 500;
            $response->setStatusCode($code);

            $controller = new \App\Controllers\ErrorController($request, $response);
            $method = $code === 404 ? 'NotFound' : 'ServerError';
            $controller->$method($e->getMessage());
        }

    }
?>