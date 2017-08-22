<?php
class DBManager{

  public function __construct(){
    // do nothing
  }

  // DB接続用共通メソッド
  protected function _open_db(){
    // 毎回読むのも微妙なんで、sessionにdb接続情報を保存しておくのがいいか...とも思ったけど、セキュリティ的に多少の懸念があったんでやめとこーっと。
    // そんなにごく頻繁にコールされるもんでもないしね

    // iniファイル読込
    $configs = parse_ini_file("../../dbconfig.ini", true);
    if(!$configs){
      throw new Exception("cannot open ini...");
    }

    // PDOに変更
    $dbh = null;// ブロックスコープなしなんで、ここで宣言する必要はないのだけれど...

    try{
      $dsn = "mysql:host=".$configs["dbinfo"]["dbhost"] . ";dbname=" . $configs["dbinfo"]["dbname"] . ";charset=utf8";
      $dbh = new PDO($dsn, $configs["dbinfo"]["dbuser"], $configs["dbinfo"]["dbpw"]);
    }
    catch(PDOException $e){
      throw new Exception("cannot open db...");
    }

    return $dbh;
  }

  function getRecordsBySqlAndKVObj($query, $params, $kvobj, $dbh){
    // 共通返却Obj
    $if_return = array("return_cd"=> 9, "msg"=> "ERROR OCCURRED...", "item"=> null);

    try{
      $need_destroy_dbh = false;

      // DB接続用Object取得
      if($dbh == null){
        $dbh = $this->_open_db();
        $need_destroy_dbh = true;
      }

      // Queryコンパイル
      $stmt = $dbh->prepare($query);
      // Query実行
      $stmt->execute($params);
      // 返却用
      $res = [];

      while($r = $stmt->fetch()){
        $tmp_obj = array();
        // key-val objectのプロパティ定義に従って値をコピー
        foreach ($kvobj as $key => $value) {
          $tmp_obj[$key] = $r[$value];
        }
        $res[] = $tmp_obj;
      }

      // 返却用Objにセット
      $if_return["return_cd"] = 0;
      $if_return["msg"] = "";
      $if_return["item"] = $res;

      // 解放
      if($need_destroy_dbh){
        $dbh = null;
      }
    }
    catch(Exception $e){
      $if_return["return_cd"] = 9;
      $if_return["msg"] = $e->getMessage();
    }

    return $if_return;

  }

  // IN句のバインド
  protected function getInString($params){
    return substr(str_repeat(',?', count($params)), 1);
  }

  function returnIniErrorObj(){
    return array("return_cd"=> 9, "msg"=> "conf file get error...");
  }

  protected function returnDBErrorObj($msg){
    return array("return_cd"=> 9, "msg"=> "db error...".$msg);
  }
}
?>
