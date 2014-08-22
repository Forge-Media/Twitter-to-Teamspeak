<?PHP

/*
sendtweet.php
Is a small script which can send a text variable to Teamspeak 3 via ts3admin.class
by Jeremy Paton - Forge Media

Requires: Twitter.php (Twitter API)
Requires: ts3admin.class.php (http://ts3admin.info)

Please note this script uses CRON. Please settup a cron-job to call this script as often as possible,
ideally 5 - 15min intervals.

* /15 * * * *	/usr/bin/php /home/directory/public_html/directory/sendmsg.php >/dev/null 2>&1

/*-------SETTINGS-------*/

$ts3_ip = '';
$ts3_queryport = 10011;
$ts3_user = ''; #Avoid using serveradmin
$ts3_pass = '';
$ts3_port = 9987;
$mode = 3; : #1: send to client | 2: send to channel | 3: send to server
$target = 1; #serverID
$botName = 'Forge Media Twitter Bot';
$file = 'lasttweet.txt'; #Simple text file stores last tweet

/*----------------------*/

#Include ts3admin.class.php  & Twitter.php
require("library/ts3admin.class.php");
require("library/Twitter.php");


#build a new ts3admin object
$tsAdmin = new ts3admin($ts3_ip, $ts3_queryport);


if($tsAdmin->getElement('success', $tsAdmin->connect())) {
	
	#login as serveradmin
	$tsAdmin->login($ts3_user, $ts3_pass);

	#select teamspeakserver
	$tsAdmin->selectServer($ts3_port);

	#set bot name
	$tsAdmin->setName($botName);

	#open the file to get existing content
	$lasttweet = file_get_contents($file);


	if ($lasttweet != $tweetText) 
		{
			#send message
			$tsAdmin->sendMessage($mode, $target, $tweetText);

			#Write the contents to the file,
			#and the LOCK_EX flag to prevent anyone else writing to the file at the same time
			file_put_contents($file, $tweetText, LOCK_EX);
		}
	}
	else{

		echo 'Connection to server could not be established.';

}

/**

 * This code retuns all errors from the debugLog

 */

if(count($tsAdmin->getDebugLog()) > 0) {

	foreach($tsAdmin->getDebugLog() as $logEntry) {

		echo '<script>alert("'.$logEntry.'");</script>';
	}
}

?>