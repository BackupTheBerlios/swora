<?
if(is_array($_GET)) 	{foreach($_GET as $a=>$b) 	{$GLOBALS[$a] = $b;}}
if(is_array($_POST)) 	{foreach($_POST as $a=>$b) 	{$GLOBALS[$a] = $b;}}
if(is_array($_COOKIE)) 	{foreach($_COOKIE as $a=>$b) 	{$GLOBALS[$a] = $b;}}
if(is_array($_SERVER)) 	{foreach($_SERVER as $a=>$b) 	{$GLOBALS[$a] = $b;}}
if(is_array($_ENV)) 	{foreach($_ENV as $a=>$b) 	{$GLOBALS[$a] = $b;}}

////////////////////////////
// SQL TABLES-STRUCTS
////////////////////////////
	$create[avatar] 	 = "CREATE TABLE `\$form[avatar]` (";
	$create[avatar] 	.= "  `id` int(30) NOT NULL auto_increment,";
	$create[avatar] 	.= "  `userid` int(30) NOT NULL default '0',";
	$create[avatar] 	.= "  `path` varchar(255) NOT NULL default '0',";
	$create[avatar] 	.= "  PRIMARY KEY  (`id`)";
	$create[avatar] 	.= ")";
	$create[config] 	 = "CREATE TABLE `\$form[config]` (";
	$create[config] 	.= "  `name` varchar(255) NOT NULL,";
	$create[config] 	.= "  `value` text NOT NULL,";
	$create[config] 	.= "  PRIMARY KEY  (`name`)";
	$create[config] 	.= ")";
	$create[error]		 = "CREATE TABLE `\$form[error]` (";
	$create[error]		.= "  `id` int(30) NOT NULL auto_increment,";
	$create[error]		.= "  `request_uri` varchar(255) NOT NULL default '',";
	$create[error]		.= "  `scriptname` varchar(255) NOT NULL default '',";
	$create[error]		.= "  `line` varchar(255) NOT NULL default '',";
	$create[error]		.= "  `text` text NOT NULL,";
	$create[error]		.= "  PRIMARY KEY  (`id`)";
	$create[error]		.= ")";
	$create[loader]		.= "CREATE TABLE `\$form[loader]` (";
	$create[loader]		.= "  `id` int(30) NOT NULL auto_increment,";
	$create[loader]		.= "  `title` varchar(255) NOT NULL default '',";
	$create[loader]		.= "  `file` varchar(255) NOT NULL default '',";
	$create[loader]		.= "  `mode` int(1) NOT NULL default '',";
	$create[loader]		.= "  `loads` int(10) NOT NULL default '0',";
	$create[loader]		.= "  PRIMARY KEY  (`id`)";
	$create[loader]		.= ")";
	$create[menu]		.= "CREATE TABLE `\$form[menu]` (";
	$create[menu]		.= "  `id` int(30) NOT NULL auto_increment,";
	$create[menu]		.= "  `mode` varchar(255) NOT NULL default '',";
	$create[menu]		.= "  `position` int(2) NOT NULL default '0',";
	$create[menu]		.= "  `sub_cat` int(30) NOT NULL default '0',";
	$create[menu]		.= "  `title` varchar(255) NOT NULL default '',";
	$create[menu]		.= "  `target` varchar(255) NOT NULL default '',";
	$create[menu]		.= "  `link` varchar(255) NOT NULL default '',";
	$create[menu]		.= "  `html` text NOT NULL,";
	$create[menu]		.= "  `script` varchar(255) NOT NULL default '',";
	$create[menu]		.= "  `sort` int(5) NOT NULL default '0',";
	$create[menu]		.= "  `showonlylogin` int(1) NOT NULL default '0',";
	$create[menu]		.= "  `showonlylogout` int(1) NOT NULL default '0',";
	$create[menu]		.= "  PRIMARY KEY  (`id`)";
	$create[menu]		.= ")";
	$create[news]		.= "CREATE TABLE `\$form[news]` (";
	$create[news]		.= "  `id` int(30) NOT NULL auto_increment,";
	$create[news]		.= "  `autid` int(30) NOT NULL default '0',";
	$create[news]		.= "  `date` int(15) NOT NULL default '0',";
	$create[news]		.= "  `title` varchar(255) NOT NULL default '',";
	$create[news]		.= "  `text` text NOT NULL,";
	$create[news]		.= "  `smilies` int(1) NOT NULL default '0',";
	$create[news]		.= "  `html` int(1) NOT NULL default '0',";
	$create[news]		.= "  `activated` int(1) NOT NULL default '0',";
	$create[news]		.= "  `blocked` int(1) NOT NULL default '0',";
	$create[news]		.= "  PRIMARY KEY  (`id`)";
	$create[news]		.= ")";
	$create[news_comments]	.= "CREATE TABLE `\$form[news_comments]` (";
	$create[news_comments]	.= "  `id` int(30) NOT NULL auto_increment,";
	$create[news_comments]	.= "  `newsid` int(30) NOT NULL default '0',";
	$create[news_comments]	.= "  `autname` varchar(255) NOT NULL default '',";
	$create[news_comments]	.= "  `title` varchar(255) NOT NULL default '',";
	$create[news_comments]	.= "  `text` text NOT NULL,";
	$create[news_comments]	.= "  `date` int(15) NOT NULL default '0',";
	$create[news_comments]	.= "  PRIMARY KEY  (`id`)";
	$create[news_comments]	.= ")";
	$create[onlinemessages]	.= "CREATE TABLE `\$form[onlinemessages]` (";
	$create[onlinemessages]	.= "  `id` int(30) NOT NULL auto_increment,";
	$create[onlinemessages]	.= "  `aut_id` int(30) NOT NULL default '0',";
	$create[onlinemessages]	.= "  `time` int(15) NOT NULL default '0',";
	$create[onlinemessages]	.= "  `touserid` int(30) NOT NULL default '0',";
	$create[onlinemessages]	.= "  `text` text NOT NULL,";
	$create[onlinemessages]	.= "  `send` int(1) NOT NULL default '0',";
	$create[onlinemessages]	.= "  PRIMARY KEY  (`id`)";
	$create[onlinemessages]	.= ")";
	$create[right]		.= "CREATE TABLE `\$form[right]` (";
	$create[right]		.= "  `id` int(30) NOT NULL auto_increment,";
	$create[right]		.= "  `userid` int(30) NOT NULL default '0',";
	$create[right]		.= "  `section` int(30) NOT NULL default '0',";
	$create[right]		.= "  `boardid` int(30) NOT NULL default '0',";
	$create[right]		.= "  `is_admin` int(1) NOT NULL default '0',";
	$create[right]		.= "  `activ` int(1) NOT NULL default '0',";
	$create[right]		.= "  `settime` int(14) NOT NULL default '0',";
	$create[right]		.= "  PRIMARY KEY  (`id`)";
	$create[right]		.= ")";
	$create[sections]	.= "CREATE TABLE `\$form[sections]` (";
	$create[sections]	.= "  `id` int(10) NOT NULL auto_increment,";
	$create[sections]	.= "  `title` varchar(255) NOT NULL default '',";
	$create[sections]	.= "  `name` varchar(255) NOT NULL default '',";
	$create[sections]	.= "  `active` int(1) NOT NULL default '0',";
	$create[sections]	.= "  PRIMARY KEY  (`id`)";
	$create[sections]	.= ")";
	$create[smilies]	.= "CREATE TABLE `\$form[smilies]` (";
	$create[smilies]	.= "  `id` int(30) NOT NULL auto_increment,";
	$create[smilies]	.= "  `sfile` varchar(255) NOT NULL default '',";
	$create[smilies]	.= "  `stext` varchar(255) NOT NULL default '',";
	$create[smilies]	.= "  PRIMARY KEY  (`id`)";
	$create[smilies]	.= ")";
	$create[tmps]		.= "CREATE TABLE `\$form[tmps]` (";
	$create[tmps]		.= "  `id` int(30) NOT NULL auto_increment,";
	$create[tmps]		.= "  `name` varchar(255) NOT NULL default '',";
	$create[tmps]		.= "  `parama` varchar(255) NOT NULL default '',";
	$create[tmps]		.= "  `paramb` varchar(255) NOT NULL default '',";
	$create[tmps]		.= "  `paramc` text NOT NULL,";
	$create[tmps]		.= "  PRIMARY KEY  (`id`)";
	$create[tmps]		.= ")";
	$create[traffic]	.= "CREATE TABLE `\$form[traffic]` (";
	$create[traffic]	.= "  `traffic` double NOT NULL default '0',";
	$create[traffic]	.= "  `date` int(30) NOT NULL default '0'";
	$create[traffic]	.= ")";
	$create[user]		.= "	CREATE TABLE `\$form[user]` (";
	$create[user]		.= "id int(30) NOT NULL auto_increment,";
	$create[user]		.= "user_login varchar(30) NOT NULL default '',";
	$create[user]		.= "user_password varchar(255) NOT NULL default '',";
	$create[user]		.= "user_name varchar(255) NOT NULL default '',";
	$create[user]		.= "user_email varchar(30) NOT NULL default '',";
	$create[user]		.= "user_icq varchar(20) NOT NULL default '',";
	$create[user]		.= "user_aim varchar(20) NOT NULL default '',";
	$create[user]		.= "user_yim varchar(20) NOT NULL default '',";
	$create[user]		.= "user_hp varchar(255) NOT NULL default '',";
	$create[user]		.= "user_interests text NOT NULL,";
	$create[user]		.= "user_signatur text NOT NULL,";
	$create[user]		.= "user_text text NOT NULL,";
	$create[user]		.= "user_birth int(15) NOT NULL default '0',";
	$create[user]		.= "user_location varchar(255) NOT NULL default '',";
	$create[user]		.= "user_work varchar(255) NOT NULL default '',";
	$create[user]		.= "user_gender int(1) NOT NULL default '0',";
	$create[user]		.= "reg_email varchar(30) NOT NULL default '',";
	$create[user]		.= "reg_date int(15) NOT NULL default '0',";
	$create[user]		.= "last_activ int(15) NOT NULL default '0',";
	$create[user]		.= "last_forum_read int(15) NOT NULL default '0',";
	$create[user]		.= "points int(30) NOT NULL default '0',";
	$create[user]		.= "rate_points int(30) NOT NULL default '0',";
	$create[user]		.= "rate_count int(20) NOT NULL default '0',";
	$create[user]		.= "blocked int(1) NOT NULL default '0',";
	$create[user]		.= "activated int(1) NOT NULL default '0',";
	$create[user]		.= "activation_code varchar(25) NOT NULL default '',";
	$create[user]		.= "logins int(30) NOT NULL default '0',";
	$create[user]		.= "uin varchar(50) NOT NULL default '',";
	$create[user]		.= "lostpassword varchar(25) NOT NULL default '',";
	$create[user]		.= "md5 int(1) NOT NULL default '0',";
	$create[user]		.= "PRIMARY KEY  (id)";
	$create[user]		.= ")";
	// CHANGE SEP. 2002
	//$create[user]		.= "CREATE TABLE `\$form[user]` (";
	//$create[user]		.= "  `id` int(30) NOT NULL auto_increment,";
	//$create[user]		.= "  `user_login` varchar(30) NOT NULL default '',";
	//$create[user]		.= "  `user_password` varchar(255) NOT NULL default '',";
	//$create[user]		.= "  `user_name` varchar(255) NOT NULL default '',";
	//$create[user]		.= "  `user_email` varchar(30) NOT NULL default '',";
	//$create[user]		.= "  `user_icq` varchar(20) NOT NULL default '',";
	//$create[user]		.= "  `user_aim` varchar(20) NOT NULL default '',";
	//$create[user]		.= "  `user_yim` varchar(20) NOT NULL default '',";
	//$create[user]		.= "  `user_hp` varchar(255) NOT NULL default '',";
	//$create[user]		.= "  `user_interests` text NOT NULL,";
	//$create[user]		.= "  `user_signatur` text NOT NULL,";
	//$create[user]		.= " 	$insertlater[user][]	= "INSERT INTO \$tabs[user] VALUES (2, '\$form[admin_username]', '\$form[admin_pwd]', '\$form[admin_username]', '\$form[admin_mail]', '', '', '', '', '', '', '', '', '', '', '\$form[admin_mail]', '\$date', '\$date', '\$date', 0, '', 1, 1, 1, 0, 0, 0, 0, 1)"; `user_text` text NOT NULL,";
	//$create[user]		.= "  `user_birth` int(15) NOT NULL default '0',";
	//$create[user]		.= "  `user_location` varchar(255) NOT NULL default '',";
	//$create[user]		.= "  `user_work` varchar(255) NOT NULL default '',";
	//$create[user]		.= "  `user_gender` int(1) NOT NULL default '0',";
	//$create[user]		.= "  `reg_email` varchar(30) NOT NULL default '',";
	//$create[user]		.= "  `reg_date` int(15) NOT NULL default '0',";
	//$create[user]		.= "  `last_activ` int(15) NOT NULL default '0',";
	//$create[user]		.= "  `last_forum_read` int(15) NOT NULL default '0',";
	////$create[user]		.= "  `upload_allow` int(1) NOT NULL default '0',";
	////$create[user]		.= "  `upload_paths` text NOT NULL,";
	//$create[user]		.= "  `allow_pm` int(1) NOT NULL default '0',";
	////$create[user]		.= "  `show_email` int(1) NOT NULL default '0',";
	////$create[user]		.= "  `newsletter` int(1) NOT NULL default '0',";
	//$create[user]		.= "  `points` int(30) NOT NULL default '0',";
	//$create[user]		.= "  `rate_points` int(30) NOT NULL default '0',";
	//$create[user]		.= "  `rate_count` int(20) NOT NULL default '0',";
	//$create[user]		.= "  `blocked` int(1) NOT NULL default '0',";
	//$create[user]		.= "  `activated` int(1) NOT NULL default '0',";
	//$create[user]		.= "  `activation_code` varchar(25) NOT NULL default '',";
	//$create[user]		.= "  `logins` int(30) NOT NULL default '0',";
	//$create[user]		.= "  `uin` varchar(50) NOT NULL default '',";
	//$create[user]		.= "  `lostpassword` varchar(25) NOT NULL default '',";
	//$create[user]		.= "  `md5` int(1) NOT NULL default '0',";
	//$create[user]		.= "  PRIMARY KEY  (`id`)";swora_user
	//$create[user]		.= ")";
	$create[ftp]		.= "CREATE TABLE \$form[ftp] (";
	$create[ftp]		.= "  `id` int(30) NOT NULL auto_increment,";
	$create[ftp]		.= "  `title` varchar(255) NOT NULL default '',";
	$create[ftp]		.= "  `host` varchar(255) NOT NULL default '',";
	$create[ftp]		.= "  `port` int(7) NOT NULL default '0',";
	$create[ftp]		.= "  `user` varchar(255) NOT NULL default '',";
	$create[ftp]		.= "  `pwd` varchar(255) NOT NULL default '',";
	$create[ftp]		.= "  `path` varchar(255) NOT NULL default '',";
	$create[ftp]		.= "  PRIMARY KEY  (id)";
	$create[ftp]		.= ")";
	$create[upload_access]	.= "CREATE TABLE \$form[upload_access] (";
	$create[upload_access]	.= "  `id` int(30) NOT NULL auto_increment,";
	$create[upload_access]	.= "  `userid` int(30) NOT NULL,";
	$create[upload_access]	.= "  `serverid` int(30) NOT NULL,";
	$create[upload_access]	.= "  `path` varchar(255) NOT NULL,";
	$create[upload_access]	.= "  PRIMARY KEY  (id)";
	$create[upload_access]	.= ")";
	$create[user_options]	.= "CREATE TABLE \$form[user_options] (";
	$create[user_options]	.= "  `id` int(30) NOT NULL auto_increment,";
	$create[user_options]	.= "  `userid` int(30) NOT NULL,";
	$create[user_options]	.= "  `name` text NOT NULL,";
	$create[user_options]	.= "  `value` text NOT NULL,";
	$create[user_options]	.= "PRIMARY KEY  (id)";
	$create[user_options]	.= ")";
	$create[help]			.= "CREATE TABLE \$form[help] (";
	$create[help]			.= "  id int(30) NOT NULL auto_increment,";
	$create[help]			.= "  name varchar(255) NOT NULL default '',";
	$create[help]			.= "  text text NOT NULL,";
	$create[help]			.= "  views int(10) NOT NULL default '0',";
	$create[help]			.= "  PRIMARY KEY  (id)";
	$create[help]			.= ")";

	$create[ugb]			.= "CREATE TABLE \$form[ugb] (";
	$create[ugb]			.= "`id` int(30) NOT NULL auto_increment,";
	$create[ugb]			.= "`uid` int(30) NOT NULL default '0',";
	$create[ugb]			.= "`aid` int(30) NOT NULL default '0',";
	$create[ugb]			.= "`time` int(16) NOT NULL default '0',";
  	$create[ugb]			.= "`title` varchar(255) NOT NULL default '',";
  	$create[ugb]			.= "`text` text NOT NULL,";
  	$create[ugb]			.= "PRIMARY KEY  (`id`)";
	$create[ugb]			.= ");";

