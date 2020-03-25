<?php
//プロフィールのデフォルト画像
if ($_SERVER['PHP_SELF'] !== "/index.php") {
    $user = new \MyApp\Model\User();
    $profile = $user->getProfile($userInfo);
    $profImagePath = 'profthumbs/' . $profile['saveFileName'];
    if (!isset($profile['saveFileName'])) {
        $profImagePath = "/source_image/default.png";
}
}
?>


<ul id="header">
    <li><?= $app->getTime(); ?></li>
    <?php if (isset($userInfo)) {

        echo '<li><a href="/profile.php"><img src="' . $profImagePath . '"></a></li>';

        if ($_SERVER['PHP_SELF'] !== "/userspage.php") {
            echo '<li><a class="gray" href="/userspage.php">'. h($userInfo->email). '</a></li>';
        }
        echo '<li><a href="logout.php">ログアウト</a></li>';
    } else {
        echo '<li><a href="login.php">ログイン</a></li>';
        echo '<li><a href="signup.php">新規登録</a></li>';
    }
    ?>
</ul>
