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

  <title><?php echo ($is_admin_user ? "MyHistoryMap" : "日本の絶景マップ｜写真と地図で本当の絶景が一目で分かる！") ?></title>
  <meta name="description" content="実際に訪れた日本の絶景を写真と地図で見やすく紹介しています。旅行で訪れる地域の周辺スポットの検索や、「富士山ビュースポット」「桜のビュースポット」など目的別スポット検索などもできます。" />
  <meta property="og:image"  content="http://tasokori.net/wp/wp-content/uploads/2017/06/zekkei-circle.png">
  <meta name="twitter:image" content="http://tasokori.net/wp/wp-content/uploads/2017/06/zekkei-circle.png">
  <meta itemprop="image"     content="http://tasokori.net/wp/wp-content/uploads/2017/06/zekkei-circle.png">


  <link rel="stylesheet" type="text/css" href="css/my_history_map.css">
  <link rel="stylesheet" type="text/css" href="lib/lightbox/css/lightbox.css">
  <link rel="stylesheet" href="lib/slick/slick.css">
  <link rel="stylesheet" href="lib/slick/slick-theme.css">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css" integrity="sha384-rwoIResjU2yc3z8GV/NPeZWAv56rSmLldC3R/AZzGRnGxQQKnKkoFVhFQhNUwEyJ" crossorigin="anonymous">

  <link href="//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css" rel="stylesheet">

  <script src="http://maps.googleapis.com/maps/api/js?key=AIzaSyAC5TnApJHV0fXpLJ7NyEsrKevtWEefP_M"></script>
  <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js" integrity="sha384-DztdAPBWPRXSA/3eYEEUWrWCy7G5KFbe8fFjk5JAIxUYHKkDx6Qin1DkWx51bBrb" crossorigin="anonymous"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/js/bootstrap.min.js" integrity="sha384-vBWWzlZJ8ea9aCX4pEW3rVHjgjt7zpkNpZk+02D9phzyeVkE+jo0ieGizqPLForn" crossorigin="anonymous"></script>
  <script type="text/javascript" src="js/storageManager.js"></script>

  <style>
  /*
  body{
    opacity: 0.15;
  }
  img{
    opacity: 0.4 !important;
  }
  .panel_img{
    opacity: 0.4 !important;
  }
  */

  #search-cond-disp-area{
    overflow: hidden;
    text-overflow: ellipsis;
  }
  .searchcond-showall{
    overflow: visible;
  }
  .searchcond-hide{
    overflow: hidden;
    max-height: 1.2em;
  }

  </style>

  <base href="/webapps/zekkei-map/">
</head>

<body ng-controller="RootController">

  <div id="initial-view" style="position: absolute; text-align: center; width: 100%; height: 100%; display: table;">
    <p style="display: table-cell; vertical-align: middle; ">Loading...<img src="../common/img/support-loading.gif" style="width: 24px;"></p>
  </div>

  <?php
  if($is_admin_user){
    echo '<admin-memo memo-master-name="MHM-memo" access-key="myts"></admin-memo>';
  }
  ?>

  <nav-header <?php if($is_admin_user){ echo "is-admin";} ?>></nav-header>

  <div id="contents" ng-view autoscroll="true"></div>

  <!-- normal js liblalies -->
  <script src="lib/lightbox/js/lightbox.js"></script>
  <!-- Angular Cmmmons -->
  <!-- Angular core -->
  <script src="lib/angular/angular.js"></script>
  <script src="lib/angular/angular-route.js"></script>
  <!-- Angular libs-->
  <script src="lib/slick/slick.js"></script>
  <script src="lib/slick/angular-slick.js"></script>
  <!-- Services -->
  <script src="js/service/MapHandlerService.js"></script>
  <!-- APP specific -->
  <!-- Define MHM-APP module -->
  <script src="js/main.js"></script>
  <!-- Directive -->
  <script src="js/directive/navSearch.js"></script>
  <?php
  if(!$is_admin_user){
    echo '<script src="js/directive/adsense.js"></script>';
  }
  else{
    echo '<script src="js/directive/no-adsense.js"></script>';
    echo '<script src="js/directive/_admin-memo.js"></script>';
    echo '<script src="js/directive/_regist-comment.js"></script>';
    echo '<script src="https://apis.google.com/js/api.js"></script>';
    echo '<script src="js/SheetsManager.js"></script>';
  }
  ?>
  <!-- Controller -->
  <script src="js/controller/HeaderController.js"></script>
  <script src="js/controller/DetailController.js"></script>

</body>
</html>
