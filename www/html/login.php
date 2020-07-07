<?php
require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';

session_start();

// iframeを禁止
header('X-FRAME-OPTIONS: DENY');

// ログイン済みか確認し、trueならtopページへリダイレクト
if(is_logined() === true){
  redirect_to(HOME_URL);
}

// $tokenを生成し、sessionに格納
$token = get_random_string(30);
$_SESSION['token'] = $token;

include_once VIEW_PATH . 'login_view.php';