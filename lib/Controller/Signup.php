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
            $this->setErrors('username', $e->getMessage());
            return;
        } catch(\MyApp\Exception\Email $e) {
            $this->setErrors('email', $e->getMessage());
            return;
        } catch(\MyApp\Exception\Password $e) {
            $this->setErrors('password', $e->getMessage());
            return;
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
        if ($_POST['username'] === "") {
            throw new \Exception('Usernameを入力して下さい');
        }
        if (!preg_match('/\A[a-zA-Z0-9]+\z/', $_POST['username'])) {
            throw new \Exception('UserNameは半角英数字にして下さい');
        }
        if (mb_strlen($_POST['username']) > 20) {
            throw new \Exception('UserNameは20文字以下です');
        }
        $this->validateEmailAndPass();
    }
}

