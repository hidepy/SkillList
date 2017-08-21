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

    <title>Skill List</title>

    <link rel="stylesheet" href="lib/onsen/css/onsenui.css" />
    <link rel="stylesheet" href="lib/onsen/css/onsen-css-components.css" />

    <link rel="stylesheet" href="css/main.css" />

    <script src="js/common/commonFunctions.js"></script>

    <!-- Angular core -->
    <script src="lib/onsen/js/angular/angular.js"></script>
    <script src="lib/onsen/js/onsenui.js"></script>
    <script src="lib/onsen/js/angular-onsenui.js"></script>

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
    <ons-navigator var="myNavigator"></ons-navigator>

    Now Loading...

    <ons-toolbar id="h-head-toolbar">
        <div class="left">
          <h2 >Skill List</h2>
        </div>
        <div class="right">
          <ons-toolbar-button ng-click='myNavigator.resetToPage("view/main.html")'><ons-icon icon="md-home" size="32px"></ons-icon></ons-toolbar-button>
        </div>
    </ons-toolbar>
  </body>
</html>
