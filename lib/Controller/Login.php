<?php

namespace MyApp\Controller;

class Login extends \MyApp\Controller {
    public function run() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->postProcess();
        }
    }
    
        public function postProcess() {
    
            try {
                $this->validateEmailAndPass();
            } catch(\Exception $e) {
                echo $e->getMessage();
                exit;
            }
            $this->login();
            if (isset($_POST['image'])) {
                header('Location: http://' . $_SERVER['HTTP_HOST'] . '/comment.php?image=' . $_POST['image']);
            } else {
                header('Location: http://' . $_SERVER['HTTP_HOST'] . '/mainpage.php?state=1');

            }
            exit;
        }
    
        private function login() {
            $user = new \MyApp\Model\User();
            try {
                $userInfo = $user->check([
                    'email' => $_POST['email'],
                    'password' => $_POST['password'],
                ]);
                $_SESSION['userInfo'] = $userInfo;

            } catch (\Exception $e) {
                echo $e->getMessage();
                exit;
            }
        }
    
    }