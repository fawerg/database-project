<?php
    ini_set('display_errors','Off');
    define ('LOGINEXPIRE', 300);
    ini_set('session.cookie_lifetime',LOGINEXPIRE);
    session_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
		<link href="alter-style.css" rel="stylesheet" type="text/css" />
		<title>Transazioni</title>
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
						</tr>
						<tr>
							<td colspan='7' class='td-containt'>
								<div class='div-table'>
									Transazioni:
								</div>
								<div class='div-scroll'>
									".print_transazioni($_SESSION['isLogged'])."
								</div>
							</td>
						</tr>";
						if(!isset($_POST['prog']) && !isset($_POST['crea']) && !isset($_POST['avanti'])){
							print"
							<tr>
								<td colspan='7' class='td-containt'>
									<form class='padding-el' method='post' action='ct.php'>									
										<input type='submit' name='transazione' value='Crea Nuova Transazione' />
									</form>
									<form class='padding-el' method='post' action='ccat.php'>
										<input type='submit' name='categoria' value='Crea Nuova Categoria' />
									</form>
									<form class='padding-el' method='post' action='transazioni.php'>
										<input type='submit' name='prog' value='Crea transazione programmata'>
									</form>
								</td>
							</tr>";
						}
						if(!isset($_POST['avanti']) && isset($_POST['prog'])){
							print "
								<tr>
									<td colspan='7' class='td-containt'>
										<form class='padding-el' method='post' action='transazioni.php'>
											<select name='tipo'><option>Spesa</option><option>Entrata</option></select>
											<input type='submit' name='avanti' value='Avanti'/>
										</form>
									</td>
								</tr> 	
							";								
						}
						if(isset($_POST['avanti'])){
							print"
							<tr>
								<td colspan='7' class='td-containt'>
									<form method='post' action='transazioni.php'> 
										<table>
												<tr>
													<td>Descrizione: </td>
													<td><input type='text' name='descrizione'/></td>
												</tr>
												<tr>
													<td>Ammontare: </td>
													<td><input type='text' name='ammontare'/></td>
												</tr>
												<tr>
													<td>Iban Associato: </td>
													<td><select name='iban'>".lista_iban($_SESSION['isLogged'], false)."</select></td>
												</tr>
												<tr>
													<td>Data programmata: </td>
													<td><input type='date' name='d'/> </td>
												</tr>
												<tr>
													<td>Categoria: </td>";
													$_POST['tipo'] == "Spesa" ? print "<td><select id='categoria' onChange='myFunction()' name='categoria'>".lista_categorie($_SESSION['isLogged'], '-')."<option>Altro</option></select></td>" : print "<td><select id='categoria' onChange='myFunction()' name='categoria'>".lista_categorie($_SESSION['isLogged'], '+')."<option>Altro</option></select></td>";
														print "
												</tr>
											
												<tr>
													<td colspas='7' >
														<input type='submit' name='crea' value='Invia'>
													</td>
												</tr>
										</table>
									</form> 
								</td>
							</tr>
							";

						}
						if(isset($_POST['crea'])){
							insert_transazione($POST['descrizione'], $POST['ammontare'], $POST['iban'], $SESSION['isLogged'], $POST['categoria'], $_POST['d'],"p");
							//header('Location: transazioni.php');
						}
			}
			else{
					header('Location: alter-test.php');
				}
			?>
			
		</table>
	</body>
</html>
