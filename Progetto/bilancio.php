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
								</div>
								<div class='div-scroll'>
									".print_bilanci($_SESSION['isLogged'], NULL)."
								</div>
							</td>
						</tr>";
					if(!isset($_POST['Crea'])&& !isset($_POST['Invia'])&& !isset($_POST['del_bilancio']) && !isset($_POST['del_bil_b'])){
						print
							"<tr>
								<td colspan='7' class='td-containt'>
									<form class='padding-el' method='post' action='bilancio.php'>	
												<input type='submit' value='Crea Nuovo Bilancio' name='Crea' />
												<input type='submit' value='Elimina bilancio' name='del_bilancio'/>
									</form>
								</td>
							</tr>";
					}
					else{
						if(!isset($_POST['del_bilancio']) && !isset($_POST['del_bil_b'])){
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
													<td>Categoria di bilancio: </td><td>".lista_categorie_checkbox($_SESSION['isLogged'], "-")."</td>
												</tr>
												<tr>
													<td><input type='submit' name='Invia' value='Crea'/></td>
												</tr>
											</table>
										</form>
									</td>
								</tr>";
						}
						if(isset($_POST['del_bilancio'])){
						
							print "<tr><td colspan='7' class='td-containt'>
								<form class='padding-el' method='post' action='bilancio.php'>
									Identificativo: <select name='del_id'>".lista_id($_SESSION['isLogged'])."</select>
									<input type='submit' name='del_bil_b' value='Invia'/>
								</form>
							</td></tr>";
						}
						if(isset($_POST['del_bil_b'])){
							remove_bilancio($_SESSION['isLogged'], $_POST['del_id']);
							header('Location: bilancio.php');
						}
					
						if(isset($_POST['Invia'])){
							$array_key = array_keys($_POST);
							$array_categories = array();
							for($i=4; $i<count($array_key)-1; $i++){
								$array_categories[] = $_POST[$array_key[$i]];
							}
							insert_bilancio($_POST['disponibilità'], $_POST['val_iniziale'], $_POST['data_fine'], $_POST['ib_rif'], $_SESSION['isLogged'], $array_categories);
							header('Location: bilancio.php');
						}
					}
				}
				else{
					header('Location: alter-test.php');
				}
			?>
		</table>
	</body>
</html>
