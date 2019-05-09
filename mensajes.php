<?php
/*
Autor: Ilie Florea 
email: virginialyonsit@gmail.com 
Version: 1.0
*/



			if (isset($_GET["mensaje"])){
				$mensaje=$_GET['mensaje'];
					
					if ($mensaje < "1") {
							echo "<article>";
               			    echo "	<div class='alert warning'>";
 							echo "	  <span class='closebtn'>&times;</span>";
  							echo "		<strong>Warning!</strong> Operation unsucessfull please contact your administrator.";
                			echo "      <script src='js/mensaje_borrar_trabajador_ok.js'></script>";
							echo "	</div>";
            				echo "</article>";			
						
						
					} else {
							echo "<article>";
               			    echo "	<div class='alert success'>";
 							echo "	  <span class='closebtn'>&times;</span>";
  							echo "		<strong>OK!</strong> One record has been deleted.";
                			echo "      <script src='js/mensaje_borrar_trabajador_ok.js'></script>";
							echo "	</div>";
            				echo "</article>";
							}
			}
			
			
			if (isset($_GET["ok"])){
				$ok=$_GET['ok'];
					
					if ($ok < "1") {
							echo "<article>";
               			    echo "	<div class='alert warning'>";
 							echo "	  <span class='closebtn'>&times;</span>";
  							echo "		<strong>Warning!</strong> Error updating record please contact your administrator.";
                			echo "      <script src='js/mensaje_borrar_trabajador_ok.js'></script>";
							echo "	</div>";
            				echo "</article>";			
						
						
					} else {
							echo "<article>";
               			    echo "	<div class='alert success'>";
 							echo "	  <span class='closebtn'>&times;</span>";
  							echo "		<strong>OK !</strong> Record updated successfully.";
                			echo "      <script src='js/mensaje_borrar_trabajador_ok.js'></script>";
							echo "	</div>";
            				echo "</article>";
							}
			}
		
		 if (isset($_GET["ok2"])){
				$ok2=$_GET['ok2'];
					
					if ($ok2 < "1") {
							echo "<article>";
               			    echo "	<div class='alert warning'>";
 							echo "	  <span class='closebtn'>&times;</span>";
  							echo "		<strong>Warning!</strong> Error creating new record, please contact your administrator.";
                			echo "      <script src='js/mensaje_borrar_trabajador_ok.js'></script>";
							echo "	</div>";
            				echo "</article>";			
						
						
					} else {
							echo "<article>";
               			    echo "	<div class='alert success'>";
 							echo "	  <span class='closebtn'>&times;</span>";
  							echo "		<strong>OK !</strong> Record created successfully.";
                			echo "      <script src='js/mensaje_borrar_trabajador_ok.js'></script>";
							echo "	</div>";
            				echo "</article>";
							}
			}
		
        ?>