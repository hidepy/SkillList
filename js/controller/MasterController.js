(function(){
  'use strict';

	angular.module('SKILL-LIST-APP').controller('MasterController', function($scope, SkillSetService) {

    $scope.proc_type = "USER"; // USER, SKILL, DEPART が存在

    // マスタテーブルのレコードが格納される
    $scope.items = [];
    // マスタテーブルの1レコード目のカラム定義を格納する
    $scope.col_def = [];

    // 各マスタ情報により変化する値
    var proc_type_props = {
      "USER": {
        service_name: "getUserInfo",
        col_def: [
          {name: "id"},
          {name: "name"},
          {name: "depart_id"},
          //{name: "depart_name", readonly: true},
          {name: "is_midcarreer", type: "checkbox"}
        ]
      },
      "SKILL": {
        service_name: "getSkillInfo",
        col_def: [
          {name: "id"},
          {name: "name"},
          {name: "type"},
          {name: "rel_id"}
        ]
      },
      "DEPART": {
        service_name: "getDepartInfo",
        col_def: [
          {name: "type_id"},
          {name: "type_name"}
        ]
      }
    };

		$scope.master_init = function(){

      var options = myNavigator.topPage.data || {};
      $scope.proc_type = options.type;

      $scope.col_def = proc_type_props[$scope.proc_type].col_def;

			SkillSetService[proc_type_props[$scope.proc_type].service_name]()
				.then(function(res){
          console.log(res);
          $scope.items = res.item;
				});
		};

    $scope.updateMaster = function(){

      modal.show();

      SkillSetService
        .updateSkillSheet(skill_list_plain)
          .then(function(res){
            if(res && (res.return_cd == "0")){
              showOnsDialog("更新しました");
            }
            else{
              showOnsDialog("更新に失敗しました...");
            }

            modal.hide();
          }, function(err){
            modal.hide();
            showOnsDialog("Fatal Error...");
          })
    };

  });
})();
