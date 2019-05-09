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
<title>Update Ckeckin</title>
<script src="js/confirmar.js"></script>
      
        <link rel="stylesheet" media="screen" href="css/estilos_generales.css"/>
		<link rel="apple-touch-icon" href="img/IconCkeckIn.png">
		<link rel="icon" href="img/IconCkeckIn.png" sizes="16x16" type="image/png">
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
					
					$id_t=$_GET['id_t'];
					$nom=$_GET['nom'];
					$id_jornada=$_GET['id_j'];
					$fecha_j=$_GET['fecha_j'];
					$hora_entrada=$_GET['hora_entrada'];
					$hora_salida=$_GET['hora_salida'];
					
				}else{
					
					$id_t=$_POST['id_t'];
					$nom=$_POST['nom'];
					$id_jornada=$_POST['id_j'];
					$fecha_j=$_POST['fecha_j'];
					$hora_entrada=$_POST['hora_entrada'];
					$hora_salida=$_POST['hora_salida'];
					
					if($hora_entrada>$hora_salida){
								   	echo "<article>";
									echo "	<div class='alert warning'>";
									echo "	  <span class='closebtn'>&times;</span>";
									echo "     <strong>Error</strong> :(  Checkout time must be later than: ". $hora_entrada;
									echo "     <script src='js/mensaje_borrar_trabajador_ok.js'></script>";
									echo "	</div>";
									echo "</article>";
						
						} else {
						////////////////////////////////////
						//Getting the first Break form BBDD to avoid users Pausing twice 
						//Comprobamos la primera pausa de la base de datos para que la entrada no sea mas pequeña que la primera pausa.
						$cons_d="SELECT * FROM descanso WHERE id_empleado='".$id_t."' AND fecha='".$fecha_j."' AND id_jornada='".$id_jornada."'ORDER BY id_descanso ASC LIMIT 1";
						$control_d=mysqli_query($conexion, $cons_d)  or die ("Error obteniendo hora de Pausa!");
						$row_d=mysqli_fetch_array($control_d);
						$id_descanso=$row_d[0];
						$descanso=$row_d[4];
						
								if(isset($descanso)){
											if($hora_entrada>$descanso){
													echo "<article>";
													echo "	<div class='alert warning'>";
													echo "	  <span class='closebtn'>&times;</span>";
													echo "     <strong>Error</strong> :(  Ckeckin time must be earlier than: ". $descanso;
													echo "     <script src='js/mensaje_borrar_trabajador_ok.js'></script>";
													echo "	</div>";
													echo "</article>";
												}else{
													
													///////// Vamos con la comprobacion de la hora de salida.
											//Getting the first Break form BBDD to avoid users Pausing twice 
											//Comprobamos la primera pausa de la base de datos para que la entrada no sea mas pequeña que la primera pausa.	
													
											$cons_u="SELECT * FROM descanso WHERE id_empleado='".$id_t."' AND fecha='".$fecha_j."' AND id_jornada='".$id_jornada."'ORDER BY id_descanso DESC LIMIT 1";
											$control_u=mysqli_query($conexion, $cons_u)  or die ("Error obteniendo hora de Pausa!");
											$row_u=mysqli_fetch_array($control_u);
											$id_descanso=$row_u[0];
											$descanso_u=$row_u[5];	
													
											
												if($hora_salida < $descanso_u){
														echo "<article>";
														echo "	<div class='alert warning'>";
														echo "	  <span class='closebtn'>&times;</span>";
														echo "     <strong>Error</strong> :(  Ckeckout time must be earlier than: ". $descanso_u;
														echo "     <script src='js/mensaje_borrar_trabajador_ok.js'></script>";
														echo "	</div>";
														echo "</article>";
													
													}else{
														
														//////////////////////////////////////
														/**/	$sql = "UPDATE jornada SET hora_entrada='".$hora_entrada."', hora_salida='".$hora_salida."' WHERE id_jornada='".$id_jornada."'";
														/**/	if (mysqli_query($conexion, $sql)) {
														/**/		header("Location: checkins.php?ok=<strong>OK</strong> Ckeckin Updated successfully ! ");
																		echo "<article>";
												  						echo "	<div class='alert success'>";
												 				   		 echo "	  <span class='closebtn'>&times;</span>";
												  						echo "     <strong>Great</strong> :)  Ckeckin updated successfuly ";
												  						echo "     <script src='js/mensaje_borrar_trabajador_ok.js'></script>";
												 						 echo "	</div>";
												 						 echo "</article>";
														/**/		} else {
														/**/		header("Location: checkins.php?error=<strong>Error</strong> updating this checkin ! ");
														/**/	}
														///////////////////////////////////////////
														
														 }
													
												////////////////////////////////////////////////////////////////////////////////////////////////////////////	
													
													}
									
								 }else{
									
											$sql = "UPDATE jornada SET hora_entrada='".$hora_entrada."', hora_salida='".$hora_salida."' WHERE id_jornada='".$id_jornada."'";
												if (mysqli_query($conexion, $sql)) {
													//header("Location: checkins.php?ok=<strong>OK</strong> Ckeckin Updated successfully ! ");
													echo("<script>location.href ='checkins.php?ok=<strong>OK</strong> Ckeckin Updated successfully ! ';</script>");
													} else {
														header("Location: checkins.php?error=<strong>Error</strong> :( updating this checkin ! ");
														echo("<script>location.href ='checkins.php?error=<strong>Error</strong> :( updating this checkin ! ';</script>");
														}
										
										}
						
						
						}
						///////////////////////////////////	
							
							
							
							
				}
		?>
        
        <H1 align="center" style="margin-bottom:40px;">Modify  <?php echo $nom; ?>'s  CheckIn</H1>
                <form class="formulario_actualizar" method='POST' action="<?php echo $_SERVER['PHP_SELF']; ?>">
                    <table align="center">
                        <tr>
                            <td colspan="2" id="modify_details">Modify Checkin</td>
                        </tr>
                        <!-----------------Aqui empieza el formulario que se ve en pantalla---------------------------------->
                        <tr>
                            <td colspan="2"> <label for="id_t"></label>
                            <input type="hidden" name="id_t" id="id_t" value='<?php echo $id_t;?>'autofocus ></td>
                        </tr>
                        <tr>
                            <td id="primera_columna">Name: </td>
                            <td><label for="nom"></label>
                            	<input type="text" name="nom" id="nom" readonly value='<?php echo $nom; ?>' >
                            </td>
                        </tr>
                        <tr>
                            <td id="primera_columna" >Shift: </td>
                            <td><label for="shift"></label>
                            	<input type="text" name="id_j" id="id_j" readonly value='<?php echo $id_jornada; ?>' >
                            </td>
                        </tr>
                        <tr>
                            <td id="primera_columna" >Date: </td>
                            <td><label for="date"></label>
                            	<input type="date" name="fecha_j" id="fecha_j" readonly value='<?php echo $fecha_j; ?>' >
                            </td>
                        </tr>
                        <tr>
                            <td id="primera_columna" >Checkin: </td>
                            <td><label for="checkin"></label>
                            	<input type="time" name="hora_entrada" id="hora_entrada" value='<?php echo $hora_entrada; ?>' >
                            </td>
                        </tr>
                        <tr>
                            <td id="primera_columna" >Checkout: </td>
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