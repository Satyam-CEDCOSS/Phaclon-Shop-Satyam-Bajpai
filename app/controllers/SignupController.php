<?php

namespace MyApp\Controllers;

use Phalcon\Mvc\Controller;

class SignupController extends Controller
{
    public function indexAction()
    {
        //  Redirect to View
    }

    public function signupAction()
    {
        $user = new \Users();
        $user->assign(
            $this->request->getPost(),
            [
                'name',
                'email',
                'password',
            ]
        );
        $done = $user->save();
        if ($done) {
            $this->response->redirect('index');
        } else {
            $this->response->redirect('signup');
        }
    }
}
