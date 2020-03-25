<?php

namespace MyApp\Controller;

class Comment extends \MyApp\Controller {
    public function run() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->postProcess();
        }
    }
    private function postProcess() {
        try {
            $this->validate();
            $this->saveComment();
        } catch(\Exception $e) {
            echo $e->getMessage();
        }
    }

    private function saveComment() {
        $user = new \MyApp\Model\User();
        $userName = $_POST['username'];
        $comment = $_POST['comment'];
        $filePath = $_POST['filepath'];
        $user->createComment($userName, $comment, $filePath);

    }

    private function validate() {
        if (!isset($_POST['username']) || empty($_POST['username'])) {
            throw new \Exception('Invalid username');
        }
        if (!isset($_POST['comment']) || empty($_POST['comment'])) {
            throw new \Exception('Invalid comment');
        }
        if (!isset($_POST['filepath']) || empty($_POST['filepath'])) {
            throw new \Exception('Invalid filepath');
        }
    }

    public function getComments($path) {
        $user = new \MyApp\Model\User();
        list($username, $comments, $count) = $user->getCommentfromDB($path);
        return [$username, $comments, $count];
    }     

}