<?php

namespace Multiple\Admin\Controllers;

use Phalcon\Mvc\Controller;


class ProductsController extends Controller
{

    public function indexAction()
    {
        $this->view->product = [];
        $this->view->locale = $this->locale;
        $logInfo = $this->session->get('login');
        if (!$logInfo) {
            $this->response->redirect('/login', TRUE);
        }
        if ($this->request->get('search')) {
            $products = $this->dbHelper->searchProductByName($this->request->get('search'));
        } else {
            $products = $this->dbHelper->getAllProducts();
        }

        $this->view->products = $products;
    }

    /**
     * viewproductAction()
     *
     * function returning single product data to a ajax request
     * 
     * @return json
     */
    public function viewproductAction()
    {
        $id = $this->request->getPost('id');
        if ($id) {
            $product =  $this->dbHelper->getProduct($id);
        } else {
            $this->response->redirect();
        }
        return json_encode($product);;
    }

    /**
     * addproductAction()
     * 
     * function to add product in database
     *
     * @return void
     */
    public function addAction()
    {

        $check = $this->request->isPost();
        $this->view->locale = $this->locale;
        $logInfo = $this->session->get('login');
        if (!$logInfo) {
            $this->response->redirect('/login', TRUE);
        }
        if ($check) {
            //handling delete  and update request
            if ($this->request->getPost('btn') == 'update') {
                $this->dbHelper->updateProduct($this->request->getPost());
                $this->response->redirect('/admin/products/index', TRUE);
            } else if ($this->request->getPost('btn') == 'delete') {
                $this->dbHelper->deleteProduct($this->request->getPost('id'));
                $this->response->redirect('/admin/products/index', TRUE);
            } else {
                $this->dbHelper->addProduct($this->request->getPost());
                $this->response->redirect('/admin/products/index', TRUE);
            }
        }
    }
}
