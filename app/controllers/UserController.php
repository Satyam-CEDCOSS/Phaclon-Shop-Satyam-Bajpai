<?php

namespace MyApp\Controllers;

use Phalcon\Mvc\Controller;

class UserController extends Controller
{
    public function indexAction()
    {
        $sql = 'SELECT * FROM Users';
        $query = $this->modelsManager->createQuery($sql);
        $user = $query->execute();
        $this->view->usertable = "";
        foreach ($user as $value) {
          $this->view->usertable .= '
            <tr>
            <td>' . $value->name . '</td> <td>' . $value->email . '</td>
            <td>' . $value->password . '</td><td>' . $value->type . '</td>
            <td><a href="/user/edit?id=' . $value->id . '" class="btn btn-warning">Edit</a></td>
            <td><a href="/user/delete?id=' . $value->id . '" class="btn btn-danger">Delete</a></</td>
            </tr>';
        }
    }
    public function editAction()
    {
        $sql = 'SELECT * FROM Users WHERE id = :id:';
        $query = $this->modelsManager->createQuery($sql);
        $user = $query->execute(
            [
                'id' => $_GET["id"]
            ]
        );
        $this->session->set('update', $_GET["oid"]);
        $this->view->name = $user[0]->name;
        $this->view->email = $user[0]->email;
        $this->view->password = $user[0]->password;
        $this->view->type = $user[0]->type;
    }
    public function updateAction()
    {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $type = $_POST['type'];
        print_r($orderStatus);die;
        $id = $this->session->get('update');
        $phql = "UPDATE users SET name = '$name', email = '$email', password = '$password', type = '$type
        ' WHERE users.id = $id ";
        $result = $this->db->execute($phql);
        if ($result) {
          $this->response->redirect('user');
        }
    }
    public function deleteAction()
    {
        $user = new \Users();
        $user->id = $_GET["id"];
        $done = $user->delete();
        if ($done) {
          $this->response->redirect('user');
        }
    }
}