<?php
// 全ユーザ対象にセッションを開始する
session_start();

// ログインユーザID
$user_id = $_SERVER['REMOTE_USER'];

$_SESSION["is_admin"] = 0;

// ここは好みで別のユーザに, 又はiniファイル等外出ししてください
if($user_id == "admin"){
  $_SESSION["is_admin"] = 1;
}

$is_admin = 1;
$is_https = 1; // とりあえず

/*
if(empty($user_id)){
  echo "<p>ユーザのログインが必要です</p>";
  exit;
}
*/

/*
2017/09/12 残 追加機能

マスタ更新(スキル本体, 部署, ユーザ)(admin限定)

ユーザ情報更新(初回ログイン時に)

スキル本体のエラーケース処理フロー

*/
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
    <script src="js/controller/SkillSheetController.js"></script>
    <script src="js/controller/MasterController.js"></script>
    <script src="js/controller/HeaderController.js"></script>
    <script src="js/controller/DetailController.js"></script>
  </head>

  <body ng-controller="RootController" ng-init="init(<?php echo $is_https . "," . $is_admin ?>)">
    <!-- Entry View Page -->
    <ons-navigator var="myNavigator"></ons-navigator>

    <ons-toolbar id="h-head-toolbar" class="toolbar toolbar--material ">
        <div class="toolbar--material__left left">
          スキルポータル(β) for <?php echo $user_id; ?>
        </div>
        <div class="toolbar--material__right right">
          <ons-toolbar-button ng-click='myNavigator.resetToPage("view/home.html")'>
            <ons-icon icon="md-home" size="32px" style="color: #fff"></ons-icon>
          </ons-toolbar-button>
          <ons-toolbar-button ng-click='myNavigator.resetToPage("view/main.html")'>
            <ons-icon icon="search" size="32px" style="color: #fff"></ons-icon>
          </ons-toolbar-button>
        </div>
    </ons-toolbar>

    <ons-bottom-toolbar id="h-foot-toolbar">
      <div>
        <p>powered by Angular.js + OnsenUI. created by hidetaso</p>
      </div>
    </ons-bottom-toolbar>

    <div id="initial-screen">
      <div>
        <span>
          Now Loading...
        </span>
        <svg class="progress-circular progress-circular--indeterminate">
          <circle class="progress-circular__background"/>
          <circle class="progress-circular__primary progress-circular--indeterminate__primary"/>
          <circle class="progress-circular__secondary progress-circular--indeterminate__secondary"/>
        </svg>
      </div>
    </div>

    <!-- modal -->
    <ons-modal var="modal">
      <ons-icon icon="ion-load-c" spin="true"></ons-icon>
      Please wait...
    </ons-modal>

    <!-- alert dialog -->
    <ons-alert-dialog var="alertDialog">
      <div class="alert-dialog-content alert-dialog-content--material">
        <span id="alertDialog-content"></span>
      </div>
      <div class="alert-dialog-footer alert-dialog-footer--material">
        <button class="alert-dialog-button alert-dialog-button--material" ng-click="alertDialog.hide()">close</button>
      </div>
    </ons-alert-dialog>

  </body>
</html>
