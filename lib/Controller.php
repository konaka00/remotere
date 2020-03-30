<?php

namespace MyApp;

class Controller {
    private $error;
    public function __construct() {
        $this->error = new \stdClass();
    }
    public function isLoggedIn() {
        if (isset($_SESSION['userInfo'])) {
            header('Location: http://' . $_SERVER['HTTP_HOST'] . '/mainpage.php');
        } 
    }
    /*
    エラー出力制御
    */
    
    protected function setErrors($key, $err) {
        $this->error->$key = $err;
    }
    public function getErrors($key) {
        return isset($this->error->$key) ? $this->error->$key : '';
    }


    /*
    オリジナル画像・サムネイル保存
    */
    protected $files;
    protected $fileName;
    protected $userName;

    //拡張子取得
    protected function getType($files) {
        $imageType = exif_imagetype($files['tmp_name']);
         switch($imageType) {
             case IMAGETYPE_GIF:
                 return 'gif';
             case IMAGETYPE_JPEG:
                 return 'jpg';
             case IMAGETYPE_PNG:
                 return 'png';
             default:
                 throw new \Exception('Not Upload type' . $files['type']);
         }
     }

    // オリジナル画像
    protected function saveImage($ext, $saveDir) {
        if (isset($this->files['tmp_name'])) {
            $this->fileName = sprintf(
                '%s_%s_%s.%s',
                $this->userName,
                time(),
                sha1(uniqid(mt_rand(), true)),
                $ext 
            );
            $filePath =  $saveDir . '/' . $this->fileName;
            move_uploaded_file($this->files['tmp_name'], $filePath);
            return $filePath;
        }
    }

    //サムネイル画像
    protected function createThumbnail($filePath, $thumbWidth) {
        list($imageWidth, $imageHeight) = getimagesize($filePath);

        if ($imageWidth > $thumbWidth) {
        $thumbHeight = round($thumbWidth * $imageHeight / $imageWidth);
        $thumbResouce = imagecreatetruecolor($thumbWidth, $thumbHeight);
        $ImageResouce = $this->createImageResouce($filePath, $this->files);
        imagecopyresampled($thumbResouce, $ImageResouce, 0,0,0,0, 
        $thumbWidth, $thumbHeight, $imageWidth, $imageHeight);
        return $thumbResouce;
        }
    }

    protected function createImageResouce($filePath, $files) {
        switch(pathinfo($filePath, PATHINFO_EXTENSION)) {
            case 'gif':
                $ImageResouce = imagecreatefromgif($filePath);
                return $ImageResouce;
            case 'jpg':
                $ImageResouce = imagecreatefromjpeg($filePath);
                return $ImageResouce;
            case 'png':
                $ImageResouce = imagecreatefrompng($filePath);
                return $ImageResouce;
            default:
                throw new \Exception('Cant create ImageResouce' . $files['type']);
        }
    }

    protected function saveThumbnail($filePath, $thumbResouce, $savethumbsDir) {

        switch(pathinfo($filePath, PATHINFO_EXTENSION)) {

            case 'gif':
                imagegif($thumbResouce, $savethumbsDir . '/' . $this->fileName);
                break;
            case 'jpg':
                $res = imagejpeg($thumbResouce, $savethumbsDir . '/' . $this->fileName);
                break;
            case 'png':
                imagepng($thumbResouce, $savethumbsDir . '/' . $this->fileName);
                break;
            default:
                throw new \Exception('Cant create thumbs' . $this->files['type']);

        }
    }


    /*
    オリジナル画像・サムネイル削除
    */
    public function deleteFile($fileName) {
        $res = unlink(IMAGES_DIR_PATH . $fileName);
        if (file_exists(THUMBS_DIR_PATH . $fileName)) {
            unlink(THUMBS_DIR_PATH . $fileName);
        }
    }


    /*
    validateFile
    */
    protected function validateFile($files) {
        var_dump($files);
        if ($files['tmp_name'] === "" || !isset($files['error'])) {
            throw new \MyApp\Exception\File('画像を選んでください');
            return;
        }
        switch($files['error']) {
            case 0:
                return true;
            case 1:
            case 2:
                throw new \MyApp\Exception\File('オーバーサイズです');
            case 4:
                throw new \MyApp\Exception\File('アップロードに失敗しました');
            default:
                throw new \MyApp\Exception\File('Upload Error:' . $files['error']);
        }
    }

    protected function validateEmailAndPass() {
        if (!isset($_POST['email']) || $_POST['email'] === '' ) {
            throw new \MyApp\Exception\Email('Emailをセットして下さい');
        }
        if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            throw new \MyApp\Exception\Email('Emailの型が正しくありません');
        }
        if (!isset($_POST['password']) || $_POST['password'] === '') {
            throw new \MyApp\Exception\Password('Passwordをセットして下さい');
        }
        if (!preg_match('/\A[a-zA-Z0-9]+\z/', $_POST['password'])) {
            throw new \MyApp\Exception\Password('Passwordは半角英数字です');
        }

    }

    /*
    メイン表示画像
    */
    //画像ファイル名取得
    //表示用に配列を作成
    public function getMainImages($user = '') {
        $results = glob(IMAGES_DIR_PATH . '/' . $user . '*');

        list($title, $time) = $this->getTitle($results);

        $imageFilePath = [];
        $nameAndPath = [];
        $timeAndPath = []; 

        foreach ($results as $res) {
            if (file_exists(THUMBS_DIR_PATH . substr($res, strpos($res, 'images') + 6))) {
                $imageFilePath[$title[$res]] = 'thumbs' . substr($res, strpos($res, 'images') + 6);
                
                $name = $this->getPostInfo($res);
                $nameAndPath['thumbs' . substr($res, strpos($res, 'images') + 6)] = $name;
                $timeAndPath['thumbs' . substr($res, strpos($res, 'images') + 6)] = $time[$res];

            } else {
                $imageFilePath[$title[$res]] = substr($res, strpos($res, 'images'));

                $name = $this->getPostInfo($res);
                $nameAndPath[substr($res, strpos($res, 'images'))] = $name;
                $timeAndPath[substr($res, strpos($res, 'images'))] = $time[$res];
            }
        }

        if ($user === '') {
            return [$imageFilePath, $nameAndPath, $timeAndPath];
        } else {
            return $imageFilePath;
        }
    }

    private function getPostInfo($res) {
        //filepathからusername部を切り出す。
        $filename = substr($res, strpos($res, 'images') + 7);
        // $name = substr($filename, 0, strpos($filename, '_'));
        $name = strstr($filename, '_', true);
        return $name;
    }




    /*
    DBから値を取得
    */
     public function getNices() {
        $user = new \MyApp\Model\User();
        $nices = $user->getNiceAll();
        return $nices;
     }

    public function getTitle($results) {
        $user = new \MyApp\Model\User();
        list($title, $created) = $user->getTitleInfoFromDB($results);
        return [$title, $created];
    }

    public function getUserName($userInfo) {
      
        $user = new \MyApp\Model\User();
        $userName = $user->getUserNamefromDB([
            'userInfo' => $userInfo
        ]);
        return $userName;
    }

    public function getUserInfo() {
        if (isset($_SESSION['userInfo'])) {
            $this->userInfo = $_SESSION['userInfo'];
        } else {
            return;
        }
        return $this->userInfo;
    }



    /*
    現在時刻
    */
    public function getTime() {
        // echo date("Y/m/d");
        $date = new \DateTime('now');
        echo $date->format('Y/m/d');
    }

}