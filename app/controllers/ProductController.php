<?php

namespace MyApp\Controllers;

use Phalcon\Mvc\Controller;

class ProductController extends Controller
{
  public function indexAction()
  {
    $sql = 'SELECT * FROM Products';
    $query = $this->modelsManager->createQuery($sql);
    $product = $query->execute();
    $this->session->set('product', $product);
    $this->view->main = "";
    foreach ($product as $value) {
      $this->view->main .= '<div class="col-lg-3 col-md-6 col-sm-6 d-flex">
            <div class="card w-100 my-2 shadow-2-strong">
              <img src=' . $value->image . ' class="card-img-top" style="aspect-ratio: 1 / 1" />
              <div class="card-body d-flex flex-column">
                <h5 class="card-title">' . $value->productName . '</h5>
                <h5 class="card-title">' . $value->company . '</h5>
                <p class="card-text">$' . $value->price . '</p>
                <div class="card-footer d-flex align-items-end pt-3 px-0 pb-0 mt-auto">
                  <a href="order/add?pid=' . $value->productId . '" class="btn btn-primary shadow-0 me-1">Buy Now</a>
                </div>
              </div>
            </div>
          </div>';
    }
  }
  public function crudAction()
  {
    $sql = 'SELECT * FROM Products';
    $query = $this->modelsManager->createQuery($sql);
    $product = $query->execute();
    $this->view->prodtable = "";
    foreach ($product as $value) {
      $this->view->prodtable .= '
        <tr>
        <td>' . $value->productName . '</td> <td>' . $value->company . '</td>
        <td>' . $value->type . '</td><td>' . $value->quantity . '</td>
        <td>' . $value->price . '</td><td>' . $value->image . '</td>
        <td><a href="/product/edit?pid=' . $value->productId . '" class="btn btn-warning">Edit</a></td>
        <td><a href="/product/delete?pid=' . $value->productId . '" class="btn btn-danger">Delete</a></</td>
        </tr>';
    }
  }
  public function addAction()
  {
    $product = new \Products();
        $product->assign(
            $this->request->getPost(),
            [
                'productName',
                'company',
                'type',
                'quantity',
                'price',
                'image'
            ]
        );
        $done = $product->save();
        if ($done) {
            $this->response->redirect('product/crud/');
        }
  }
  public function deleteAction()
  {
    $product = new \Products();
    $product->productId = $_GET["pid"];
    $done = $product->delete();
    if ($done) {
      $this->response->redirect('product/crud');
    }
  }
  public function editAction()
  {
    $sql = 'SELECT * FROM Products WHERE productId = :id:';
    $query = $this->modelsManager->createQuery($sql);
    $product = $query->execute(
        [
            'id' => $_GET["pid"]
        ]
    );
    $this->session->set('update', $_GET["pid"]);
    $this->view->productName = $product[0]->productName;
    $this->view->company = $product[0]->company;
    $this->view->type = $product[0]->type;
    $this->view->quantity = $product[0]->quantity;
    $this->view->price = $product[0]->price;
    $this->view->image = $product[0]->image;
  }
  public function updateAction()
  {
    $productName = $_POST['productName'];
    $company = $_POST['company'];
    $type = $_POST['type'];
    $quantity = $_POST['quantity'];
    $price = $_POST['price'];
    $image = $_POST['image'];
    $id = $this->session->get('update');
    $phql = "UPDATE products SET productName = '$productName', company = '$company',type = '$type
    ', quantity = '$quantity', price = '$price',image = '$image' WHERE products.productId = $id ";
    
    $result = $this->db->execute($phql);
    if ($result) {
      $this->response->redirect('product/crud');
    }
  }
}
