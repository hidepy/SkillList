<?php
// FROM Git wbrxcorp(shimarin) thanx!!

require_once("DBManager.php");
require_once("UserSkillManager.php");
require_once("MasterManager.php");

// PHP 5.3以上必須(無名関数を使用)
///////////////////////////////////////////////////////////////////////////
// HTTPのエラーステータスを例外で表現するためのクラス
class HttpErrorStatus extends Exception {}
///////////////////////////////////////////////////////////////////////////
// 例外発生時の処理
function exception_handler($exception) {
    // HTTPエラーステータスの場合
    if ($exception instanceof HttpErrorStatus) {
        $message = sprintf("%d %s", $exception->getCode(), $exception->getMessage());
        header(sprintf("HTTP/1.1 %s", $message));
        echo $message;
        exit;
    }
    // その他の例外の場合は500エラーを返す
    debug_log($exception);
    header("HTTP/1.1 500 Internal Server Error");
    echo "<html><body><h1>500 Internal Server Error</h1>";
    echo $exception->getMessage();
    echo "<pre>";
    echo $exception->getTraceAsString();
    echo "</pre>";
    echo "</body></html>";
    exit;
}
set_exception_handler("exception_handler");
///////////////////////////////////////////////////////////////////////////
// (REST APIでは特に)エラーに対しては容赦なく例外を上げてHTTPレベルでエラーにすべき
function exception_error_handler($errno, $errstr, $errfile, $errline ) {
    throw new ErrorException($errstr, $errno, 0, $errfile, $errline);
}
set_error_handler("exception_error_handler");
///////////////////////////////////////////////////////////////////////////
// デバッグログ出力用関数
function debug_log($value) {
  // PHPの組み込みWebサーバで実行されている時以外はデバッグログを出力しない
  if (php_sapi_name() != "cli-server") return;
  $value_type = gettype($value);
  if ($value_type == "array" || $value_type == "object") {
    $value = var_export($value, TRUE);
  }
  error_log($value);
}
///////////////////////////////////////////////////////////////////////////
// HTTP POSTのボディ部分をJSONとしてパースする
function parse_json() {
  $request_body = file_get_contents('php://input');
  debug_log("Request: " . $request_body);
  return json_decode($request_body, true);
}
///////////////////////////////////////////////////////////////////////////
// Unicode文字列をエスケープせずにArrayをJSONエンコードする
function raw_json_encode($value) {
    // PHPのバージョンが 5.4以上の場合は JSON_UNESCAPED_UNICODEオプションが使えるのでそれを使う
    if (PHP_VERSION_ID >= 50400) return json_encode($value, JSON_UNESCAPED_UNICODE);
    // version_compare関数を使う方が良いかも http://php.net/manual/ja/function.version-compare.php
    // CentOS <= 6 など、PHPが古い場合は正規表現による置換でどうにかする
    return preg_replace_callback(
        '/\\\\u([0-9a-zA-Z]{4})/',
        function($matches) { return mb_convert_encoding(pack('H*',$matches[1]),'UTF-8','UTF-16'); },
        json_encode($value)
    );
}
///////////////////////////////////////////////////////////////////////////
// ArrayをJSONエンコードしてレスポンスする
function response_json($value) {
    $response = raw_json_encode($value);
    debug_log("Response: " . $response);
    header('Content-Type: application/json');
    // 古いIEがおかしいMIMEタイプ判定をしてXSSの原因になる問題を避けるための呪符
    header('X-Content-Type-Options: nosniff');
    echo $response;
}
///////////////////////////////////////////////////////////////////////////
// このスクリプト自身のURLを生成する
function script_url() {
  $scheme = (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"])? "https":"http";
  return sprintf("%s://%s%s/", $scheme, $_SERVER["HTTP_HOST"], $_SERVER["SCRIPT_NAME"]);
}
///////////////////////////////////////////////////////////////////////////

// !! 他のスクリプトからインクルードされている場合はここでこのスクリプトを終了して処理を戻す !!
// 2017/08/21 一旦コメントアウト
//if ( __FILE__ != str_replace("/", "\\", $_SERVER["SCRIPT_FILENAME"]) ) return;
///////////////////////////////////////////////////////////////////////////
// リクエストの処理を開始。メソッドとパスから適切な処理に分岐する
$method = $_SERVER["REQUEST_METHOD"];
$path = isset($_SERVER["PATH_INFO"])? $_SERVER["PATH_INFO"] : "/";
$params = Array();  // preg_match_allの結果を受け取るArray

// ルーティング本処理

// ログインユーザを取得
//$user_id = $_SERVER["REMOTE_USER"];
//$user_id = "hideyuki.kawamura";
$user_id = "379";

// 認証が済んでいなければ
if(empty($user_id)){
  throw new HttpErrorStatus("Unauthorized", 401);
  exit;
}

// スキルセット取得. 各種パラメータによってスキルを検索
if (preg_match_all("/^\/skillset$/", $path, $params)) {
    if ($method == "GET") {
      // ユーザ情報取得
      $usm = new UserSkillManager();
      response_json($usm->getUserSkillWithCondition($_GET));
      exit;
    } else if ($method == "POST") {
        //response_json(Array("Hello, わーるど!POST!"));
        // ここでメソッドコール
        exit;
    } else {
        throw new HttpErrorStatus("Method Not Allowed", 405);
    }

}
// スキルシート取得/更新. 取得時はユーザidが必須. 更新時はPOSTデータにskilllistを詰めること
else if (preg_match_all("/^\/skillsheet\/([0-9]*)$/", $path, $params)) {

    if (($method == "GET") && (count($params) >= 2) && !empty($params[1][0])) {
      // ユーザ情報取得
      $usm = new UserSkillManager();
      response_json($usm->getSkillSheet($params[1][0]));
      exit;
    }
    else if($method == "POST"){
      $usm = new UserSkillManager();
      response_json($usm->updateSkillSheet($user_id, $_POST));
      exit;
    }
    else {
        throw new HttpErrorStatus("Method Not Allowed", 405);
    }

}
else if(preg_match_all("/^\/master\/([a-z]*)$/", $path, $params)){

  if ($method == "GET") {

    $mm = new MasterManager();

    // 全マスタ
    if(empty($params[1][0])){
      response_json($mm->getAllMaster());
      exit;
    }
    else if($params[1][0] == "skill"){
      response_json($mm->getSkillMaster($_GET));
      exit;
    }
    else if($params[1][0] == "user"){
      response_json($mm->getUserMaster($_GET));
      exit;
    }
    else if($params[1][0] == "depart"){
      response_json($mm->getDepartMaster($_GET));
      exit;
    }

    throw new HttpErrorStatus("Method Not Allowed", 405);

  }
  else {
      throw new HttpErrorStatus("Method Not Allowed", 405);
  }
}
else if(preg_match_all("/^\/test\/([a-z]*)$/", $path, $params)){
  
  if ($method == "GET") {
    if(empty($params[1][0])){
      echo $_SERVER["REMOTE_USER"];
    }    
    exit;
  }
  else if($method == "POST"){
    // POSTメソッド
    if($params[1][0] == "execsql"){
      if(!empty($_POST["sql"])){
        $dbm = new DBManager();
        return response_json($dbm->execSQL($_POST["sql"]));
      }
    }
    exit;
  }
  else {
      throw new HttpErrorStatus("Method Not Allowed", 405);
  }
  
}

/*
if (preg_match_all("/^\/hello\/([0-9]+)$/", $path, $params) && $method == "POST") {
    $path_variable1 = (int)$params[1][0];
    $path_variable2 = (int)$params[2][0];
    response_json(Array($path_variable1, $path_variable2));
    exit;
}
*/

///////////////////////////////////////////////////////////////////////////
// 上記のいずれにも該当しないURIがリクエストされた場合は Not Foundとする
throw new HttpErrorStatus("Not Found", 404);
?>
