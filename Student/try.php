<?php

include '../Includes/dbcon.php';
include '../Includes/session.php';

// Get the rowCount with the WHERE condition
$query1 = "SELECT COUNT(*) as rowCount FROM `tblattendance`
    WHERE `admissionNo` = '$_SESSION[admissionNumber]' AND `status` = 1";
$rsk = $conn->query($query1);
$numk = $rsk->num_rows;
$rrwk = $rsk->fetch_assoc();

// Get the total rowCount without the WHERE condition
$query2 = "SELECT COUNT(*) as totalRowCount FROM `tblattendance`";
$rsk2 = $conn->query($query2);
$numk2 = $rsk2->num_rows;
$rrwk2 = $rsk2->fetch_assoc();

echo "RowCount with condition: " . $rrwk['rowCount'];
echo "<br>";
echo "Total RowCount: " . $rrwk2['totalRowCount'];
echo "<br>";
echo "Hello";
?>