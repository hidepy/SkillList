<?php
// 全ユーザ対象にセッションを開始する
session_start();

// セッションはこんな感じで使う
$_SESSION["imtasokori"] = "test";
?>

<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">

    <title>MYTEST</title>

    <link rel="stylesheet" href="lib/onsen/css/onsenui.css" />
    <link rel="stylesheet" href="lib/onsen/css/onsen-css-components-blue-basic-theme.css" />

    <link rel="stylesheet" href="css/main.css" />

    <script src="js/common/commonFunctions.js"></script>

    <!-- Angular core -->
    <script src="lib/onsen/js/angular/angular.js"></script>
    <script src="lib/onsen/js/onsenui.js"></script>

    <!-- Define SKILL-LIST-APP module -->
    <script src="js/main.js"></script>
    <!-- Directive -->

    <!-- Service -->
    <script src="js/service/SkillSetService.js"></script>

    <!-- Controller -->
    <script src="js/controller/RootController.js"></script>
    <script src="js/controller/HeaderController.js"></script>
    <script src="js/controller/DetailController.js"></script>
  </head>

  <body ng-controller="RootController" ng-init="init()">
    <!-- Entry View Page -->
    <ons-navigator var="myNavigator" page="view/main.html"></ons-navigator>

    <ons-toolbar id="h-head-toolbar">
        <div class="left">
          <h2>スキルリストが一覧で見れるよっ！</h2>
        </div>
        <div class="right">
          <ons-toolbar-button ng-click='myNavigator.pushPage("favorite.html")'><ons-icon icon="ion-android-star" style="font-size: 32px"></ons-icon></ons-toolbar-button>
          <ons-toolbar-button ng-click='myNavigator.pushPage("page_setting_search_condition.html")'><ons-icon icon="ion-ios-search-strong" style="font-size: 32px"></ons-icon></ons-toolbar-button>
        </div>
    </ons-toolbar>
  </body>
</html>
