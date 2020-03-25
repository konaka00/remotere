<?php

namespace MyApp\Model;

class User extends \MyApp\Model {
    public function create($values) {
        $sql = 'insert into users (email, username, password, created) value (:email, :username, :password, now())';
        $stmt = $this->db->prepare($sql);
        $res = $stmt->execute([
            ':email' => $values['email'],
            ':password' => $values['password'],
            ':username' => $values['username']
        ]);
        if ($res === false) {
            throw new \Exception('Cant use Email,Password,Username');
        }
        
    }
    public function check($values) {
        $sql = "select * from users where email = :email";
        $stmt = $this->db->prepare($sql);
        $res = $stmt->execute([
            ':email' => $values['email']
        ]);
        if ($res === false) {
            throw new \Exception('Not Found this Email');
        } 
        $stmt->setFetchMode(\PDO::FETCH_CLASS, 'stdClass');
        $user = $stmt->fetch();
        if ($user->password !== $values['password']) {
            throw new \Exception('Not match Password');
        }
        return $user;
       }

    public function getUserNamefromDB($values) {
        $sql = "select username from users where email = :email";
        $stmt = $this->db->prepare($sql);
        $res = $stmt->execute([
            ':email' => $values['userInfo']->email
        ]);
        if ($res === false) {
            throw new \Exception('Not Found this username');
        } 
        $userName = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $userName['username'];
       }
    
       public function saveTitleAndPath($title, $filePath) {
           $sql = 'insert into titles (title, filePath, created) value (:title, :filePath, now())';
           $stmt = $this->db->prepare($sql);
           $res = $stmt->execute([
               ':title' => $title,
               ':filePath' => $filePath
           ]);
           if($res = false) {
               throw new \Exception('Dont save Title');
           }
       }
       public function getTitleInfoFromDB($results) {
           $sql = 'select title,created from titles where filePath = :filePath';
           $stmt = $this->db->prepare($sql);
           $title = [];
           $created = [];
           foreach ($results as $fp) {            
               $stmt->execute([':filePath' => $fp]);
               $res = $stmt->fetch(\PDO::FETCH_ASSOC);
               $title[$fp] = $res['title'];
               $created[$fp] = $res['created'];           
        }
        if($title === false) {
            throw new \Exception('Dont save Title');
        }
        return [$title, $created];          
       }

       public function nice($niceDir, $userName) {
           $sql = 'insert into nices (username, niceDir, created, remote_addr, user_agent) value (:username, :niceDir, now(), :remote_addr, :user_agent )';
           $stmt = $this->db->prepare($sql);
           try {
               $this->db->beginTransaction();
               $stmt->execute([
                    ':niceDir' => $niceDir,
                    ':username' => $userName,
                    ':remote_addr' => $_SERVER['REMOTE_ADDR'], 
                    ':user_agent' => $_SERVER['HTTP_USER_AGENT']
                   ]);
                   $nice = $this->getNice($niceDir);
                } catch (\PDOException $e) {
                    echo $e->getMessage();
                    $this->db->rollBack();
                    exit;
                } 
                $this->db->commit();
                return $nice;       
       }
       public function deleteNice($niceDir, $userName) {
        $sql = 'delete from nices where username = :username && niceDir = :niceDir';
        $stmt = $this->db->prepare($sql);
        try {
            $this->db->beginTransaction();
            $stmt->execute([
                 ':niceDir' => $niceDir,
                 ':username' => $userName,
                ]);
                $nice = $this->getNice($niceDir);
             } catch (\PDOException $e) {
                 echo $e->getMessage();
                 $this->db->rollBack();
                 exit;
             } 
             $this->db->commit();
             return $nice;       
    }
            private function getNice($niceDir) {
                $sql = 'select count(id) as count from nices where niceDir = :niceDir';
                $stmt = $this->db->prepare($sql);
                $stmt->execute([':niceDir' => $niceDir]);
                $nice = $stmt->fetch(\PDO::FETCH_ASSOC);
                return $nice;
            }

       public function getNiceAll() {
           $nices = [];
           $sql = 'select niceDir,count(id) as count from nices group by niceDir';
           $stmt = $this->db->prepare($sql);
           $stmt->execute();
           $res = $stmt->fetchALl(\PDO::FETCH_ASSOC);
           foreach($res as $r) {
                $nices[$r['niceDir']] = $r['count'];
           }       
           return $nices;
       }

       public function addDone($userName) {
        $myNice = [];
        $sql = 'select niceDir from nices where username = ?';
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(1, $userName, \PDO::PARAM_STR);
        $stmt->execute();
        $res = $stmt->fetchALl(\PDO::FETCH_ASSOC);
        foreach ($res as $r) {
            $myNice[$r['niceDir']] = 'done';
        }
      
        return $myNice;

       }

       //CreateProfileクラスから
       public function createProfileforDB($username, $intro, $saveFileName) {
            $sql = 'insert into profiles (username, intro, saveFileName, created) value (:username, :intro, :saveFileName, now())';

                $stmt = $this->db->prepare($sql);
                $res = $stmt->execute([
                ':username' => $username,
                ':intro' => $intro,
                ':saveFileName' => $saveFileName
                ]);

            
       }

       //profile.phpから
       public function getProfile($userInfo) {
           $sql = "select * from profiles where username = ?";
           $stmt = $this->db->prepare($sql);
           $stmt->bindValue(1, $userInfo->username, \PDO::PARAM_STR);
           $stmt->execute();
           $profile = $stmt->fetch(\PDO::FETCH_ASSOC);
                     
           return $profile;

       }

       //Comment.phpから
       public function createComment($userName, $comment, $filePath) {
           $sql = "insert into comments (username, comment, filePath, created) value (:username, :comment, :filePath, now())";
           $stmt = $this->db->prepare($sql);
           $stmt->execute([
            ':username' => $userName, 
            ':comment' => $comment, 
            ':filePath' => $filePath
           ]);

       }
       public function getCommentfromDB($filePath) {
           $sql = "select comment,username from comments where filePath = ?";
           $stmt = $this->db->prepare($sql);
           $stmt->bindValue(1, $filePath, \PDO::PARAM_STR);
           $stmt->execute();
           $count = $stmt->rowCount();
           //なんとなくオブジェクト指定
           $res = $stmt->fetchAll(\PDO::FETCH_CLASS, 'stdClass');
           $username = [];
           $comments = [];
           foreach($res as $r) {
            $username[$r->comment] = $r->username;
            $comments[] = $r->comment;
           }

           return [$username, $comments, $count];

       }

}