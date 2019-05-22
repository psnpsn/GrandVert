<?php
// getting data from database
$conn= mysqli_connect("localhost","root","","garden");
// getting data from table
$result= mysqli_query($conn,"SELECT nom FROM plante WHERE plante.proposition=1");
//storing in array
$data=array();
while ($row= mysqli_fetch_assoc($result)){
    $data[]=$row;
}
echo json_encode($data);