<?php

    namespace App\Controllers;

    use App\Models\User;
    use Core\Controller;
    use Core\Validator;
    use Core\Session;

    class UserController extends Controller {
        private User $userModel;

        public function __construct(\Core\Request $request, \Core\Response $response) {
            parent::__construct($request, $response);
            $this->userModel = new User();
        }

        /** GET /Users */
        public function index(): void {
            $page = (int)$this->request->get('page', 1);
            $users = $this->userModel->paginate($page, 10);

            $this->view('users/index', [
                'title' => 'Users',
                'users' => $users,
            ]);
        }

        /** GET /Users/{id} */
        public function show(): void {
            $id = $this->request->param('id');
            $user = $this->userModel->findOrFail($id);

            $this->view('users/show', [
                'title' => 'User: ' . e($user['name']),
                'user' => $user,
            ]);
        }

        /** GET /users/create */
        public function create(): void {
            $this->view('users/form', [
                'title' => 'Create User',
                'user' => [],
                'errors' => Session::getFlash('errors', []),
                'old' => Session::getFlash('old', []),
            ]);
        }

        /** POST /users */
        public function store(): void {
            $data = $this->request->only(['name', 'email', 'password']);
            $validator = new Validator();

            if (!$validator->validate($data, [
                'name' => 'required|min:2|max:100',
                'email' => 'required|email',
                'password' => 'required|min:8',
            ])) {
                Session::flash('errors', $validator->errors());
                Session::flash('old', $data);
                $this->redirect('/users/create');
            }
    
            // Check unique email 
            if ($this->userModel->findByEmail($data['email'])) {
                Session::flash('errors', ['email' => ['That email is already taken.']]);
                Session::flash('old', $data);
                $this->redirect('/users/create');
            }

            $this->userModel->create($data);
            Session::flash('success', 'User created successfully.');
            $this->redirect('/users');
        }

        /** GET /users/{id}/edit */
        public function edit(): void {
            $id = $this->request->param('id');
            $user = $this->userModel->findOrfail($id);

            $this->view('users/form', [
                'title' => 'Edit User',
                'user' => $user,
                'errors' => Session::getFlash('errors', []),
                'old' => Session::getFlash('old', $user),
            ]);
        }

        /** POST /users/{id} (method override to PUT) */
        public function update(): void {
            $id = $this->request->param('id');
            $data = $this->request->only(['name', 'email']);
            $validator = new Validator();
        }

        /** GET /users/{id}/delete */
        public function destroy(): void {
            $id = $this->request->param('id');
            $this->userModel->findOrFail($id); // 404 if not found
            $this->userModel->delete($id);

            Session::flash('success', 'User deleted.');
            $this->redirect('/users');
        }
    }
?>