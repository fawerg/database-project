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
					</tr>";
					if(!isset($_POST['invia'])){
						print "
						<tr>
							<td colspan='7' class='td-containt'>
								<div class='div-table'>
									Rapporto sul saldo contabile
								</div>
								<br>
								<table>
									<tr>
										<div>
											Il saldo contabile in un dato momento risulta dalla differenza tra le operazioni registrate a credito e quelle registrate a debito in ordine di data; esso pertanto comprende anche le partite postgregate ed è il saldo solitamente risultante dall’estratto conto.
										</div>
									</tr>";	
									if(!isset($_POST['saldo_conto'])){
										print"
											<tr>
												<form class='padding-el' method='post' action='rapporti.php'>
													<input type='submit' name='saldo_conto' value='Richiedi Saldo Contabile'>
												</form>
											</tr>";
									} else {
										print"
											<tr>
												<form class='padding-el' method='post' action='rapporti.php'>
													Iban di riferimento: <select name='ib_rif'>".lista_iban($_SESSION['isLogged'], false)."</select>
													Data di inizio : <input type='date' name='data_inizio'/>
													Data di fine : <input type='date' name='data_fine'/>
													<input type='hidden' name='type' value='A'/><br>
													<input type='submit' name='invia' value='Invia'>
												</form>
											</tr>";
									}
								print
								"</table>
							</td>
						</tr>
						<tr>
							<td colspan='7' class='td-containt'>
								<div class='div-table'>
									Rapporto su saldo bilancio
								</div>
								<br>
								<table>
									<tr>
										<div>
											Il rapporto sul saldo del bilancio offre un'iidea di quanto si è speso per la categoria a cui è dedicata il bilancio.
										</div>
									</tr>";	
									if(!isset($_POST['saldo_bilancio'])){
										print"
											<tr>
												<form class='padding-el' method='post' action='rapporti.php'>
													<input type='submit' name='saldo_bilancio' value='Richiedi Saldo Bilancio'>
												</form>
											</tr>";
									} else {
										print"
											<tr>
												<form class='padding-el' method='post' action='rapporti.php'>
													Identificativo: <select name='id'>".lista_id($_SESSION['isLogged'])."</select>
													Data di inizio : <input type='date' name='data_inizio'/>
													Data di fine : <input type='date' name='data_fine'/>
													<input type='hidden' name='type' value='B'/><br>
													<input type='submit' name='invia' value='Invia'>
												</form>
											</tr>";
									}
								print
								"</table>
							</td>
						</tr>
						<tr>
							<td colspan='7' class='td-containt'>
								<div class='div-table'>
									Rapporto su confonto di bilanci 
								</div>
								<br>
								<table>
									<tr>
										<div>
											Il rapporto su confronto di diversi bilanci offre un paragone di spesa su differenti bilanci.
										</div>
									</tr>";	
									if(!isset($_POST['confronto_bilanci'])){
										print"
											<tr>
												<form class='padding-el' method='post' action='rapporti.php'>
													<input type='submit' name='confronto_bilanci' value='Richiedi Confronto Bilanci'>
												</form>
											</tr>";
									} else {
										print"
											<tr>
												<form class='padding-el' method='post' action='rapporti.php'>
													Identificativo1: <select name='id1'>".lista_id($_SESSION['isLogged'])."</select>
													Identificativo2: <select name ='id2' >".lista_id($_SESSION['isLogged'])."</select>
													Data di inizio : <input type='date' name='data_inizio'/>
													Data di fine : <input type='date' name='data_fine' /><br>
													<input type='hidden' name='type' value='C'/><br>
													<input type='submit' name='invia' value='Invia'>
												</form>
											</tr>";
									}
								print
								"</table>
							</td>
						</tr>
						<tr>
							<td colspan='7' class='td-containt'>
								<div class='div-table'>
									Rapporto su spese di categoria
								</div>
								<br>
								<table>
									<tr>
										<div>
											Il rapporto su spese di categorie offre un prospetto sulle percentuali di spesa dedicate alle categorie.
										</div>
									</tr>";	
									if(!isset($_POST['percentuale_categoria'])){
										print"
											<tr>
												<form class='padding-el' method='post' action='rapporti.php'>
													<input type='submit' name='percentuale_categoria' value='Richiedi Percentuali Spese'>
												</form>
											</tr>";
									} else {
										print"
											<tr>
												<form class='padding-el' method='post' action='rapporti.php'>
													Data di inizio : <input type='date' name='data_inizio'/>
													Data di fine : <input type='date' name='data_fine'/><br>
													<input type='hidden' name='type' value='D'/><br>
													<input type='submit' name='invia' value='Invia'>
												</form>
											</tr>";
									}
								print
								"</table>
							</td>
						</tr>";
					}
					else{
						switch($_POST['type']){
							case 'A':
								print"
									<tr>
										<td colspan='7' class='td-containt'>
											<div class='div-table'>
												Rapporto sul saldo contabile
											</div>
											<br>
											<table width='100%'>
												<tr>
													<td class='td-rapporti'>Data transazione(gg/mm/yy)</td><td class='td-rapporti'>Descrizione</td><td class='td-rapporti'>Categoria</td><td class='td-rapporti'></td><td class='td-rapporti'>Entità economica (€)</td>
												</tr>"
												.saldo_contabile($_SESSION['isLogged'], $_POST['ib_rif'], $_POST['data_inizio'], $_POST['data_fine']).
											"</table>
										</td>
									</tr>";
								break;
							case 'B':
								print"
									<tr>
										<td colspan='7' class='td-containt'>
											<div class='div-table'>
												Rapporto sul saldo bilancio
											</div>
											<br>
											<table width='100%'>
												<tr>
													<td class='td-rapporti'>Data transazione(gg/mm/yy)</td><td class='td-rapporti'>Descrizione</td><td class='td-rapporti'>Categoria</td><td class='td-rapporti'></td><td class='td-rapporti'>Entità economica (€)</td>
												</tr>"
												.saldo_bilancio($_SESSION['isLogged'], $_POST['id'], $_POST['data_inizio'], $_POST['data_fine']).
											"</table>
										</td>
									</tr>";
								break;
							case 'C':
								print"
									<tr>
										<td colspan='7' class='td-containt'>
											<div class='div-table'>
												Rapporto su confronti di bilanci
											</div>
											<br>
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
											"</table>
										</td>
									</tr>";
								break;
							case 'D':
								print"
									<tr>
										<td colspan='7' class='td-containt'>
											<div class='div-table'>
												Rapporto su spese di categoria
											</div>
											<br>
											<table width='100%'>
												<tr>
													<td class='td-rapporti'>Iban</td><td class='td-rapporti'>Nome categoria</td><td class='td-rapporti'></td><td class='td-rapporti'></td><td class='td-rapporti'>Somma entrate/spese</td>
												</tr>
												<yt>
													<td class='td-rapporti'>".percentuale_spesa($_SESSION['isLogged'], $_POST['data_inizio'], $_POST['data_fine'])."</td>
												</tr>
											</table>
										</td>
									</tr>";
								break;
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
