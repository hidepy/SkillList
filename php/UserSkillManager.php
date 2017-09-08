<?php
require_once("BDManager.php");

class UserSkillManager extends DBManager{

  public function __construct(){
    // do nothing
  }

  // ユーザスキルセット取得共通ロジック
  private function _getUserSkill($condition_str, $params){
    // 共通戻りI/Fオブジェクト
    $if_return = array("return_cd"=> 9, "msg"=> "ERROR OCCURRED...", "item"=> null);

    try{
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
          "skill_level"=> $r["skill_level"],
          "skill_acquire_ym"=> $r["acquire_ym"]
        );
      }

      // 返却用Objにセット
      $if_return["return_cd"] = 0;
      $if_return["msg"] = "";
      $if_return["item"] = $res;
    }
    catch(Exception $e){
      $if_return["return_cd"] = 9;
      $if_return["msg"] = $e->getMessage();
    }

    // 解放
    $dbh = null;

    return $if_return;
  }

  // 全ユーザのスキルセットを取得. 管理者のみとかにさせるべきか
  public function getUserSkill(){
    // 共通メソッドコール
    return $this->_getUserSkill("", array());
  }

  public function getUserSkillWithCondition($params){
    if(!isset($params)){
      return $this->getUserSkill();
    }

    // 一般的なスキル取得条件
    $condition_keys = array("user_id"=>"m1.user_id", "skill_id"=>"m1.skill_id", "depart_id"=> "m2.depart");
    $condition_str = "";
    $condition_params = array();

    // 一般的な全検索条件について検索文字列とバインドを組み立てる
    foreach ($condition_keys as $key=> $value) {
      //if(isset($params[$key])){
      if(!empty($params[$key])){
        $param = explode("-", $params[$key]);
        $condition_str .= sprintf(" AND ".$value." IN (%s)", $this->getInString($param));
        $condition_params = array_merge($condition_params, $param);
      }
    }

    // スキルレベル(スキルとレベルペア)による検索条件
    if(empty($params["skill_id"]) && isset($params["skill_level"])){
      $param = explode("-", $params["skill_level"]);

      foreach ($param as $p) {
        $skill_level = explode(":", $p);
        if(count($skill_level) == 2){
          $condition_str .= " AND (m1.skill_id = ? AND m1.skill_level >= ?)";
          array_push($condition_params, $skill_level[0], $skill_level[1]);
        }
      }
    }

    $if_return = $this->_getUserSkill($condition_str, $condition_params);

    $if_return["msg"] = $condition_str;

    return $if_return;

    // 検索実行
    //return $this->_getUserSkill($condition_str, $condition_params);
  }

  // ユーザのスキルシート表示用
  public function getSkillSheet($user_id){
    // 共通戻りI/Fオブジェクト
    $if_return = array("return_cd"=> 9, "msg"=> "ERROR OCCURRED...", "item"=> null);

    try{
      // DB接続用Object取得
      $dbh = $this->_open_db();

      // 発行するクエリ
      $query = "
      SELECT
        m1.skill_id
        ,m1.skill_name
        ,m1.type
        ,COALESCE(m2.skill_level, 0) as skill_level
        ,COALESCE(m2.acquire_ym, '') as acquire_ym
      FROM
        m_skill m1
        LEFT OUTER JOIN
          m_userskill m2
          ON
            m1.skill_id = m2.skill_id
      	    AND m2.user_id = :user_id
      ORDER BY
        m1.skill_id
      ";

      // Queryコンパイル
      $stmt = $dbh->prepare($query);
      // Query実行
      $stmt->execute(array("user_id"=> $user_id));
      //$stmt->execute();

      $res = [];

      while($r = $stmt->fetch()){
        //$res[] = $r;// 面倒なんで直接入れちゃう. と、配列側のインデックスも拾ってしまうようなんで、やっぱりプロパティでセット
        $res[] = array(
          "skill_id"   => $r["skill_id"],
          "skill_name" => $r["skill_name"],
          "skill_type" => $r["type"],
          "skill_level"=> $r["skill_level"],
          "skill_acquire_ym"=> $r["acquire_ym"]
        );
      }

      // 返却用Objにセット
      $if_return["return_cd"] = 0;
      $if_return["msg"] = "";
      $if_return["item"] = $res;
    }
    catch(Exception $e){
      $if_return["return_cd"] = 9;
      $if_return["msg"] = $e->getMessage();
    }

    // 解放
    $dbh = null;

    return $if_return;
  }

  public function updateSkillSheet($user_id, $params){
    // var_dump($params);
    // $params["skillsheet_data"] にスキルシート情報が入っている

    $if_return = array("return_cd"=> 9, "msg"=> "ERROR OCCURRED...", "item"=> null);

    // POSTデータのスキルシート情報を取得
    $skillsheet_data = json_decode($params["skillsheet_data"], true);
    if(count($skillsheet_data) == 0){
      return $if_return;
    }

    // DB接続用オブジェクト
    $dbh = null;

    try{
      // DB接続用Object取得
      $dbh = $this->_open_db();
    }
    catch(Exception $e){
      $if_return["return_cd"] = 9;
      $if_return["msg"] = $e->getMessage();
      return $if_return;
    }

    try{
      // 発行するクエリ
      $query = "";

      $acquire_ym = "291709";

      // トランザクション開始
      $dbh->beginTransaction();

      $query_delete = "DELETE FROM m_userskill WHERE user_id = '".$user_id."' AND acquire_ym = '".$acquire_ym."'";
      $stmt = $dbh->prepare($query_delete);
      // Query実行
      $stmt->execute();

      // SQL組み立て
      $query = "INSERT INTO m_userskill (user_id, skill_id, skill_level, acquire_ym) VALUES ";

      // シングルクォートのエスケープと、自動付与をすること！
      $query_values = array();
      foreach($skillsheet_data as $data){
        $query_values[] = "(" . implode(",", array("'".$user_id."'", "'".$data["skill_id"]."'", $data["skill_level"], "'".$acquire_ym."'") ) . ")";
      }
      $query .= implode(",", $query_values);

      //$if_return["msg"] = $query;
      //return $if_return;

      // Queryコンパイル
      $stmt = $dbh->prepare($query);

      // Query実行
      //$stmt->execute($params);
      $stmt->execute();

      $dbh->commit();

      // 返却用Objにセット
      $if_return["return_cd"] = 0;
      //$if_return["msg"] = "all ok.".$query."::".$query_delete;
    }
    catch(Exception $e){
      $dbh->rollBack();

      $if_return["return_cd"] = 9;
      $if_return["msg"] = $e->getMessage();
    }

    // 解放
    $dbh = null;

    return $if_return;
  }

}


?>
