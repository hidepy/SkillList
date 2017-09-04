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

      // ログインユーザ情報
      $scope.login_user_info = {
        user_id: "379",
        user_name: "hideyuki.kawamura",
        depart_id: "",
        depart_name: "",
        is_midcarrer: false,
        nendo: null,
        skills: []
      };

      // ユーザ情報表示定義
      $scope.com_user_info_disp_definition = [
        {"name": "社員ID", "description": $scope.login_user_info.user_id},
        {"name": "所属部署", "description": "" + $scope.login_user_info.depart_name + "(" + $scope.login_user_info.depart_id + ")"},
        {"name": "入社年度", "description": "" + $scope.login_user_info.nendo + ($scope.login_user_info.is_midcarrer ? "[中途]" : "")}
      ];

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
          //return;
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
            //myNavigator.resetToPage("view/main.html");
            myNavigator.resetToPage("view/home.html");
<<<<<<< HEAD

=======
>>>>>>> 0a3d3fefb24e5040810c1adc6971c43d5454f2b7
          }, function(err){
            // エラー側ハンドラ
            showOnsDialog("SelectList Initialization Failure...");

            // 一旦エラーだった場合もhomeへ転送する(local用)
            myNavigator.resetToPage("view/home.html");
          });
      };

      $scope.move2SkillSheet = function(){
        myNavigator.pushPage("view/skill-sheet.html");
      };

      $scope.move2Head = function(type, id){
        var search_conditions;

        if(type == "S"){
          search_conditions["skill_id"] = id;
        }
        else if(type == "U"){
          search_conditions["user_id"] = id;
        }

console.log("move2Head");
console.log(search_conditions);

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
