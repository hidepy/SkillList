(function(){
  'use strict';

  angular.module('SKILL-LIST-APP')
    .controller('RootController', function($scope, SkillSetService) {

      // スキル種別翻訳用
      $scope.skill_type_hash = {
        L: "プログラミング言語",
        D: "データベース",
        E: "環境/その他",
        F: "フレームワーク",
        O: "OS"
      };

      // スキルマスタリスト
      $scope.com_skill_list = [];
      $scope.com_skill_hash = {};
      // 部署マスタリスト
      $scope.com_depart_list = [];
      $scope.com_depart_hash = {};
      // ユーザマスタリスト
      $scope.com_user_list = [];
      $scope.com_user_name = {};

      // ---------- methods ----------
      $scope.init = function(protcol_type){
        console.log("root initialized");

        console.log(protcol_type);

        if(protcol_type != 1){
          return;
        }

        // 取得されていなければリストを作成
        SkillSetService.getAllMasterInfo()
          .then(function(res){

            if(res && (res.return_cd == 0)){

              // マスタ情報を取得
              $scope.com_skill_list = angular.copy(res.item.skills);
              $scope.com_user_list = angular.copy(res.item.users);
              $scope.com_depart_list = angular.copy(res.item.departs);

              // マスタハッシュを作成
              $scope.com_skill_hash = convArr2Hash(
                $scope.com_skill_list, "id"
              );
              $scope.com_user_hash = convArr2Hash(
                $scope.com_user_list, "id"
              );
              $scope.com_depart_hash = convArr2Hash(
                $scope.com_depart_list, "id"
              );
            }

            // 準備ができたらNavogatorにエントリポイントを仕掛ける
            myNavigator.resetToPage("view/main.html");
          }, function(err){
            // エラー側ハンドラ
            $scope.showMessage("SelectList Initialization Failure...");
          });
      };

      $scope.move2Head = function(type, id){
        var search_conditions = {};

        if(type == "S"){
          search_conditions["skill_id"] = id;
        }
        else if(type == "U"){
          search_conditions["user_id"] = id;
        }

        myNavigator.pushPage("view/main.html", {
          data: {
            type: type,
            search_conditions: search_conditions
          }
        });
      };

      // 詳細画面への遷移
      $scope.move2Detail = function(type, id){
        if((type != "U") && (type != "S")){
          cnsole.log("invalid type...");
          return;
        }

        myNavigator.pushPage("view/detail.html", {
          data: {
            type: type,
            id: id
          }
        });
      };
    })
})();
