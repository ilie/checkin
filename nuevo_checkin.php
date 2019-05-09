<?php 
session_start();
if (!isset($_SESSION['nombre'])) {
header("Location: index.php");
}
    /*
	Autor: Ilie Florea 
 	email: virginialyonsit@gmail.com 
 	Version: 1.0
    -------------------------------------------------------------------------------------
	INICIAMOS CONEXION CON LA BASE DE DATOS
    -------------------------------------------------------------------------------------
    */
    //Abrimos la conexion desde el archivo conexion.php con la siguiente fucccion.
    include('conexion.php');
    
    //Conmprobamos que no se puede accder a esta pagina sin usuario ni contras
    
	//////// Getting data form previous page 
	$id_trabajador=$_POST["trabajador"];
	$fecha=$_POST["fecha"];
	$h_entrada=$_POST["time_in"];
	$h_salida=$_POST["time_out"];
	//Comprobamos que no hay otro checkin el mismo dia
	$consulta="SELECT * from jornada WHERE fecha='".$fecha."' AND id_empleado='".$id_trabajador."' ";
	$control=mysqli_query($conexion, $consulta)  or die ("Error obteniendo Checkin!");
	$fila=mysqli_fetch_array($control);
	$id_jornada=$fila[0];
	$entrada=$fila[3];
	$salida=$fila[4];
	
	if (isset($entrada)){
		$mensaje="<strong>Error !</strong> You have another checkin registered on ".$fecha.", Plesae modify that one or delete it.";
		header("Location: checkins.php?error=".$mensaje);
		echo("<script>location.href ='checkins.php?error=".$mensaje."';</script>");
		} else {
			if ($h_entrada > $h_salida) {
				
				$mensaje="<strong>Error !</strong> Checkin must be earlier than Checkout";
				header("Location: checkins.php?error=".$mensaje);
				echo("<script>location.href ='checkins.php?error=".$mensaje."';</script>");
				} else {
					
				$sql="INSERT INTO jornada (id_empleado, fecha, hora_entrada, hora_salida) VALUES ('".$id_trabajador."','".$fecha."','".$h_entrada."','".$h_salida."')";	
						if (mysqli_query($conexion, $sql)) {
							header("Location: admin.php?ok2=1");
							$ok="<strong>OK !</strong> Checkin inserted correctly";
							header("Location: checkins.php?ok=".$ok);
							echo("<script>location.href ='checkins.php?ok=".$ok."';</script>");
						} else {
							
							$mensaje="<strong>Error !</strong> inserting your Checkin";
							header("Location: checkins.php?error=".$mensaje);
							echo("<script>location.href ='checkins.php?error=".$mensaje."';</script>");
							
						}
					mysqli_close($conexion);
				
				}
			}
	
?>