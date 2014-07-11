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
					print "	
						<tr>
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
								</div>".print_transazioni($_SESSION['isLogged'])."
							</td>
						</tr>";
					if(!isset($_POST['continua']) && !isset($_POST['step']) && !isset($_POST['crea'])){
						print "<tr>
							<td colspan='7' class='td-containt'>
								<div class='div-table'>Dettagli Conto</div>
								<form class=\"padding-el\" method=\"post\" action=\"ct.php\">
									<table>
										<tr>
											<td>Tipologia:</td>
											<td><select name='tipo'><option>Spesa</option><option>Entrata</option></select></td>
										</tr>
									</table>	
									<input type='submit' name='continua' value='Coninua'>
								</form>
							</td>
						</tr>";
					}
					else{
						if(isset($_POST['continua'])){
							print "<tr>
									<td colspan='7' class='td-containt'>
										<div class='div-table'>Dettagli Transazione</div>
										<form class='padding-el' method='POST' action='ct.php'>
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
													<td>Categoria: </td>";
													$_POST['tipo'] == "Spesa" ? print "<td><select name='categoria'>".lista_categorie($_SESSION['isLogged'], '-')."<option>Altro</option></select></td>" : print "<td><select name='categoria'>".lista_categorie($_SESSION['isLogged'], '+')."<option>Altro</option></select></td>";
														print "
												</tr>
												<tr>
													<td><input type='hidden' name='tipo' value='".$_POST['tipo']."'/></td>
												</tr>
											</table>
											<input type='submit' name='step' value='Crea Transazione'>
										<form>
									</td>
								</tr>";
						}
						if(isset($_POST['step'])){
							if($_POST['categoria'] != "Altro"){
								insert_transazione($_POST['descrizione'], $_POST['ammontare'], $_POST['iban'], $_SESSION['isLogged'], $_POST['categoria']);
								header('Location: transazioni.php');
							}
							else{
								print "<tr>
									<td colspan='7' class='td-containt'>
										<div class='div-table'>Dettagli Transazione</div>
										<form class='padding-el' method='POST' action='ct.php'>
											<table>
												<tr>
													<td>Nome: </td>
													<td><input type='text' name='nome'/></td>
												</tr>
												<tr>
													<td>Macro-categoria: </td>";
													$_POST['tipo'] == "Spesa" ? print "<td><select name='padre'>".lista_categorie($_SESSION['isLogged'], '-')."</select></td>" : print "<td><select name='padre'>".lista_categorie($_SESSION['isLogged'], '+')."</select></td>";
														print "
												</tr>
												<tr>
													<td><input type='hidden' name='descrizione' value='".$_POST['descrizione']."'/></td>
												</tr>
												<tr>
													<td><input type='hidden' name='ammontare' value='".$_POST['ammontare']."'/></td>
												</tr>
												<tr>
													<td><input type='hidden' name='iban' value='".$_POST['iban']."'/></td>
												</tr>
												<tr>
													<td><input type='hidden' name='tipo' value='".$_POST['tipo']."'/></td>
												</tr>
											</table>
											<input type='submit' name='crea' value='Crea Transazione'>
										<form>
									</td>
								</tr>";
							}
						}
						if(isset($_POST['crea'])){
							insert_categoria($_POST['nome'], $_POST['tipo'], $_SESSION['isLogged'], $_POST['padre']);
							insert_transazione($_POST['descrizione'], $_POST['ammontare'], $_POST['iban'], $_SESSION['isLogged'], $_POST['nome']);
							header('Location: transazioni.php');
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
