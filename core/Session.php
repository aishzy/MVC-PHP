<?php

    namespace Core;

    class Session {

        public static function start(): void {
            
            if (session_status() === PHP_SESSION_NONE) {
                session_name('MVC_SESSION');
                session_start([
                    'cookie_httponly' => true,
                    'cookie_samesite' => 'lax',
                ]);
            }
        }

        public static function set(string $key, mixed $value): void {
            $_SESSION[$key] = $value;
        }

        public static function get(string $key, mixed $default = null): mixed {
            return $_SESSION[$key] ?? $default;
        }

        public static function has(string $key): bool {
            return isset($_SESSION[$key]);
        }

        public static function remove(string $key): void {
            unset($_SESSION[$key]);
        }

        public static function destroy(): void {
            session_unset();
            session_destroy();
        }

        // Flash messages 
        public static function flash(string $key, mixed $value): void {
            $_SESSION['_flash'][$key] = $value;
        }
        
        public static function getFlash(string $key, mixed $default = null): mixed {
            $value = $_SESSION['_flash'][$key] ?? $default;
            unset($_SESSION['_flash'][$key]);
            return $value;
        }

        public static function hasFlash(string $key): bool {
            return isset($_SESSION['_flash'][$key]);
        }
    }
?>