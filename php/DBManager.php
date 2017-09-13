<?php
class DBManager{

  public function __construct(){
    // do nothing
  }

  // DB接続用共通メソッド
  // MySQL用のDB接続オブジェクト
  /*
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

      // エラーレベルを変更. エラー発生でException発生させる
      $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    catch(PDOException $e){
      die("db connect error...:" . $e->getMessage());
    }

    return $dbh;
  }
  */
  
  // SQLite版. 移植性を高めようと思って
  protected function _open_db(){
    $dbh = null;// ブロックスコープなしなんで、ここで宣言する必要はないのだけれど...

    try{
      // セキュリティの観点からは、ドキュメントルートより上位層に配置するのが望ましい。とりあえずね
      $dbh = new PDO("sqlite:./skill_list.db");

      // エラーレベルを変更. エラー発生でException発生させる
      $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

      $dbh->exec("
        CREATE TABLE IF NOT EXISTS m_skilltype (
          type_id varchar(2) NOT NULL,
          type_name varchar(30),
          PRIMARY KEY (type_id)
        )
      ");

      $dbh->exec("
        CREATE TABLE IF NOT EXISTS m_depart (
          depart_id varchar(2) NOT NULL,
          depart_name varchar(30) DEFAULT NULL,
          PRIMARY KEY (depart_id)
        )
      ");

      $dbh->exec("
        CREATE TABLE IF NOT EXISTS m_skill (
          skill_id varchar(6) NOT NULL,
          skill_name varchar(30) DEFAULT NULL,
          type char(1) DEFAULT NULL,
          rel_id varchar(6) DEFAULT NULL,
          PRIMARY KEY (skill_id)
        )
      ");

      $dbh->exec("
        CREATE TABLE IF NOT EXISTS m_user (
          id varchar(30) NOT NULL,
          name varchar(30) DEFAULT NULL,
          depart varchar(2) DEFAULT NULL,
          is_midcarreer char(1) DEFAULT NULL,
          nendo char(4) DEFAULT NULL,
          p_name varchar(50) DEFAULT NULL,
          PRIMARY KEY (id)
        )
      ");

      $dbh->exec("
        CREATE TABLE IF NOT EXISTS m_userskill (
          user_id varchar(30) NOT NULL,
          skill_id varchar(6) NOT NULL,
          skill_level tinyint(4) DEFAULT '0',
          acquire_ym varchar(6) NOT NULL DEFAULT '',
          PRIMARY KEY (user_id,skill_id,acquire_ym)
        )
      ");

    }
    catch(PDOException $e){
      die("db connect error...:" . $e->getMessage());
    }

    return $dbh;
  }


  function getRecordsBySqlAndKVObj($query, $params, $kvobj, $dbh){
    // 共通返却Obj
    $if_return = array("return_cd"=> 9, "msg"=> "ERROR OCCURRED...", "item"=> null);

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

    return $if_return;

  }

  // ※※ 渡された文字列をそのままSQLとして実行する(危険！！ユーザや権限を限定されたし！！)　※※
  public function execSQL($s){
    // たとえば、ユーザ名で制限するとかさ

    $if_return = array("return_cd"=> 0, "msg"=> "", "item"=> null);

    $dbh = null;

    //if($_SERVER["REMOTE_USER"] == "hogehoge"){
    if(true){
      try{
        $dbh = $this->_open_db();
        // Queryコンパイル
        $stmt = $dbh->prepare($s);
        // Query実行
        $stmt->execute();

        $res = [];

        while($r = $stmt->fetch())
          $res[] = $r;

        // 返却用Objにセット
        $if_return["return_cd"] = 0;
        $if_return["msg"] = "affect rows=".$stmt->rowCount();
        $if_return["item"] = $res;
      }
      catch(Exception $e){
        $if_return["return_cd"] = 9;
        $if_return["msg"] = "FATAL ERROR... ".$e->getMessage();
      }
    }

    return $if_return;
  }

  // IN句のバインド
  protected function getInString($params){
    return substr(str_repeat(',?', count($params)), 1);
  }
}
?>
