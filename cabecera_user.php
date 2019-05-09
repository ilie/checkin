 <div id="logo_cabecera"><img src="img/LogoCkeckIn.jpg" alt="LogoCheckIn"></div>
            <table border="0" cellspacing="0" cellpadding="5" id="tabla_cabecera">
          		<tr>
            		<td id="usuario_cabecera"><?php echo $_SESSION['nombre'] . " | " ; ?></td>
            		<td id="cerrar_sesion_cabecera"> <a href="logout.php"> <img src="img/cerrar_sesion.png" width="20" height="19" longdesc="logout.php"/></a></td>
            		<td id="logout"><a href="logout.php">Logout</a></td>
          		</tr>
             </table>   
            <nav class="top_menu">
                <ul>
                    <li><a href="history.php">History</a></li>
                    <li><a href="user.php">CheckIn</a></li>
                </ul>
            </nav>