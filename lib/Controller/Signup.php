<?php

namespace MyApp\Controller;

class Signup extends \MyApp\Controller {
public function run() {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $this->postProcess();
    }
}

    public function postProcess() {

        try {
            $this->validateUserName();

        } catch(\Exception $e) {
            echo $e->getMessage();
            exit;
        }
        $this->signUp();

        header('Location: http://' . $_SERVER['HTTP_HOST'] . '/login.php?state=1');
        exit;
    }

    private function signUp() {
        $user = new \MyApp\Model\User();
        try {
            $user->create([
                'email' => $_POST['email'],
                'password' => $_POST['password'],
                'username' => $_POST['username'],
            ]);
        } catch (\Exception $e) {
            echo $e->getMessage();
            exit;
        }
    }

    private function validateUserName() {
        $this->validateEmailAndPass();
        if (!preg_match('/\A[a-zA-Z0-9]+\z/', $_POST['username'])) {
            throw new \Exception('Invalid User Name');
        }
        if ($_POST['usewrname'] === "") {
            throw new \Exception('Enter Username');
        }
        if (mb_strlen($_POST['usewrname']) > 20) {
            throw new \Exception('Too Long! Within 20 words');
        }
    }
}

