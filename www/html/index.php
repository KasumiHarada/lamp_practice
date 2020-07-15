<?php
require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'user.php';
require_once MODEL_PATH . 'item.php';

require_once MODEL_PATH . 'db.php';
session_start();

// iframeを禁止
header('X-FRAME-OPTIONS: DENY');

// ログイン済みか確認し、falseならloginページへリダイレクト
if(is_logined() === false){
  redirect_to(LOGIN_URL);
}

// $tokenを生成し、sessionに格納
$token = get_random_string(30);
$_SESSION['token'] = $token;

// DB接続
$db = get_db_connect();
// login済みのuser_idをセッションから取得して変数に格納
$user = get_login_user($db);

// // 公開中の商品のみを取得する item.php
// $items = get_open_items($db);

// 人気商品上位3つを取得する
$popular_lines = get_items_popular($db);

// get送信された並べ替えの機能を取得してsessionに格納
$sort = get_get('sort');
$_SESSION['sort'] = $sort;

// 公開中の商品のみを取得する item.php
$items = get_items_sort($db);

include_once VIEW_PATH . 'index_view.php';