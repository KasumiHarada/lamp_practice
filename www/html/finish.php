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
// $userからuser_id だけを取得する
$user_id = $user['user_id'];

// 送信されたuser_idに一致するカートの中身を取得 cart.php
$carts = get_user_carts($db, $user['user_id']);

// カートの商品の合計金額を計算する
$total_price = sum_carts($carts);



// sessionのtokenとpost（hidden）送信されたtokenを比較して問題なければ処理を続ける
if (isset($_POST['token'])===false && $_POST['token'] !== $_SESSION['token']){
  // 不正な処理が行われたからsession情報消去
  redirect_to(LOGIN_URL);
  $_SESSION = array();
  exit;

} else {
  // カートの中身をチェック（validate_cart_purchase）して問題なければ、stockの在庫数を更新しカートから削除
  if(purchase_carts($db, $carts) === false){
    set_error('商品が購入できませんでした。');
    redirect_to(CART_URL);

  } else {

    
    

      // トランザクションで、historyテーブルとpurchase_detailテーブルにinsertする  
      $db->beginTransaction();
      try{
        // sql文
        $sql ='INSERT INTO history (user_id)VALUES(:user_id)';
        // 準備
        $stmt=$db->prepare($sql);
        // 値をバインド
        $stmt->bindValue('user_id', $user_id, PDO::PARAM_INT);
        // 実行
        $stmt->execute();
      
        // lastInsertedIdでhistory_idを取得する
        $history_id = $db->lastInsertId('history_id');
        
        // purchase_detailテーブルを更新する
        $sql='INSERT INTO purchase_detail (history_id, item_id, amount, price)VALUES(:history_id, :item_id, :amount, :price)';
        
        $stmt=$db->prepare($sql);
        
        $stmt->bindValue('history_id', $history_id, PDO::PARAM_INT);
        
        foreach($carts as $cart){
          $amount = $cart['amount'];
          $item_id= $cart['item_id'];
          $price  = $cart['price'];  

          $stmt->bindValue('item_id', $item_id, PDO::PARAM_INT);
          $stmt->bindValue('amount', $amount, PDO::PARAM_INT);
          $stmt->bindValue('price', $price, PDO::PARAM_INT);
          $stmt->execute();
        }
        // コミット
        $db->commit(); 

      } catch (PDOException $e){
        print $e->getMessage();
        // ロールバック処理
        $db->rollback();
        // 例外をスロー
        throw $e;
      }
        
  }
}

include_once '../view/finish_view.php';