////////////////////////////
// SQL TABLES-VALUES (DEFAULT)
////////////////////////////
	$insert[help][] 			 = "INSERT INTO \$form[help] VALUES ('10', 'admin.modules.forum', 'Hier k�nne Sie das portaleigene Forum nach Ihren w�schen anpassen.\r\n\r\n[b]Allgemein[/b] ::\r\n Benutzerspezifische und AnsichtsEinstellungen.\r\n\r\n[b]Boards[/b] ::\r\n Erstellung und Bearbeitung von Kategorien und Boards', '0');";
	$insert[help][] 			 = "INSERT INTO \$form[help] VALUES ('9', 'admin.autoinstall', 'Hier werden s�mtliche Module angezeigt, welche noch nicht ins Portal eingebunden worden sind.\r\n\r\nUm diese zu Installieren klicken Sie bitte auf \\\"Installieren\\\" und \r\nfolgen Sie den Anweisungen der Installation.', '0');";
	$insert[help][] 			 = "INSERT INTO \$form[help] VALUES ('11', 'admin.modules.gbook', 'Hier k�nnen Sie das portaleigene G�stebuch nach Ihren W�nschen anpassen.\r\n\r\n[b]Konfiguration[/b] ::\r\nG�stebuchspezifische Einstallungen\r\n\r\n[b]Eintrag kommentieren[/b] ::\r\nSie erhalten hier die M�glichkeit einzelne Beitr�ge im G�stebuch zu kommentieren.\r\n\r\n[b]Eintrag l�schen[/b] ::\r\nL�schen von unerw�nschten Beitr�gen.', '0');";
	$insert[help][] 			 = "INSERT INTO \$form[help] VALUES ('12', 'admin.modules.hacks', 'Zur �usseren Aufarbeitung des Portals existiert die M�glichkeit kleinere Scripts (sog. Hacks) ins Menu aufzunehmen.\r\n\r\nSpeziellere Hacks bed�rfen einer Konfiguration.', '0');";
	$insert[help][] 			 = "INSERT INTO \$form[help] VALUES ('13', 'admin.modules.loader', 'Hier k�nnen Sie eigene HTML seiten sowie Links zu anderen Seiten erstellen\r\nund verf�gen dann �ber einen �berblick, sowie �ber einen Clickcounter der einzelnen Links.', '0');";
	$insert[help][] 			 = "INSERT INTO \$form[help] VALUES ('14', 'admin.modules.news', 'Hier k�nnen Sie das portaleigene Newssystem konfigurieren und News eintragen.\r\n\r\n[b]Konfiguration[/b] ::\r\n�ussere Einstellugen zum Newssystem.\r\n\r\n[b]neue News[/b] ::\r\nAbschicken neuer News.\r\n\r\n[b]News bearbeiten[/b] ::\r\nAktivierung, Editierung, Blockierung und Bearbeitung einzelner Kommentare.', '0');";
	$insert[help][] 			 = "INSERT INTO \$form[help] VALUES ('15', 'admin.modules.pm', 'Hier k�nnen Sie das System f�r private Nachichten konfigurieren.\r\n\r\n[b]Konfiguration[/b] ::\r\nBegrenzung der Nachrichtenzahl f�r den Ein-/Ausgang.', '0');";
	$insert[help][] 			 = "INSERT INTO \$form[help] VALUES ('16', 'admin.modules.swora', 'Also \\\'Swora\\\' wird das Grundsystem des Portals bezeichnet. Hierzu geh�ren unteranderem die unten genannten Features.\r\nDer Gro�teil der Konfigurationsarbeit wurde bereits bei der Installation fertiggestellt oder wurden standardgem�� eingerichtet.\r\n\r\n[b]Startseite[/b] ::\r\n Allgemeine Informationen: Neue Module, Version des Portals, Statistik\r\n\r\n[b]Konfiguration[/b] ::\r\n Wichtige Konfigurationen, die Sie nach dem Setup einstellen sollten.\r\n\r\n[b]Verbannung[/b] ::\r\n 3 M�glichkeiten um unerw�nschten Personen den Zutritt zu verweigern.\r\n\r\n[b]Html Code[/b] ::\r\n Anpassung der MetaTags der Seite (<meta>)\r\n\r\n[b]Module[/b] ::\r\n Informationen und Bearbeitungsm�glichkeiten f�r Module\r\n\r\n[b]Menu erweitern[/b] ::\r\n Anpassung Ihres Menus\r\n\r\n[b]Menu bearbeiten[/b] ::\r\n Sortierung und Bearbeitung einzelner Menupunkte\r\n\r\n[b]Smilies[/b] ::\r\n Anpassung der zur Verf�gung stehenden Smilies\r\n\r\n[b]Punkte System[/b] ::\r\n Anpassung des portalinternen PunkteSystems\r\n\r\n[b]Fehler[/b] ::\r\n Falls Fehler im Portal entstehen sollten, werden diese hier angezeigt.\r\n\r\n[b]Ftp-Accounts[/b] ::\r\n Konfiguration des Avatar-FTP und der Users-FTPs\r\n\r\n[b]Hacks[/b] ::\r\n Installation und Deinstallation einzelner Hacks\r\n\r\n[b]Update[/b] ::\r\n Update auf eine neuere Version des Portals', '2');";
	$insert[help][] 			 = "INSERT INTO \$form[help] VALUES ('17', 'admin.modules.user', 'Hier k�nnen Sie Ihre Mitglieder hinzuf�gen, bearbeiten, blockieren und vieles mehr.\r\n\r\n[b]Neues Mitglied erstellen[/b] ::\r\n Hier erstellen Sie ein neues Mitglied.\r\n\r\n[b]Mitglied bearbeiten[/b] ::\r\n �ndern Sie Eigenschaften bestimmter Benutzer\r\n\r\n[b]Moderation[/b] ::\r\n Ein gutes Portal braucht Moderatoren die sich um den Inhalt sorgen. Hier k�nnen Sie Mitglieder zu Moderatoren der Module ernennen.\r\n\r\n[b]Uploads erlauben[/b] ::\r\n Das portaleigene UploadSystem erlaubt es Benutzern Daten auf einen Ihrer FtpServer zu speichern und gegebenenfalls auf der Seite zu verlinken. (Lesen Sie Hierzu die Hilfe bei der Konfiguration)\r\n\r\n[b]blockieren/deaktivieren[/b] ::\r\n unbeliegte Mitglieder k�nnen Sie aus der Community ausschliessen.\r\n\r\n[b]Newsletter[/b] ::\r\n Hier geben wir ihnen die M�glichkeit alle ihrer Mitglieder mit einer Email �ber �nderungen und Erweiterungen in Kenntnis zu setzten.', '1');";
	$insert[help][] 			 = "INSERT INTO \$form[help] VALUES ('18', 'admin.main.statistik', 'Eine ausf�hrliche Statistik �ber wissenswerte Informationen.', '0');";
	$insert[help][] 			 = "INSERT INTO \$form[help] VALUES ('19', 'admin.main.system', 'Version:\r\n Die aktuell von Ihnen benutzte Version des Portals\r\n Updates werden auf unserer Homepage bekannt gegeben.\r\n\r\n[b]Idee & Entwicklung[/b] ::\r\n Hier sehen Sie wieviele und wer an der Entwicklung und Erweiterung des Portals arbeitet\r\n\r\n[b]Grafik & Layout[/b] ::\r\n Hier sehen Sie wieviele und wer am Layout und den Grafiken des Portals arbeitet\r\n\r\n[b]Support & Foren[/b] ::\r\n Hier sehen Sie von wem Sie gegebenen Falls Support erhalten k�nnen.\r\n\r\n[b]Ihre SerialId[/b] ::\r\n Eine Spezialit�t dieses Portals. Jeder Besitzer des Portals erh�lt die M�glichkeit sein Produkt �ber eine einzigartige Id bei uns zu registrieren.\r\n(Dies Registrierung ist aber Version 1.2 keine Pflicht mehr.)', '2');";
	$insert[help][] 			 = "INSERT INTO \$form[help] VALUES ('20', 'admin.module.traffic', 'Hier erhalten Sie ausf�hrliche Informationen �ber den aktuellen Stand des Datenverkehrs (engl. Traffic), der durch dieses Portal verursacht wird.\r\n\r\n[b](!! Es handelt sich hierbei keinesfalls um den exakten Wert. Es k�nnen auch hier Fehler bei der Berechnung entstehen. !!)[/b]', '0');";
	$insert[help][] 			 = "INSERT INTO \$form[help] VALUES ('21', 'admin.module.banner.new', '[b]Titel[/b] ::\r\nDer Titel eines Banners wird nur ben�tigt um eine �bersicht alles Banner zu wahren. Er bennent den Sourcecode.\r\n\r\n[b]Quellcode[/b] ::\r\nDer Quellcode f�r den Werbungbanner. Meist wird dieser vom Provider des Banners bereitgestellt. Dieser ist hier einztragen.', '0');";
	$insert[help][] 			 = "INSERT INTO \$form[help] VALUES ('22', 'admin.module.banner.delete', 'Hier sehen Sie eine Liste aller momentan zur Verf�gung stehender Banner.\r\nFalls Sie einen (oder mehrer) Banner l�schen m�chten klicken Sie auf dessen (\\\'Check\\\')Box und dann auf \\\'Auswahl l�schen\\\'.', '0');";
	$insert[help][] 			 = "INSERT INTO \$form[help] VALUES ('23', 'admin.module.swora.bannes', 'Hier haben Sie die M�glichkeit bestimmte Benutzer zu definieren, welche keinen Zutritt zur Community erhalten d�rfen.\r\n\r\n[b]Gebannte Emails[/b] ::\r\nEmailadressen, welche bei einer Anmeldung nicht angegeben werden d�rfen\r\nMehrere Emailadressen bitte mit \\\'Leerzeichen\\\' trennen.\r\n[u][i]Format:[/i][/u]\r\nMuster@mann.de\r\n\r\n[b]Gebannte Namen[/b] ::\r\nNamen, welche bei einer Anmeldung nicht angegeben werden d�rfen\r\nMehrere Namen bitte mit \\\'Leerzeichen\\\' trennen.\r\n[u][i]Format:[/i][/u]\r\nFritz Helga Anton\r\n\r\n[b]Gebannte Ips[/b] ::\r\nIpadressen, welche der keinen Zugriff auf die Zeite besitzen.\r\nMehrere Ipadressen bitte mit \\\'Leerzeichen\\\' trennen.\r\nF�r eine Angabe einer Gesamten Ip-Reihe gibt es die M�glichkeit des Benutzens von \\\'%\\\' als PLatzhalter:\r\n[u][i]Format:[/i][/u]\r\n192.168.0.1\r\n192.168.0.%  ([i]Beispiel f�r Ip-Reihe (192.168.0.1 - 192.168.0.255)[/i])', '0');";
	$insert[help][] 			 = "INSERT INTO \$form[help] VALUES ('24', 'admin.module.swora.errors', 'Dieses Portal verf�gt �ber ein internes Fehlerprotokollsystem, welches gro�e Fehler im Quellcode aufzeichnet.\r\n\r\nHier erhalten Sie Auskunft �ber ...\r\n... die Art des Fehlers\r\n... die Adresse des Fehlers\r\n... den Namen das Scripts, das den Fehler verursacht\r\n... und �ber die Zeile indem der Fehler verursacht wird.\r\n\r\nDieses System wurde so konzipiert, dass jeder Benutzer die M�glichkeit erh�lt m�gliche Fehler selbst korrigieren zu k�nnen. Sollte ein Fehler auftauchen, mit dem Sie nichts anfangen k�nnen, schreiben Sie bitte in unser Forum.', '0');";
	$insert[help][] 			 = "INSERT INTO \$form[help] VALUES ('25', 'admin.module.swora.errors.details', 'Hier erhalten Sie detailierte Informationen �ber gr��ere Fehler.\r\n\r\n(Bitte Informieren Sie sich in der Hilfe zu \\\'Fehler\\\')', '0');";
	$insert[help][] 			 = "INSERT INTO \$form[help] VALUES ('26', 'admin.module.swora.ftp.edit', 'Hier erhalten Sie die M�glichkeit ihren Ftp-Eintrag nachtr�glich zu �ndern.\r\n\r\n[b]Titel[/b] ::\r\nBezeichnet den Account mit einem Einmaligen Namen und sorgt somit f�r �bersicht.\r\n\r\n[b]Adresse[/b] ::\r\nDie Adresse zu Ihrem FTP-Server. Anzugeben in einer Ipadresse oder einem Hostnamen.\r\n[u][i]Format:[/u][/i]\r\nftp.musterman.com\r\n80.156.156.5\r\n\r\n[b]Port[/b] ::\r\nDer Port aufdem Ihr Ftp-Server l�uft. (Meist: 21)\r\n\r\n[b]Benutzername[/b] ::\r\nDer Benutzernamen, mitdem Sie sich bei diesem Ftp anmelden m�chten.\r\n\r\n[b]Benutzerpasswort[/b] ::\r\nDas, zum Benutzernamen geh�rige Passwort f�r das Einloggen in den Server.\r\n\r\n[b]Pfad[/b] ::\r\nDer Pfad aufden die Anzeige begrenzt werden soll.\r\nWird zu einen FTPServer verbunden und eingeloggt, so erfolgt eine Verzeichnis�nderung zu diesem angegebenen Pfad. Es kann nicht �ber diesen Pfad hinaus gelesen werde.\r\n[u][i]Beispiel:[/u][/i]\r\nPfad = \\\'/pub/software\\\'\r\nEs kann nun nicht in verzeichnisse gewechselt werden, die nicht innerhalt des Angegebenen Pfades liegen.\r\n/pub/software/music : OK\r\n/pub/software/video : OK\r\n/pub/upload : Fehler', '0');";
	$insert[help][] 			 = "INSERT INTO \$form[help] VALUES ('27', 'admin.module.swora.ftp', 'Hier finden Sie eine Liste aller zur Verf�gung stehenden Ftp-Server (und deren Daten), auf die zugegriffen werden k�nnen.', '0');";
	$insert[help][] 			 = "INSERT INTO \$form[help] VALUES ('28', 'admin.module.swora.ftp.new', 'Hier k�nnen Sie einen neuen FtpServer eintragen und f�r andere Verf�gbar machen.\r\n\r\n[b][FONT COLOR=RED]Zur Verf�gbarkeit[/b] ::\r\nNicht jeder Benutzer hat Zugriff auf die FtpServer. Sie m�ssen jedem Benutzer Einzeln �ber den Menupunk \\\'Allow Uploads\\\' (in \\\'User\\\') explizit erlauben, dass und auf welchen FTPServer dieser Zugriff hat.\r\n[/font]\r\n\r\n\r\n[b]Titel[/b] ::\r\nBezeichnet den Account mit einem Einmaligen Namen und sorgt somit f�r �bersicht.\r\n\r\n[b]Adresse[/b] ::\r\nDie Adresse zu Ihrem FTP-Server. Anzugeben in einer Ipadresse oder einem Hostnamen.\r\n[u][i]Format:[/u][/i]\r\nftp.musterman.com\r\n80.156.156.5\r\n\r\n[b]Port[/b] ::\r\nDer Port aufdem Ihr Ftp-Server l�uft. (Meist: 21)\r\n\r\n[b]Benutzername[/b] ::\r\nDer Benutzernamen, mitdem Sie sich bei diesem Ftp anmelden m�chten.\r\n\r\n[b]Benutzerpasswort[/b] ::\r\nDas, zum Benutzernamen geh�rige Passwort f�r das Einloggen in den Server.\r\n\r\n[b]Pfad[/b] ::\r\nDer Pfad aufden die Anzeige begrenzt werden soll.\r\nWird zu einen FTPServer verbunden und eingeloggt, so erfolgt eine Verzeichnis�nderung zu diesem angegebenen Pfad. Es kann nicht �ber diesen Pfad hinaus gelesen werde.\r\n[u][i]Beispiel:[/u][/i]\r\nPfad = \\\'/pub/software\\\'\r\nEs kann nun nicht in verzeichnisse gewechselt werden, die nicht innerhalt des Angegebenen Pfades liegen.\r\n/pub/software/music : OK\r\n/pub/software/video : OK\r\n/pub/upload : Fehler', '0');";
	$insert[help][] 			 = "INSERT INTO \$form[help] VALUES ('29', 'admin.module.swora.hack.install', 'F�r eine Installation ist in jeden Hack eine eigene Installationsroutine eingebaut. Um diesen Vorgang zu aktivieren und dass Skript installieren zu k�nnen (vorausgesetzt es ben�tigt eine Installation), m�ssen Sie den Namen des Skripts hier angeben.\r\n\r\n[b]zum Beispiel[/b] ::\r\ne_calender.php\r\ne_minichat.php\r\n\r\n[b]Bemerkung [/b]::\r\nDer Dateiname s�mtliche Hacks beginnt mit \\\'e_\\\' und endet mit \\\'.php\\\'.', '0');";
	$insert[help][] 			 = "INSERT INTO \$form[help] VALUES ('30', 'admin.module.swora.hack.uninstall', 'Ben�tigen Sie eine Hack nicht l�nger, so k�nnen Sie ihn hier nun l�schen.\r\n\r\n[font color=red][b]Achtung[/b] ::\r\nBeim L�schen eines Hacks gehen s�mtliche mit diesen Skript verbundene Daten verloren.[/font]', '0');";
	$insert[help][] 			 = "INSERT INTO \$form[help] VALUES ('31', 'admin.module.swora.htmlcode', 'Um die Gestalltung der Seite, insbesondere des Quellcodes zu perfektionieren, k�nnen Sie hier die sog. Meta Tags editieren.\r\n\r\n[b]Beschreibung (Meta-Description)[/b] ::\r\nEine kurze Beschreibung der Seite.\r\n\r\n[b]Author (Meta-Author)[/b] ::\r\nDer Name/Synonym des Autors/Webmaster der Seite.\r\n\r\n[b]Schl�sselw�rter (Meta-KeyWords)[/b] ::\r\nSchl�sselw�rter die ihre Seite beschreiben.\r\nDieser Eintrag ist n�tig, damit eine Metatext basierte Suchmaschiene ihre Seite einordnen kann.\r\n\r\n[font color=red][b]Hinweis[/b] ::\r\nS�mtliche \\\'Meta-Tags\\\' werden im \\\'Head\\\'-Teil des Quellcodes angezeigt und erscheinen nicht beim Betrachten der Seite in ihrem Browser.\r\n[/font]', '0');";
	$insert[help][] 			 = "INSERT INTO \$form[help] VALUES ('32', 'admin.module.swora.trafficlimit', 'Hier geben wir Ihnen die M�glichkeit den Verkehr und somit die Kosten des Portals zu kontrollieren.\r\n\r\n[b]Beschr�nkung aktivieren[/b] ::\r\nFalls Sie keine Kontrollierung des Verkehrs w�nschen, so verneinen Sie diese Frage, andernfalls stellen Sie die Einstellungen auf \\\'Ja\\\' und dr�cken Sie auf \\\'Speichern\\\'.\r\n\r\nEs folgt nun eine Liste aller bisher eingerichteten Begrenzungen f�r den sog. Traffic. Falls Sie einen dieser Begrenzungen aufheben wollen aktivieren Sie dessen Box an der linken Seite der Liste und klicken Sie \\\'l�schen\\\'.\r\n\r\n[b](!! Es handelt sich hierbei keinesfalls um den exakten Wert. Es k�nnen auch hier Fehler bei der Berechnung entstehen. !!)[/b]', '0');";
	$insert[help][] 			 = "INSERT INTO \$form[help] VALUES ('33', 'admin.module.swora.trafficlimit.new', 'Hier k�nnen Sie neue Beschrenkungen f�r den Datenverkehr einrichten.\r\n\r\n[b]Beschr�nken bei mehr als[/b] ::\r\nHier bitte einen Datengr��e im entsprechendem Format angeben, ab der jeglicher Verkehr gesperrt werden soll.\r\n\r\n[b]Beschr�nken f�r[/b] ::\r\nEine Dauer f�r die diese Beschr�nkung aktiviert sein soll. Nach dem verstreichen dieser Frist wird jeder Verkehr wieder zugelassen.\r\n\r\n[b]zu schliessende Module[/b] ::\r\nHier k�nnen Sie angeben, ob die ganze Seite, oder lediglich ein Teil/Modul des Portals beschr�nkt werden soll.\r\n\r\n\r\n[b](!! Es handelt sich hierbei keinesfalls um den exakten Wert. Es k�nnen auch hier Fehler bei der Berechnung entstehen. !!)[/b]', '0');";
	$insert[help][] 			 = "INSERT INTO \$form[help] VALUES ('34', 'admin.module.swora.mainconfig', '[FONT COLOR=RED][b]Die Entwicklung dieses Services befindet sich leider noch im Anfangsstadium. Es empfiehlt sich nicht diesen Dienst zu aktivieren, da er nur in den allerwenigsten F�llen reibungslos funktioniert.[/b][/FONT]\r\n\r\nHier haben Sie die M�glichkeit den Versand von Emails mit einem SMTP Server zu bew�ltigen. Dies Empfiehlt sich insbesondere bei vielen Mitgliedern und dem Wunsch Newsletter an alle zu verschicken.\r\n\r\n[b]Email des Webmasters[/b] ::\r\nEine g�ltige eMailadresse des Webmasters.\r\n\r\n[b]SMTP benutzen[/b] ::\r\nHier k�nnen Sie die Verwendung des SMTP Servers aktivieren.\r\n\r\n[b]SMTP-Adresse[/b] ::\r\nDie Adresse des SMTP Server. (IP oder Hostnamen)\r\n\r\n[b]SMTP-Benutzer[/b] ::\r\nBenutzername f�r die Einwahl an einen SMTP Server.\r\n\r\n[b]SMTP-Passwort[/b] ::\r\nBenutzerpasswort f�r die Einwahl an einen SMTP Server.', '1');";
	$insert[help][] 			 = "INSERT INTO \$form[help] VALUES ('35', 'admin.module.swora.designconfig', 'Sie k�nnen nun das Design des Portals ausw�hlen.\r\n\r\nSollten Sie einen Designwechsler aktiviert haben, empfiehlt es sich \\\'Festes Design\\\' zu deaktivieren.', '0');";
	$insert[help][] 			 = "INSERT INTO \$form[help] VALUES ('36', 'admin.module.swora.startsection', 'W�hlen Sie nun das Modul, welches aufgerufen werde soll, sobald kein anders Modul angew�hlt ist.', '0');";
	$insert[help][] 			 = "INSERT INTO \$form[help] VALUES ('37', 'admin.module.swora.avatar', 'Nun werden einige Informationen f�r die korrekte Installation des Avatarsystems ben�tigt.\r\n\r\n[b]max. Gr��e des Avatars[/b] ::\r\nDie Gr��e die ein Avatar maximal besitzen darf. Angegeben in Bytes. (1024Bytes = 1 KiloByte)\r\n\r\n[b]FtpId[/b] ::\r\nEs ist nun n�tig einen Ftp-Account zu w�hlen, welcher [b]direkt[/b] in das Verzeichnis \\\'./images/avatars\\\' f�hrt, damit ein Upload des Avatar erfolgreich durchgef�hrt werden kann.\r\nMehr Zum Thema \\\'FTP-Accounts\\\' finden Sie bei der dortigen Hilfe.', '0');";
	$insert[help][] 			 = "INSERT INTO \$form[help] VALUES ('38', 'admin.module.swora.badwords', 'Hier findet die Konfiguration des Badword-Filters statt.\r\n\r\nalle hier angegebenen W�rter werden bei vor der Ausgabe der Seite unkenntlich gemacht, sodass Beleidigungen nur erschwert auf diesem Portal stattfinden k�nnen.\r\nDies Bedarf jedoch einer Regelm��igen Aktuallisierung auf den Wortschatz der Besucher.\r\n\r\nPro Zeile ist ein Wort anzugeben.', '0');";
	$insert[help][] 			 = "INSERT INTO \$form[help] VALUES ('39', 'admin.module.swora.menu', 'Hier k�nnen Sie mit Hilfe einer skizzenhaften Darstellung ihr eigenes Menu einrichten.\r\nDes weiteren erkennenn Sie hier sofort welche Link/Kategorie f�r welche Benutzer sichtbar erscheint.\r\n\r\n[b]gr�n[/b] :: registrierte und unregistierte Benutzer sehen diesen Eintrag\r\n[b]blau[/b] :: aussschliesslich unregistierte Benutzer sehen diesen Eintrag\r\n[b]rot[/b] :: aussschliesslich registrierte  Benutzer sehen diesen Eintrag\r\n\r\n[b]Lage[/b] ::\r\nDie Lage einzelner Links und ganzer Kategorien k�nnen Sie mit Hilfe der Zahlenmenus bestimmen. Die Eintrichtung erfolgt von \\\'1\\\'(oben) nach \\\'15\\\'(unten).\r\n\r\n[b]Menueintr�ge l�schen[/b] ::\r\nZum L�schen eines oder mehrerer Eintr�ge aktivieren Sie die Klickboxen neben dem/den besagten Eintrag und klicken Sie auf \\\'l�schen\\\'.\r\n\r\nEs besteht ebenso die M�glichkeit das komplette Menu zu deaktivieren, um gegebenen Falls ein individuelles Menu (zB mit Frames) zu verwelden.\r\nBitte beachten Sie, dass hierdurch auch s�mtliche Skripts aus der Anzeige genommen werden.', '0');";
	$insert[help][] 			 = "INSERT INTO \$form[help] VALUES ('40', 'admin.module.swora.menu.new', 'Hier k�nnen Sie neue Links/Kategorien in Ihr Portal einbauen.\r\n\r\n[b]Sorte des Menupunktes[/b] ::\r\nSie k�nnen zwischen 4 m�glichen Arten von Menueintrag w�hlen.\r\n[i]Kategorie[/i] :\r\nEine Kategorie f�r eine Sammlung von Verkn�pfungen/Links.\r\n[font color=blue]zu beachtende Eingaben: Position,Titel,Anzeige[/font]\r\n[i]Link[/i] :\r\nEine Link zu einer bestimmten Seite, oder zu einem Modul.\r\n[font color=blue]zu beachtende Eingaben: Position,Unterkategorie,Titel,Ziel,Ziel Frame,Anzeige[/font]\r\n[i]HTML-Code[/i] :\r\nSie k�nnen ebensogut Ihre eigenen Ideen in HTML ausdr�cken und in das Menu einbinden.\r\n[font color=blue]zu beachtende Eingaben: Position,HTML-Code,Anzeige[/font]\r\n[i]Hack/Skript[/i] :\r\nWir bieten Ihnen die M�glichkeit kleine Programme, sog. Skripts in ihre Menu einzubauen, mit denen der Komfort gesteigert werden kann.\r\n[font color=blue]zu beachtende Eingaben: Position,Skript,Anzeige[/font]\r\n\r\n[b]Position[/b] ::\r\nBestimmen Sie wo der Eintrag angeziet werden soll. Oben, unten, links oder rechts. [font color=red]Diese Einstellungsm�glichkeit ist nicht bei jedem Design m�glich.[/font]\r\n\r\n[b]Unterkategorie[/b] ::\r\nF�r den Fall, Sie m�chten eine neue Link einbinden, ben�tigen Sie eine �bergeordnete Kategorie inder die besagte Verkn�pfung eingef�gt werden soll.\r\n\r\n[b]Titel[/b] ::\r\nDer Name der f�r die Link angezeigt werden soll.\r\n\r\n[b]Ziel[/b] ::\r\nDas Ziel zudem die Link f�hren soll.\r\nModule werden wie folgt geladen.\r\n[u]index.php?section=[i]Modulname[/i][/u]\r\n\r\n[b]Zielframe[/b] ::\r\nF�r den Fall, dass Sie sich entschieden haben, dieses Portal in einem Frame laufen zu lassen, haben Sie die M�glichkeit zu w�hlen zu welchem Frame eine Link f�hrt.\r\n\r\n[b]HTML-Code[/b] ::\r\nF�r den Fall Sie m�chten ihren eigenen Quellcode einf�gen, schalten Sie bei \\\'Sorte des Menupunkts\\\' auf \\\'HTML-Code\\\' und geben Sie ihren Quelltext ein.\r\n\r\n[b]Skript[/b] ::\r\nF�r das Einbinden von Skripts k�nnen Sie hier den Dateinamen des Skripts angeben.\r\n[font color=red]Das Skript [b]MUSS[/b] im Verzeichnis \\\'./includes\\\' liegen.[/font]\r\n\r\n[b]Anziege[/b] ::\r\nHier w�hlen Sie welchen Personen die Link/Kategorie/etc. angezeigt werden soll.\r\n\r\nZum Fertigstellen klicken Sie auf \\\'erstellen\\\'.', '1');";
	$insert[help][] 			 = "INSERT INTO \$form[help] VALUES ('41', 'admin.module.swora.points', 'Um Anzeigen zu k�nnen welche Mitglieder in Ihrer Community starke Aktivit�ten zeigen k�nnen Sie ihnen Punkte f�r gewisse Aktionen geben.\r\nDiese Punkte werden summiert und dienen als Anhaltspunkt f�r aktive Mitglieder.', '0');";
	$insert[help][] 			 = "INSERT INTO \$form[help] VALUES ('42', 'admin.module.swora.sections', 'Hier finden Sie detaiierte Informationen �ber die, ins Portal eingebundenen Module.\r\nDie erste Spalte zeigt den Portalinternen Namen des Moduls an. Diesen Namen ben�tigen Sie um in Ihrem Menu eine Link zu diesem Modul zu setzten.\r\nEine Spalte weiter wird der Name des Moduls angegeben und eins weiter finden Sie die Version des verwendenten Moduls.\r\nDie zweit letzten Spalten dienen dazu ein Modul zu daktivieren, um gebenen Falls �nderungen vorzunehmen und unben�tigte Module zu entfernen.\r\nBei einer Deinstallation wird die modulinteren Deinstallationsroutine verwendet, welcher Sie folgen m�ssen.\r\n[font color=red][b]Zu beachten[/b]: Bei der Deinstallation eines Moduls gehen s�mtliche mit desem Modul verbundenen Daten verloren.[/font]\r\n\r\nAusnahmen bilden folgende 9 Module :\r\n[i]Profil\r\nContact\r\nDL\r\nUploader\r\nSwora\r\nError\r\nLoader\r\nUser\r\nHelp[/i]\r\n\r\nDiese Module k�nnen nicht deinstalliert werden, da sie n�tig sind um das Portal lauff�hig zu halten.', '0');";
	$insert[help][] 			 = "INSERT INTO \$form[help] VALUES ('43', 'admin.module.swora.sections.new', 'F�r den Fall, dass ein Modul keine Installationsroutinen besitzt (dies erkennt man, falls \\\'Automatisches Installieren\\\' m�glich ist), so k�nnen Sie hier das Modul \\\'per Hand\\\' in das Portal einf�gen.\r\n\r\n[b]Modul in \\\'./sections\\\'[/b] ::\r\nJedes Modul des Portals besitzt ein eigenens Verzeichnis in \\\'./sections\\\'\r\ndieses Verzeichnis bezeichnet auch den Namen, mit dem Sie das Modul aufrufen k�nnen, und den Namen der Datei dir f�r die Verarbeitung der Daten f�r das Modul zust�ndig ist, die sog. Moduldatei.\r\n\r\n[b]Beschreibung[/b] ::\r\nGeben Sie eine kurze Beschreibung f�r das Modul an.\r\n\r\nUm das Modul in das Portal einzuf�gen klicken Sie nun auf \\\'speichern\\\'.', '0');";
	$insert[help][] 			 = "INSERT INTO \$form[help] VALUES ('44', 'admin.module.swora.smilies', 'Hier sehen Sie eine Liste aller eingebundenen Smilies mit sammt dem dazugeh�rigen Pfad zu Bilddatei und dem K�rzel zur verwendung des Smilies.\r\n\r\nF�r den Fall, Sie m�chten eines oder mehrere dieser Smilies l�schen, aktivieren Sie die KickBox des/der besagten Smilies und klicken Sie auf \\\'Auswahl l�schen\\\'.', '0');";
	$insert[help][] 			 = "INSERT INTO \$form[help] VALUES ('45', 'admin.module.swora.smilies.new', 'Hier k�nnen Sie ein neues Smilie in das Portal einf�gen.\r\n\r\n[b]Pfad in \\\'images/smilies\\\'[/b] ::\r\nDer Name des Bilddatei im Verzeichnis \\\'./images/smilies\\\'\r\n\r\n[b]Zu �bersetztender Text[/b] ::\r\nDas Alias f�rwelches das Smilie eingef�gt werden soll.', '0');";
	$insert[help][] 			 = "INSERT INTO \$form[help] VALUES ('46', 'admin.module.swora.traffic', 'Hier erhalten Sie Informationen �ber den Datenverkehr (Traffic).\r\n\r\nDie Anzeige erfolgt in einer gesamten, einer w�chentlichen und einer ausf�hrlichen Statistik.', '0');";
	$insert[help][] 			 = "INSERT INTO \$form[help] VALUES ('47', 'admin.module.swora.updates', 'Hier k�nnen Sie ein Update ausf�hren, um das Portal auf den aktuellsten Stand zu bringen und Fehler (sog. Bugs) zu schliessen.', '0');";
	$insert[help][] 			 = "INSERT INTO \$form[help] VALUES ('48', 'admin.module.swora.updates.done', 'Hier erhalten Sie Informationen �ber aktuelle Updates und Bugfixes, welche bereits f�r ihr Portal installiert wurden.', '0');";
	$insert[help][] 			 = "INSERT INTO \$form[help] VALUES ('49', 'admin.module.swora.updates.online', 'Hier k�nnen Sie ien OnlineUpdate durchf�hren.\r\n\r\n[b]Dieses Feature ist bis dato nicht funktionst�chtig. Wir bitten Sie um Verst�ndnis.[/b]', '0');";

	$insert[config][]	= "INSERT INTO \$form[config] VALUES ('version_swora','0.1')";
	$insert[config][]	= "INSERT INTO \$form[config] VALUES ('version_contact','0.1')";
	$insert[config][]	= "INSERT INTO \$form[config] VALUES ('version_dl','0.1')";
	$insert[config][]	= "INSERT INTO \$form[config] VALUES ('version_error','0.1')";
	$insert[config][]	= "INSERT INTO \$form[config] VALUES ('version_loader','0.1')";
	$insert[config][]	= "INSERT INTO \$form[config] VALUES ('version_news','0.1')";
	$insert[config][]	= "INSERT INTO \$form[config] VALUES ('version_profil','0.1')";
	$insert[config][]	= "INSERT INTO \$form[config] VALUES ('version_uploader','0.1')";
	$insert[config][]	= "INSERT INTO \$form[config] VALUES ('version_user','0.1')";
	$insert[config][]	= "INSERT INTO \$form[config] VALUES ('version_help','0.1')";

	$insert[config][]	= "INSERT INTO \$form[config] VALUES ('ban_name', '')";
	$insert[config][]	= "INSERT INTO \$form[config] VALUES ('ban_email', '')";
	$insert[config][]	= "INSERT INTO \$form[config] VALUES ('code_no_eval', 'fopen(.*)\r\nfputs(.*)\r\nfwrite(.*)\r\nrename(.*)\r\nunlink(.*)\r\nfile(.*)\r\nexit;\r\nmail(.*)\r\ninclude\r\nrequire\r\nrequire_once\r\ninclude_once\r\nfsockopen(.*)\r\nexec(.*)\r\npopen(.*)\r\nsystem(.*)')";
	$insert[config][]	= "INSERT INTO \$form[config] VALUES ('traffic', '0')";
	$insert[config][]	= "INSERT INTO \$form[config] VALUES ('p_news_activate', '150')";
	$insert[config][]	= "INSERT INTO \$form[config] VALUES ('p_news_com', '10')";
	$insert[config][]	= "INSERT INTO \$form[config] VALUES ('avatar_maxsize', '10000')";
	$insert[config][]	= "INSERT INTO \$form[config] VALUES ('avatar_path', 'images/avatar/')";
	$insert[config][]	= "INSERT INTO \$form[config] VALUES ('news_show', '1')";
	$insert[config][]	= "INSERT INTO \$form[config] VALUES ('news_include_others', './sections/userstats.php')";
	$insert[config][]	= "INSERT INTO \$form[config] VALUES ('news_list', '10')";

	$insert[config][]	= "INSERT INTO \$form[config] VALUES ('tab_user', '\$form[user]')";
	$insert[config][]	= "INSERT INTO \$form[config] VALUES ('tab_section', '\$form[sections]')";
	$insert[config][]	= "INSERT INTO \$form[config] VALUES ('tab_config', '\$form[config]')";
	$insert[config][]	= "INSERT INTO \$form[config] VALUES ('tab_smilie', '\$form[smilies]')";
	$insert[config][]	= "INSERT INTO \$form[config] VALUES ('tab_menu', '\$form[menu]')";
	$insert[config][]	= "INSERT INTO \$form[config] VALUES ('tab_right', '\$form[right]')";
	$insert[config][]	= "INSERT INTO \$form[config] VALUES ('tab_news', '\$form[news]')";
	$insert[config][]	= "INSERT INTO \$form[config] VALUES ('tab_news_comment', '\$form[news_comments]')";
	$insert[config][]	= "INSERT INTO \$form[config] VALUES ('tab_loader', '\$form[loader]')";
	$insert[config][]	= "INSERT INTO \$form[config] VALUES ('tab_error', '\$form[error]')";
	$insert[config][]	= "INSERT INTO \$form[config] VALUES ('tab_onlinemessage', '\$form[onlinemessages]')";
	$insert[config][]	= "INSERT INTO \$form[config] VALUES ('tab_avatar', '\$form[avatar]')";
	$insert[config][]	= "INSERT INTO \$form[config] VALUES ('tab_traffic', '\$form[traffic]')";
	$insert[config][]	= "INSERT INTO \$form[config] VALUES ('tab_tmp', '\$form[tmps]')";
	$insert[config][]	= "INSERT INTO \$form[config] VALUES ('tab_ftp', '\$form[ftp]')";
	$insert[config][]	= "INSERT INTO \$form[config] VALUES ('tab_upload_access', '\$form[upload_access]')";
	$insert[config][]	= "INSERT INTO \$form[config] VALUES ('tab_useroption', '\$form[user_options]')";
	$insert[config][]	= "INSERT INTO \$form[config] VALUES ('tab_help', '\$form[help]')";
	$insert[config][]	= "INSERT INTO \$form[config] VALUES ('tab_ugb', '\$form[ugb]')";


	$insert[config][]	= "INSERT INTO \$form[config] VALUES ('badwords', 'fuck\r\nhure\r\nnutte\r\nschlampe\r\nbitch')";
	$insert[sections][]	= "INSERT INTO \$form[sections] VALUES (1, 'user', 'User Managment', 1)";
	$insert[sections][]	= "INSERT INTO \$form[sections] VALUES (2, 'swora', 'Community', 1)";
	$insert[sections][]	= "INSERT INTO \$form[sections] VALUES (3, 'contact', 'Kontakt', 1)";
	$insert[sections][]	= "INSERT INTO \$form[sections] VALUES (4, 'profil', 'Profil', 1)";
	$insert[sections][]	= "INSERT INTO \$form[sections] VALUES (5, 'dl', 'Download', 1)";
	$insert[sections][]	= "INSERT INTO \$form[sections] VALUES (6, 'loader', 'External Loader', 1)";
	$insert[sections][]	= "INSERT INTO \$form[sections] VALUES (7, 'uploader', 'Uploader', 1)";
	$insert[sections][]	= "INSERT INTO \$form[sections] VALUES (8, 'news', 'News', 1)";
	$insert[sections][]	= "INSERT INTO \$form[sections] VALUES (9, 'error', 'Error Managment', 1)";
	$insert[sections][]	= "INSERT INTO \$form[sections] VALUES (10, 'help', 'Hilfe im Admin', 1)";
	// CHANGE SEPT 2002
	//ov//$insert[sections][]	= "INSERT INTO \$form[sections] VALUES (53, 'tmp', 'TempBase', 1)";
	$insert[smilies][]	= "INSERT INTO \$form[smilies] VALUES (2, 'smile01.gif', ':-)')";
	$insert[smilies][]	= "INSERT INTO \$form[smilies] VALUES (4, 'smile02.gif', ';-)')";
	$insert[smilies][]	= "INSERT INTO \$form[smilies] VALUES (5, 'smile03.gif', '8-)')";
	$insert[smilies][]	= "INSERT INTO \$form[smilies] VALUES (6, 'smile04.gif', '>-)')";
	$insert[smilies][]	= "INSERT INTO \$form[smilies] VALUES (7, 'smile05.gif', ':-]')";
	$insert[smilies][]	= "INSERT INTO \$form[smilies] VALUES (8, 'smile06.gif', ':)')";
	$insert[smilies][]	= "INSERT INTO \$form[smilies] VALUES (9, 'smile07.gif', ':))')";
	$insert[smilies][]	= "INSERT INTO \$form[smilies] VALUES (10, 'smile08.gif', ':-(')";
	$insert[smilies][]	= "INSERT INTO \$form[smilies] VALUES (11, 'smile09.gif', '-?-')";
	$insert[smilies][]	= "INSERT INTO \$form[smilies] VALUES (12, 'smile10.gif', '-!-')";
	$insert[smilies][]	= "INSERT INTO \$form[smilies] VALUES (13, 'smile11.gif', '|-)')";
	$insert[smilies][]	= "INSERT INTO \$form[smilies] VALUES (14, 'smile12.gif', '8)')";
	$insert[smilies][]	= "INSERT INTO \$form[smilies] VALUES (16, 'smile18.gif', ':-/')";
	$insert[smilies][]	= "INSERT INTO \$form[smilies] VALUES (17, 'smile20.gif', ':-0')";
	$insert[user][]		= "INSERT INTO \$form[user] VALUES (1, 'guest', '', 'Gast', 'eMail', '', '', '', '', '', '', '', '\$date', '', '', 0, '', '\$date', 0, 0, 0, 0, 0, 0, 1, 0, 0, 0, 0, 1)";
	$insert[config][]	= "INSERT INTO \$form[config] VALUES ('smtp_use', '0')";
	$insert[config][]	= "INSERT INTO \$form[config] VALUES ('smtp_host', '')";
	$insert[config][]	= "INSERT INTO \$form[config] VALUES ('smtp_user', '')";
	$insert[config][]	= "INSERT INTO \$form[config] VALUES ('smtp_pass', '')";
	$insert[config][] 	= "INSERT INTO \$form[config] VALUES ('e_menu', '1')";
	$insert[menu][] 	= "INSERT INTO \$form[menu] VALUES (1, 'html', 0, 0, '', '', '', '<!-- Hier k�nne Sie ihr Logo platzieren.//-->', '', 0, 0, 0)";
	$insert[menu][] 	= "INSERT INTO \$form[menu] VALUES (2, 'cat', 1, 0, 'Main', '', '', '', '', 0, 0, 0)";
	$insert[menu][] 	= "INSERT INTO \$form[menu] VALUES (3, 'link', 1, 2, 'News', '', 'index.php?section=news', '', '', 0, 0, 0)";
	$insert[menu][] 	= "INSERT INTO \$form[menu] VALUES (4, 'link', 1, 2, 'New Account', '', 'index.php?section=user', '', '', 0, 0, 1)";
	$insert[menu][] 	= "INSERT INTO \$form[menu] VALUES (5, 'script', 1, 0, '', '', '', '', 'e_quicklogin.php', 0, 0, 0)";
	$insert[menu][] 	= "INSERT INTO \$form[menu] VALUES (6, 'cat', 1, 0, 'Kontakt', '', '', '', '', 0, 0, 0)";
	$insert[menu][] 	= "INSERT INTO \$form[menu] VALUES (7, 'link', 1, 6, 'Webmaster', '', 'index.php?section=contact&who=webmaster', '', '', 0, 0, 0)";
	$insert[menu][] 	= "INSERT INTO \$form[menu] VALUES (8, 'link', 1, 2, 'UserListe', '', 'index.php?section=user', '', '', 0, 1, 0)";
	$insert[news][] 	= "INSERT INTO \$form[news] VALUES (1, 2, 1027955455, 'Swora.Community', 'Die Installation wurde erfolgreich abgeschlossen.\r\n\r\nBitte loggen Sie sich ein und betreten Sie den AdministrationsBereich f�r weitere Einstellungen.\r\n\r\n<center>\r\n-> <a href=\\\"index.php?section=admin\\\">Administration</a> <-\r\n</center>\r\n\r\nMfG\r\n\r\nXeRoc', 1, 1, 1, 0)";

	$insertlater[config][]	= "INSERT INTO \$tabs[config] VALUES ('master_email', '\$form[admin_mail]')";
	// CHANGE SEPT 2002
	//ov//$insertlater[user][]	= "INSERT INTO \$tabs[user] VALUES (2, '\$form[admin_username]', '\$form[admin_pwd]', '\$form[admin_username]', '\$form[admin_mail]', '', '', '', '', '', '', '', '', '', '', 1, '\$form[admin_mail]', '\$date', '\$date', '\$date', 0, '', 1, 1, 1, 0, 0, 0, 0, 1, '', 0, '\$ssid', '0', 1)";
	$insertlater[user][]	= "INSERT INTO \$tabs[user] VALUES (2, '\$form[admin_username]', '\$form[admin_pwd]', '\$form[admin_username]', '\$form[admin_mail]', '', '', '', '', '', '', '', '', '', '', '', '\$form[admin_mail]', '\$date', '\$date', '\$date', 0, 0, 0, 0, 1, 0, 0, 0, 0, 1)";
	$insertlater[right][]	= "INSERT INTO \$tabs[right] VALUES (1, 2, 0, 0, 1, 1, \$date)";


