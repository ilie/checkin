<?php 
	include("conexion.php");
	
	$id=$_GET["id_descanso"];
	$consulta="DELETE FROM descanso WHERE id_descanso='".$id."'";
	mysqli_query($conexion, $consulta);
	$mensaje=mysqli_affected_rows($conexion);
	header("Location: checkins.php?mensaje=<strong>OK</strong>,".$mensaje." Record has been successfuly deleted !");
	echo("<script>location.href ='checkins.php?mensaje=<strong>OK</strong>, ".$mensaje."Record has been successfuly deleted!';</script>");
	mysqli_close($conexion);
?>