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

        // SkillSheet情報を取得する
        this.getSkillSheetByUserId = function(user_id){
          return $http.get("php/router.php/skillsheet/" + user_id)
            .then(function(response){
              return response.data || [];
            });
        };

        // SkillSheet情報を更新する
        this.updateSkillSheet = function(skill_list){

          var postData = 'skillsheet_data=' + JSON.stringify(skill_list);
          return $http({
              method : "POST",
              url : "php/router.php/skillsheet/",
              data: postData,
              headers : {'Content-Type': 'application/x-www-form-urlencoded'}
            })
              .then(function(response){
                return response.data || [];
              })
          ;
        };

        // Master情報を取得する
        this.getAllMasterInfo = function(){
          return $http.get("php/router.php/master/")
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



        // テスト用です。
        this.doTest = function(){
          return $http.get("php/router.php/test")
            .then(function(response){
              console.log(response);
              alert("test done");
            });
        }

    });
})();
