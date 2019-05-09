<?php
/*
Autor: Ilie Florea 
email: virginialyonsit@gmail.com 
Version: 1.0

*/





			if (isset($_GET["error"])){
				$mensaje=$_GET['error'];
							echo "<article>";
               			    echo "	<div class='alert warning'>";
 							echo "	  <span class='closebtn'>&times;</span>";
  							echo  $mensaje;
                			echo "      <script src='js/mensaje_borrar_trabajador_ok.js'></script>";
							echo "	</div>";
            				echo "</article>";			
						
						
					
			}
			
			if (isset($_GET["ok"])){
				$ok=$_GET['ok'];
							echo "<article>";
               			    echo "	<div class='alert success'>";
 							echo "	  <span class='closebtn'>&times;</span>";
  							echo  $ok;
                			echo "      <script src='js/mensaje_borrar_trabajador_ok.js'></script>";
							echo "	</div>";
            				echo "</article>";			
						
						
					}
			if (isset($_GET["mensaje"])){
				$ok=$_GET['mensaje'];
							echo "<article>";
               			    echo "	<div class='alert success'>";
 							echo "	  <span class='closebtn'>&times;</span>";
  							echo  $ok;
                			echo "      <script src='js/mensaje_borrar_trabajador_ok.js'></script>";
							echo "	</div>";
            				echo "</article>";			
						
						
					}
		
?>