<?php
require_once("DBManager.php");

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
        ,COALESCE(vm2.skill_level, 0) as skill_level
        ,COALESCE(vm2.acquire_ym, '') as acquire_ym
      FROM
        m_skill m1
        LEFT OUTER JOIN
          (
              SELECT
                m2.skill_id
                ,m2.skill_level
                ,MAX(m2.acquire_ym) acquire_ym
              FROM
                m_userskill m2
              WHERE
                m2.user_id = :user_id
              GROUP BY
                m2.skill_id
              ,m2.skill_level
          ) vm2
          ON
            m1.skill_id = vm2.skill_id
      ORDER BY
        m1.skill_id
      ";

      /*
      // acquire_ym指定なら
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
      WHERE
          m2.acquire_ym = :acquire_ym
      ORDER BY
        m1.skill_id
      ";
      */

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

    // 習得年月(m_userskill-> acquire_ym)については...保留. やはり不要なのでは...？とは思えど、ユーザの成長記録が残らないとね...？
    $today = date("Ymd");// yyyyMMdd形式の値が取得できる
    // 習得年月をセット
    $acquire_ym = substr($today, 0, 4) . $acquire_ym = substr($today, 4, 2);

    // POSTデータのスキルシート情報を取得
    $skillsheet_data = json_decode($params["skillsheet_data"], true);

    //  入力データなしは終了
    if(count($skillsheet_data) == 0){
      $if_return["return_cd"] = 1;
      $if_return["msg"] = "No Update Data...";
      return $if_return;
    }

    // 必要パラメータ無しは終了(user_id)
    if(empty($user_id)){
      $if_return["return_cd"] = 9;
      $if_return["msg"] = "No Valid User...";
      return $if_return;
    }

    // 必要パラメータ無しは終了(acquire_ym)
    if(empty($acquire_ym)){
      $if_return["return_cd"] = 9;
      $if_return["msg"] = "No Acquire YM...";
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
      // トランザクション開始
      //$dbh->beginTransaction(); // ※※※ トランザクション張った状態でDEL-> INSするとPrimaryKey違反になってしまう...んで、一旦トラン無しで。誰かなおして。

      // 既存データDelete用
      $query_delete = "DELETE FROM m_userskill WHERE user_id = ? AND acquire_ym = ?";

      // Delete実行
      $stmt_delete = $dbh->prepare($query_delete);

      // Query実行
      $stmt_delete->execute(array($user_id, $acquire_ym));


      // SQL組み立て REPLACE句=INSERTで重複データは上書きする
      //$query = "INSERT INTO m_userskill (user_id, skill_id, skill_level, acquire_ym) VALUES ";
      $query = "REPLACE INTO m_userskill (user_id, skill_id, skill_level, acquire_ym) VALUES ";

      // バインドパラメータ(Insert用)

      $bind_params = array();
      // シングルクォートのエスケープと、自動付与をすること！
      $query_values = array();
      foreach($skillsheet_data as $data){
        //$query_values[] = "(" . implode(",", array("'".$user_id."'", "'".$data["skill_id"]."'", $data["skill_level"], "'".$acquire_ym."'") ) . ")";
        // Insert文を追加
        $query_values[] = "(?, ?, ?, ?)";
        // Insert用バインドパラメータを追加
        array_push($bind_params, $user_id, $data["skill_id"], $data["skill_level"], $acquire_ym);
      }
      $query .= implode(",", $query_values);

      // Queryコンパイル
      $stmt = $dbh->prepare($query);

      // Query実行
      $stmt->execute($bind_params);
      //$stmt->execute();

      // $dbh->commit();  // ※※※ トランザクション張った状態でDEL-> INSするとPrimaryKey違反になってしまう...んで、一旦トラン無しで。誰かなおして。

      // 返却用Objにセット
      $if_return["return_cd"] = 0;
      $if_return["msg"] = "";
    }
    catch(Exception $e){
      // $dbh->rollBack(); // ※※※ トランザクション張った状態でDEL-> INSするとPrimaryKey違反になってしまう...んで、一旦トラン無しで。誰かなおして。

      $if_return["return_cd"] = 9;
      $if_return["msg"] = "affect rows=".$stmt_delete->rowCount()."(delete), ".$stmt->rowCount()."(insert). ".$e->getMessage();
    }

    // 解放
    $dbh = null;

    return $if_return;
  }

}


?>
