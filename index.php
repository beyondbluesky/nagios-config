<?php
ob_start();

include_once "include/nagios-config.php";
include_once "include/sNDO.php";
include_once "include/sSnmp.php";
include_once "include/nagios.php";
include_once "include/file.php";

$wwwroot="/nagios-config";

$accio=$_POST['sAccio'];
if( ! isset( $accio )){
        $accio= $_GET['sAccio'];
}

if( isset($accio)){

        if( $accio == "snmp" ){
                $sTitle= "Informacio SNMP del host";


                $hostname=$_POST['sHost'];
                if( ! isset( $hostname )){
                        $hostname= $_GET['sHost'];
                }

                $community=$_POST['sCommunity'];
                if( ! isset( $community )){
                        $community= $_GET['sCommunity'];
                }

                $host= new SnmpServer($hostname, $community);

                $sysname= $host->getSysName();
                $contact= $host->getContact();
                $desc= $host->getDescr();
                $loc= $host->getLocation();

        }
        else if( $accio == "nagios" ){
                $sTitle= "Informacio del Nagios";

                $hostname=$_POST['sHost'];
                $community=$_POST['sCommunity'];

                //echo "Host: ".$hostname." Community: ".$community."<br>\n";

                $procs=$_POST['procs'];
                $ifaces=$_POST['iface'];
                $stor=$_POST['stor'];

                $host= new SnmpServer($hostname,$community);

                $sysname= $host->getSysName();
                $sysdesc= $host->getDescr();

                //echo "Sys: ".$sysname. " Desc: ".$sysdesc;

                $ndo= new NDOServer();
        }
        else if( $accio == "nagios-res" ){
            $sTitle= "Informacio del Nagios";

            $hostname=$_POST['sHostIP'];
            $community=$_POST['sCommunity'];
            $sysname=$_POST['sHost'];
            $sysdesc=$_POST['sDesc'];
            $nagHG=$_POST['sNagiosHG'];
            $nagCG=$_POST['sNagiosCG'];
            $procs= unserialize(stripslashes($_POST['sProcs']));
            $ifaces= unserialize(stripslashes($_POST['sIfaces']));
            $stor= unserialize(stripslashes($_POST['sStor']));
            $nagAlarm= $_POST['sNagiosAlarmHour'];
            $nagTempl= $_POST['sNagiosTemplate'];
            $nagParent= $_POST['sNagiosParent'];

            $ndo= new NDOServer();
    }
    else if( $accio == "nagiosout" ){
            $sTitle= "Resum final";

            $hostname=$_POST['sHostIP'];
            $community=$_POST['sCommunity'];
            $sysname=$_POST['sHost'];
            $sysdesc=stripslashes($_POST['sDesc']);
            $nagHG=unserialize(stripslashes($_POST['sNagHG']));
            $nagCG=unserialize(stripslashes($_POST['sNagCG']));
            $procs= $_POST['procs'];
            $procs_warn= $_POST['proc_warn'];
            $procs_err= $_POST['proc_err'];
            $procs_sg= $_POST['sNagiosSG'];
            $ifaces= unserialize(stripslashes($_POST['sIfaces']));
            $stor= unserialize(stripslashes($_POST['sStor']));
            $nagAlarm= $_POST['sNagiosAlarmHour'];
            $nagTempl= $_POST['sNagiosTemplate'];
            $nagParent= $_POST['sNagiosParent'];

    }
}
?>
<html>
<head>

<title>Nagios-Config: <?= $sTitle ?></title>
<link rel="stylesheet" type="text/css" href="/css-fib/fib.css">

</head>
<body>
<div id="overDiv" style="position: absolute;"></div>
<div align="center"><font size="1"></font>
<p><font size="1">&nbsp;</font></p><div align="right"></div><table align="center" border="0" cellpadding="2" cellspacing="2" width="690"><tbody><tr><td colspan="2" bgcolor="#ffffff" height="43" valign="middle"><a href="http://www.fiberpachs.com/" target="_top"> <img src="/img-fib/fiberpachs.png" alt="Fiberpachs" border="0" width="154"></a></td><td align="right" bgcolor="#ffffff">&nbsp;&nbsp;<a href="index.php" class="bodylink">Inici</a></td></tr><tr><td align="left" bgcolor="#ffffff" valign="middle">&nbsp;</td></tr></tbody></table>

