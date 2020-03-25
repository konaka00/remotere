<?php
//元URL https://rmrmrmarmrmrm.hatenablog.com/entry/458740948.html

//下記のdocument.writeをjavascriptとして動作させるため
header("Content-type: application/x-javascript");

//GETパラメータから動作モードを取得
$mode = $_GET['MODE'];
$mode = 1;

//総数、当日、前日ファイル指定
$base_path = '/counter/';
$date_all_file = $base_path . 'all.txt';
$date_today_file = $base_path . date('Ymd') . '.txt';
$date_yesterday_file = $base_path . date('Ymd', strtotime('- 1 day')) . '.txt';
var_dump($date_yesterday_file);
exit;

//総数、当日ファイルをカウントアップ,countUp()??
countUp($date_all_file);
countUp($date_today_file);

//動作モード'1'の場合、アクセス数表示
if ($mode == 1) {

    $count_all = getCount($date_all_file);
    $count_today = getCount($date_today_file);
    $count_yesterday = getCount($date_yesterday_file);

    echo "document.write('ALL       COUNT:".$count_all."<br/>');";
    echo "document.write('TODAY     COUNT:".$count_today."<br/>');";
    echo "document.write('YESTERDAY COUNT:".$count_yesterday."<br/>');";}

function countUp($filepath) {
    if (file_exists($filepath)) {
        // fopenでファイルを開く　filepointer : OSまたはライブラリがファイルの読み書きのためにつかう識別子
        $fp = fopen($filepath, 'r+');
        //作業中,他の人がファイルに作業(この場合書き込み)を行えないようにする
        if (flock($fp, LOCK_EX)) {
            //一度fgetsすると、ポインタが示すファイルの読み込み位置が移動してしまう
            $current_count = fgets($fp, 4096);
            $next_count = $current_count + 1;
            // fpを任意の位置へ移動　ファイルの先頭に移動する。
            fseek($fp, 0);
            //内容を$next_countに書き込み
            fputs($next_count);

            flock($fp ,LOCK_UN);
            fclose($fp);
        }
    } else {
        //１アクセスを書き込む
        file_put_contents($filepath, '1');
    }
}

function getCount($filepath) {
    $count = 0;
    if (file_exists($filepath)) {
        $fp = fopen($filepath, 'r');
        $count = fgets($fp, 4096);
        fclose($fp);
    }

    return $count;
}


