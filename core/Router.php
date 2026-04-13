<?php

    namespace Core;

    class Router {

        private array $routes = [];

        // registration
        public function get(string $path, string $handler): void {
            $this->addRoute('GET', $path, $handler);
        }

        public function post(string $path, string $handler): void {
            $this->addRoute('POST', $path, $handler);
        }

        public function put(string $path, string $handler): void {
            $this->addRoute('PUT', $path, $handler);
        }

        public function delete(string $path, string $handler): void {
            $this->addRoute('DELETE', $path, $handler);
        }

        private function addRoute(string $method, string $path, string $handler): void {
            [$controllerClass, $action] = explode('@', $handler);

            $this->routes[] = [
                'method' => $method,
                'path' => $path,
                'pattern' => $this->pathToRegex($path),
                'controller' => "App\\Controllers\\{$controllerClass}",
                'action' => $action,
            ];
        }

        // Dispatch 
        public function dispatch(Request $request, Response $response): void {
            $method = $request->method();
            $uri = $request->uri();

            foreach ($this->routes as $route) {
                if ($route['method'] !== $method) continue;
                if (!preg_match($route['pattern'], $uri, $matches)) continue;

                // Extract named params like {id}
                $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
                $request->setParams($params);

                $controller = new $route['controller']($request, $response);
                $action = $route['action'];

                if (!method_exists($controller, $action)) {
                    throw new \RuntimeException("Action {$action} not found.", 500);
                }
                $controller->$action();
                return;
            }
            throw new \RuntimeException("Route not found: {$uri}", 403);
        }

        // Helpers 
        private function pathToRegex(string $path): string {
            // convert {param} placeholders to named capture groups
            $pattern = preg_replace('/\{([a-zA-Z_]+)\}/', '(?P<$1>[^/]+)', $path);
            return '#^' . $pattern . '$#';
        }
    }
?>