////////////////////////////
// GET TABLES TO SAVE VALUES
////////////////////////////
function gettables() {
	global $configtab;
	$q = mysql_query("SELECT * FROM $configtab[configtab] WHERE name LIKE 'tab_%'");
	while($a = mysql_fetch_array($q)) {
		if(preg_match("#tab_(.*)#",$a[name],$re)) {
			$return[$re[1]] = $a[value];
		}
	}
	return $return;
}
////////////////////////////
// SETUPTEMPLATES
////////////////////////////
function gettemplate($file) {
	return str_replace("\"","\\\"",implode("",file("templates/default/install/$file.html")));
}
////////////////////////////
// SAVE DATA IN COOKIE
////////////////////////////
function savedata($data,$name) {
	if(!is_array($data)) @setcookie("settings[".$name."|".$name."]",$data,time()+3600);
	else foreach($data as $a=>$b) {
		@setcookie("settings[".$name."|".$a."]",$b,time()+3600);
	}
}
////////////////////////////
// GET DATA FROM COOKIE
////////////////////////////
function getdata($var) {
	global $HTTP_COOKIE_VARS,$HTTP_SESSION_VARS;
	if(is_array($HTTP_COOKIE_VARS["settings"])) {
		foreach($HTTP_COOKIE_VARS["settings"] as $a=>$b) {
			list($set,$val) = explode("|",$a);
				if($set == $var) {$re[$val] = $b;}
		}
		return $re;
	} else return 0;
}


