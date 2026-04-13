<?php

    namespace App\Controllers;

    use Core\Controller;

    class ErrorController extends Controller {
        
        public function NotFound(string $message = 'Page not found'): void {
            $this->view('errors/404', ['title' => '404 Not Found', 'message' => $message], 'main');
        }

        public function ServerError(string $message = 'Internal server error'): void {
            $debug = filter_var($_ENV['APP_DEBUG'] ?? false, FILTER_VALIDATE_BOOLEAN);
            $this->view("errors/500", [
                'title' => "500 Server Error",
                'message' => $debug ? $message : 'Something went wrong.',
            ], "main");
        }
    }
?>