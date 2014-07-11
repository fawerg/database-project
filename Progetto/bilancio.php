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
		<title>Gestione Conti</title>
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
									Bilanci: 
								</div>".print_bilanci($_SESSION['isLogged'])."							
							</td>
						</tr>";
					if(!isset($_POST['Crea'])& !isset($_POST['Invia'])){
						print
							"<tr>
								<td colspan='7' class='td-containt'>
									<form class='padding-el' method='post' action='bilancio.php'>									
												<input type='submit' value='Crea Nuovo Bilancio' name='Crea' />
									</form>
								</td>
							</tr>";
					}
					else{
							print
								"<tr>	
									<td colspan='7' class='td-containt'>
										<form class='padding-el' method='post' action='bilancio.php'>
											<table>
												<tr>
													<td>Disponibilità: </td><td><input type='text' name='disponibilità'/></td>
												</tr>
												<tr>
													<td>Valore Iniziale: </td><td><input type='text' name ='val_iniziale'/></td>
												</tr>
												<tr>
													<td>Data finale: </td><td><input type='date' name='data_fine'></td>
												</tr>
												<tr>
													<td>Iban Riferimento: </td><td><select name='ib_rif'>".lista_iban($_SESSION['isLogged'], false)."</select></td>
												</tr>
												<tr>
													<td>Categoria di bilancio: </td><td><select name='cat_ref'>".lista_categorie($_SESSION['isLogged'], "-")."</select></td>
												</tr>
												<tr>
													<td><input type='submit' name='Invia' value='Crea'/></td>
												</tr>
											</table>
										</form>
									</td>
								</tr>";
						}
						if(isset($_POST['Invia'])){
							insert_bilancio($_POST['disponibilità'], $_POST['val_iniziale'], $_POST['data_fine'], $_POST['ib_rif'], $_SESSION['isLogged'], $_POST['cat_ref']);
							header('Location: bilancio.php');
						}
					
				}
				else{
					header('Location: alter-test.php');
				}
			?>
		</table>
	</body>
</html>