if(!$step) $step = "1";
session_start();

// HEADER
eval("\$output = \"".gettemplate("install.swora.head")."\";");


////////////////////////////
// SAVE STEPS
////////////////////////////
if($checkstep) {
	## CHECK DATABASE-CONNECTION
	if($checkstep == 1) {
		if(@mysql_connect($form[host],$form[username],$form[userpwd])) {
			if(@mysql_select_db($form[db])) {
				$dbdata 	= $form;
						savedata($dbdata,"dbdata");
						header("LOCATION: install.php?step=2");
			} else 	{eval("\$fail = \"".gettemplate("install.swora.fail.nodb")."\";");$step = 1;}
		} else  	{eval("\$fail = \"".gettemplate("install.swora.fail.nodbconnection")."\";");$step = 1;}
	}
	## CREATE TABLES
	if($checkstep == 2) {
		$dbdata = getdata("dbdata");
		$successfullinstalledtables=0;$failed="";$failedinstalltables=0;$successfullinstalledvalues=0;$failedinstallvalues=0;
		if(@mysql_connect($dbdata[host],$dbdata[username],$dbdata[userpwd])) {
			if(mysql_select_db($dbdata[db])) {
				$tabs 		= $form;
				savedata($tabs[config],"configtab");

				foreach($create as $tab=>$line) {
					eval("\$line = \"$line\";");
					if(mysql_query($line)) 	{$successfullinstalledtables++;}
					else			{$error=mysql_error();$failedinstalltables++;eval("\$failed[$failedinstalltables] =\"". mysql_error()."\";");}
					//eval("\$output .= \"".gettemplate("in	stall.swora.tabelcreated")."\";");
				}

				foreach($insert as $tab=>$a) {
					foreach($a as $line) {
						eval("\$line = \"$line\";");
						if(mysql_query($line)) 	{$successfullinstalledvalues++;}
						else			{$error=mysql_error();$failedinstallvalues++;eval("\$failedv[$failedinstallvalues] .= \"".mysql_error()."\";");}
					}
				}
				eval("\$output .= \"".gettemplate("install.swora.finished.step2")."\";");
				if($failedinstallvalues || $failedinstalltables) {
					foreach($failed as $a) $failures_tables .= "$a<br>";
					foreach($failedv as $a) $failures_values .= "$a<br>";
					eval("\$output .= \"".gettemplate("install.swora.tablecreated.fail")."\";");
				} else {
					eval("\$output .= \"".gettemplate("install.swora.tablecreated")."\";");
				}
				$nostep = TRUE;
			} else 	{eval("\$fail = \"".gettemplate("install.swora.fail.nodbconnection")."\";");$step = 1;}
		} else  	{eval("\$fail = \"".gettemplate("install.swora.fail.nodb")."\";");$step = 1;}
	}
	## SAVE ADMIN
	if($checkstep == 3) {
		if($form[admin_pwd] != $form[admin_pwd2]) {eval("\$fail = \"".gettemplate("install.swora.fail.pwdsnotsame")."\";");$step=3;}
		else {
			$dbdata 	= getdata("dbdata");
			$configtab 	= getdata("configtab");
			$admin 		= $form;
			$successfullinstalledvalues=0;$failed=0;$failures="";
					savedata($admin,"admin");
	
			if(@mysql_connect($dbdata[host],$dbdata[username],$dbdata[userpwd])) {
				if(mysql_select_db($dbdata[db])) {
					$tabs 			= gettables();
					$date 			= time();
					$form[admin_pwd] 	= md5($form[admin_pwd]);
					foreach($insertlater as $tab=>$a) {
						foreach($a as $line) {
							eval("\$line = \"$line\";");
							if(mysql_query($line)) 	{$successfullinstalledvalues++;}
							else			{$failed++;;eval("\$failures[$failed] = \"".mysql_error()."\";");}
						}
					}
					eval("\$output .= \"".gettemplate("install.swora.finished.step3")."\";");
					if($failed) {
						foreach($failures as $a) $failtext .= "$a<br>";
						eval("\$output .= \"".gettemplate("install.swora.insert.fail")."\";");
					} else {
						eval("\$output .= \"".gettemplate("install.swora.insert")."\";");
					}
					$nostep = TRUE;
				} else 	{eval("\$fail = \"".gettemplate("install.swora.fail.nodbconnection")."\";");$step = 1;}
			} else  	{eval("\$fail = \"".gettemplate("install.swora.fail.nodb")."\";");$step = 1;}
}	}	}

////////////////////////////
// DISPLAY STEPS
////////////////////////////
if($step && !$nostep) {
	if($step!=1) {
		$dbdata 	= getdata("dbdata");
		$configtab 	= getdata("configtab");
		$admin 		= getdata("admin");
	}
	######
	if($step == 1) {eval("\$output .= \"".gettemplate("install.swora.step1")."\";");}
	if($step == 2) {eval("\$output .= \"".gettemplate("install.swora.step2")."\";");}
	if($step == 3) {
		$str = $HTTP_HOST.$REQUEST_URI;
		preg_match("#^(http(s)?://)?(www.)?(.*)/install.php(.*)#i",$str,$re);
		$url = "http://www.".$re[4];
		eval("\$output .= \"".gettemplate("install.swora.step3")."\";");
	}
	if($step == 4) {
		eval("\$entry .= \"".gettemplate("install.swora.configfile.entry")."\";");
		eval("\$output .= \"".gettemplate("install.swora.step4")."\";");
	}
}
/////////////////////////////////////////////////////////////
eval("\$output .= \"".gettemplate("install.swora.foot")."\";");
echo $output;
?>
