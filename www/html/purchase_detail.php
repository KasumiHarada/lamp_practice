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
try{
  $sql ='SELECT 
          items.name,
          purchase_detail.price,
          purchase_detail.amount,
          history.purchase_datetime,
          history.history_id,
          history.user_id
        FROM 
          items LEFT OUTER JOIN purchase_detail ON items.item_id = purchase_detail.item_id
        JOIN history ON purchase_detail.history_id = history.history_id
        WHERE 
          history.history_id = :history_id';
  
  $results = fetch_all_query($db, $sql, array($history_id));

  if($results[0]['user_id']!==$user_id){
    redirect_to(HOME_URL);
    exit;
  }

} catch (PDOException $e){
  print '購入履歴詳細を表示できない'. $e->getMessage();
}

// select文で「注文番号」「購入日時」「合計金額」をページ上部に表示するためのデータ取得
try {
  $sql ='SELECT 
          history.history_id,
          history.purchase_datetime,
          sum(purchase_detail.price*purchase_detail.amount) as total
        FROM 
          history LEFT OUTER JOIN purchase_detail ON history.history_id = purchase_detail.history_id
        WHERE 
          history.history_id =:history_id
        GROUP BY
          history_id';

  $total = fetch_query($db, $sql, array($history_id));

} catch (PDOException $e){
  print 'エラー'.$e->getMessage();
}

include_once '../view/purchase_detail_view.php';