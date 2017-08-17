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

  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css" integrity="sha384-rwoIResjU2yc3z8GV/NPeZWAv56rSmLldC3R/AZzGRnGxQQKnKkoFVhFQhNUwEyJ" crossorigin="anonymous">

  <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js" integrity="sha384-DztdAPBWPRXSA/3eYEEUWrWCy7G5KFbe8fFjk5JAIxUYHKkDx6Qin1DkWx51bBrb" crossorigin="anonymous"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/js/bootstrap.min.js" integrity="sha384-vBWWzlZJ8ea9aCX4pEW3rVHjgjt7zpkNpZk+02D9phzyeVkE+jo0ieGizqPLForn" crossorigin="anonymous"></script>

  <base href="/skill_list/">
</head>

<body ng-controller="RootController">

  <div id="initial-view" style="position: absolute; text-align: center; width: 100%; height: 100%; display: table;">
    <p style="display: table-cell; vertical-align: middle; ">Loading...</p>
  </div>

  <div id="contents" ng-view autoscroll="true"></div>

  <!-- Angular Cmmmons -->
  <!-- Angular core -->
  <script src="lib/angular/angular.js"></script>
  <script src="lib/angular/angular-route.js"></script>
  <!-- Services -->

  <!-- APP specific -->
  <!-- Define MHM-APP module -->
  <script src="js/main.js"></script>
  <!-- Directive -->

  <!-- Controller -->
  <script src="js/controller/HeaderController.js"></script>
  <script src="js/controller/UserDetailController.js"></script>

</body>
</html>
