(function(){
  'use strict';

	angular.module('SKILL-LIST-APP').controller('SkillSheetController', function($scope, SkillSetService) {

    // 画面タイプ 0=照会, 1=更新
    $scope.proc_type = "0";

		$scope.skill_types = [
      {id: "L", name: "言語"},
      {id: "D", name: "DB"},
      {id: "F", name: "フレームワーク"},
      {id: "O", name: "OS"},
      {id: "E", name: "その他スキル"}
    ];

		$scope.skill_list = {
		};

		$scope.skillsheet_init = function(){

      var options = myNavigator.topPage.data || {};
      $scope.proc_type = (options.type == "1") ? "1" : "0";// 0 or 1に寄せる

			SkillSetService
				.getSkillSheetByUserId($scope.login_user_info.user_id)
					.then(function(res){

            var list = res.item;

            $scope.skill_types.forEach((v)=>{
              $scope.skill_list[v.id] = [];
            });

            (list || []).forEach((v)=>{
              v.skill_level = Number(v.skill_level) || 0;
              ($scope.skill_list[v.skill_type]).push(v);
            });

					});
		};

  });
})();
