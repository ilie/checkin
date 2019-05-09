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
    
?>

<!doctype html>
<html>
<head>
<meta charset="UTF-8">
<title>Update Break</title>
<script src="js/confirmar.js"></script>
     	<link rel="apple-touch-icon" href="img/IconCkeckIn.png">
		<link rel="icon" href="img/IconCkeckIn.png" sizes="16x16" type="image/png">
      
        <link rel="stylesheet" media="screen" href="css/estilos_generales.css"/>

        <!--JqueryUI-->
        <link rel="stylesheet" href="library/jquery-ui/jquery-ui.css"/>
        <script src="library/jquery-ui/jquery-ui.js"></script> 
        <!--Jquery-->
        <script src="js/jquery-3.2.1.js"></script>

        <link rel="stylesheet" href="library/bootstrap/css/bootstrap.min.css" />
        <script src="library/bootstrap/js/bootstrap.min.js"></script>
        
        
        <script type="text/javascript" src="library/ui3/jquery-ui-1.10.4.custom.js"></script>
        <link rel="stylesheet" type="text/css" href="library/ui3/css/sunny/jquery-ui-1.10.4.custom.css"/>
        
        
        <script type="text/javascript" src="./../../plugins/ui3/jquery-ui-1.10.4.custom.js"></script>
        <link rel="stylesheet" type="text/css" href="./../../plugins/ui3/css/sunny/jquery-ui-1.10.4.custom.css"/>

        <!--Icons-->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <script >
			$(function () {
				$.datepicker.setDefaults($.datepicker.regional["es"]);
				$("#datepicker1 , #datepicker2").datepicker({
					dateFormat: "yy-mm-dd",
				});
			});
			
			$('#time_in').timepicker({ 'timeFormat': 'H:i:s' });
			
        </script>
