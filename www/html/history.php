<?
require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'user.php';
require_once MODEL_PATH . 'item.php';
require_once MODEL_PATH . 'cart.php';
require_once MODEL_PATH . 'history.php';

session_start();

// ログイン済みか確認し、falseならloginページへリダイレクト
if(is_logined() === false){
  redirect_to(LOGIN_URL);
}

// DB接続
$db = get_db_connect();

// login済みのuser_idをセッションから取得して変数に格納
$user = get_login_user($db);

// $userからuser_id だけを取得する
$user_id = $user['user_id'];

// 購入履歴をselect文で取得する（合計金額）history.php
$results = get_history_total($db, $user['user_id']);

include_once '../view/history_view.php';