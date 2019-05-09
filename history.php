<?php
session_start();
if (!isset($_SESSION['nombre'])) {
    header("Location: index.php");
}

$id = $_SESSION['id'];
/*
  -------------------------------------------------------------------------------------
  CONNECT TO BBDD
  -------------------------------------------------------------------------------------
 */
//Using this function.
require('conexion.php');

//Avoiding unregistered users to access this page by pasting the URL inte the search bar

?>

<!doctype html>
<html><head>
        <meta charset="UTF-8">
        <title>History</title>
        <link rel="apple-touch-icon" href="img/IconCkeckIn.png">
		<link rel="icon" href="img/IconCkeckIn.png" sizes="16x16" type="image/png">
        <meta name="viewport" content="width=device-width, minimum-scale=1.0, maximum-scale=1.0" />
        <link rel="stylesheet" media="screen" href="css/estilos_user.css"/>
        
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
        <!--Bootstrap Date Picker
        <script src="library/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>
        <script src="library/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
        <link rel="stylesheet" media="screen" href="library/bootstrap-datepicker/css/bootstrap-datepicker.css">
        <script src="js/confirmar.js"></script> -->
        <!--Icons-->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <script >
$(function () {
    $.datepicker.setDefaults($.datepicker.regional["es"]);
    $("#datepicker1 , #datepicker2").datepicker({
        dateFormat: "yy-mm-dd",
    });
});


        </script>
    </head>
    <body>
        <header>
            <?php require("cabecera_user.php"); ?>
        </header>
        <section>
            		<h1>Your CheckIns</h1> 
            <article>
            	
                    
                   
                	<form method='POST' style="text-align:center; margin-bottom:20px;" class="form-inline" name="fechas" action="<?php echo $_SERVER['PHP_SELF']; ?>" >
                    	<div class="form-group">
   						 <label for="Start Date">From:</label>
    					 <input type="text" id="datepicker1" name="fecha_inicio" placeholder="Start date YY/mm/dd"/>
                        </div>
                        <div class="form-group">
   						 <label for="Finish Date">to:</label>
    					 <input type="text" id="datepicker2" name="fecha_final" placeholder="Final date YY/mm/dd"/>
                        </div>
                    	<button type="submit" name="Go" value="Go" class="btn btn-default"><i class="fa fa-search"></i>  Go</button>
                    </form>
               
			 </article>
                </article>
                <article>
                <table class=" table table-striped table-bordered table-hover table-condensed table-responsive">
                    <thead>
                        <tr>
                            <td><span class="glyphicon glyphicon-calendar"></span>  Date</td>
                            <td><i class="fa fa-sign-in" ></i> In/Break </td>
                            <td><i class="fa fa-sign-out"></i> Out/Back </td>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
						
							$tamagno_paginas=10;
					
							if (isset($_GET['pagina'])) {
						
								if($_GET['pagina']==1){
									$pagina=1;
								
										//echo("<script>location.href ='history.php';</script>");
									
									}else{
										$pagina=$_GET['pagina'];
									
									}
							}else {
						
								$pagina=1;
								}	
						
						$empezar_desde=($pagina-1)*$tamagno_paginas;
						
							if (!isset($_POST["Go"])){
								
	
							//Todos los Registros	
							$sql_total = "SELECT * FROM jornada where id_empleado='" . $id . "'ORDER BY fecha DESC";
							$result_total= mysqli_query($conexion, $sql_total) or die ("No se ha podido realizar la consulta de jornada total 1!");
								
							//Consulta con limite	
							$sql="SELECT * FROM `jornada` where id_empleado='".$id."' ORDER BY jornada.fecha DESC LIMIT $empezar_desde,$tamagno_paginas" ;
							$result = mysqli_query($conexion, $sql) or die("No se ha podido realizar la consulta de jornada con limite 1!");				
							
							}else{
							$fecha_inicio=$_POST["fecha_inicio"];
							$fecha_final=$_POST["fecha_final"];
							
									if (empty($fecha_inicio) AND empty($fecha_final)) {
										$sql="SELECT * FROM `jornada` where id_empleado='".$id."' ORDER BY jornada.fecha DESC LIMIT $empezar_desde,$tamagno_paginas" ;
										$sql_total = "SELECT * FROM jornada where id_empleado='" . $id . "'ORDER BY fecha DESC";
										$result = mysqli_query($conexion, $sql) or die("No se ha podido realizar la consulta de jornada con limite 2!");
										$result_total= mysqli_query($conexion, $sql_total) or die ("No se ha podido realizar la consulta de jornada total 2!");
										
									}else{
							
											$sql = "SELECT * FROM jornada where id_empleado='". $id ."' AND  fecha BETWEEN '".$fecha_inicio."' AND '".$fecha_final."'  ORDER BY fecha DESC";
											$result = mysqli_query($conexion, $sql) or die("No se ha podido realizar la consulta de jornada between!");
									}
						
							}
                        
                        $row_cnt = mysqli_num_rows($result_total);
						$segundos_trabajo=0;
						$segundos_pausa=0;
                        while ($row = mysqli_fetch_array($result)) {

                            echo "<tr class='success'>";
                            echo "<td>" . $row['fecha'] . "</td>";
                            echo "<td>CheckIn at: " . $row['hora_entrada'] . "</td>";
                            

                            $id_jornada = $row[0];
							///////////Segundos Trabajo ///////////////////////////////////////////////////////////
							// Extraigo los tiempos y los comparo
							if(empty($row[4])) {$seconds=strtotime('00:00:00')-strtotime($row[3]); $class='danger';} else {
							$seconds=strtotime($row[4])-strtotime($row[3]); $class='';}
							$time=$seconds;
							$hours = floor($time / 3600);
							$minutes = floor(($time / 60) % 60);
							$seconds2 = $time % 60;
							$diff_horas=$hours.":".$minutes.":".$seconds2;
							$segundos_trabajo+=$seconds;
							echo "<td class='".$class."'>CheckOut at: " . $row['hora_salida'] . "</td>";
                            echo "</tr>";
                            
							$consulta = "SELECT * FROM `descanso` WHERE `id_jornada`=" . $id_jornada;
                            $resultados = mysqli_query($conexion, $consulta) ;
                            $row_cnt2 = mysqli_num_rows($resultados);
                            $row_cnt = $row_cnt + $row_cnt2;
                            
							while ($fila = mysqli_fetch_array($resultados)) {
							///////////MINUTOS Pausa ///////////////////////////////////////////////////////////	
								// Extraigo los tiempos y los comparo
								if(empty($fila[5])) { $segundos=strtotime('00:00:00')-strtotime($fila[4]); $class2='danger'; } else {
								$segundos=strtotime($fila[5])-strtotime($fila[4]); $class2=''; }
									$tiempo=$segundos;
									$horas = floor($tiempo / 3600);
									$minutos = floor(($tiempo / 60) % 60);
									$segundos2 = $tiempo % 60;
									$diff_horas_p=$horas.":".$minutos.":".$segundos2;
									$segundos_pausa+=$segundos;
								
                                echo "<tr>";
                                echo "<td>" . $fila['fecha'] . "</td>";
                                echo "<td>Break at: " . $fila['hora_entrada'] . "</td>";
                                echo "<td class='".$class2."'>Back at: " . $fila['hora_salida'] . "</td>";
                                echo "</tr>";
                            }
                        }
                        ?>	
                    </tbody>
                    <tfoot>
                        <tr>
                            <td height="50"><i class="fa fa-toggle-on"></i> Total Checkins: 
							<?php 
								if (isset($resultados)){
							
								printf($row_cnt);
                        		mysqli_free_result($resultados);
                       			mysqli_free_result($result); 
								
								}
								
								?></td>
                            <td><i class="fa fa-clock-o"></i> Working Hours:
                             <?php 
							 	$e_w_s=$segundos_trabajo-$segundos_pausa;
								$tiempo_trabajo_h=$e_w_s;
									$horas_t_t = floor($tiempo_trabajo_h / 3600);
									$minutos_t_t = floor(($tiempo_trabajo_h / 60) % 60);
									$segundos2_t_t = $tiempo_trabajo_h % 60;
									$diff_h_t=$horas_t_t.":".$minutos_t_t.":".$segundos2_t_t;
									echo $diff_h_t ;
							 
							 ?>
                            </td>
                            <td>
                            	<i class="fa fa-smile-o"></i> Break Hours:
                            	<?php 
							 	 
							 	$tiempo_pausa_h=$segundos_pausa;
									$horas_t_p = floor($tiempo_pausa_h / 3600);
									$minutos_t_p = floor(($tiempo_pausa_h / 60) % 60);
									$segundos2_t_p = $tiempo_pausa_h % 60;
									$diff_h_p=$horas_t_p.":".$minutos_t_p.":".$segundos2_t_p;
									echo $diff_h_p;
							 		
							 ?>
                            </td>
                      </tr>
                  </tfoot>
                </table>
                <table class="table">
            	<tr>
                      	<td style="font-size:14px">                        
                      		<?php  $total_paginas=ceil($row_cnt/$tamagno_paginas);
							echo " Page ".$pagina." of ".$total_paginas; 
							//echo". Showing ".$tamagno_paginas." records per page ";
					echo "</td>";
					echo "<td colspan='5'  style='text-align: right'>";
								for ($i=1; $i<=$total_paginas; $i++) {
									
									
									if (isset($_GET["Go"])){
										$fecha_inicio=$_GET["fecha_inicio"];
										$fecha_final=$_GET["fecha_final"];
										$id_trabajador=$_GET["trabajador"];
									//echo "   <a href='?fecha_inicio=".$fecha_inicio."&fecha_final=".$fecha_final."&trabajador=".$id_trabajador."&pagina=".$i."&Go=GO'>" .$i. "</a> ";
									}else{
										
										//echo "   <a href='?pagina=". $i."'>" .$i. "</a> ";
										
										}
									
									}
							
							mysqli_close($conexion);
							
							
							// Si hay pagina la guardamos en la variable pageno
							if (isset($_GET['pagina'])) {
    							$pageno = $_GET['pagina'];
							} else {
   								 $pageno = 1;
							}
							
							//Total paginas
							$total_pages = $total_paginas;
							?>
                             	
                        </td>
                       
                      </tr>
            </table>
            
             <?php if (isset($_GET["Go"])){ 
            
            // ===================================================================
            // Si buscamos entre fechas y/o por trabajador
            // ===================================================================
            
            	$fecha_inicio=$_GET["fecha_inicio"];
				$fecha_final=$_GET["fecha_final"];
            
            ?>
            <!------------------------------- First ---------------------->
             <ul class="pagination pull-right " style="margin-top: -50px;">
    				<li>
    					<a href="?pagina=1&fecha_inicio=<?php 
    															echo $fecha_inicio.
    																 "&fecha_final=" . $fecha_final .
    																 "&Go=Go"; 
    													?>">
    						First
    					</a>
    				</li>
    				
            <!------------------------------- Preview ---------------------->    				
    				<li class="<?php if($pageno <= 1){ echo 'disabled'; } ?>">
      			 	 <a href="<?php 
      			 	 	if($pageno <= 1){ 
      			 	 		echo '#'; 
      			 	 		} else { 
       				 					echo "?pagina=".($pageno - 1).
       				 						 "&fecha_inicio=".$fecha_inicio.
       				 						 "&fecha_final=".$fecha_final.
       				 						 "&Go=Go" ; 
      			 	 		
      			 	 	}
      			 	 
      			 	 ?>">Prev</a>
      			 	 
      			 	 
      
    				</li>
    				
    				
    				
             <!------------------------------- Next ---------------------->   				
    				
    				
   			   		 <li class="<?php if($pageno >= $total_pages){ echo 'disabled'; } ?>">
       				 <a href="<?php 
       				 				if($pageno >= $total_pages){ 
       				 					echo '#'; 
       				 					} else { 
       				 					echo "?pagina=".($pageno + 1).
       				 						 "&fecha_inicio=".$fecha_inicio.
       				 						 "&fecha_final=".$fecha_final.
       				 						 "&Go=Go" ; 
       				 					
       				 					} 
       				 				
       				 		  ?>">Next</a>
   					 </li>
            <!------------------------------- Last ---------------------->
            
            
                		
    		<li>
    			<a href="?pagina=<?php 
    						          echo  $total_pages.
    						          	    "&fecha_inicio=".$fecha_inicio.
    						          	    "&fecha_final=".$fecha_final.
    						          	    "&Go=Go"; 
    						      ?>">Last
    			</a>
    		</li>
		  </ul>
            
            
            
            
            <?php 
            
            } else { 
            
            	// =========================================================================
           		 // Si NO buscamos entre fechas y/o por trabajador la paginación sera normal
            	// =========================================================================            
            
            
	            ?>
	            
	            <ul class="pagination pull-right " style="margin-top: -50px;">
	    			<li><a href="?pagina=1">First</a></li>
 		   			<li class="<?php if($pageno <= 1){ echo 'disabled'; } ?>">
 	     			  <a href="<?php if($pageno <= 1){ echo '#'; } else { echo "?pagina=".($pageno - 1); } ?>">Prev</a>
  		  			</li>
 	  			    <li class="<?php if($pageno >= $total_pages){ echo 'disabled'; } ?>">
  	     			 <a href="<?php if($pageno >= $total_pages){ echo '#'; } else { echo "?pagina=".($pageno + 1); } ?>">Next</a>
  	 				 </li>
 		   			<li><a href="?pagina=<?php echo $total_pages; ?>">Last</a></li>
			  </ul>
            
            
            
            
            <?php }  ?>
            
            
            
            
            

            </article>
        </section>
        <footer>
<?php require("footer.php"); ?>
        </footer>
    </body>
</html>