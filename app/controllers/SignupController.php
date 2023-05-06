<?php

namespace MyApp\Controllers;

use Phalcon\Mvc\Controller;
use Phalcon\Logger;
use Phalcon\Logger\Adapter\Stream;

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
            $adapter = new Stream(APP_PATH .'/logs/my.log');
            $logger  = new Logger(
                'messages',
                [
                    'main' => $adapter,
                ]
            );
            $logger->info("signup successful");
            $this->response->redirect('index');
        } else {
            $this->response->redirect('signup');
        }
    }
}
