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
<caption align="top">QODBC PHP Script To Display SQL Results</caption>
<?php
$conn = new PDO ("odbc:QuickBooks Data", "", "");
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$conn->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING );
$query = "SELECT TxnID,CustomerRefFullName,TxnDate,RefNumber,BillAddressAddr1,BillAddressAddr2,BillAddressCity,BillAddressState,BillAddressPostalCode,TermsRefFullName,DueDate,Subtotal,InvoiceLineItemRefFullName,InvoiceLineDesc,InvoiceLineQuantity,InvoiceLineRate,InvoiceLineAmount FROM InvoiceLine";
$stmt = $conn->prepare($query);
$stmt->execute();
$colcount = $stmt->columnCount();
foreach ($stmt->fetchall(PDO::FETCH_BOTH) as $row)
{
  echo '<tr>';
  for($i = 0; $i < $colcount;  $i++ ) {
		echo '<td>';
		print_r($row["$i"]);
		echo '</td>';
  }
  echo '</tr>';
}
?>
</table>
</td>
</tr>
</table>
</body>
</html>