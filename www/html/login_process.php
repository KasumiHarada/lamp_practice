<?php
require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'user.php';

session_start();

// ログイン済みか確認し、trueならトップページへリダイレクト
if(is_logined() === true){
  redirect_to(HOME_URL);
}

// post送信されたnameを変数に格納
$name = get_post('name');
// post送信されたpasswordを変数に格納
$password = get_post('password');

// DB接続
$db = get_db_connect();

// sessionのtokenとpost（hidden）送信されたtokenを比較して問題なければ処理を続ける
if (isset($_POST['token']) ===false && $_POST['token'] !== $_SESSION['token']){
  // 不正な処理が行われたからsession情報消去
  redirect_to(LOGIN_URL);
  $_SESSION = array();
  exit;

} else {  
  // nameに一致するユーザー情報をひとつ取得する→ユーザーが存在しなければ、エラー表示してlogiｎページへリダイレクト
  $user = login_as($db, $name, $password);
  if( $user === false){
    set_error('ログインに失敗しました。');
    redirect_to(LOGIN_URL);
  }

  // sessionにメッセージを格納する。管理者なら管理ページへリダイレクト
  set_message('ログインしました。');
  if ($user['type'] === USER_TYPE_ADMIN){
    redirect_to(ADMIN_URL);
  }
  
} 

redirect_to(HOME_URL);