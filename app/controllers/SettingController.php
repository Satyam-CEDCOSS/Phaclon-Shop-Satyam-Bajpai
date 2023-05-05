<?php

namespace MyApp\Controllers;

use Phalcon\Mvc\Controller as ControllerBase;

class SettingController extends ControllerBase
{
    public function indexAction()
    {
        //  Redirect to View
    }
    public function addAction()
    {
        print_r($_POST);die;
    }
}