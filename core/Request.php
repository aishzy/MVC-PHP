<?php
    
    namespace Core;

    class Request {

        private array $params = [];

        public function method(): string {
            // Allow method override via hidden _method field 
            $override = $_POST['_method'] ?? null;
            if ($override && in_array(strtoupper($override), ['PUT', 'PATCH', 'DELETE'])) {
                return strtoupper($override);
            }
            return strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET');
        }

        public function uri(): string {
            $uri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
            return rtrim($uri, '/') ?: '/';
        }

        public function isPost(): bool {
            return $this->method() === 'POST';
        }

        public function isGet(): bool {
            return $this->method() === 'GET';
        }

        public function isAjax(): bool {
            return ($_SERVER['HTTP_X_REQUESTED_WITH'] ?? '') === 'XMLHttpRequest'; 
        }

        // Input 
        public function get(string $key, mixed $default = null): mixed {
            return $_GET[$key] ?? $default;
        }

        public function post(string $key, mixed $default = null): mixed {
            return $_POST[$key] ?? $_GET[$key] ?? $default;
        }

        public function input(string $key, mixed $default = null): mixed {
            return $_POST[$key] ?? $_GET[$key] ?? $default;
        }

        public function all(): array {
            return array_merge($_GET, $_POST);
        }

        public function only(array $keys): array {
            return array_intersect_key($this->all(), array_flip($keys));
        }

        public function has(string $key): bool {
            return isset($_POST[$key]) || isset($_GET[$key]);
        }

        // Route parameters
        public function setParams(array $params): void {
            $this->params = $params;
        }

        public function param(string $key, mixed $default = null): mixed {
            return $this->params[$key] ?? $default;
        }

        // Files 
        public function file(string $key): array | null {
            return $_FILES[$key] ?? null;
        }

        // Headers
        public function header(string $name): ?string {
            $key = 'HTTP_' . strtoupper(str_replace('-', '_', $name));
            return $_SERVER[$key] ?? null;
        }

        public function ip(): string {
            return $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
        }
    }
?>