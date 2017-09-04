<?php
require_once("BDManager.php");

class MasterManager extends DBManager{

  public function __construct(){
    // do nothing
  }

  public function getAllMaster(){
    // 複数回マスタ参照するんで先にopenしておく
    $dbh = $this->_open_db();

    $if_return = array("return_cd"=> 0, "msg"=> "", "item"=> null);

    $skills  = $this->getSkillMaster();
    $users   = $this->getUserMaster();
    $departs = $this->getDepartMaster();

    $wrapper = array(
      "skills"=> ($skills && $skills["return_cd"] == "0") ? $skills["item"] : array(),
      "users"=> ($users && $users["return_cd"] == "0") ? $users["item"] : array(),
      "departs"=> ($departs && $departs["return_cd"] == "0") ? $departs["item"] : array(),
    );

    $if_return["item"] = $wrapper;

    $dbh = null;

    return $if_return;
  }

  public function getSkillMaster($params = null, $dbh = null){

    $condition_str = "";
    $condition_params = array();
    $condition_keys = array("id"=>"m1.skill_id", "name"=>"m1.skill_name");
    $kvobj = array("id"=> "skill_id", "name"=> "skill_name", "type"=> "type", "rel_id"=> "rel_id");

    // 一般的な全検索条件について検索文字列とバインドを組み立てる
    foreach ($condition_keys as $key=> $value) {
      if(!empty($params[$key])){
        $param = explode("-", $params[$key]);
        $condition_str .= sprintf(" AND ".$value." IN (%s)", $this->getInString($param));
        $condition_params = array_merge($condition_params, $param);
      }
    }

    $query = "
    SELECT
      m1.skill_id
      ,m1.skill_name
      ,m1.type
      ,m1.rel_id
    FROM
      m_skill m1
    WHERE
      1 = 1
    ".$condition_str;

    return $this->getRecordsBySqlAndKVObj($query, $condition_params, $kvobj, $dbh);
  }

  public function getUserMaster($params = null, $dbh = null){

    $condition_str = "";
    $condition_params = array();
    $condition_keys = array("id"=>"m1.id", "name"=>"m1.name");
    $kvobj = array("id"=> "id", "name"=> "name", "depart_id"=> "depart", "depart_name"=> "depart_name", "is_midcarreer"=> "is_midcarreer", "nendo"=> "nendo");

    // 一般的な全検索条件について検索文字列とバインドを組み立てる
    foreach ($condition_keys as $key=> $value) {
      if(!empty($params[$key])){
        $param = explode("-", $params[$key]);
        $condition_str .= sprintf(" AND ".$value." IN (%s)", $this->getInString($param));
        $condition_params = array_merge($condition_params, $param);
      }
    }

    $query = "
    SELECT
      m1.id
      ,m1.name
      ,m1.depart
      ,m2.depart_name
      ,m1.is_midcarreer
      ,m1.nendo
    FROM
      m_user m1
      inner join
        m_depart m2
        on
          m1.depart = m2.depart_id
    WHERE
      1 = 1
    ".$condition_str;

    return $this->getRecordsBySqlAndKVObj($query, $condition_params, $kvobj, $dbh);
  }

  public function getDepartMaster($params = null, $dbh = null){

    $condition_str = "";
    $condition_params = array();
    $condition_keys = array("id"=>"m1.depart_id", "name"=>"m1.depart_name");
    $kvobj = array("id"=> "depart_id", "name"=> "depart_name");

    // 一般的な全検索条件について検索文字列とバインドを組み立てる
    foreach ($condition_keys as $key=> $value) {
      if(!empty($params[$key])){
        $param = explode("-", $params[$key]);
        $condition_str .= sprintf(" AND ".$value." IN (%s)", $this->getInString($param));
        $condition_params = array_merge($condition_params, $param);
      }
    }

    $query = "
    SELECT
      m1.depart_id
      ,m1.depart_name
    FROM
      m_depart m1
    WHERE
      1 = 1
    ".$condition_str;

    return $this->getRecordsBySqlAndKVObj($query, $condition_params, $kvobj, $dbh);
  }
}
?>
