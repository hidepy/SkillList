(function(){
  'use strict';

  angular.module('SKILL-LIST-APP')
    .controller('RootController', function($scope, SkillSetService) {

      // メッセージ表示に関するobject
      $scope.message_info = {
          message: "",
          show: false,
          status: ""
      };

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
      $scope.init = function(){
        console.log("root initialized");

        // 取得されていなければリストを作成
        SkillSetService.getAllMasterInfo()
          .then(function(res){

            if(res && (res.return_cd == 0)){

              // マスタ情報を取得
              $scope.com_skill_list = angular.copy(res.item.skills);
              $scope.com_user_list = angular.copy(res.item.users);
              $scope.com_depart_list = angular.copy(res.item.depart);

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

      $scope.showMessage = function(message, status){
          $scope.message_info.message = message;
          $scope.message_info.status = status || "alert-info";
          $scope.message_info.show = true;
          $timeout(function(){
              $scope.message_info.show = false;
          }, 3000);
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
