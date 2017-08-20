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
            nendo: null
          };

          // Display skill info
          $scope.skill_info = {
            skill_id: "",
            skill_name: "",
            type: "",
            type_name: "",
            rel_id: ""
          };

          var options = myNavigator.getCurrentPage().options;
          $scope.id = options.id;
          $scope.type = options.type;

          $scope.init = function(){
            if($scope.type == "U"){
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
                    $scope.skill_info.rel_name = ($scope.com_skill_hash[data.rel_id] || {name: ""})["name"];

                  }
                })
              ;
            }
          };
        });
})();
