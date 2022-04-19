<?php

namespace Multiple\Admin\Controllers;

use Phalcon\Mvc\Controller;

class LoginController extends Controller
{
    public function indexAction()
    {
        $email = $this->escaper->sanitize($this->request->getPost('email'));
        $password = $this->escaper->sanitize($this->request->getPost('password'));
        if ($email && $password) {
            $result =  $this->dbHelper->checkUser($email, $password);
            if ($result) {
                $this->session->set('login', 1);
                $this->logger->log('info', 'user logged in');
            } else {
                $this->logger->log('info', 'auth failed');
            }
        }
        $logInfo = $this->session->get('login');
        if ($logInfo) {
            $this->response->redirect('/admin/products', TRUE);
        }
    }
    public function logoutAction()
    {
        $this->session->set('login', 0);
        $this->response->redirect('/login', TRUE);
    }
}
