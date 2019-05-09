<?php 
	/*
	Autor: Ilie Florea 
	email: virginialyonsit@gmail.com 
	Version: 1.0
	*/
	include("conexion.php");
	
	$id=$_GET["Id"];
	$consulta="DELETE FROM trabajador WHERE id_trabajador='".$id."'";
	mysqli_query($conexion, $consulta);
	$mensaje=mysqli_affected_rows($conexion);
	$_GET['mensaje']=$mensaje;
	echo("<script>location.href ='admin.php?mensaje=".$mensaje."';</script>");
	header("Location: admin.php?mensaje=".$mensaje);
	echo("<script>location.href ='admin.php?mensaje=".$mensaje." !';</script>");
	mysqli_close($conexion);
?>
