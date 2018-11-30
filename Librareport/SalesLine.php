<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
    <title>QODBC PHP Script To Display SQL Results</title>
</head>
<body topmargin="3" leftmargin="3" marginheight="0" marginwidth="0" bgcolor="#ffffff" link="#000066" vlink="#000000" alink="#0000ff" text="#000000">
<table border="0" border="0" bgcolor="lightgreen" bordercolor="black" cellpadding="0" cellspacing="0">
    <tr>
        <td>
            <table border="2" bordercolor="black" bgcolor="white" cellpadding="5" cellspacing="0">
                <thead>
                    <caption align="top">QODBC PHP Script To Display SQL Results</caption>
                    <tr>
                        <th>Row</th>
<?php
set_time_limit(1200);

#Connect to a System DSN "QuickBooks Data" with no user or password
$oConnect = odbc_connect("QuickBooks Data QRemote", "", "");

#Set the SQL Statement
$sSQL = "SELECT * FROM SalesLine where TimeModified >= {ts '2018-10-01 00:00:00.000'}";

#Perform the query
$oResult = odbc_exec($oConnect, $sSQL);
$lFldCnt = 0;
$lFieldCount = odbc_num_fields($oResult);
while ($lFldCnt < $lFieldCount) {
	$lFldCnt++;
        $sFieldName = odbc_field_name($oResult, $lFldCnt);
	print("                        <th>$sFieldName</th>\n");
}
?>
                    </tr>
                </thead>
                <tbody>
<?php
$lRecCnt = 0;
#Fetch the data from the database
while(odbc_fetch_row($oResult)) {
	$lRecCnt++;
	print("                    <tr>\n");
	print("                        <td>$lRecCnt</td>\n");
	$lFldCnt = 0;
	$lFieldCount = odbc_num_fields($oResult);
	while ($lFldCnt < $lFieldCount) {
		$lFldCnt++;
		$sFieldValue = trim(odbc_result($oResult, $lFldCnt));
		If ($sFieldValue == "") {
			print("                        <td> </td>\n");
		}
		else {
			print("                        <td valign=\"Top\">$sFieldValue</td>\n");
		}
	}
	print("                    </tr>\n");
}
#Close the connection
odbc_close($oConnect);
?>
                </tbody>
                <tfoot>
<?php
print("                    <caption align=\"bottom\">Results of: $sSQL</caption>");
?>
                </tfoot>
            </table>
        </td>
    </tr>
</table>
</body>
</html>