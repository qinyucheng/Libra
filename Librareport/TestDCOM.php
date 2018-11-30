<% @Language = "VBScript" %>
<% Response.Buffer = true %>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
    <title>QODBC PHP DCOM Access Test Page</title>
</head>
<body topmargin="3" leftmargin="3" marginheight="0" marginwidth="0" bgcolor="#ffffff" link="#000066" vlink="#000000" alink="#0000ff" text="#000000">
<table border="0" border="0" bgcolor="lightgreen" bordercolor="black" cellpadding="0" cellspacing="0">
    <tr>
        <td>
            <table border="2" bordercolor="black" bgcolor="white" cellpadding="5" cellspacing="0">
                <caption align="top">QODBC PHP DCOM Access Test Page</caption>
                <thead>
                    <tr>
                        <th>QB2002</th>
                        <th>QB2003</th>
                        <th>QB2004</th>
                        <th>QB2005</th>
                        <th>QB2006</th>
                        <th>QB2007</th>
                        <th>QB2008</th>
                        <th>QB2009</th>
                        <th>QB2010</th>
                        <th>QB2011</th>
                        <th>DCOM</th>
                        <th>COM</th>
                        <th>Object Name</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td align="center"> </td>
                        <td align="center"> </td>
                        <td align="center">•</td>
                        <td align="center">•</td>
                        <td align="center">•</td>
                        <td align="center">•</td>
                        <td align="center">•</td>
                        <td align="center">•</td>
                        <td align="center">•</td>
                        <td align="center">•</td>
                        <td align="center">•</td>
                        <td align="center"> </td>
                        <td>QBXMLRP2EQODBCInteractive.exe</td>
<?php
#Test QB2004 DCOM Requestor
$lGlobalErrorNo = 0;
$sGlobalErrorMsg = "";
$oQBXMLRP2EQODBCInteractive = new COM("QBXMLRP2EQODBCInteractive.RequestProcessor");
if ($lGlobalErrorNo == 0) {
	echo("                        <td align=\"center\">Success</td>");
}
else {
	echo("                        <td align=\"center\">Failed: $lGlobalErrorNo - $sGlobalErrorMsg</td>");
}
unset($oQBXMLRP2EQODBCInteractive);
?>
                    </tr>

                    <tr>
                        <td align="center"> </td>
                        <td align="center"> </td>
                        <td align="center">•</td>
                        <td align="center">•</td>
                        <td align="center">•</td>
                        <td align="center">•</td>
                        <td align="center">•</td>
                        <td align="center">•</td>
                        <td align="center">•</td>
                        <td align="center">•</td>
                        <td align="center"> </td>
                        <td align="center">•</td>
                        <td>QBXMLRP2.dll</td>
<?php
#Test QB2004 COM Requestor
$lGlobalErrorNo = 0;
$sGlobalErrorMsg = "";
$oQBXMLRP2 = new COM("QBXMLRP2.RequestProcessor");
if ($lGlobalErrorNo == 0) {
	echo("                        <td align=\"center\">Success</td>");
}
else {
	echo("                        <td align=\"center\">Failed: $lGlobalErrorNo - $sGlobalErrorMsg</td>");
}
unset($oQBXMLRP2);
?>
                    </tr>

                    <tr>
                        <td align="center">•</td>
                        <td align="center">•</td>
                        <td align="center">•</td>
                        <td align="center">•</td>
                        <td align="center">•</td>
                        <td align="center">•</td>
                        <td align="center">•</td>
                        <td align="center">•</td>
                        <td align="center">•</td>
                        <td align="center">•</td>
                        <td align="center"> </td>
                        <td align="center">•</td>
                        <td>XERCES-COM.dll</td>
<?php
#Test QB2002, QB2003, QB2004 COM XML Parser
$lGlobalErrorNo = 0;
$sGlobalErrorMsg = "";
$oXERCES = new COM("Xerces.DOMDocument");
if ($lGlobalErrorNo == 0) {
	echo("                        <td align=\"center\">Success</td>");
}
else {
	echo("                        <td align=\"center\">Failed: $lGlobalErrorNo - $sGlobalErrorMsg</td>");
}
unset($oXERCES);
?>
                    </tr>

                    <tfoot>
<?php
#Show the current user
if (array_key_exists("LOGON_USER",$_SERVER)) {
	$sLogonUser = $_SERVER["LOGON_USER"];
}
else {
	$sLogonUser = "?????";
}

if (array_key_exists("AUTH_USER",$_SERVER)) {
	$sAuthUser = $_SERVER["AUTH_USER"];
}
else {
	$sAuthUser = "?????";
}

if (array_key_exists("PHP_AUTH_USER",$_SERVER)) {
	$sPHPAuthUser = $_SERVER["PHP_AUTH_USER"];
}
else {
	$sPHPAuthUser = "?????";
}
echo("                        <caption align=\"bottom\">Current Logged on User is: $sLogonUser<br>Authenticated user is: $sAuthUser<br>PHP Current Logged on User is: $sPHPAuthUser</caption>\n")
?>
                    </tfoot>

                </tbody>
            </table>
        </td>
    </tr>
</table>
</body>
</html>