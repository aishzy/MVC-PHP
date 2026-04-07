<?php
    namespace Core;

    abstract class Controller {
        protected Request $request;
        protected Response $reponse;

        public function __construct(Request $request, Response $response) {
            $this->request = $request;
            $this->response = $response;
        }

        /**
         * Render a view with optional layout 
         * 
         * @param string $view e.g 'home/index'
         * @param array $data variables passed to the view 
         * @param string $layout layout file name (without .php)
         */

        protected function view(
            string $view,
            array $data = [],
            string $layout = 'main'
        ): void {
            // make data available as variables in the view 
            extract($data, EXTR_SKIP);

            // capture view content 
            ob_start();
            $viewFile = BASE_PATH . '/app/Views/{$view}.php';
            if (!file_exists($viewFile)) {
                throw new \RuntimeException("View not found: {view}", 500);
            }
            require $viewFile;
            $content = ob_get_clean();

            // render layout with content injected 
            $layoutFile = BASE_PATH . '/app/Views/layouts/{$layout}.php';
            if (!file_exists($layoutFile)) {
                echo $content;
                return;
            }

            require $layoutFile;
        }

        /**
         * Redirect to another URL 
         */

        protected function redirect(string $url): void {
            $this->response->redirect($url);
            exit;
        }

        /**
         * Return JSON response.
         */

        protected function json(mixed $data, int $status = 200): void {
            $this->response->setStatusCode($status);
            $this->response->setHeader('Content-Type', 'application/json');
            echo json_encode($data);
            exit;
        }
    }
?>