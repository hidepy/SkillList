(function(){
    'use strict';

	angular.module('SKILL-LIST-APP')
        .controller('UserDetailController', function($scope, $timeout, SkillSetService) {

          $scope.user_info = {};

          $scope.init = function(){

            $scope.user_info.user_id = "379";

            if(!!$scope.user_info.user_id){
              SkillSetService.getSkillSetWithCondition({
                user_id: $scope.user_info.user_id
              });
            }
          };
        });
})();
