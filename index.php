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

    <link rel="stylesheet" href="lib/onsen/css/onsenui.css">
    <link rel="stylesheet" href="lib/onsen/css/onsen-css-components-blue-basic-theme.css">

    <!-- Angular core -->
    <script src="lib/onsen/js/angular/angular.js"></script>
    <script src="lib/onsen/js/onsenui.js"></script>

    <!-- Define SKILL-LIST-APP module -->
    <script src="js/main.js"></script>
    <!-- Directive -->

    <!-- Controller -->
    <script src="js/controller/HeaderController.js"></script>
    <script src="js/controller/UserDetailController.js"></script>
  </head>

  <body ng-controller="RootController">
    <!-- Entry View Page -->
    <ons-navigator var="myNavigator" page="view/main.html"></ons-navigator>
  </body>
</html>
