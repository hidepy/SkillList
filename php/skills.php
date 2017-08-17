<?php
class UserSkillManager{

  public function __construct(){
    // do nothing
  }

  // DB接続用共通メソッド
  private function _open_db(){
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
      echo "dsn=".$dsn."\n";
      $dbh = new PDO($dsn, $configs["dbinfo"]["dbuser"], $configs["dbinfo"]["dbpw"]);
      echo "dbh instantiated\n";
    }
    catch(PDOException $e){
      die("db connect error...:" . $e->getMessage());
    }

    return $dbh;
  }

  // ユーザスキルセット取得共通ロジック
  private function _getUserSkill($condition_str, $params){
    // 共通戻りI/Fオブジェクト
    $if_return = array("return_cd"=> 9, "msg"=> "ERROR OCCURRED...", "item"=> null);

    // DB接続用Object取得
    $dbh = $this->_open_db();

    // 発行するクエリ
    $query = "
    SELECT
      m1.user_id
      ,m2.name
      ,m1.skill_id
      ,m3.skill_name
      ,m3.type
      ,m1.skill_level
      ,m1.acquire_ym
    FROM
      m_userskill m1
      INNER JOIN
        m_user m2
    	ON
    	  m1.user_id = m2.id
      INNER JOIN
        m_skill m3
    	ON
    	  m1.skill_id = m3.skill_id
    WHERE
      1 = 1
    ".$condition_str;

    // Queryコンパイル
    $stmt = $dbh->prepare($query);
    // Query実行
    $stmt->execute($params);

    $res = [];

    while($r = $stmt->fetch()){
      //$res[] = $r;// 面倒なんで直接入れちゃう. と、配列側のインデックスも拾ってしまうようなんで、やっぱりプロパティでセット
      $res[] = array(
        "user_id"    => $r["user_id"],
        "user_name"  => $r["name"],
        "skill_id"   => $r["skill_id"],
        "skill_name" => $r["skill_name"],
        "skill_type" => $r["type"],
        "skill_lebel"=> $r["skill_level"],
        "skill_acquire_ym"=> $r["acquire_ym"]
      );
    }

    // 返却用Objにセット
    $if_return["return_cd"] = 0;
    $if_return["msg"] = "";
    $if_return["item"] = $res;
    // 解放
    $dbh = null;

    return $if_return;
  }

  // IN句のバインド
  private function getInString($params){
    return substr(str_repeat(',?', count($params)), 1);
  }


  // 全ユーザのスキルセットを取得. 管理者のみとかにさせるべきか
  public function getUserSkill(){
    // 共通メソッドコール
    return $this->_getUserSkill("", array());
  }

  /*
  public function getUserSkillByUserId($user_id){
    $condition_str = "
      AND m1.user_id = :user_id
    ";
    return $this->_getUserSkill($condition_str, array("user_id"=> $user_id));
  }
  */

  public function getUserSkillWithCondition($params){
    if(!isset($params)){
      return $this->getUserSkill();
    }

    // 一般的なスキル取得条件
    $condition_keys = array("user_id"=>"m1.user_id", "skill_id"=>"m1.skill_id");
    $condition_str = "";
    $condition_params = array();

    // 一般的な全検索条件について検索文字列とバインドを組み立てる
    foreach ($condition_keys as $key=> $value) {
      if(isset($params[$key])){
        $param = explode("-", $params[$key]);
        $condition_str .= sprintf(" AND ".$value." IN (%s)", $this->getInString($param));
        $condition_params = array_merge($param);
      }
    }

    // スキルレベル(スキルとレベルペア)による検索条件
    if(!isset($params["skill_id"]) && isset($params["skill_level"])){
      $param = explode("-", $params["skill_level"]);
      $condition_str .= sprintf(" AND m1.".$key." IN (%s)", $this->getInString($param));
      $condition_params = array_merge($param);
    }

    // 検索実行
    return $this->_getUserSkill($condition_str, $condition_params);
  }

}
?>
