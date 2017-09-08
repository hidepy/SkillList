<?php
// 全ユーザ対象にセッションを開始する
session_start();

// セッションはこんな感じで使う
$_SESSION["imtasokori"] = "test";

$accept_display = 0;
if(!empty($_SERVER["HTTPS"])){
  $accept_display = 1;
}
// local用
$accept_display = 1;

// ログインユーザID
$user_id = $_SERVER['REMOTE_USER'];
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
    <script src="js/controller/HeaderController.js"></script>
    <script src="js/controller/DetailController.js"></script>
  </head>

  <body ng-controller="RootController" ng-init="init(<?php echo $accept_display ?>)">
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
        <p>powered by Angular.js + OnsenUI<button ng-click="callTest()">TEST</button></p>
        <p>hideyuki.kawamura(379) created @2017/08/20</p>
      </div>
    </ons-bottom-toolbar>

    <div id="initial-screen">
      <?php
      if($accept_display == 1){
        echo '
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
        ';
      }
      else{
        echo "<div>HTTPでのアクセスは許可されていません. HTTPSでアクセスしてください</div>";
      }
      ?>
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
