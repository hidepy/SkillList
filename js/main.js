(function(){
    'use strict';

    // angular module setup
   //angular.module('SKILL-LIST-APP', [])
   ons.bootstrap('SKILL-LIST-APP', [])
        // 最親のController
        .controller('RootController', function($scope, $location, $timeout){

            // メッセージ表示に関するobject
            $scope.message_info = {
                message: "",
                show: false,
                status: ""
            };

            // ---------- methods -------i---
            $scope.showMessage = function(message, status){
                $scope.message_info.message = message;
                $scope.message_info.status = status || "alert-info";
                $scope.message_info.show = true;
                $timeout(function(){
                    $scope.message_info.show = false;
                }, 3000);

            };
            $scope.move = function(path, param){
                $location.path(path).search(param || {});
            };
            $scope.getCurrentPage = function(){
                return $location.path();
            };
        })
        // 改行をbrに変換する
        .filter('conv2br', function() {
            return function(s){
                return s ? s.replace(/\/br/g, "\n") : s;
            };
        })
        // Header-Detail画面で値のやり取りに使用. 既に検索しているheader情報や選択しているindexの値を保持する
        .service("CurrentState", function(){
            this.items = [];
            this.search_conditions = {};
        })
        // 外部Moduleにする必要はないからね.
        .service("SkillSetService", function($http, $httpParamSerializer){
            // paramsに従って検索条件を組みたててREST APIを叩く
            this.getSkillSetWithCondition = function(params){
              console.log(params);
              return $http.get("php/router.php/skillset?" + $httpParamSerializer(params))
                .then(function(response){
                  return response.data || [];
                });
            };

        });
})();
