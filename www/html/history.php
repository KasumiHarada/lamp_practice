<?
require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'user.php';
require_once MODEL_PATH . 'item.php';
require_once MODEL_PATH . 'cart.php';
//require_once MODEL_PATH . 'history.php';

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

// adminでなければ、
try{
    $sql ='SELECT 
      history.history_id,
      history.purchase_datetime,
      sum(purchase_detail.price) 
    FROM 
      history LEFT OUTER JOIN purchase_detail ON history.history_id = purchase_detail.history_id
    WHERE 
      user_id =:user_id
    GROUP BY history_id DESC;';
    
    // // 計算せずにデータだけ取り出す場合→history_id がダブってる
    // $sql ='SELECT 
    //         history.history_id,
    //         history.purchase_datetime,
    //         purchase_detail.price,
    //         purchase_detail.amount
    //       FROM 
    //         history LEFT OUTER JOIN purchase_detail ON history.history_id = purchase_detail.history_id
    //       WHERE 
    //         user_id =:user_id';

    $stmt=$db->prepare($sql);

    $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);

    $stmt->execute();

    $results = $stmt->fetchAll();

} catch (PDOException $e){
    print '購入履歴を表示できない'. $e->getMessage();
}

// adminだったら全部表示

include_once '../view/history_view.php';