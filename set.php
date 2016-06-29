<?php
$LIZI = $_POST['LIZI'];
$GENERATION = $_POST['GENERATION'];
$W = $_POST['W'];
$C1 = $_POST['C1'];
$C2 = $_POST['C2'];

$con = mysql_connect("localhost","root","");//连接数据库
if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }
mysql_select_db("pso", $con);
mysql_query('set names GB2312');
mysql_query("DELETE FROM data WHERE id=0");
$sql="insert into `pso`.`data` (LIZI,GENERATION,W,C1,C2) values ('$LIZI','$GENERATION','$W','$C1','$C2')"; //数据入库
if (!mysql_query($sql,$con))
  {
  die('Error: ' . mysql_error());
  }
mysql_close($con);

require("index.php")
?>
