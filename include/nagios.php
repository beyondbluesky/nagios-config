<?php
/*



*/

class Nagios {

	static $COMMAND_CGI="/nagios/cgi-bin/cmd.cgi";

	static function sendCommand($command){

		//$cmd= fopen($COMMAND_CMD,"rw") or die ("Unable to open file");
		//$time= time();
		//fwrite($cmd, "[".$time."] ".$command."\n");	
		//fclose($cmd);

		// FROM common.h
		$cmd_code= 0;
		$cmd_mode= 0;
		if( $command == "RESTART_PROGRAM") { 
			$cmd_code= 13;
			$cmd_mode= 2;
		}

		if( $cmd_code != 0 ){
			$cmd= curl_init("http://localhost".Nagios::$COMMAND_CGI."?cmd_typ=".$cmd_code."&cmd_mod=".$cmd_mode);
			curl_setopt($cmd, CURLOPT_USERPWD, "nagios-config".":"."F1b3rp2chs"); 
			$res= curl_exec($cmd);
			curl_close($cmd);
		}
	}
}

?>
