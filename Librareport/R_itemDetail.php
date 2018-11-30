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
set_time_limit(220);
include ('connection.php');
#Connect to a System DSN "QuickBooks Data" with no user or password
$oConnect = odbc_connect("QuickBooks Data", "", "");

#Set the SQL Statement
$sSQL = "sp_report SalesByItemDetail show Text, Blank, TxnType, Date, RefNumber, Memo, Name, Quantity, UnitPrice, Amount parameters DateFrom = {d'2018-10-01'}, DateTo= {d'2018-10-02'}";

#Perform the query
$oResult = odbc_exec($oConnect, $sSQL);
$lFldCnt = 0;
$lFieldCount = odbc_num_fields($oResult);
$keys = array();

while ($lFldCnt < $lFieldCount) {
	$lFldCnt++;
        $sFieldName = odbc_field_name($oResult, $lFldCnt);
		 array_push($keys, $sFieldName);
	
}
while(odbc_fetch_row($oResult)) {
	$lRecCnt++;
	$values = array();
	$lFldCnt = 0;
	$lFieldCount = odbc_num_fields($oResult);
	while ($lFldCnt < $lFieldCount) {
		$lFldCnt++;
		$sFieldValue = trim(odbc_result($oResult, $lFldCnt));
		 array_push($values, $sFieldValue);
	}
	$resInsert = mysqli_query($link, "INSERT INTO r_salesbyitemdetail (" . implode(", ", $keys) . ") VALUES ('" . implode("', '", $values) . "')"); // insert one row into new table
		if ($resInsert)
		{
			
			echo "r_salesbyitemdetail--Insert :Success";

		}
		else
		{
			print ("r_salesbyitemdetail--INSERT INTO r_salesbyitemdetail (" . implode(", ", $keys) . ") VALUES ('" . implode("', '", $values) . "')<br>");

		}

}
#Close the connection
odbc_close($oConnect);
mysqli_close($link);


?>
                </tfoot>
            </table>
        </td>
    </tr>
</table>
</body>
</html>