<?php
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'db.php';

// 購入履歴を登録する
function insert_history($db, $user_id){
  $sql = "
  INSERT INTO
    history(
      user_id,
    )
  VALUES(?);
  ";
  
  return execute_query($db, $sql, array($user_id));
}

// 購入履歴詳細を登録する
function insert_purchase_detail($db, $user_id, $item_id, $amount, $price){
  $sql = "
  INSERT INTO
    purchase_detail(
      history_id,
      item_id,
      amount,
      price
    )
  VALUES(?, ?, ?, ?);
  ";
  
  return execute_query($db, $sql, array($user_id, $item_id, $amount, $price));
}

