(function(){
    'use strict';

	angular.module('SKILL-LIST-APP')
    .controller('DetailController', function($scope, $timeout, SkillSetService) {

      $scope.id = "";     // Must Set
      $scope.type = "U";  // or S

      // Display user info
      $scope.user_info = {
        user_id: "",
        user_name: "",
        depart_id: "",
        depart_name: "",
        is_midcarrer: false,
        nendo: null,
        skills: []
      };
      // 表示定義
      $scope.user_info_disp_definition = [];

      // Display skill info
      $scope.skill_info = {
        skill_id: "",
        skill_name: "",
        type: "",
        type_name: "",
        rel_id: ""
      };
      // 基本情報リスト表示用の定義(Skill側)
      $scope.skill_info_disp_definition = [];

      // initialize 処理
      $scope.init = function(){

        // 初期処理
        var options = myNavigator.topPage.data;
        $scope.id = options.id;
        $scope.type = options.type;

        if($scope.type == "U"){

          // ユーザ情報1件取得
          SkillSetService.getUserInfo({
            id: $scope.id
          })
            .then(function(res){

              if(res && (res.return_cd == 0) && res.item && res.item[0]){
                var data = res.item[0];

                $scope.user_info.user_id = data.id;
                $scope.user_info.user_name = data.name;
                $scope.user_info.depart_id = data.depart_id;
                $scope.user_info.depart_name = data.depart_name;
                $scope.user_info.is_midcarrer = (data.is_midcarreer == "1");
                $scope.user_info.nendo = data.nendo;
              }

              // ユーザ情報表示定義
              $scope.user_info_disp_definition = [
                {"name": "社員ID", "description": $scope.user_info.user_id},
                {"name": "所属部署", "description": "" + $scope.user_info.depart_name + "(" + $scope.user_info.depart_id + ")"},
                {"name": "入社年度", "description": "" + $scope.user_info.nendo + ($scope.user_info.is_midcarrer ? "[中途]" : "")}
              ];
            })
          ;

          // ユーザに紐づくスキルセット情報全件取得
          SkillSetService.getSkillSetWithCondition({
            user_id: $scope.id
          })
            .then(function(res){
              if(res && (res.return_cd == 0) && res.item){
                $scope.user_info.skills = res.item;
              }
            })
          ;
        }
        else if($scope.type == "S"){
          SkillSetService.getSkillInfo({
            id: $scope.id
          })
            .then(function(res){
              if(res && (res.return_cd == 0) && res.item && res.item[0]){
                var data = res.item[0];

                $scope.skill_info.skill_id = data.id;
                $scope.skill_info.skill_name = data.name;
                $scope.skill_info.type = data.type;
                $scope.skill_info.type_name = $scope.skill_type_hash[data.type];
                $scope.skill_info.rel_id = data.rel_id;
                $scope.skill_info.rel_name = data.rel_id ? ($scope.com_skill_hash[data.rel_id] || {name: ""})["name"] : "";

                console.log(($scope.com_skill_hash[data.rel_id] || {name: ""})["name"]);
              }

              $scope.skill_info_disp_definition = [
                {"name": "スキルID", "description": $scope.skill_info.skill_id},
                {"name": "スキル種別", "description": $scope.skill_info.type_name + "(" + $scope.skill_info.type + ")"},
                {"name": "関連/基盤言語", "description": !!$scope.skill_info.rel_id ? $scope.skill_info.rel_name + "(" + $scope.skill_info.rel_id + ")" : ""}
              ];
            })
          ;
        }
      };
    });
})();