</head>
<body>
	<header>
         <?php require('cabecera.php'); ?> 
    </header>
    <section>
    <?php 
	if (!isset($_POST["guardar"])){
					
		$id_trabajador=$_GET['id_t'];
		$nombre=$_GET['nombre'];
		$id_descanso=$_GET['id_descanso'];
		$fecha_descanso=$_GET['fecha_d'];
		$hora_entrada=$_GET['hora_entrada'];
		$hora_salida=$_GET['hora_salida'];
		$id_jornada=$_GET['id_jornada'];

	} else {
		/////////// En caso de darle a guardar empezamos con las comprobaciones y luego vamos haciendo la actualizacion
		 	 $id_trabajador=$_POST['id_t'];
			 $nombre=$_POST['nombre'];
			 $id_descanso=$_POST['id_descanso'];
			 $fecha_descanso=$_POST['fecha_d'];
			 $hora_entrada=$_POST['hora_entrada'];
			 $hora_salida=$_POST['hora_salida'];
			 $id_jornada=$_POST['id_jornada'];
			
				if($hora_entrada>$hora_salida){
							echo "<article>";
							echo "	<div class='alert warning'>";
							echo "	  <span class='closebtn'>&times;</span>";
							echo "     <strong>Error</strong> :( Ckeckout time must be later than: ". $hora_entrada;
							echo "     <script src='js/mensaje_borrar_trabajador_ok.js'></script>";
							echo "	</div>";
							echo "</article>";

					}else{

							////////////////////////////////////
							//Getting the Break before this record form BBDD to avoid users inserting smaller pause than before 
							//Comprobamos la pausa  antes de este registro de la base de datos para que esta entrada no sea mas pequeña que la primera pausa.
							$cons_d="SELECT * FROM descanso WHERE id_empleado='".$id_trabajador."' AND fecha='".$fecha_descanso."' 
							AND id_jornada='".$id_jornada."' AND id_descanso < '".$id_descanso."'  ORDER BY 		hora_salida DESC LIMIT 1";
							$control_d=mysqli_query($conexion, $cons_d)  or die ("Error obteniendo hora de Pausa de antes!");
							$row_d=mysqli_fetch_array($control_d);
							$id_reg_anterior=$row_d[0];
							$reg_anterior=$row_d[5]; //Registro Anterior
		
					         //Getting the Break after this record form BBDD to avoid users inserting smaller pause than before 
							//Comprobamos la pausa despues de este registro de la base de datos para que esta entrada no sea mas pequeña que la primera pausa.
							$cons_u="SELECT * FROM descanso WHERE id_empleado='".$id_trabajador."' AND fecha='".$fecha_descanso."' 
			  				AND id_jornada='".$id_jornada."' AND id_descanso > '".$id_descanso."'  ORDER BY hora_entrada ASC LIMIT 1";
							$control_u=mysqli_query($conexion, $cons_u)  or die ("Error obteniendo hora de Pausa de despues!");
							$row_u=mysqli_fetch_array($control_u);
							$id_reg_posterior=$row_u[0];
							$reg_posterior=$row_u[4]; //Registro Posterior
					
							//Getting the checkin 
							$cons_j="SELECT * FROM jornada WHERE id_empleado='".$id_trabajador."' AND fecha='".$fecha_descanso."' 
			  				AND id_jornada='".$id_jornada."' ";
        					$control_j=mysqli_query($conexion, $cons_j)  or die ("Error obteniendo hora de Checkin !");
        					$row_j=mysqli_fetch_array($control_j);
							$id_descanso_j=$row_j[0];
							$entrada=$row_j[3]; //Registro Entrada
							$salida=$row_j[4]; //Registro Salida
							/*
							---------------------------En caso de que sea la unica pausa en el dia----------------------------------------
							*/
							if (!isset($reg_anterior) AND (!isset($reg_posterior))){
									if($hora_entrada<$entrada) {
										echo "<article>";
										echo "	<div class='alert warning'>";
										echo "	  <span class='closebtn'>&times;</span>";
										echo "     <strong>Error</strong> :( Ckeckin time must be later than: ". $entrada;
										echo "     <script src='js/mensaje_borrar_trabajador_ok.js'></script>";
										echo "	</div>";
										echo "</article>";
							//////////////////////////////////////////////////////////////
									}else if ($hora_entrada > $salida){
										echo "<article>";
										echo "	<div class='alert warning'>";
										echo "	  <span class='closebtn'>&times;</span>";
										echo "     <strong>Error</strong> :( Ckeckin time must be earlier than: ". $salida;
										echo "     <script src='js/mensaje_borrar_trabajador_ok.js'></script>";
										echo "	</div>";
										echo "</article>";
							//////////////////////////////////////////////////////////////
									}else if ($hora_salida<$entrada) {
										echo "<article>";
										echo "	<div class='alert warning'>";
										echo "	  <span class='closebtn'>&times;</span>";
										echo "     <strong>Error</strong> :( Ckeckout time must be later than: ". $entrada;
										echo "     <script src='js/mensaje_borrar_trabajador_ok.js'></script>";
										echo "	</div>";
										echo "</article>";
							//////////////////////////////////////////////////////////////	
									}else if ($hora_salida > $salida) {
										echo "<article>";
										echo "	<div class='alert warning'>";
										echo "	  <span class='closebtn'>&times;</span>";
										echo "     <strong>Error</strong> :( Ckeckout time must be earlier than: ". $salida;
										echo "     <script src='js/mensaje_borrar_trabajador_ok.js'></script>";
										echo "	</div>";
										echo "</article>";
							//////////////////////////////////////////////////////////////
									}else {
									///////////////SI Nada de esto ha pasado hacemos el Update de la tabla descanso
									$sql = "UPDATE descanso SET hora_entrada='".$hora_entrada."', hora_salida='".$hora_salida."' WHERE id_descanso='".$id_descanso."'";
															if (mysqli_query($conexion, $sql)) {
																header("Location: checkins.php?ok=<strong>OK</strong> Ckeckin Updated successfully ! ");
																echo "<article>";
																echo "	<div class='alert success'>";
																echo "	  <span class='closebtn'>&times;</span>";
																echo "     <strong>Great</strong> :) Ckeckin updated successfuly ";
																echo "     <script src='js/mensaje_borrar_trabajador_ok.js'></script>";
																echo "	</div>";
																echo "</article>";
							//////////////////////////////////////////////////////////////
																
																
																} else {
																header("Location: checkins.php?error=<strong>Error</strong> :( updating this checkin ! ");
																echo("<script>location.href ='checkins.php?error=<strong>Error</strong> :( updating this checkin ! ';</script>");
															}
									////////////////////////////////////////////////////////////
											}
							
							/*
							---------------------------En caso de que sea la primera pausa en el dia----------------------------------------
							*/
							////En caso de que sea la primera pausa en el dia			
							 } else if (!isset($reg_anterior) AND (isset($reg_posterior))) {
									if ($hora_entrada<$entrada) {
										echo "<article>";
										echo "	<div class='alert warning'>";
										echo "	  <span class='closebtn'>&times;</span>";
										echo "     <strong>Error</strong> :( Ckeckin time must be later than: ". $entrada;
										echo "     <script src='js/mensaje_borrar_trabajador_ok.js'></script>";
										echo "	</div>";
										echo "</article>";
							//////////////////////////////////////////////////////////////
									}else if ($hora_entrada > $reg_posterior){
										echo "<article>";
										echo "	<div class='alert warning'>";
										echo "	  <span class='closebtn'>&times;</span>";
										echo "     <strong>Error</strong> :( Ckeckin time must be earlier than: ". $reg_posterior;
										echo "     <script src='js/mensaje_borrar_trabajador_ok.js'></script>";
										echo "	</div>";
										echo "</article>";
							//////////////////////////////////////////////////////////////
									}else if ($hora_salida<$entrada) {
										echo "<article>";
										echo "	<div class='alert warning'>";
										echo "	  <span class='closebtn'>&times;</span>";
										echo "     <strong>Error</strong> :( Ckeckout time must be later than: ". $entrada;
										echo "     <script src='js/mensaje_borrar_trabajador_ok.js'></script>";
										echo "	</div>";
										echo "</article>";
							//////////////////////////////////////////////////////////////	
									}else if ($hora_salida > $reg_posterior) {
										echo "<article>";
										echo "	<div class='alert warning'>";
										echo "	  <span class='closebtn'>&times;</span>";
										echo "     <strong>Error</strong> :( Ckeckout time must be earlier than: ". $reg_posterior;
										echo "     <script src='js/mensaje_borrar_trabajador_ok.js'></script>";
										echo "	</div>";
										echo "</article>";
							//////////////////////////////////////////////////////////////
									}else {
										///////////////SI Nada de esto ha pasado hacemos el Update de la tabla descanso
									$sql = "UPDATE descanso SET hora_entrada='".$hora_entrada."', hora_salida='".$hora_salida."' WHERE id_descanso='".$id_descanso."'";
												if (mysqli_query($conexion, $sql)) {
												  header("Location: checkins.php?ok=<strong>OK</strong> Ckeckin Updated successfully ! ");
												  echo "<article>";
												  echo "	<div class='alert success'>";
												  echo "	  <span class='closebtn'>&times;</span>";
												  echo "     <strong>Great</strong> :) Ckeckin updated successfuly ";
												  echo "     <script src='js/mensaje_borrar_trabajador_ok.js'></script>";
												  echo "	</div>";
												  echo "</article>";
												} else {
												  header("Location: checkins.php?error=<strong>Error</strong> :( updating this checkin ! ");
												  echo("<script>location.href ='checkins.php?error=<strong>Error</strong> :( updating this checkin ! ';</script>");
												}
									    ////////////////////////////////////////////////////////////
										
										}
							/*
							---------------------------En caso de que sea la ultima pausa en el dia----------------------------------------
							*/		
							//En caso de que sea la ultima pausa del dia	
							}else if (isset($reg_anterior) AND (!isset($reg_posterior))) {
									if ($hora_entrada<$reg_anterior) {
										echo "<article>";
										echo "	<div class='alert warning'>";
										echo "	  <span class='closebtn'>&times;</span>";
										echo "     <strong>Error</strong> :( Ckeckin time must be later than: ". $reg_anterior;
										echo "     <script src='js/mensaje_borrar_trabajador_ok.js'></script>";
										echo "	</div>";
										echo "</article>";
							//////////////////////////////////////////////////////////////
									}else if ($hora_entrada > $salida){
										echo "<article>";
										echo "	<div class='alert warning'>";
										echo "	  <span class='closebtn'>&times;</span>";
										echo "     <strong>Error</strong> :( Ckeckin time must be earlier than: ". $salida;
										echo "     <script src='js/mensaje_borrar_trabajador_ok.js'></script>";
										echo "	</div>";
										echo "</article>";
							//////////////////////////////////////////////////////////////
									}else if ($hora_salida<$reg_anterior) {
										echo "<article>";
										echo "	<div class='alert warning'>";
										echo "	  <span class='closebtn'>&times;</span>";
										echo "     <strong>Error</strong> :( Ckeckout time must be later than: ". $reg_anterior;
										echo "     <script src='js/mensaje_borrar_trabajador_ok.js'></script>";
										echo "	</div>";
										echo "</article>";
							//////////////////////////////////////////////////////////////	
									}else if ($hora_salida > $salida) {
										echo "<article>";
										echo "	<div class='alert warning'>";
										echo "	  <span class='closebtn'>&times;</span>";
										echo "     <strong>Error</strong> :( Ckeckout time must be earlier than: ". $salida;
										echo "     <script src='js/mensaje_borrar_trabajador_ok.js'></script>";
										echo "	</div>";
										echo "</article>";
							//////////////////////////////////////////////////////////////
									}else {
										///////////////SI Nada de esto ha pasado hacemos el Update de la tabla descanso
											$sql = "UPDATE descanso SET hora_entrada='".$hora_entrada."', hora_salida='".$hora_salida."' WHERE id_descanso='".$id_descanso."'";
											if (mysqli_query($conexion, $sql)) {
												header("Location: checkins.php?ok=<strong>OK</strong> Ckeckin Updated successfully ! ");
												echo "<article>";
												  echo "	<div class='alert success'>";
												  echo "	  <span class='closebtn'>&times;</span>";
												  echo "     <strong>Great</strong> :) Ckeckin updated successfuly ";
												  echo "     <script src='js/mensaje_borrar_trabajador_ok.js'></script>";
												  echo "	</div>";
												  echo "</article>";
											} else {
												  header("Location: checkins.php?error=<strong>Error</strong> :( updating this checkin ! ");
												  echo("<script>location.href ='checkins.php?error=<strong>Error</strong> :( updating this checkin ! ';</script>");
												}
									    ////////////////////////////////////////////////////////////
										 }
							
							/*
							---------------------------En caso de que la pausa sea en el medio----------------------------------------
							*/			
							}else {
									if ($hora_entrada<$reg_anterior) {
										echo "<article>";
										echo "	<div class='alert warning'>";
										echo "	  <span class='closebtn'>&times;</span>";
										echo "     <strong>Error</strong> :( Ckeckin time must be later than: ". $reg_anterior;
										echo "     <script src='js/mensaje_borrar_trabajador_ok.js'></script>";
										echo "	</div>";
										echo "</article>";
							//////////////////////////////////////////////////////////////
									}else if ($hora_entrada > $reg_posterior){
										echo "<article>";
										echo "	<div class='alert warning'>";
										echo "	  <span class='closebtn'>&times;</span>";
										echo "     <strong>Error</strong> :( Ckeckin time must be earlier than: ". $reg_posterior;
										echo "     <script src='js/mensaje_borrar_trabajador_ok.js'></script>";
										echo "	</div>";
										echo "</article>";
							//////////////////////////////////////////////////////////////
									}else if ($hora_salida<$reg_anterior) {
										echo "<article>";
										echo "	<div class='alert warning'>";
										echo "	  <span class='closebtn'>&times;</span>";
										echo "     <strong>Error</strong> :( Ckeckout time must be later than: ". $reg_anterior;
										echo "     <script src='js/mensaje_borrar_trabajador_ok.js'></script>";
										echo "	</div>";
										echo "</article>";
							//////////////////////////////////////////////////////////////
									}else if ($hora_salida > $reg_posterior) {
										echo "<article>";
										echo "	<div class='alert warning'>";
										echo "	  <span class='closebtn'>&times;</span>";
										echo "     <strong>Error</strong> :( Ckeckout time must be earlier than: ". $reg_posterior;
										echo "     <script src='js/mensaje_borrar_trabajador_ok.js'></script>";
										echo "	</div>";
										echo "</article>";
							//////////////////////////////////////////////////////////////
									}else { 
										///////////////SI Nada de esto ha pasado hacemos el Update de la tabla descanso
											$sql = "UPDATE descanso SET hora_entrada='".$hora_entrada."', hora_salida='".$hora_salida."' WHERE id_descanso='".$id_descanso."'";
											if (mysqli_query($conexion, $sql)) {
												header("Location: checkins.php?ok=<strong>OK</strong> Ckeckin Updated successfully ! ");
												echo "<article>";
												  echo "	<div class='alert success'>";
												  echo "	  <span class='closebtn'>&times;</span>";
												  echo "     <strong>Great</strong> :) Ckeckin updated successfuly ";
												  echo "     <script src='js/mensaje_borrar_trabajador_ok.js'></script>";
												  echo "	</div>";
												  echo "</article>";
											} else {
												  header("Location: checkins.php?error=<strong>Error</strong> :( updating this checkin ! ");
												  echo("<script>location.href ='checkins.php?error=<strong>Error</strong> :( updating this checkin ! ';</script>");
												}
									   ////////////////////////////////////////////////////////////
										}
								}
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////							
						}


		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	}
    ?>
    
    <H1 align="center" style="margin-bottom:40px;">Modify  <?php echo $nombre; ?>'s  Break</H1>
    <form class="formulario_actualizar" method='POST' action="<?php echo $_SERVER['PHP_SELF']; ?>">
                    <table align="center">
                        <tr>
                            <td colspan="2" id="modify_details">Modify Break</td>
                        </tr>
                        <!-----------------Aqui empieza el formulario que se ve en pantalla---------------------------------->
                        <tr>
                            <td colspan="2"> <label for="id_t"></label>
                            <input type="hidden" name="id_t" id="id_t" value='<?php echo $id_trabajador;?>'autofocus ></td>
                        </tr>
                        <tr>
                            <td id="primera_columna">Name: </td>
                            <td><label for="nombre"></label>
                            	<input type="text" name="nombre" id="nombre" readonly value='<?php echo $nombre; ?>' >
                            </td>
                        </tr>
                        <tr>
                            <td id="primera_columna" >Shift Id: </td>
                            <td><label for="shift"></label>
                            	<input type="text" name="id_jornada" id="id_jornada" readonly value='<?php echo $id_jornada; ?>' >
                            </td>
                        </tr>
                        <tr>
                            <td id="primera_columna" >Break Id: </td>
                            <td><label for="shift"></label>
                            	<input type="text" name="id_descanso" id="id_descanso" readonly value='<?php echo $id_descanso; ?>' >
                            </td>
                        </tr>
                        <tr>
                            <td id="primera_columna" >Date: </td>
                            <td><label for="date"></label>
                            	<input type="date" name="fecha_d" id="fecha_d" readonly value='<?php echo $fecha_descanso; ?>' >
                            </td>
                        </tr>
                        <tr>
                            <td id="primera_columna" >In: </td>
                            <td><label for="checkin"></label>
                            	<input type="time" name="hora_entrada" id="hora_entrada" value='<?php echo $hora_entrada; ?>' >
                            </td>
                        </tr>
                        <tr>
                            <td id="primera_columna" >Out: </td>
                            <td><label for="checkout"></label>
                            	<input type="time" name="hora_salida" id="hora_salida" value='<?php echo $hora_salida; ?>' >
                            </td>
                        </tr>
                        
                        
                        <tr><td></td>
                            <td>
                            	<input id="guardar" name="guardar" value="Save" type="submit">
                            </td>
                        </tr>
                           

                    </table>
                </form>
        
    </section>
    <footer>
            <?php require('footer.php');?> 
    </footer>
</body>
</html>