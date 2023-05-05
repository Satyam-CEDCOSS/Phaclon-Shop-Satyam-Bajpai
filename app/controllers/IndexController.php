<?php

namespace MyApp\Controllers;

use Phalcon\Mvc\Controller;

class IndexController extends Controller
{
    public function indexAction()
    {
        //  Redirect to View
    }

    public function loginAction()
    {
        $sql = 'SELECT * FROM Users WHERE email = :email: AND password = :password:';
        $query = $this->modelsManager->createQuery($sql);
        $user = $query->execute(
            [
                'email' => $_POST['email'],
                'password' => $_POST['password']
            ]
        );
        if (isset($user[0])) {
            $this->session->set('login', $user[0]->id);
            $this->response->redirect('/product');
        }
        else {
            $this->response->redirect('/index');
        }
    }
}