<font face="arial, verdana, helvetica">

<?php
if( isset($accio)){

        if( $accio == "snmp" ){
                echo "<FORM ENCTYPE=\"multipart/form-data\" name=\"sSnmp\" action=\"" . $wwwroot . "/
                        index.php\" METHOD=\"post\">
                        <INPUT TYPE=\"hidden\" NAME=\"sAccio\" VALUE=\"nagios\">
                        <INPUT TYPE=\"hidden\" NAME=\"sHost\" VALUE=\"".$hostname."\">
                        <INPUT TYPE=\"hidden\" NAME=\"sCommunity\" VALUE=\"".$community."\">";
                echo "<h2>Informaci&oacute; general</h2>\n";
                echo "<b>Nom de maquina:</b> " . $sysname."<br>\n";
                echo "<b>Contacte:</b> " . $contact."<br>\n";
                echo "<b>Descripcio:</b> " . $desc."<br>\n";
                echo "<b>Localitzacio:</b> " . $loc."<br>\n";

                echo "<h2>Interficies de xarxa</h2>" . sSnmpGetIfaces($host)."<br>\n";
                echo "<h2>Emmagatzematge</h2>" . sSnmpGetStorage($host)."<br>\n";
                echo "<h2>Processos</h2>" . sSnmpGetProcess($host)."<br>\n";
                echo "<table border='0' width='690'><tr><td><INPUT TYPE=\"submit\" VALUE=\"Enviar\"></td></tr></table>";
                echo "</FORM>";
        }
        else if( $accio == "nagios" ){
                echo "<FORM ENCTYPE=\"multipart/form-data\" name=\"sSnmp\" action=\"" . $wwwroot . "/
                        index.php\" METHOD=\"post\">
                        <INPUT TYPE=\"hidden\" NAME=\"sAccio\" VALUE=\"nagios-res\">
                        <INPUT TYPE=\"hidden\" NAME=\"sHostIP\" VALUE=\"".$hostname."\">
                        <INPUT TYPE=\"hidden\" NAME=\"sCommunity\" VALUE=\"".$community."\">
                        <INPUT TYPE=\"hidden\" NAME=\"sProcs\" VALUE=\"".htmlspecialchars(serialize($procs))."\">
                        <INPUT TYPE=\"hidden\" NAME=\"sIfaces\" VALUE=\"".htmlspecialchars(serialize($ifaces))."\">
                        <INPUT TYPE=\"hidden\" NAME=\"sStor\" VALUE=\"".htmlspecialchars(serialize($stor))."\">";
                echo "<h2>Informaci&oacute; general</h2>\n";
                echo "IP de maquina: " . $hostname."<br>\n";
                echo "Nom de host: <INPUT TYPE=\"text\" value=\"".$sysname."\" name=\"sHost\"><br>\n";
                echo "Descripcio: <INPUT TYPE=\"text\" value=\"".$sysdesc."\" name=\"sDesc\"><br>\n";
                echo "<h2>Configuraci&oacute; de nagios</h2>\n";
                echo "Tipus de host: <select name=\"sNagiosTemplate\">\
                        <option value=\"windows-server\">Windows Server</option>
                        <option value=\"linux-server\">Linux Server</option>
                        <option value=\"vmware-server\">VMware ESX Server</option>
                        <option value=\"generic-host\">Generic Server</option>
                        <option value=\"generic-switch\">Switch</option>
                        <option value=\"generic-router\">Router</option>
                        </select><br>\n";
                echo "Tipus d'horari per a les alarmes: <select name=\"sNagiosAlarmHour\">\
                        <option value=\"24x7\">24x7</option>
                        <option value=\"8x5\">8x5</option>
                        </select><br>\n";

                echo "Pare d'on dependr&agrave; el host: ". sNDOChooseParents($ndo)."<br>\n";
                echo "Grup de hosts on s'ubica: ". sNDOChooseHostGroups($ndo)."<br>\n";
                echo "Grup de contactes per a alarmes: ". sNDOChooseContactGroups($ndo)."<br>\n";
                echo "<table border='0' width='690'><tr><td><INPUT TYPE=\"submit\" VALUE=\"Enviar\"></td></tr></table>";
                echo "</FORM>";
        }
        else if( $accio == "nagios-res" ){
                echo "<FORM ENCTYPE=\"multipart/form-data\" name=\"sSnmp\" action=\"" . $wwwroot . "/
                        index.php\" METHOD=\"post\">
                        <INPUT TYPE=\"hidden\" NAME=\"sAccio\" VALUE=\"nagiosout\">
                        <INPUT TYPE=\"hidden\" NAME=\"sHostIP\" VALUE=\"".$hostname."\">
                        <INPUT TYPE=\"hidden\" NAME=\"sHost\" VALUE=\"".$sysname."\">
                        <INPUT TYPE=\"hidden\" NAME=\"sCommunity\" VALUE=\"".$community."\">
                        <INPUT TYPE=\"hidden\" NAME=\"sNagHG\" VALUE=\"".htmlspecialchars(serialize($nagHG))."\">
                        <INPUT TYPE=\"hidden\" NAME=\"sNagCG\" VALUE=\"".htmlspecialchars(serialize($nagCG))."\">
                        <INPUT TYPE=\"hidden\" NAME=\"sDesc\" VALUE=\"".$sysdesc."\">
                        <INPUT TYPE=\"hidden\" NAME=\"sNagiosTemplate\" VALUE=\"".$nagTempl."\">
                        <INPUT TYPE=\"hidden\" NAME=\"sNagiosAlarmHour\" VALUE=\"".$nagAlarm."\">
                        <INPUT TYPE=\"hidden\" NAME=\"sNagiosParent\" VALUE=\"".$nagParent."\">
                        <INPUT TYPE=\"hidden\" NAME=\"sProcs\" VALUE=\"".htmlspecialchars(serialize($procs))."\">
                        <INPUT TYPE=\"hidden\" NAME=\"sIfaces\" VALUE=\"".htmlspecialchars(serialize($ifaces))."\">
                        <INPUT TYPE=\"hidden\" NAME=\"sStor\" VALUE=\"".htmlspecialchars(serialize($stor))."\">";
                echo "<h2>Informaci&oacute; general</h2>\n";
                echo "<table border='0' width='690'>";
                echo "<tr><td><b>IP de maquina:</b></td><td>" . $hostname."</td></tr>\n";
                echo "<tr><td><b>Nom de host:</b></td><td>" . $sysname."</td></tr>\n";
                echo "<tr><td><b>Descripci&oacute;:</b></td><td>" . $sysdesc."</td></tr>\n";
                echo "</table>";

                echo "<h2>Informaci&oacute; d'Interfaces </h2>\n";
                echo "<table border='0' width='690'>";
                echo "<tr>";
                if(isset($ifaces)) foreach($ifaces as $id=>$iface){
                        echo "<tr><td>".$iface."</td></tr>";
                }
                echo "</table>";

                echo "<h2>Informaci&oacute; de discos</h2>\n";
                echo "<table border='0' width='690'>";
                echo "<tr>";
                if(isset($stor)) foreach($stor as $id=>$disk){
                        echo "<tr><td>".$disk."</td></tr>";
                }
                echo "</table>";

                echo "<h2>Informaci&oacute; de processos</h2>\n";
                echo "<table border='0' width='690'>";
                echo "<tr><td>Proces</td><td>Warn</td><td>Error</td><td>Grup de serveis</td>";
                echo "<tr>";
                if(isset($procs)) foreach($procs as $id=>$proc){
                        echo "<tr><td><INPUT TYPE=\"text\" value=".str_replace("\\","",$proc)." name=\"procs[]\"></td>";
                        echo "<td><INPUT TYPE=\"text\" name=\"proc_warn[]\" size=\"3\" maxlength=\"3\"></td>";
                        echo "<td><INPUT TYPE=\"text\" name=\"proc_err[]\" size=\"3\" maxlength=\"3\"></td>";
                        echo "<td>". sNDOChooseServiceGroups($ndo)."</td></tr>";
                        echo "<!-- Contact_groups &&  Servicegroups -->";
                }
                echo "</table>";

                echo "<table border='0' width='690'><tr><td><INPUT TYPE=\"submit\" VALUE=\"Enviar\"></td></tr></table>";
                echo "</FORM>";

        }
        else if( $accio == "nagiosout" ){
                $out=sNagiosHost($sysname,$nagTempl,$hostname,$nagAlarm,$sysdesc,$nagParent,$nagHG[0],$nagCG[0]);

                echo "<table width='80%'><tr><td align='left'>";

                global $SYS_SLASH;
                global $NAGIOS_BASE;
                global $NAGIOS_BASE_CFG;

                $base= $NAGIOS_BASE_CFG[$TEMPLATE_TO_PATH[$nagTempl]];

                $config_file= $base.$SYS_SLASH.$sysname.".cfg";

                ?><center>Host creat correctament a -<?php $config_file ?>- Contingut: </center><?php
                ?><pre><?php echo $out ?></pre><?php

                $file= new File($config_file);
                $res= $file->write($out);

                if($res === FALSE ){
                        ?><b>Error escrivint fitxer a directori <?php echo $config_file ?></b><?php
                }

                $community=$_POST['sCommunity'];

                if(isset($ifaces)) foreach($ifaces as $id=>$iface){
                        $srv= new Service ("snmp-int",array("command"=>"check_snmp_int_v1!".$community."!"));
                        $srv->setDesc($iface);
                        $srv->setName($iface);
                        $srv->setHost($sysname);
                        $file->write("\n\n");
                        $file->write($srv);
                        echo "<pre>".$srv."</pre>";
                }

                if(isset($stor)) foreach($stor as $id=>$disk){
                        $srv= new Service ("snmp-stor",array("command"=>"check_snmp_storage_v1!".$community."!"));
                        $srv->setDesc($disk);
                        $srv->setName($disk);
                        $srv->setHost($sysname);
                        $file->write("\n\n");
                        $file->write($srv);
                        echo "<pre>".$srv."</pre>";
                }

                /*
                foreach($procs_err as $id=>$val) echo "Proc $id: $val";
                foreach($procs_sg as $id=>$val) echo "Proc $id: $val";
                */

                if(isset($procs)) foreach($procs as $id=>$proc){
                        $srv= new ProcessService ("snmp-proc",array("command"=>"check_snmp_process_v1!".$community."!"));
                        $srv->setDesc($proc);
                        $srv->setName($proc);
                        $srv->setHost($sysname);
                        $srv->setWarn($procs_warn[$id]);
                        $srv->setErr($procs_err[$id]);
                        $srv->setSG($procs_sg[$id]);
                        $file->write("\n\n");
                        $file->write($srv);
                        echo "<pre>".$srv."</pre>";
                }

                echo "</td></tr></table>";

                nagios::sendCommand("RESTART_PROGRAM");
            }
        }else {
        
                $output="
        <H1>Afegir host a Nagios</H1>
        <FORM ENCTYPE=\"multipart/form-data\" name=\"sSnmp\" action=\"" . $wwwroot . "/
        index.php\" METHOD=\"post\">
                <INPUT TYPE=\"hidden\" NAME=\"sAccio\" VALUE=\"snmp\">
                <div align=\"center\">
                <table border=0>
                <tr>
                        <td align=\"right\">Host a afegir</td>
                        <td align=\"left\"><INPUT TYPE=\"text\" NAME=\"sHost\"></td>
                </tr>
                <tr>
                        <td align=\"right\">Comunitat</td>
                        <td align=\"left\"><INPUT TYPE=\"text\" NAME=\"sCommunity\"></td>
                </tr>
                <tr><td colspan=\"2\">
                        <INPUT TYPE=\"submit\" VALUE=\"Enviar\">
                </td></tr>      </table>
                </table>
                </div>
        </FORM>
        ";
                echo $output;
        }
        
        ?>
        </body>
        </html>
        