(function(){
  'use strict';

	angular.module('SKILL-LIST-APP').controller('SkillSheetController', function($scope, SkillSheetService) {

		$scope.skill_types = ["lang", "db", "os"];

		$scope.skill_list = {

		};

		$scope.skillsheet_init = function(){
			SkillSheetService
				.getSkillSheetMaster()
					.then(function(res){
						$scope.skill_list = res;
						$scope.$apply();
					});
		};

  });
})();
