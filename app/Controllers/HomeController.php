<?php

    namespace App\Controllers;

    use Core\Controller;

    class HomeController extends Controller {
        
        public function index(): void {
            $this->view('home/index', [
                'title' => 'Welcome',
                'message' => 'Your PHP MVC framework is working!',
            ]);
        }

        public function about(): void {
            $this->view('home/about', ['title' => 'about']);
        }
    }
?>