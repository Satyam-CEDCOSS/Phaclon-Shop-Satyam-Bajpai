<?php

namespace MyApp\Controllers;

session_start();
use Phalcon\Mvc\Controller;
use Phalcon\Escaper;
use Phalcon\Security\JWT\Builder;
use Phalcon\Security\JWT\Signer\Hmac;

class IndexController extends Controller
{
    public function indexAction()
    {
        //  Redirect to View
    }

    public function loginAction()
    {
        $escaper = new Escaper();
        $sql = 'SELECT * FROM Users WHERE email = :email: AND password = :password:';
        $query = $this->modelsManager->createQuery($sql);
        $user = $query->execute(
            [
                'email' => $escaper->escapeHtml($_POST['email']),
                'password' => $escaper->escapeHtml($_POST['password'])
            ]
        );
        if (isset($user[0])) {
            $signer  = new Hmac();
            $builder = new Builder($signer);

            $passphrase = 'QcMpZ&b&mo3TPsPk668J6QH8JA$&U&m2';
            $builder
                ->setSubject($user[0]->type)
                ->setPassphrase($passphrase);
            $tokenObject = $builder->getToken();
            $this->session->set('login', $user[0]->id);
            $_SESSION['token'] = $tokenObject->getToken();
            $this->response->redirect('/product');
        }
        else {
            $this->response->redirect('/index');
        }
    }
}
