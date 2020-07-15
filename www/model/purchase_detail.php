<?php
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'db.php';

// select文で「商品名」「購入時の商品価格」 「購入数」「小計」を表示
function get_subtotal($db, $history_id){
    $sql='
    SELECT 
      history.purchase_datetime,
      history.history_id,
      history.user_id,
      purchase_detail.price,
      purchase_detail.amount,
      purchase_detail.price * purchase_detail.amount as total,
      items.name
    FROM 
      history LEFT OUTER JOIN purchase_detail ON purchase_detail.history_id = history.history_id
    JOIN items ON purchase_detail.item_id = items.item_id
    WHERE 
      history.history_id = :history_id';

    return fetch_all_query($db, $sql, array($history_id));

    // 他人の購入履歴を見れないようにする
    if($results[0]['user_id']!==$user_id){
    redirect_to(HOME_URL);
    exit;
    }
}

// select文で「注文番号」「購入日時」「合計金額」をページ上部に表示するためのデータ取得
function get_total($db, $history_id) {
    $sql ='
    SELECT 
      history.history_id,
      history.purchase_datetime,
      sum(purchase_detail.price * purchase_detail.amount) as total
    FROM 
      history LEFT OUTER JOIN purchase_detail ON history.history_id = purchase_detail.history_id
    WHERE 
      history.history_id =:history_id
    GROUP BY
      history_id';

    return fetch_query($db, $sql, array($history_id));
}
