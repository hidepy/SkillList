(function(){
    'use strict';

    // load event
    // normal javascript
    document.addEventListener("DOMContentLoaded", function(){
        window.CommonFunctions = {
            formatDate: function(date){
                return ("" + date.getFullYear() + ("00" + (date.getMonth() + 1)).slice(-2) + ("00" + date.getDate()).slice(-2) + ("00" + date.getHours()).slice(-2) + ("00" + date.getMinutes()).slice(-2) + ("00" + date.getSeconds()).slice(-2) );
            },
            isEmpty: function(s){
                return (s == null) || (s == undefined) || (s == "");
            }
        };
        // このあたりで初回ロードか否かを判定するためのlocalStorageをセットしておく

        angular.bootstrap(document, ['SKILL-LIST-APP']);

        angular.element("#initial-view").animate({
            opacity: 0
        }, 1000)
        .queue(function(){
            this.remove();
        })

    });

    // angular module setup
   angular.module('SKILL-LIST-APP', ['ngRoute'])
        // Module 設定
        .config(function($routeProvider, $locationProvider){
            $routeProvider
                .when("/", {
                    templateUrl: "js/view/main.html",
                    controller: "HeaderController"
                })
                .when("/userdetail/:user_id", {
                    templateUrl: "js/view/user-detail.html",
                    controller: "UserDetailController"
                })
                .otherwise({
                    redirectTo: "/"
                });
            $locationProvider.hashPrefix('');
            $locationProvider.html5Mode(true);
        })
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

            /*
            this.getData = function(param){
              return $http.get("php/router.php/skillset")
                .then(function(response_wrapper){

                  console.log("in http#get");
                  console.log(response_wrapper);

                  console.log("before http#get return");

                  return response_wrapper.data || [];
                }
              );
            }
            */
        });
})();
