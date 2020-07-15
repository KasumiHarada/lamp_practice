<?php
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'db.php';


// // トランザクションで、historyテーブルとpurchase_detailテーブルにinsertする
// $db->beginTransaction();
// try{
//   // sql文
//   $sql ='INSERT INTO history (user_id)VALUES(:user_id)';
//   // 準備
//   $stmt=$db->prepare($sql);
//   // 値をバインド
//   $stmt->bindValue('user_id', $user_id, PDO::PARAM_INT);
//   // 実行
//   $stmt->execute();

//   // lastInsertedIdでhistory_idを取得する
//   $history_id = $db->lastInsertId('history_id');
  
//   // purchase_detailテーブルを更新する
//   $sql='INSERT INTO purchase_detail (history_id, item_id, amount, price)VALUES(:history_id, :item_id, :amount, :price)';
  
//   $stmt=$db->prepare($sql);
  
//   $stmt->bindValue('history_id', $history_id, PDO::PARAM_INT);
  
//   foreach($carts as $cart){
//     $amount = $cart['amount'];
//     $item_id= $cart['item_id'];
//     $price  = $cart['price'];  

//     $stmt->bindValue('item_id', $item_id, PDO::PARAM_INT);
//     $stmt->bindValue('amount', $amount, PDO::PARAM_INT);
//     $stmt->bindValue('price', $price, PDO::PARAM_INT);
//     $stmt->execute();
//   }
//   // コミット
//   $db->commit(); 

// } catch (PDOException $e){
//   print $e->getMessage();
//   // ロールバック処理
//   $db->rollback();
//   // 例外をスロー
//   throw $e;
// }


// 購入履歴を取得する（合計金額）history.php
function get_history_total($db, $user_id){

  $where ='';
  $array=array();
  if ($user_id !==4){
    $where ='WHERE user_id = ? ';
    $array[]=$user_id;
  }
  $sql ="SELECT 
    history.history_id,
    history.purchase_datetime,
    SUM(purchase_detail.price * purchase_detail.amount) as total
  FROM 
    history LEFT OUTER JOIN purchase_detail ON history.history_id = purchase_detail.history_id
    {$where}
  GROUP BY history_id 
  ORDER BY history_id DESC;";

  return fetch_all_query($db, $sql, array($user_id));

}