<?php

namespace MyApp\Controller;

class UsersPage extends \MyApp\Controller {
    public function run() {

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            try {
                $this->postImageProcess();
            } catch(\Exception $e) {
                echo $e->getMessage();
                exit;
            }
            header('Location: http://' . $_SERVER['HTTP_HOST'] . '/userspage.php');
        }
    }

        public function postImageProcess() {
                $this->files = $_FILES['image'];
                $this->userName = $_POST['username']; 
                
                $this->validate();
                $ext = $this->getType($this->files);
                $filePath = $this->saveImage($ext, IMAGES_DIR_PATH);
                $this->saveTitle($filePath);
                $thumbResouce = $this->createThumbnail($filePath, 400);
                if(isset($thumbResouce)){
                    $this->saveThumbnail($filePath, $thumbResouce, THUMBS_DIR_PATH);
                }
        }

       
        private function saveTitle($filePath) {
                $user = new \MyApp\Model\User();
                $title = $_POST['title'];
                $user->saveTitleAndPath($title, $filePath);
        }

        private function validate() {
            if (!isset($_POST['title']) || empty($_POST['title'])) {
                throw new \Exception('Please insert Title');
            }
            $this->validateFile($this->files);
        }
    }
    
