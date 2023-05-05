<?php

namespace MyApp\Controllers;

use Phalcon\Mvc\Controller;

class OrderController extends Controller
{
    public function indexAction()
    {
        $sql = 'SELECT * FROM Orders';
        $query = $this->modelsManager->createQuery($sql);
        $order = $query->execute();
        $product = $this->session->get('product');
        $this->view->order = "";
        $this->view->total = 0;
        foreach ($order as $value) {
            foreach ($product as $item) {
                if ($value->productId == $item->productId) {
                    $this->view->total += $item->price;
                    $this->view->order .= '<div class="card mb-4">
                    <div class="card-body p-4">
          
                      <div class="row align-items-center">
                        <div class="col-md-2">
                          <img src='.$item->image.'
                            class="img-fluid" alt="Generic placeholder image">
                        </div>
                        <div class="col-md-2 d-flex justify-content-center">
                          <div>
                            <p class="small text-muted mb-4 pb-2">Name</p>
                            <p class="lead fw-normal mb-0">'.$item->productName.'</p>
                          </div>
                        </div>
                        <div class="col-md-2 d-flex justify-content-center">
                          <div>
                            <p class="small text-muted mb-4 pb-2">Company</p>
                            <p class="lead fw-normal mb-0">'.$item->company.'</p>
                          </div>
                        </div>
                        <div class="col-md-2 d-flex justify-content-center">
                          <div>
                            <p class="small text-muted mb-4 pb-2">Quantity</p>
                            <p class="lead fw-normal mb-0">1</p>
                          </div>
                        </div>
                        <div class="col-md-2 d-flex justify-content-center">
                          <div>
                            <p class="small text-muted mb-4 pb-2">Status</p>
                            <p class="lead fw-normal mb-0">'.$value->orderStatus.'</p>
                          </div>
                        </div>
                        <div class="col-md-2 d-flex justify-content-center">
                          <div>
                            <p class="small text-muted mb-4 pb-2">Price</p>
                            <p class="lead fw-normal mb-0">$'.$item->price.'</p>
                          </div>
                        </div>
                      </div>
          
                    </div>
                  </div>';
                }
            }
        }
    }
    public function addAction()
    {
        $order = new \Orders();
        $arr = [
            'id' => $this->session->get('login'),
            'productId' => $_GET['pid']
        ];
        $order->assign(
            $arr,
            [
                'id',
                'productId'
            ]
        );
        $done = $order->save();
        if ($done) {
            $this->response->redirect('product');
        }
    }
    public function crudAction()
    {
        $sql = 'SELECT * FROM Orders';
        $query = $this->modelsManager->createQuery($sql);
        $order = $query->execute();
        $this->view->ordtable = "";
        foreach ($order as $value) {
          $this->view->ordtable .= '
            <tr>
            <td>' . $value->id . '</td> <td>' . $value->productId . '</td>
            <td>' . $value->orderStatus . '</td>
            <td><a href="/order/edit?oid=' . $value->orderId . '" class="btn btn-warning">Edit</a></td>
            <td><a href="/order/delete?oid=' . $value->orderId . '" class="btn btn-danger">Delete</a></</td>
            </tr>';
        }
    }
    public function editAction()
    {
        $sql = 'SELECT * FROM Orders WHERE orderId = :id:';
        $query = $this->modelsManager->createQuery($sql);
        $order = $query->execute(
            [
                'id' => $_GET["oid"]
            ]
        );
        $this->session->set('update', $_GET["oid"]);
        $this->view->id = $order[0]->id;
        $this->view->productId = $order[0]->productId;
        $this->view->orderStatus = $order[0]->orderStatus;
    }
    public function updateAction()
    {
        $ids = $_POST['id'];
        $productId = $_POST['productId'];
        $orderStatus = $_POST['orderStatus'];
        // print_r($orderStatus);die;
        $id = $this->session->get('update');
        $phql = "UPDATE orders SET id = '$ids', productId = '$productId',orderStatus = '$orderStatus
        ' WHERE orders.orderId = $id ";
        $result = $this->db->execute($phql);
        if ($result) {
          $this->response->redirect('order/crud');
        }
    }
    public function deleteAction()
    {
        $order = new \Orders();
        $order->orderId = $_GET["oid"];
        $done = $order->delete();
        if ($done) {
          $this->response->redirect('order/crud');
        }
    }
}
