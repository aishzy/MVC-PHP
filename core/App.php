<?php
    /**
     * Core Framework
     */

    namespace Core;

    class App {

        private Router $router;
        private array $config;

        public function __construct() {
            $this->config = require BASE_PATH . '/config/app.php';
            date_default_timezone_set($this->config['timezone']);

            if ($this->config['debug']) {
                ini_set('display_errors', '1');
                error_reporting(E_ALL);
            }

            session::start();
            $this->router = new Router();
        }

        public function run(): void {
            // Register routes
            $router = $this->router;
            require_once BASE_PATH . '/routes.php';

            // Dispatch current request 
            $request = new Request();
            $response = new Response();

            try {
                $this->router->dispatch($request, $response);
            }
            catch (\Exception $e) {
                $this->handleException($e, $response);
            }
        }

        private function handleException(\Exception $e, Response $response): void {
            
            $code = $e->getCode() ?: 500;
            $response->setStatusCode($code);

            $controller = new \App\Controllers\ErrorController();
            $method = $code === 404 ? 'notFound' : 'serverError';
            $controller->$method($e->getMessage());
        }
    }
?>