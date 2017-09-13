CREATE TABLE `m_depart` (
  `depart_id` varchar(2) NOT NULL,
  `depart_name` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`depart_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8

depart_id	depart_name
01	ss
02	ks


CREATE TABLE `m_skill` (
  `skill_id` varchar(6) NOT NULL,
  `skill_name` varchar(30) DEFAULT NULL,
  `type` char(1) DEFAULT NULL,
  `rel_id` varchar(6) DEFAULT NULL,
  PRIMARY KEY (`skill_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8

skill_id	skill_name	type	rel_id
000010	Java	L	?(NULL)?
000011	Groovy	L	?(NULL)?
000012	Kotlin	L	?(NULL)?
000013	Scala	L	?(NULL)?
000020	C	L	?(NULL)?
000030	C++	L	?(NULL)?
000040	CÅî	L	?(NULL)?
000050	PHP	L	?(NULL)?
000060	Ruby	L	?(NULL)?
000070	Python	L	?(NULL)?
000080	Perl	L	?(NULL)?
000090	Objective-C	L	?(NULL)?
000100	Swift	L	?(NULL)?
000110	VB6.0	L	?(NULL)?
000111	VB.net	L	?(NULL)?
000120	Go	L	?(NULL)?
000130	JavaScript	L	?(NULL)?
000131	TypeScript	L	?(NULL)?
000132	CofeeScript	L	?(NULL)?
000133	JSCript	L	?(NULL)?
000134	Node.js	L	?(NULL)?
000140	HTML+CSS	L	?(NULL)?
000141	HTML5	L	?(NULL)?
000142	CSS3	L	?(NULL)?
000143	Sass	L	?(NULL)?
000150	SQL	L	?(NULL)?
000160	COBOL	L	?(NULL)?
000170	ABAP	L	?(NULL)?
D00010	Oracle Database	D	?(NULL)?
D00020	MySQL	D	?(NULL)?
D00030	PostgreSQL	D	?(NULL)?
D00040	SQLite	D	?(NULL)?
D00050	SQL Server	D	?(NULL)?
D00060	DB2	D	?(NULL)?
D00070	MongoDB	D	?(NULL)?
E00010	Apache	E	?(NULL)?
E00020	Tomcat	E	?(NULL)?
E00030	nginx	E	?(NULL)?
E00040	WEBrick	E	?(NULL)?
E00050	thin	E	?(NULL)?
E00060	Unicorn	E	?(NULL)?
E00070	Passenger	E	?(NULL)?
E00080	Zope	E	?(NULL)?
E00090	Hadoop	E	?(NULL)?
E00100	Redis	E	?(NULL)?
E00110	memcached	E	?(NULL)?
E00120	Amazon Web Service	E	?(NULL)?
E00130	Microsoft Azure	E	?(NULL)?
E00140	Google Cloud Platform	E	?(NULL)?
E00150	WordPress	E	?(NULL)?
E00160	Emacs	E	?(NULL)?
E00170	Eclipse	E	?(NULL)?
E00180	CVS	E	?(NULL)?
E00190	Subversion	E	?(NULL)?
E00200	Git	E	?(NULL)?
E00210	Jenkins	E	?(NULL)?
E00220	TravisCI	E	?(NULL)?
E00230	CircleCI	E	?(NULL)?
E00240	Bamboo	E	?(NULL)?
E00250	Trac	E	?(NULL)?
E00260	Redmine	E	?(NULL)?
F01001	Struts	F	000010
F01002	Seasar2	F	000010
F01003	JSF	F	000010
F01004	Spring	F	000010
F01005	Play Framework	F	000010
F03001	cocos2d	F	000030
F04001	Unity	F	000040
F05001	Symfony	F	000050
F05002	CakePHP	F	000050
F05003	CodeIgniter	F	000050
F05004	FuelPHP	F	000050
F05005	Zend Framework	F	000050
F05006	Ethna	F	000050
F05007	Smarty	F	000050
F05008	Laravel	F	000050
F06001	Ruby on Rails	F	000060
F07001	Django	F	000070
F13001	jQuery	F	000130
F13002	AngularJS	F	000130
F13003	prototype.js	F	000130
F13004	Express	F	000130
F13005	Dojo Toolkit	F	000130
F13006	Titanium Mobile	F	000130
F13007	React	F	000130
F13008	Bootstrap	F	000130
F13009	Cordova	F	000130
F13010	OnsenUI	F	000130
Fxx001	DirectX	F	
Fxx002	OpenGL	F	
Fxx003	iOS SDK	F	
Fxx004	Android SDK	F	
O00010	Linux	O	?(NULL)?
O00011	Cent OS	O	?(NULL)?
O00012	Debian	O	?(NULL)?
O00013	Red Hat	O	?(NULL)?
O00020	UNIX	O	?(NULL)?
O00021	Solaris	O	?(NULL)?
O00022	FreeBSD	O	?(NULL)?
O00030	Mac OS X	O	?(NULL)?
O00040	Windows	O	?(NULL)?
O00041	Windows Server	O	?(NULL)?


CREATE TABLE `m_user` (
  `id` varchar(6) NOT NULL,
  `name` varchar(30) DEFAULT NULL,
  `depart` varchar(2) DEFAULT NULL,
  `is_midcarreer` char(1) DEFAULT NULL,
  `nendo` char(4) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8

id	name	depart	is_midcarreer	nendo
379	h.kawamura	01	0	2013
380	s.sato	01	0	2013
381	k.miko	02	0	2013


CREATE TABLE `m_userskill` (
  `user_id` varchar(6) NOT NULL,
  `skill_id` varchar(6) NOT NULL,
  `skill_level` tinyint(4) DEFAULT '0',
  `acquire_ym` varchar(6) DEFAULT NULL,
  PRIMARY KEY (`user_id`,`skill_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8

user_id	skill_id	skill_level	acquire_ym
379	000010	4	?(NULL)?
379	000020	3	?(NULL)?
379	000040	4	?(NULL)?
379	000050	4	?(NULL)?
379	000110	3	?(NULL)?
379	000111	3	?(NULL)?
379	000130	4	?(NULL)?
379	000131	3	?(NULL)?
379	000133	3	?(NULL)?
379	000134	3	?(NULL)?
379	000140	4	?(NULL)?
379	000141	3	?(NULL)?
379	000142	3	?(NULL)?
379	000150	3	?(NULL)?
380	000010	4	?(NULL)?
381	000010	3	?(NULL)?
381	000040	4	?(NULL)?
