(function(){
  'use strict';

  angular.module('SKILL-LIST-APP')
    .service("SkillSheetService", function($http, $httpParamSerializer){

        this.getSkillSheetMaster = function(params){
          return new Promise(function(resolve, reject){
            resolve({
              lang: [
                {id: "000010", name: "Java"},
                {id: "000011", name: "Groovy"},
                {id: "000012", name: "Kotlin"},
                {id: "000013", name: "Scala"},
                {id: "000020", name: "C"},
                {id: "000030", name: "C++"},
                {id: "000040", name: "C＃"},
                {id: "000050", name: "PHP"},
                {id: "000060", name: "Ruby"},
                {id: "000070", name: "Python"},
                {id: "000080", name: "Perl"},
                {id: "000090", name: "Objective-C"},
                {id: "000100", name: "Swift"},
                {id: "000110", name: "VB6.0"},
                {id: "000111", name: "VB.net"},
                {id: "000120", name: "Go"},
                {id: "000130", name: "JavaScript"},
                {id: "000131", name: "TypeScript"},
                {id: "000132", name: "CofeeScript"},
                {id: "000133", name: "JSCript"},
                {id: "000134", name: "Node.js"},
                {id: "000140", name: "HTML+CSS"},
                {id: "000141", name: "HTML5"},
                {id: "000142", name: "CSS3"},
                {id: "000143", name: "Sass"},
                {id: "000150", name: "SQL"},
                {id: "000160", name: "COBOL"},
                {id: "000170", name: "ABAP"}
              ],
              db: [
                {id: "D00010", name: "Oracle Database"},
                {id: "D00020", name: "MySQL"},
                {id: "D00030", name: "PostgreSQL"},
                {id: "D00040", name: "SQLite"},
                {id: "D00050", name: "SQL Server"},
                {id: "D00060", name: "DB2"},
                {id: "D00070", name: "MongoDB"}
              ],
              os: [
                {id: "O00010", name:"Linux"},
                {id: "O00011", name:"Cent OS"},
                {id: "O00012", name:"Debian"},
                {id: "O00013", name:"Red Hat"},
                {id: "O00020", name:"UNIX"},
                {id: "O00021", name:"Solaris"},
                {id: "O00022", name:"FreeBSD"},
                {id: "O00030", name:"Mac OS X"},
                {id: "O00040", name:"Windows"},
                {id: "O00041", name:"Windows Server"}
              ]
            });
          });
        };


    });
})();
