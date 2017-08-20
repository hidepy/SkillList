(function(){
  'use strict';

	angular.module('SKILL-LIST-APP').controller('HeaderController', function($scope, SkillSetService) {

    // 一覧表示用
    $scope.items = [];

    // 一覧表示用itemsから、key-subkey(の配列)を作って納めておくためのhash
    $scope.items_key = {};
    // 一覧表示用itemsから、subkeyで値を引ける用にするためのhash
    $scope.items_hash = {};
    // subkeyのid, nameプロパティ名
    $scope.subkey_prop_id = "";
    $scope.subkey_prop_name = "";
    // key翻訳用に使用するハッシュ
    $scope.mainkey_conv_hash = {};

    // 検索条件選択リスト
    $scope.select_lists = {
      skill_multi: [],
      skill_single: [],
      depart: [],
      nendo: []
    };

    $scope.init = function(){
        // この画面で使用するオブジェクトにメイン側からコピー
        $scope.select_lists.skill_multi = angular.copy($scope.com_skill_list);
        $scope.select_lists.skill_single = angular.copy($scope.com_skill_list);
        $scope.select_lists.depart = angular.copy($scope.com_depart_list);
        $scope.select_lists.nendo = (function(){
          var res = [];
          for(var i = 1967; i <= (new Date()).getFullYear(); i++)
            res.push(i);
          return res;
        })();
    };

    $scope.search_conditions = {
      user_id    : "",
      skill_id   : "",
      skill_level: ""
    };

    // 表示タイプ(0: 列挙, 1: ユーザベースで, 2: スキルベースで)
    $scope.disp_condition = "0";

    $scope.getSkillSet = function(){
      SkillSetService.getSkillSetWithCondition($scope.search_conditions)
        .then(function(res){
          /*
          $scope.items = (res.item || []).map(function(v){
            return v;
          });
          */
          $scope.items = res.item;

          // items以外のタブを更新する
          $scope.changeDispType();
        });
    };

    $scope.changeDispType = function(){

      $scope.items_key = {};
      $scope.items_hash = {};
      $scope.mainkey_conv_hash = {};

      if($scope.disp_condition == "1"){
        ($scope.items || []).forEach(function(v){
          var h = $scope.items_key[v.user_id] || [];
          h.push(v.skill_id);
          $scope.items_key[v.user_id] = h;
        });

        $scope.items_hash = convArr2Hash($scope.items, "skill_id");
        $scope.subkey_prop_id = "skill_id";
        $scope.subkey_prop_name = "skill_name";
      }
      else if($scope.disp_condition == "2"){
        ($scope.items || []).forEach(function(v){
          var h = $scope.items_key[v.skill_id] || [];
          h.push(v.user_id);
          $scope.items_key[v.skill_id] = h;
        });

        $scope.items_hash = convArr2Hash($scope.items, "user_id");
        $scope.subkey_prop_id = "user_id";
        $scope.subkey_prop_name = "user_name";

        $scope.mainkey_conv_hash = $scope.com_skill_hash;
      }
    };

  });
})();
