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
$history_id = get_post('history_id');

// adminでなければ、if ($user===)
// select文で「注文番号」「購入日時」「合計金額」をページ上部に表示
// select文で「商品名」「購入時の商品価格」 「購入数」「小計」を表示
try{
  $sql ='SELECT 
          items.name,
          purchase_detail.price,
          purchase_detail.amount,
          history.purchase_datetime,
          history.history_id
        FROM 
          items LEFT OUTER JOIN purchase_detail ON items.item_id = purchase_detail.item_id
        JOIN history ON purchase_detail.history_id = history.history_id
        WHERE 
          history.history_id = :history_id';
  
  $stmt=$db->prepare($sql);

  $stmt->bindValue(':history_id', $history_id, PDO::PARAM_INT);

  $stmt->execute();

  $results = $stmt->fetchAll();

} catch (PDOException $e){
  print '購入履歴詳細を表示できない'. $e->getMessage();
}

// 以下はなくてもいいかも。history.phpのsql文を使い回しできそうだけど、history_id問題
// select文で「注文番号」「購入日時」「合計金額」をページ上部に表示するためのデータ取得
try {
  $sql ='SELECT 
          history.history_id,
          history.purchase_datetime,
          sum(purchase_detail.price) 
        FROM 
          history LEFT OUTER JOIN purchase_detail ON history.history_id = purchase_detail.history_id
        WHERE 
          user_id =:user_id AND history.history_id =:history_id
        GROUP BY
          history_id';
  
  $stmt=$db->prepare($sql);

  $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
  $stmt->bindValue(':history_id', $history_id, PDO::PARAM_INT);

  $stmt->execute();

  $total = $stmt->fetchAll();

} catch (PDOException $e){
  print 'エラー'.$e->getMessage();
}

include_once '../view/purchase_detail_view.php';