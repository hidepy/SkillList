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
    $condition_keys = array("user_id"=>"m1.user_id", "skill_id"=>"m1.skill_id");
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
    if(!isset($params["skill_id"]) && isset($params["skill_level"])){
      $param = explode("-", $params["skill_level"]);

      foreach ($param as $p) {
        $skill_level = explode(":", $p);
        if(count($skill_level) == 2){
          $condition_str .= " AND (m1.skill_id = ? AND m1.skill_level >= ?)";
          array_push($condition_params, $skill_level[0], $skill_level[1]);
        }
      }
    }

    // 検索実行
    return $this->_getUserSkill($condition_str, $condition_params);
  }


}
?>
