<?php 
session_start();
        if (!isset($_SESSION['nombre'])) {
                header("Location: index.php");
            }
 	/*
    -------------------------------------------------------------------------------------
	CONNECT TO BBDD
    -------------------------------------------------------------------------------------
    */
    //Using this function.
    require('conexion.php');

	//Avoiding unregistered users to access tih page by pasting the URL inte the search bar
    
			
	//Datos del formulario
	$id=$_POST['id'];
	$action=$_POST['action'];
	$fecha=$_POST['fecha'];
	$hora=$_POST['hora'];
	$dia=date("Y-m-d");

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
	
	//Getting the last Checkout form BBDD to avoid users CkeckingOut twice 
	//Comprobamos el último Checkout de la base de datos para no introducir 2 veces salidas.
	$cons_s="SELECT * FROM jornada WHERE id_empleado='".$id."' AND fecha='".$fecha."' AND id_jornada='".$id_jornada."' ORDER BY hora_entrada DESC LIMIT 1";
	$control_s=mysqli_query($conexion, $cons_s)  or die ("Error obteniendo hora de Sntrada!");
	$row_s=mysqli_fetch_array($control_s);
	$salida=$row_s[4];
	
	/*----------------------------------------------------------------------------------------------------------------------------
	                                                       EN CASO DE CKECKIN
	------------------------------------------------------------------------------------------------------------------------------*/	

	if ($action=='CHECKIN'){
		
		if (!empty($entrada)) {
		header("Location: user.php?mensaje=You Cant't CheckIn again today");
		echo("<script>location.href ='user.php?mensaje=You Cant't CheckIn again today';</script>");
		} else {
			$sql="INSERT INTO jornada (id_empleado, fecha, hora_entrada, hora_salida) VALUES ('".$id."','".$fecha."','".$hora."', NULL)";
			//Checking the querry //Comprobamos que hemos hecho bien la entrada
				if (mysqli_query($conexion, $sql)) {
					header("Location: user.php?mensaje=Checkin OK !");
					echo("<script>location.href ='user.php?mensaje=Checkin OK !';</script>");
				} else {
					
					header("Location: user.php?mensaje=Error processing your CheckIn");
					echo("<script>location.href ='user.php?mensaje=Error processing your CheckIn !';</script>");
					}
			}
		}
	/*----------------------------------------------------------------------------------------------------------------------------
	                                                       EN CASO DE PAUSA
	------------------------------------------------------------------------------------------------------------------------------*/
	if ($action=='BREAK'){
		//Impedimos insertar una pausa antes de que se haya insertado un Checkin //Avoiding a Break before a Checkin
		if(empty($entrada)){
	  		echo "You must checkin First!";	
		   }else{
			   
					 if(empty($salida))	{ 
					 //--------------------------------------------------------------------------------------------------
						if(!empty($descanso)){
								 $consulta="SELECT * FROM descanso WHERE id_descanso='".$id_descanso."'";
								 $resultados=mysqli_query($conexion, $consulta)  or die ("Error obteniendo hora del último descanso l:90!");
								 $num_filas=mysqli_num_rows($resultados);
								 $fila=mysqli_fetch_array($resultados);
								 $h_entrada =$fila[4];
								 $h_salida=$fila[5];
								 if(empty($h_salida)){
									 
									 header("Location: user.php?mensaje=<strongError!</strong> You have to resume your shift first !"); 
									 }else{
										 $sql="INSERT INTO descanso (id_jornada, id_empleado, fecha, hora_entrada, hora_salida) VALUES ('".$id_jornada."','".$id."','".$fecha."','".$hora."', NULL)";
										 if (mysqli_query($conexion, $sql)) {
														header("Location: user.php?mensaje=Enjoy your break !");
														echo("<script>location.href ='user.php?mensaje=Enjoy your break !';</script>");
													} else {
														
														header("Location: user.php?mensaje=<strongError</strong> processing your break !");
														echo("<script>location.href ='user.php?mensaje=<strongError</strong> processing your break !';</script>");
														} 
										 }
							
							}else {
								
								$sql="INSERT INTO descanso (id_jornada, id_empleado, fecha, hora_entrada, hora_salida) VALUES ('".$id_jornada."','".$id."','".$fecha."','".$hora."', NULL)";
								if (mysqli_query($conexion, $sql)) {
														header("Location: user.php?mensaje=Enjoy your break !");
														echo("<script>location.href ='user.php?mensaje=Enjoy your break !';</script>");
													} else {
														header("Location: user.php?mensaje=<strongError</strong> processing your break !");
														echo("<script>location.href ='user.php?mensaje=<strongError</strong> processing your break !';</script>");
														}	
								
								}
					 //--------------------------------------------------------------------------------------------------
					 } else {echo " Error ! You have checked Out Today "; header("Location: user.php?mensaje=<strongError</strong> You have checked Out Today !");}
		
		 }		
	}
	/*----------------------------------------------------------------------------------------------------------------------------
	                                                    EN CASO DE VUELTA
	------------------------------------------------------------------------------------------------------------------------------*/
	if ($action=='BACK'){
		//Impedimos insertar una vuelta antes de que se haya insertado una Pausa  //Avoiding a Break before a Checkin
		if(empty($entrada)){
	  		echo "You must checkin First!";	
		   }else{
		 //--------------------------------------------------------------------------------------------------
			if(!empty($descanso)){
					 $consulta="SELECT * FROM descanso WHERE id_descanso='".$id_descanso."'";
					 $resultados=mysqli_query($conexion, $consulta)  or die ("Error obteniendo hora del último descanso l-138!");
		 	    	 $num_filas=mysqli_num_rows($resultados);
					 $fila=mysqli_fetch_array($resultados);
	   			     $h_entrada =$fila[4];
	                 $h_salida=$fila[5];
					 if(!empty($h_salida)){
						 
						 header("Location: user.php?mensaje=<strongError</strong> You have marked this action earlier !");
						 echo("<script>location.href ='user.php?mensaje=<strongError</strong> You have marked marked this action earlier !';</script>");
						 }else{
							 
							 	/////////////////////////////////////////////////////////////////////////////////////////////////////////
								//Insertamos la hora de vuelta en el registro del desanso //
								$update="UPDATE `descanso` SET `hora_salida` = '".$hora."' WHERE id_descanso =".$id_descanso;
								if (mysqli_query($conexion, $update)) {
									
									header("Location: user.php?mensaje=Nice to see you again !");
									echo("<script>location.href ='user.php?mensaje=Nice to see you again !';</script>");
								} else {
									echo $update;
									
									header("Location: user.php?mensaje=<strongError</strong> We can't process your querry!");
									echo("<script>location.href ='user.php?mensaje=<strongError</strong> We can't process your querry !';</script>");
								}
								/////////////////////////////////////////////////////////////////////////////////////////////////////////
								
									 
						}
						
				}else { echo "Mark your pause before resume !"; header("Location: user.php?mensaje=<strongError</strong> Mark your pause !");
				        echo("<script>location.href ='user.php?mensaje=<strongError</strong> Mark your pause !';</script>");
					    }
		 //--------------------------------------------------------------------------------------------------
		 
		 }		
	}
	/*----------------------------------------------------------------------------------------------------------------------------
	                                                   EN CASO DE SALIDA
	------------------------------------------------------------------------------------------------------------------------------*/
	if($action=='CHECKOUT'){
			//Impedimos insertar una salida antes de que se haya insertado una Entrada  //Avoiding a Checkout before a Checkin
			if (!isset($entrada)) {echo "<strong>Error! </strong>You have to CheckIn first";} else {
				
				
					if(empty($entrada)){ echo "Your ckeckin is not valid!"; header("Location: user.php?mensaje=<strongError</strong> Your ckeckin registered before is not valid!"); 
						echo("<script>location.href ='user.php?mensaje=<strongError</strong> Your ckeckin registered before is not valid !';</script>");
					 }else{
		 			//--------------------------------------------------------------------------------------------------
							if(empty($salida)){
									 $consulta="SELECT * FROM jornada WHERE id_jornada='".$id_jornada."'";
									 $resultados=mysqli_query($conexion, $consulta)  or die ("Error obteniendo hora del último checkin l-181!");
									 $num_filas=mysqli_num_rows($resultados);
									 $fila=mysqli_fetch_array($resultados);
									 $h_entrada =$fila[3];
									 $h_salida=$fila[4];
									 if(!empty($h_salida)){
										 
										 header("Location: user.php?mensaje=<strongError</strong> No more checkins today!");
										 echo("<script>location.href ='user.php?mensaje=<strongError</strong> No more checkins today !';</script>");
										 }else{
											   //impedimos hacer el checkout antes de terminar la pausa
											   $consulta="SELECT * FROM descanso WHERE id_descanso='".$id_descanso."'";
											   $resultados=mysqli_query($conexion, $consulta)  or die ("Error obteniendo hora del último descanso l-138!");
		 	    	                           $num_filas=mysqli_num_rows($resultados);
					                           $fila=mysqli_fetch_array($resultados);
	   			                               $h_entrada =$fila[4];
	                                           $h_salida=$fila[5];
												 if (!isset($h_entrada)){
														/////////////////////////////////////////////////////////////////////////////////////////////////////////
														//Insertamos la hora de salida en el registro de la jornada //
														$update="UPDATE `jornada` SET `hora_salida` = '".$hora."' WHERE id_jornada =".$id_jornada;
														if (mysqli_query($conexion, $update)) {
															
															header("Location: user.php?mensaje=:) Bye, see you soon !");
															echo("<script>location.href ='user.php?mensaje=:) Bye, see you soon !';</script>");
														} else {
															echo $update;
															
															header("Location: user.php?mensaje=<strongError</strong> We can't process your Checkout !");
															echo("<script>location.href ='user.php?mensaje=<strongError</strong> We can't process your Checkout !';</script>");
														}
														/////////////////////////////////////////////////////////////////////////////////////////////////////////
												
												  } else {
													     
														 if (empty($h_salida)) {
														 	header("Location: user.php?mensaje=<strongError</strong> You have to press before you checkout !");
															echo "You have to come back to work before you checkout";
															echo("<script>location.href ='user.php?mensaje=<strongError</strong> Press Back before Checkout !';</script>");
															} else {
															 
															 /////////////////////////////////////////////////////////////////////////////////////////////////////////
															//Insertamos la hora de salida en el registro de la jornada //
															$update="UPDATE `jornada` SET `hora_salida` = '".$hora."' WHERE id_jornada =".$id_jornada;
															if (mysqli_query($conexion, $update)) {
																
																header("Location: user.php?mensaje=Bye, see you soon !");
																echo("<script>location.href ='user.php?mensaje=:) Bye, see you soon !';</script>");
															  } else {
															  echo $update;
															 
														   	  header("Location: user.php?mensaje=<strongError</strong> We can't process your Checkout !");
														   	  echo("<script>location.href ='user.php?mensaje=<strongError</strong> We can't process your Checkout !';</script>"); 
														  }
														 /////////////////////////////////////////////////////////////////////////////////////////////////////////
															 
															 	 
														}
													
													
													
											   }			
													 
										}
										
								}else {  header("Location: user.php?mensaje=<strongError</strong> you have Checkout today!");   
								 		 echo("<script>location.href ='user.php?mensaje=<strongError</strong> :( no more Checkouts today !';</script>");
								   }
		 //--------------------------------------------------------------------------------------------------
		 			 }		
				
			}
	}	
    /*----------------------------------------------------------------------------------------------------------------------------
	                                                FIN EN CASO DE SALIDA
	------------------------------------------------------------------------------------------------------------------------------*/
?>
