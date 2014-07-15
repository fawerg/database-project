<?php
    ini_set('display_errors','On');
    define ('LOGINEXPIRE', 300);
    ini_set('session.cookie_lifetime',LOGINEXPIRE);
    session_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
		<link href="alter-style.css" rel="stylesheet" type="text/css" />
		<title>Crea categoria</title>
		<script>
			function myFunction(){
				if(document.getElementById("categoria").value == "Altro"){
					document.getElementById("button").value = "Continua";
				}
				else{
					document.getElementById("button").value = "Crea Transazione";
				}
			}
		</script>
	</head>
	<body>
		<div class="div-header">HOME BANKING - LA BANCA A CASA TUA</div>
		<table class='table-ext'>
			<?php
			include_once('bdlab-lib-fun.php');
               		if ($_SESSION['isLogged']){			
					print "<tr>
							<td class='td-menu-home'>
								<a href='alter-test.php'>H</a>
							</td>
							<td class='td-menu'>
								<a href='profilo.php'>Gestione Profilo</a>
							</td>
							<td class='td-menu'>
								<a href=\"conti.php\">Gestione Conti</a>
							</td>
							<td class='td-menu'>
								<a href=\"transazioni.php\">Gestione Transazioni</a>
							</td>
							<td class='td-menu'>
								<a href=\"bilancio.php\">Gestione Bilanci</a>
							</td>
							<td class='td-menu'>
								<a href='rapporti.php'>Rapporti</a>
							</td>
							<td class='td-menu'>
								<a href=\"logout.php\">Logout</a>
							</td>
						</tr>";
						if(!isset($_POST['crea'])){
							print"
							<tr>
								<td colspan='7' class='td-containt'>
									<form class='padding-el' method='post' action='ccat.php'>
										Tipo: <select name='tipo'><option>Spesa</option><option>Entrata</option></select>
										<input type='submit' name='crea' value='Invia' />
									</form>
								</td>
							</tr>";
						}
						if(!isset($_POST['crea2'])&&isset($_POST['crea'])){
							print"
							<tr>
								<td colspan='7' class='td-containt'>
									<form class='padding-el' method='post' action='ccat.php'>
									<table>
										<tr><td>Nome:</td> <td><input type='text' name='nome'/></td></tr>
										<tr><td>Descrizione:</td> <td><input type='text' nome='descr'></tr></td>
										<tr><td>Macrocategoria:</td>";
													$_POST['tipo'] == "Spesa" ? print "<td><select name='padre'>".lista_categorie($_SESSION['isLogged'], '-')."</select></td>" : print "<td><select name='padre'>".lista_categorie($_SESSION['isLogged'], '+')."</select></td></tr>";
														print " 
										<input type='hidden' name=tipo value=".$_POST['tipo'].">
										<tr><td><input type='submit' name='crea2' value='Invia' /></td></tr>
									</table>
									</form>
								</td>
							</tr>";
						}
						if(isset($_POST['crea2'])){
							insert_categoria($_POST['nome'], $_POST['tipo'], $_SESSION['isLogged'],$_POST['padre']);
							header('location:transazioni.php');
						}
			}
			else{
					header('Location: alter-test.php');
				}
			?>
			
		</table>
	</body>
</html>
