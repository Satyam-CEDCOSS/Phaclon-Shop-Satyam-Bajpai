<?php
namespace MyApp\Handlers;

use Phalcon\Acl\Adapter\Memory;
use Phalcon\Events\Event;
use Phaclon\Mvc\Dispacher;
use Phalcon\Mvc\Application;

class Aclistener
{
    public function beforeHandleRequest(Event $event, Application $app, Dispacher $dis)
    {
        $acl = new Memory();
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
            'signup',
            [
                'index',
            ]
        );
        
        $acl->addComponent(
            'product',
            [
                'index',
                'crud',
                'edit',
            ]
        );
        
        $acl->addComponent(
            'order',
            [
                'index',
                'crud',
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
        
        $acl->allow('admin', '*', '*');
        $acl->allow('Manager', '*', '*');
        $acl->allow('Accountant', '*', '*');
        $acl->allow('user', 'signup', '*');
        $acl->allow('*', 'index', '*');
        $acl->allow('user', 'product', 'index');
        $acl->allow('user', 'order', 'index');

        $acl->deny('Accountant', 'product', '*');
        $acl->deny('Manager', 'order', '*');

    }
}