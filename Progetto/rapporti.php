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
		<title>Rapporti</title>
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
									Rapporto sul saldo contabile
								</div>
								<br>";
								if(!isset($_POST['saldo']) && !isset($_POST['Invia'])){
									print"
									<table>			
										<tr>
											<div>
			Il saldo contabile in un dato momento risulta dalla differenza tra le operazioni registrate a credito e quelle registrate a debito in ordine di data; esso pertanto comprende anche le partite postgregate ed è il saldo solitamente risultante dall’estratto conto.
											</div>
										</tr>
										<br>
										<tr>
											<form class='padding-el' method='post' action='rapporti.php'>
												<input type='submit' name='saldo' value='Richiedi saldo contabile'>
											</form>
										
										</tr>

									</table>";
								}
								if(isset($_POST['saldo'])&& !isset($_POST['Invia'])){
									print"
									<table>
										<tr>
											<form class='padding-el' method='post' action='rapporti.php'>
												Iban di riferimento: <select name='ib_rif'>".lista_iban($_SESSION['isLogged'], false)."</select>
												Data di inizio : <input type='date' name='data_inizio'/>
												Data di fine : <input type='date' name='data_fine' /><br>
												<input type='submit' name='Invia' value='Invia'>
											</form>
										
										</tr>
									</table>
									";
								}
								if(isset($_POST['Invia'])){
									print"
									<table width='100%'>
										<tr>
											<td class='td-rapporti'>Data transazione(gg/mm/yy)</td><td class='td-rapporti'>Descrizione</td><td class='td-rapporti'>Categoria</td><td class='td-rapporti'></td><td class='td-rapporti'>Entità economica (€)</td>
										</tr>"
										.saldo_contabile($_SESSION['isLogged'], $_POST['ib_rif'], $_POST['data_inizio'], $_POST['data_fine']).
									"</table>";
									
								}
							print"
							</td>
						</tr>
						<tr>
		
							<td colspan='7' class='td-containt'>
								<div class='div-table'>
									Rapporto su saldo bilancio
								</div>
								<br>";
								if(!isset($_POST['saldo_b']) && !isset($_POST['Invia_b'])){
									print"
									<table>			
										<tr>
											<div>
												Il rapporto sul saldo del bilancio offre un'iidea di quanto si è speso per la categoria a cui è dedicata il bilancio.	
											</div>
										</tr>
										<br>
										<tr>
											<form class='padding-el' method='post' action='rapporti.php'>
												<input type='submit' name='saldo_b' value='Richiedi saldo bilancio'>
											</form>
										
										</tr>

									</table>";
								}
								if(isset($_POST['saldo_b'])&& !isset($_POST['Invia_b'])){
									print"
									<table>
										<tr>
											<form class='padding-el' method='post' action='rapporti.php'>
												Identificativo: <select name='id'>".lista_id($_SESSION['isLogged'])."</select>
												Data di inizio : <input type='date' name='data_inizio'/>
												Data di fine : <input type='date' name='data_fine' /><br>
												<input type='submit' name='Invia_b' value='Invia'>
											</form>
										
										</tr>
									</table>
									";
								}
								if(isset($_POST['Invia_b'])){
									print"
									<table width='100%'>
										<tr>
											<td class='td-rapporti'>Data transazione(gg/mm/yy)</td><td class='td-rapporti'>Descrizione</td><td class='td-rapporti'>Categoria</td><td class='td-rapporti'></td><td class='td-rapporti'>Entità economica (€)</td>
										</tr>"
										.saldo_bilancio($_SESSION['isLogged'], $_POST['id'], $_POST['data_inizio'], $_POST['data_fine']).
									"</table>";
									
								}
								print"
							</td>
						</tr>
						<tr>
		
							<td colspan='7' class='td-containt'>
								<div class='div-table'>
									Rapporto su confonto di bilanci 
								</div>
								<br>";
								if(!isset($_POST['saldo_c']) && !isset($_POST['Invia_c'])){
									print"
									<table>			
										<tr>
											<div>
												Il rapporto su confronto di diversi bilancii offre un paragone di spesa su differenti bilanci.	
											</div>
										</tr>
										<br>
										<tr>
											<form class='padding-el' method='post' action='rapporti.php'>
												<input type='submit' name='saldo_c' value='Richiedi confronto bilancii'>
											</form>
										
										</tr>

									</table>";
								}
								if(isset($_POST['saldo_c'])&& !isset($_POST['Invia_c'])){
									print"
									<table>
										<tr>
											<form class='padding-el' method='post' action='rapporti.php'>
												Identificativo1: <select name='id1'>".lista_id($_SESSION['isLogged'])."</select>
												Identificativo2: <select name ='id2' >".lista_id($_SESSION['isLogged'])."</select>
												Data di inizio : <input type='date' name='data_inizio'/>
												Data di fine : <input type='date' name='data_fine' /><br>
												<input type='submit' name='Invia_c' value='Invia'>
											</form>
										
										</tr>
									</table>
									";
								}
								if(isset($_POST['Invia_c'])){
									print"
									<table width='100%'>
										<tr>
											<td class='td-rapporti'>Data transazione(gg/mm/yy)</td><td class='td-rapporti'>Descrizione</td><td class='td-rapporti'>Categoria</td><td class='td-rapporti'></td><td class='td-rapporti'>Entità economica (€)</td>
										</tr>"
										.saldo_bilancio($_SESSION['isLogged'], $_POST['id1'], $_POST['data_inizio'], $_POST['data_fine']).
									"</table>
									<table width='100%'>
										<tr>
											<td class='td-rapporti'>Data transazione(gg/mm/yy)</td><td class='td-rapporti'>Descrizione</td><td class='td-rapporti'>Categoria</td><td class='td-rapporti'></td><td class='td-rapporti'>Entità economica (€)</td>
										</tr>"
										.saldo_bilancio($_SESSION['isLogged'], $_POST['id2'], $_POST['data_inizio'], $_POST['data_fine']).
									"</table>";
									
								}
								print"
							</td>
						</tr>
						<tr>
		
							<td colspan='7' class='td-containt'>
								<div class='div-table'>
									Rapporto su spese di categoria
								</div>
								<br>";
								if(!isset($_POST['per_cat']) && !isset($_POST['Invia_per'])){
									print"
									<table>			
										<tr>
											<div>
												Il rapporto su spese di categorie offre un prospetto sulle percentuali di spesa dedicate alle categorie.	
											</div>
										</tr>
										<br>
										<tr>
											<form class='padding-el' method='post' action='rapporti.php'>
												<input type='submit' name='per_cat' value='Richiedi spese percentuali'>
											</form>
										
										</tr>

									</table>";
								}
								if(isset($_POST['per_cat'])&& !isset($_POST['Invia_per'])){
									print"
									<table>
										<tr>
											<form class='padding-el' method='post' action='rapporti.php'>
												Data di inizio : <input type='date' name='data_inizio'/>
												Data di fine : <input type='date' name='data_fine' /><br>
												<input type='submit' name='Invia_per' value='Invia'>
											</form>
										
										</tr>
									</table>
									";
								}
								if(isset($_POST['Invia_per'])){
									print"
									<table width='100%'>
										<tr>
											<td class='td-rapporti'>".percentuale_spesa($_SESSION['isLogged'], $_POST['data_inizio'], $_POST['data_fine'])."</td>
										</tr>
									</table>"
									;
									
								}
								print"
							</td>
						</tr>";
			}
			else{
					header('Location: alter-test.php');
				}
			?>
			
		</table>
	</body>
</html>
