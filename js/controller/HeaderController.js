(function(){
  'use strict';

	angular.module('SKILL-LIST-APP').controller('HeaderController', function($scope, SkillSetService) {

    // 画面表示コントロール用 デフォルトは空白.
    $scope.type = "";

    // 一覧表示用
    $scope.items = [];

    // 一覧表示用itemsから、key-subkey(の配列)を作って納めておくためのhash
    //   これがベース. DispType一番左(0)の純粋列挙の場合に使用される. のと、ベースの受け皿
    $scope.items_key = {};

    // 一覧表示用itemsから、subkeyで値を引ける用にするためのhash
    //   これがDispType1, 2の場合の表示に使用されるhash. user_id又はskill_idがハッシュのキーとなり、valueはitemsの1要素のオブジェクトが入る
    $scope.items_hash = {};

    // subkeyのid, nameプロパティ名
    //   subkey = DispTypeが1 or 2の場合に、ユーザベース表示ならsubkey=skill_id、スキルベース表示ならsubkey=user_id
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

    $scope.h_init = function(){

      console.log("HeaderController Init");

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

      // デフォルト表示タイプが設定されていなければ
      if(!$scope.disp_condition){
        $scope.disp_condition = "1";
      }

      // 永遠に描画されても気分が悪いので、header init終了時にnow loadingの要素は消しておく
      angular.element(document.getElementById("initial-screen")).remove();

    };

    $scope.search_conditions = {
      user_id    : "",
      skill_id   : "",
      skill_level: "",
      depart_id  : ""
    };

    // 表示タイプ(0: 列挙, 1: ユーザベースで, 2: スキルベースで)
    $scope.disp_condition = "1";

    $scope.getSkillSet = function(){

      console.log($scope.search_conditions.skill_level);

      // レベルだけ入っている場合はNG
      if(!$scope.search_conditions.skill_id && (Number($scope.search_conditions.skill_level) > 0)){
        showOnsDialog("スキルを入力して下さい<br>(レベル入力時)");
        return;
      }
      else if(!!$scope.search_conditions.skill_id && (Number($scope.search_conditions.skill_level) > 0)){
        var skill_id_arr = $scope.search_conditions.skill_id.split("-");
        if(skill_id_arr.length > 1){
          showOnsDialog("複数スキル検索時にレベル指定はできません");
          return;
        }
      }

      modal.show();

      var search_conditions = angular.copy($scope.search_conditions);
      if(Number(search_conditions.skill_level) > 0){
        search_conditions["skill_level"] = search_conditions["skill_id"] + ":" + search_conditions["skill_level"];
        search_conditions["skill_id"] = "";
      }

      SkillSetService.getSkillSetWithCondition(search_conditions)
        .then(function(res){

          if(res && (res.return_cd == 0)){
            // 単純に結果をコピー
            $scope.items = res.item;

            // items以外の表示方法データを更新する
            $scope.changeDispType();
          }
          else{
            showOnsDialog("Error Occurred..." + res.msg);
          }

          modal.hide();

        }, function(err){
          showOnsDialog("Fatal Error Occurred...");

          modal.hide();
        });
    };

    $scope.kewdownOnSearchArea = function(event){
      // Enter押下で検索
      if (event.which === 13) {
        $scope.getSkillSet();
      }
    };

    // レコード表示方法(DispType)のボタン変更時イベント
    $scope.changeDispType = function(){

      $scope.items_key = {};
      $scope.items_hash = {};
      $scope.mainkey_conv_hash = {};

      // 社員ベースでスキルを表示するタイプの場合
      if($scope.disp_condition == "1"){
        ($scope.items || []).forEach(function(v){
          var h = $scope.items_key[v.user_id] || [];
          h.push(v.skill_id);
          $scope.items_key[v.user_id] = h;
        });

        $scope.items_hash = convArr2Hash($scope.items, "skill_id");
        $scope.subkey_prop_id = "skill_id";
        $scope.subkey_prop_name = "skill_name";

        $scope.mainkey_conv_hash = $scope.com_user_hash;
      }
      // スキルベースで社員を表示するタイプの場合
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

    // 初期処理
    var options = myNavigator.topPage.data || {};
    $scope.type = options.type || "";

    // スキル検索の場合
    if($scope.type == "S"){
      // スキルベースで結果を表示
      $scope.disp_condition = "2";
    }

    // デフォルト検索条件が与えられていればコピー
    if(!!options.search_conditions){
      $scope.search_conditions.user_id = options.search_conditions.user_id;
      $scope.search_conditions.skill_id = options.search_conditions.skill_id;
      $scope.search_conditions.skill_level = options.search_conditions.skill_level;

      $scope.getSkillSet();

    }
  });
})();
