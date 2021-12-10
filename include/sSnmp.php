<?php
/*
* Funcions d'usuari emprant la classe snmp
*
* Data Modif.   : 2009-12-10
*
* Des de        : 0.1
* Data Creacio  : 2009-12-10
*
* Autor         : Josep Llaurado Selvas <pep@susipep.com>
*/
include_once "snmp.php";

function sSnmpGetIfaces($host){

        $numIfaces= $host->getIfaceNum()."<br>\n";

        $out="<table>";
        $out= $out."<tr><td></td><td align=\"center\" class=\"parahead1\" bgcolor=\"#dddddd\">Interface</td><td align=\"center\" class=\"parahead1\" bgcolor=\"#dddddd\">Direcci&oacute;</td></tr>";
	$row=0;
	$oids= $host->getIfaceOIDs();
	foreach($oids as $oid){
		$strRow= ($row==0)?"#ffffff":"#eeeeee";
                $out= $out."<tr>";
                $out= $out."<td align=\"center\" class=\"bodycopy\" bgcolor=\"".$strRow."\"><input type=\"checkbox\" name=\"iface[]\" value=\"".$host->getIfaceDescr($oid)."\"></td>";
                $out= $out."<td align=\"right\" class=\"bodycopy\" bgcolor=\"".$strRow."\">".$host->getIfaceDescr($oid)."</td>";
                $out= $out."<td class=\"bodycopy\" bgcolor=\"".$strRow."\">".$host->getIfaceMAC($oid)."</td>";
                $out= $out."</tr>\n";
		$row= ($row==0)?1:0;
        }
        $out= $out."</table>";

        return $out;
}

function sSnmpGetStorage($host){


        $out="<table>";
        $out= $out."<tr><td></td><td align=\"center\" class=\"parahead1\" bgcolor=\"#dddddd\"><span class=\"parahead1\">Storage</span></td><td align=\"center\" class=\"parahead1\" bgcolor=\"#dddddd\">Tipus</td><td align=\"center\" class=\"parahead1\" bgcolor=\"#dddddd\">Mida</td><td align=\"center\" class=\"parahead1\" bgcolor=\"#dddddd\">Lliure</td></tr>";
        $storage=$host->getStorageArray();
	$row=0;
        foreach($storage as $i){
		if( $host->getStorageType($i) != "Memoria" ){
			$strRow= ($row==0)?"#ffffff":"#eeeeee";
	                $out= $out."<tr>";
	                $out= $out."<td align=\"center\" class=\"bodycopy\" bgcolor=\"".$strRow."\"><input type=\"checkbox\" name=\"stor[]\" value=\"".$host->getStorageDescr($i)."\"></td>";
	                $out= $out."<td align=\"left\" class=\"bodycopy\" bgcolor=\"".$strRow."\">".$host->getStorageDescr($i)."</td>";
	                $out= $out."<td align=\"center\" class=\"bodycopy\" bgcolor=\"".$strRow."\">".$host->getStorageType($i)."</td>";
	                $out= $out."<td align=\"right\" class=\"bodycopy\" bgcolor=\"".$strRow."\">".sSize2Human($host->getStorageSize($i))."</td>";
	                $out= $out."<td align=\"right\" class=\"bodycopy\" bgcolor=\"".$strRow."\">".sSize2Human($host->getStorageUsed($i))."</td>";
	                $out= $out."</tr>\n";
			$row= ($row==0)?1:0;
		}
        }
        $out= $out."</table>";

        return $out;
}

function sSnmpGetProcess($host){


        $out="<table>";
        $out= $out."<tr><td></td><td align=\"center\" class=\"parahead1\" bgcolor=\"#dddddd\">PID</td><td align=\"center\" class=\"parahead1\" bgcolor=\"#dddddd\">Process Name</td><td align=\"center\" class=\"parahead1\" bgcolor=\"#dddddd\">Path</td><td align=\"center\" class=\"parahead1\" bgcolor=\"#dddddd\">Parameters</td></tr>";
        $procs=$host->getProcessArray();
	$row=0;
        foreach($procs as $id){
		$strRow= ($row==0)?"#ffffff":"#eeeeee";
                $out= $out."<tr>";
                $out= $out."<td align=\"center\" class=\"bodycopy\" bgcolor=\"".$strRow."\"><input type=\"checkbox\" name=\"procs[]\" value=\"".htmlspecialchars($host->getProcessName($id))."\"></td>";
                $out= $out."<td align=\"right\" class=\"bodycopy\" bgcolor=\"".$strRow."\">".$id."</td>";
                $out= $out."<td align=\"left\" class=\"bodycopy\" bgcolor=\"".$strRow."\">".$host->getProcessName($id)."</td>";
                $out= $out."<td align=\"left\" class=\"bodycopy\" bgcolor=\"".$strRow."\">".$host->getProcessPath($id)."</td>";
                $out= $out."<td align=\"left\" class=\"bodycopy\" bgcolor=\"".$strRow."\">".$host->getProcessParams($id)."</td>";
                $out= $out."</tr>\n";
		$row= ($row==0)?1:0;
        }
        $out= $out."</table>";

        return $out;
}

function sSize2Human($size){

        $mod=1024;
        $units= explode(' ','B KB MB GB TB PB');
        for($i=0; $size>$mod; $i++){
                $size /= $mod;
        }

        return round($size,2).' '.$units[$i];
}

?>
