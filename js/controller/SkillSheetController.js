(function(){
  'use strict';

	angular.module('SKILL-LIST-APP').controller('SkillSheetController', function($scope, SkillSheetService) {

		$scope.skill_types = ["lang", "db", "os"];

		$scope.skill_list = {
		};

		var _skill_list_hash = {};

		$scope.skillsheet_init = function(){
			SkillSheetService
				.getSkillSheetMaster()
					.then(function(res){
						$scope.skill_list = res;
					})
				.getSkillSheetInfo()
					.then(function(res){
						if(res){

							
							convArr2Hash(list, key){
						}
						$scope.$apply();
					})
				;
		};

  });
})();
