<?php
session_start();
if ( !isset( $_SESSION[ 'nombre' ] ) ) {
	header( "Location: index.php" );
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
require( 'conexion.php' );

//Conmprobamos que no se puede accder a esta pagina sin usuario ni contra
//Este es el Id del trabajador conectado y lo voy a usar para insertar los datos en la tabla fichar!
$id = $_SESSION[ 'id' ];
?>

<!DOCTYPE html>
<html>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
	<meta name="viewport" content="width=device-width, minimum-scale=1.0, maximum-scale=1.0"/>
	<title>ChechIn | VLEC</title>
	<link rel="apple-touch-icon" href="img/IconCkeckIn.png">
	<link rel="icon" href="img/IconCkeckIn.png" sizes="16x16" type="image/png">
	<link rel="stylesheet" media="screen" href="css/estilos_user.css"/>
	<script src="js/jquery-3.2.1.js">
	</script>
	<link rel="stylesheet" href="library/bootstrap/css/bootstrap.min.css"/>
	<script src="library/bootstrap/js/bootstrap.min.js"></script>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</head>

<body>
	<header>
		<?php 
			require("cabecera_user.php");
			$fecha=date("Y-m-d");
			$hora=date("H:i:s");
					
			//Getting the last Checkin form BBDD to avoid users CkeckingIn twice 
			//Comprobamos el último checkin de la base de datos para no introducir 2 veces entradas.
			$cons_e="SELECT * FROM jornada WHERE id_empleado='".$id."' AND fecha='".$fecha."' ORDER BY hora_entrada DESC LIMIT 1";
			$control_e=mysqli_query($conexion, $cons_e)  or die ("Error obteniendo hora de Entrada!");
			$row_e=mysqli_fetch_array($control_e);
			$id_jornada=$row_e[0];
			$entrada=$row_e[3];
			$salida=$row_e[4];
			
			//Getting the last Break form BBDD to avoid users Pausing twice 
			//Comprobamos la ultima pausa de la base de datos para no introducir 2 veces  pausas.
			$cons_d="SELECT * FROM descanso WHERE id_empleado='".$id."' AND fecha='".$fecha."' AND id_jornada='".$id_jornada."' ORDER BY id_descanso DESC LIMIT 1";
			$control_d=mysqli_query($conexion, $cons_d)  or die ("Error obteniendo hora de Pausa!");
			$row_d=mysqli_fetch_array($control_d);
			$id_descanso=$row_d[0];
			$descanso=$row_d[4];
			
			//Getting the last Resume form BBDD to avoid users Resuming twice 
			//Comprobamos el último Resume de la base de datos para no introducir 2 veces reanudaciones.
			$cons_v="SELECT * FROM descanso WHERE id_empleado='".$id."' AND fecha='".$fecha."' ORDER BY hora_salida DESC LIMIT 1";
			$control_v=mysqli_query($conexion, $cons_v)  or die ("Error obteniendo hora de Saalida!");
			$row_v=mysqli_fetch_array($control_v);
			$id_vuelta=$row_v[0];
			$vuelta=$row_v[5];
			
			$b_checkin="active";
			$i_checkin=" <i class='fa fa-sign-in' ></i> ";
			$b_break="active";
			$i_break=" <i class='fa fa-pause'></i> ";
			$b_back="active";
			$i_back=" <i class='fa fa-play'></i> ";
			$b_checkout="active";
			$i_checkout=" <i class='fa fa-sign-out'></i> ";
			
			//Getting the last Checkout form BBDD to avoid users CkeckingOut twice 
			//Comprobamos el último Checkout de la base de datos para no introducir 2 veces salidas.
			$cons_s="SELECT * FROM jornada WHERE id_empleado='".$id."' AND fecha='".$fecha."' AND id_jornada='".$id_jornada."' ORDER BY hora_entrada DESC LIMIT 1";
			$control_s=mysqli_query($conexion, $cons_s)  or die ("Error obteniendo hora de Sntrada!");
			$row_s=mysqli_fetch_array($control_s);
			$salida=$row_s[4];
			
			if (!isset($entrada)) { 
								$b_break="disabled" ;  $i_break=" <i class='material-icons'>block</i> " ; 
								$b_back="disabled" ;   $i_back=" <i class='material-icons'>block</i> " ;
								$b_checkout="disabled"; $i_checkout=" <i class='material-icons'>block</i> " ;
				}else{
				
				 if(!empty($salida)){ 
				 		$b_checkin="disabled"; $i_checkin=" <i class='material-icons'>block</i> " ;
						$b_break="disabled"; $i_break=" <i class='material-icons'>block</i> " ; 
						$b_back="disabled" ; $i_back=" <i class='material-icons'>block</i> " ;
						$b_checkout="disabled" ; $i_checkout=" <i class='material-icons'>block</i> " ;
						
						} else {
						  if(!isset($descanso)){ 
						  		$b_back="disabled" ; $i_back=" <i class='material-icons'>block</i> " ;
								$b_checkin="disabled" ; $i_checkin=" <i class='material-icons'>block</i> " ;
								
								} else {
									 $consulta="SELECT * FROM descanso WHERE id_descanso='".$id_descanso."'";
									 $resultados=mysqli_query($conexion, $consulta)  or die ("Error obteniendo hora del último descanso l:102!");
									 $num_filas=mysqli_num_rows($resultados);
									 $fila=mysqli_fetch_array($resultados);
									 $h_entrada =$fila[4];
									 $h_salida=$fila[5];
									 
									 if(empty($h_salida)){
										 $b_checkin="disabled"; $i_checkin=" <i class='material-icons'>block</i> " ;
										 $b_break="disabled"; $i_break=" <i class='material-icons'>block</i> " ;
										 $b_checkout="disabled"; $i_checkout=" <i class='material-icons'>block</i> " ;
										 } else { 
										 $b_checkin="disabled"; $i_checkin=" <i class='material-icons'>block</i> " ;
										 $b_back="disabled" ; $i_back=" <i class='material-icons'>block</i> " ;
										 }
						  
						  }
					 
					 }
			
			}
?>
	</header>
	<section>
		<article>
			<h1>Please select one option</h1>
			<hr/>
			<table id="tabla_fichar" align="center">
				<?php
				if ( isset( $_GET[ 'mensaje' ] ) ) {
					$mensaje = $_GET[ 'mensaje' ];

					echo "<tr>";
					echo "<td>";

					echo "<div class='alert alert-success alert-dismissible'>";
					echo "<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>";
					echo $mensaje;
					echo "</div>	";
					echo "</td>";
					echo "</tr>";

				}
				?>
				<form action="fichar.php" method="post">
					<tr>
						<td class="checkin">
							<input type="hidden" name="id" value="<?php echo $id;?>"/>
							<input type="hidden" name="fecha" value="<?php echo $fecha; ?>"/>
							<input type="hidden" name="hora" value="<?php echo $hora; ?>"/>
							<button class="btn btn-primary btn-lg btn-block" id="checkin" <?php echo $b_checkin;?> name="action" type="submit" value="CHECKIN"><?php echo $i_checkin ;?> Checkin</button>
						</td>
					</tr>
				</form>
				<form action="fichar.php" method="post">
					<tr>
						<td class="break">
							<input type="hidden" name="id" value="<?php echo $id;?>"/>
							<input type="hidden" name="fecha" value="<?php echo $fecha; ?>"/>
							<input type="hidden" name="hora" value="<?php echo $hora; ?>"/>
							<button class="btn btn-warning btn-lg btn-block" id="break" <?php echo $b_break;?> name="action" type="submit" value="BREAK"><?php echo $i_break ;?> Break</button>
						</td>
					</tr>
				</form>
				<form action="fichar.php" method="post">
					<tr>
						<td class="resume">
							<input type="hidden" name="id" value="<?php echo $id;?>"/>
							<input type="hidden" name="fecha" value="<?php echo $fecha; ?>"/>
							<input type="hidden" name="hora" value="<?php echo $hora; ?>"/>
							<button class="btn btn-success btn-lg btn-block" id="back" <?php echo $b_back;?> name="action" type="submit" value="BACK"><?php echo $i_back ;?> Back</button>
						</td>
					</tr>
				</form>
				<form action="fichar.php" method="post">
					<tr>
						<td class="checkout">
							<input type="hidden" name="id" value="<?php echo $id;?>"/>
							<input type="hidden" name="fecha" value="<?php echo $fecha; ?>"/>
							<input type="hidden" name="hora" value="<?php echo $hora; ?>"/>
							<button class="btn btn-danger btn-lg btn-block" id="checkout" <?php echo $b_checkout;?> name="action" type="submit" value="CHECKOUT"><?php echo $i_checkout ;?> Checkout</button>
						</td>
					</tr>
				</form>
			</table>
			<script>
				$( document ).ready( function () {
					$( ".btn" ).click( function () {
						$( this ).button( "loading" ).delay( 500 ).queue( function () {
							$( this ).button( "reset" );
							$( this ).dequeue();
						} );
					} );
				} );
			</script>


		</article>
	</section>
</body>

</html>
<?php
mysqli_close( $conexion );
?>