<?php

    namespace Core;

    class Response {

            private int $statusCode = 200;
            private array $headers = [];

        public function setStatusCode(int $code): self {
            
            $this->statusCode = $code;
            http_response_code($code);
            return $this;
        }

        public function setHeader(string $name, string $value): self {
            $this->headers[$name] = $value;
            header("{$name}: {$value}");
            return $this;
        }

        public function redirect(string $url, int $code = 302): void {
            $this->setStatusCode($code);
            $this->setHeader('Location', $url);
            exit;
        }

        public function getStatusCode(): int {
            return $this->statusCode;
        }

        public function getHeaders(): array {
            return $this->headers;
        }
    }
?>