(function(){
    'use strict';

	angular.module('SKILL-LIST-APP').controller('HeaderController', function($scope, SkillSetService) {

    console.log("before all header logic");

    // 一覧表示用
    $scope.items = [];

    $scope.init = function(){
      console.log("init event driven");

      console.log($scope.items);
    };

    $scope.search_conditions = {
      user_id    : "",
      skill_id   : "",
      skill_level: ""
    };

    $scope.move2UserDetail = function(user_id){
      //console.log("move!!");
      //$scope.move("/userdetail/" + user_id);

      //myNavigator.pushPage("view/userdetail.html");
      myNavigator.pushPage("page.html");
    };

    $scope.move2SkillDetail = function(){
      console.log("move!! skill");
      myNavigator.pushPage("view/userdetail.html");
    };

    $scope.getSkillSet = function(){

      console.log("getSkillSet");

      SkillSetService.getSkillSetWithCondition($scope.search_conditions)
        .then(function(res){
          //$scope.items = angular.copy(res.item || []);
          $scope.items = (res.item || []).map(function(v){
            return v;
          });
        });
    };

    // 廃止予定
    $scope.getAll = function(){
      console.log("getAll");

      console.log("----------getAll start----------");
      SkillSetService.getData()
        .then(function(res){
          console.log("----------getAll#SkillSetService then start----------");
          console.log(res);

          $scope.items = res.item || [];

          console.log("----------getAll#SkillSetService then   end----------");
        })
      console.log("----------getAll   end----------");
    };
  });
})();
