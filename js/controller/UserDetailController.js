(function(){
    'use strict';

	angular.module('SKILL-LIST-APP')
        .controller('UserDetailController', function($scope, $timeout, $routeParams, SkillSetService) {

          $scope.user_info = {};

          $scope.init = function(){

            $scope.user_info.user_id = $routeParams["user_id"];

            if(!!$scope.user_info.user_id){
              SkillSetService.getSkillSetWithCondition({
                user_id: $scope.user_info.user_id
              });
            }
          };

          $scope.moveBack = function(){
            $scope.move("/");
          };

        });
})();
