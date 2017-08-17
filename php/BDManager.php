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
      die("cannot open ini file...");
    }

    // PDOに変更
    $dbh = null;// ブロックスコープなしなんで、ここで宣言する必要はないのだけれど...

    try{
      $dsn = "mysql:host=".$configs["dbinfo"]["dbhost"] . ";dbname=" . $configs["dbinfo"]["dbname"] . ";charset=utf8";
      $dbh = new PDO($dsn, $configs["dbinfo"]["dbuser"], $configs["dbinfo"]["dbpw"]);
    }
    catch(PDOException $e){
      die("db connect error...:" . $e->getMessage());
    }

    return $dbh;
  }

  // IN句のバインド
  protected function getInString($params){
    return substr(str_repeat(',?', count($params)), 1);
  }
}
?>
