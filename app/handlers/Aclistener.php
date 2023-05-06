<?php

namespace MyApp\Handlers;

use Phalcon\Di\Injectable;
use Phalcon\Acl\Adapter\Memory;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Mvc\Application;
use Phalcon\Events\Event;
use Phalcon\Security\JWT\Token\Parser;

session_start();

class Aclistener extends Injectable
{
    public function beforeHandleRequest(Event $events, Application $app, Dispatcher $dis)
    {
        $di = $this->getDI();
        $acl = new Memory();
        $acl->addRole('');
        $acl->addRole('User');
        $acl->addRole('Manager');
        $acl->addRole('Accountant');
        $acl->addRole('Admin');

        $acl->addComponent(
            'index',
            [
                'index',
            ]
        );

        $acl->addComponent(
            '',
            [
                '',
            ]
        );

        $acl->addComponent(
            'signup',
            [
                'index',
            ]
        );

        $acl->addComponent(
            'product',
            [
                '',
                'index',
                'crud',
                'edit',
            ]
        );

        $acl->addComponent(
            'order',
            [
                '',
                'index',
                'crud',
                'add',
                'edit',
            ]
        );

        $acl->addComponent(
            'user',
            [
                'index',
                'edit',
            ]
        );

        $acl->allow('Admin', '*', '*');
        $acl->allow('Manager', 'product', '*');
        $acl->allow('Accountant', 'order', '*');
        $acl->allow('User', 'signup', '*');
        $acl->allow('*', 'index', '*');
        $acl->allow('*', '', '*');
        $acl->allow('User', 'product', ['', 'index']);
        $acl->allow('User', 'order', ['', 'index','add']);
        $acl->allow('Accountant', 'product', ['', 'index']);

        if (isset($_SESSION['token'])) {
            $parser = new Parser();
            $tokenObject = $parser->parse($_SESSION['token']);
            echo "<pre>";
            $role = $tokenObject->getClaims()->getPayload()['sub'];
        } else {
            $role = "";
        }
        $controle = $dis->getControllerName();
        $action = $dis->getActionName();
        print_r($role . " ");
        $check = $acl->isAllowed($role, $controle, $action);
        if (!$check) {
            echo "Access Denied";
            die;
        }
    }
}
