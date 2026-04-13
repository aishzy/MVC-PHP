<?php
    // Global helper functions available everywhere

    if (!function_exists('url')) {
        function url(string $path = ''): string {
            $base = rtrim($_ENV['APP_URL'] ?? '', '/');
            return $base . '/' . ltrim($path, '/');
        }
    }

    if (!function_exists('asset')) {
        function asset(string $path): string {
            return url('public/' . ltrim($path, '/'));
        }
    }

    if (!function_exists('e')) {
        /**
         * HTML-escape a string
         */
        function e(mixed $value): string {
            return htmlspecialchars((string)$value, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        }
    }

    if (!function_exists('dd')) {
        /**
         * Dump and die 
         */
        function dd(mixed ...$vars): never {
            foreach ($vars as $var) {
                echo '<pre>';
                var_dump($var);
                echo '</pre>';
            }
            exit(1);
        }
    }

    if (!function_exists('session')) {
        function session(string $key, mixed $default = null): mixed {
            return Core\Session::get($key, $default);
        }
    }

    if (!function_exists('flash')) {
        function flash(string $key, mixed $value = null): mixed {
            if ($value !== null) {
                Core\Session::flash($key, $value);
                return null;
            }
            return Core\Session::getFlash($key);
        }
    }

    if (!function_exists('redirect')) {
        function redirect(string $url): never {
            header("Location: {$url}");
            exit;
        }
    }

    if (!function_exists('config')) {
        function config(string $file, string $key = ''): mixed {
            static $cache = [];
            if (!isset($cache[$file])) {
                $cache[$file] = require BASE_PATH . "/config/{$file}.php";
            }
            if ($key === '') return $cache[$file];
            return $cache[$file][$key] ?? null;
        }
    }
?>