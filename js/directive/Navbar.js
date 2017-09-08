(function(){
    'use strict';

	angular.module('SKILL-LIST-APP')
		.directive("navbar", function(){
            return {
                template:   '<ons-toolbar id="h-head-toolbar" class="toolbar toolbar--material ">   <div class="toolbar--material__left left">   <ons-button ng-click="myNavigator.popPage()">   </div>   <div class="toolbar--material__right right">   <ons-toolbar-button ng-click=\'myNavigator.resetToPage("view/home.html")\'>   <ons-icon icon="md-home" size="32px" style="color: #fff"></ons-icon>   </ons-toolbar-button>   <ons-toolbar-button ng-click=\'myNavigator.resetToPage("view/main.html")\'>   <ons-icon icon="search" size="32px" style="color: #fff"></ons-icon>   </ons-toolbar-button>   </div>   </ons-toolbar>'
            };
        });
})();
