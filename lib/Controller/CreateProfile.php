<?php

namespace MyApp\Controller;

class CreateProfile extends \MyApp\Controller {
    public function run() {

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            try {
                $this->postImageProcess();
            } catch(\Exception $e) {
                echo $e->getMessage();
                exit;
            }
            header('Location: http://' . $_SERVER['HTTP_HOST'] . '/profile.php');
        }
    }
    
    public function postImageProcess() {
        $this->files = $_FILES['image'];
        $this->userName = $_POST['username']; 

        $this->validate();
        $ext = $this->getType($this->files);
        $filePath = $this->saveImage($ext, PROFILES_DIR_PATH);
        $thumbResouce = $this->createThumbnail($filePath, 20); //プロフィール用サイズ
        $this->saveThumbnail($filePath, $thumbResouce, PROFTHUMBS_DIR_PATH);
        
        $this->createProfile();
    }

            
    


    private function createProfile() {
        $user = new \MyApp\Model\User();
        $intro = $_POST['p_introduction'];
        $user->createProfileforDB($this->userName, $intro, $this->fileName);
    }

    private function validate() {
        if (!isset($_POST['username']) || empty($_POST['username'])) {
            throw new \Exception('Not Set USER NAME');
        }
        if (!isset($_POST['p_introduction']) || empty($_POST['p_introduction'])) {
            throw new \Exception('Not Set SELF INTRODUCTION');
        }
        $this->validateFile($this->files);
    }
}