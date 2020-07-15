<?php
require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'user.php';
require_once MODEL_PATH . 'item.php';
require_once MODEL_PATH . 'cart.php';
require_once MODEL_PATH . 'purchase_detail.php';

require_once MODEL_PATH . 'db.php';

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

// hiddenで送信したhistory_idを取得
$history_id = get_get('history_id');

// select文で「商品名」「購入時の商品価格」 「購入数」「小計」を表示
$subtotal = get_subtotal($db, $history_id);

// select文で「注文番号」「購入日時」「合計金額」をページ上部に表示するためのデータ取得
$total = get_total($db, $history_id);

include_once '../view/purchase_detail_view.php';