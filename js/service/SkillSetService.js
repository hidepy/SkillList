(function(){
  'use strict';

  angular.module('SKILL-LIST-APP')
    .service("SkillSetService", function($http, $httpParamSerializer){
        // paramsに従って検索条件を組みたててREST APIを叩く

        // User-Skill情報を取得する
        this.getSkillSetWithCondition = function(params){
          return $http.get("php/router.php/skillset?" + $httpParamSerializer(params))
            .then(function(response){
              return response.data || [];
            });
        };

        this.getAllMasterInfo = function(){
          return $http.get("php/router.php/master")
            .then(function(response){
              return response.data || [];
            });
        };

        this.getUserInfo = function(params){
          return $http.get("php/router.php/master/user?" + $httpParamSerializer(params))
            .then(function(response){
              return response.data || [];
            });
        };

        this.getSkillInfo = function(params){
          return $http.get("php/router.php/master/skill?" + $httpParamSerializer(params))
            .then(function(response){
              return response.data || [];
            });
        };

    });
})();
