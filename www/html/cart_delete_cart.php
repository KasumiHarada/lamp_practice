<?php
require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'user.php';
require_once MODEL_PATH . 'item.php';
require_once MODEL_PATH . 'cart.php';

session_start();

// ログイン済みか確認し、falseならloginページへリダイレクト
if(is_logined() === false){
  redirect_to(LOGIN_URL);
}

// DB接続
$db = get_db_connect();
// login済みのuser_idをセッションから取得して変数に格納
$user = get_login_user($db);

// hidden送信されたcart_idを変数に格納する
$cart_id = get_post('cart_id');

// sessionのtokenとpost（hidden）送信されたtokenを比較して問題なければ処理を続ける
if ($_POST['token'] && $_POST['token'] === $_SESSION['token']){

  // cart_idに一致する商品をカートから削除する
  if(delete_cart($db, $cart_id)){
    set_message('カートを削除しました。');
  } else {
    set_error('カートの削除に失敗しました。');
  }
} else if ($_POST['token'] !== $_SESSION['token']){
  // 不正な処理が行われたからsession情報消去
  redirect_to(LOGIN_URL);
  $_SESSION = array();
  print '不正なアクセス';
}

redirect_to(CART_URL);