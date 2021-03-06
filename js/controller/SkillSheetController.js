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

      modal.show();

			SkillSetService
				.getSkillSheetByUserId($scope.login_user_info.user_id)
					.then(function(res){

            modal.hide();

            var list = res.item;

            $scope.skill_types.forEach((v)=>{
              $scope.skill_list[v.id] = [];
            });

            (list || []).forEach((v)=>{
              v.skill_level = Number(v.skill_level) || 0;
              ($scope.skill_list[v.skill_type]).push(v);
            });

					}, function(err){

            modal.hide();

            showOnsDialog("Fatal Error Occurred...");
          });
		};

    $scope.updateSkillSheet = function(){

      var skill_list_plain = (function(skill_hash){
        var res = [];
        for(var p in skill_hash){
          (skill_hash[p] || [])
            .filter(v=> Number(v.skill_level) > 0)
            .map(v=> {
              return {
                skill_id: v.skill_id,
                skill_level: v.skill_level,
                skill_acquire_ym: v.skill_acquire_ym
              }
            })
            .forEach(v=> res.push(v))
          ;
        }
        return res;
      })($scope.skill_list);

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
