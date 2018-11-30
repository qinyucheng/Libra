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
include('connection.php');

$result = mysqli_query($link, "SELECT * FROM SalesReceiptLine");

$lFldCnt = 0;

while ($fieldinfo = mysqli_fetch_field($result)) {
    $lFldCnt++;
    $sFieldName = $fieldinfo->name;
    print("                        <th>$sFieldName</th>\n");
}
?>
                   </tr>
                </thead>
                <tbody>
<?php
$lRecCnt = 0;
#Fetch the data from the database
while ($row = mysqli_fetch_array($result)) {
    
    $lRecCnt++;
    print("                    <tr>\n");
    print("                        <td>$lRecCnt</td>\n");
    $lFldCnt     = 0;
    $lFieldCount = mysqli_num_fields($result);
    while ($lFldCnt < $lFieldCount) {
        
        $sFieldValue = $row[$lFldCnt];
        $lFldCnt++;
        
        If ($sFieldValue == "") {
            print("                        <td></td>\n");
        } else {
            print("                        <td valign=\"Top\">$sFieldValue</td>\n");
        }
        
        
    }
    
    
    print("                    </tr>\n");
}
#Close the connection

?>
               </tbody>
                <tfoot>
<?php
print("                    <caption align=\"bottom\">Results of:</caption>");
?>
               </tfoot>
            </table>
        </td>
    </tr>
</table>
</body>
</html>