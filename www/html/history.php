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

try{
    $where ='';
    $array=array();
    if ($user_id !==4){
      $where =' where user_id = ? ';
      $array[]=$user_id;
    }
    $sql ="SELECT 
      history.history_id,
      history.purchase_datetime,
      sum(purchase_detail.price*purchase_detail.amount) as total
    FROM 
      history LEFT OUTER JOIN purchase_detail ON history.history_id = purchase_detail.history_id
      {$where}
    GROUP BY history_id DESC;";
    
    $results=fetch_all_query($db, $sql, $array);

} catch (PDOException $e){
    print '購入履歴を表示できない'. $e->getMessage();
}

include_once '../view/history_view.php';