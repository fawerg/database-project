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
		<title>Pagina Test</title>
	</head>
	<body>
		<div class="div-header">PAGINA DI REGISTRAZIONE</div>
		<table class='table-ext'>
			<tr>
				<td colspan='5' class='td-containt'>
					<div class="div-table">Registrazione</div>
					<?php
						include_once ('bdlab-lib-fun.php');
						if(!isset($_POST['registra'])){
							print '<form class="padding-el" method="POST" action="main.php">
								<table>
									<tr>
										<td>Nome:</td>
										<td><input type="text" name="nome" /></td>
									</tr>
									<tr>
										<td>Cognome:</td>
										<td><input type="text" name="cognome" /></td>
									</tr>
									<tr>
										<td>Indirizzo:</td>
										<td><input type="text" name="indirizzo" /></td>
									</tr>
									<tr>
										<td>Mail:</td>
										<td><input type="email" name="mail" /></td>
									</tr>
									<tr>
										<td>Password:</td>
										<td><input type="password" name="password" /></td>
									</tr>
								</table>
								<input type="submit" name="registra" value="Registra" />
							</form>';
						} else {
						
							$db = connection_pgsql();
							
							$bool = false;
							$sql = "SELECT mail FROM final_db.utente";
							$result = pg_prepare($db, "q", $sql);
							$result = pg_execute($db, "q", array());
							$data = array();
							
							while($row = pg_fetch_assoc($result)){
								if($row['mail'] == $_POST['mail']){
									$bool = true;
								}
								$data[] = $row;
							}
							
							if($bool){
								print '<div color="red"> Utente già presente, reinserire dati </div> 
								<form class="padding-el" method="POST" action="main.php">
								<table>
									<tr>
										<td>Nome:</td>
										<td><input type="text" name="nome" /></td>
									</tr>
									<tr>
										<td>Cognome:</td>
										<td><input type="text" name="cognome" /></td>
									</tr>
									<tr>
										<td>Indirizzo:</td>
										<td><input type="text" name="indirizzo" /></td>
									</tr>
									<tr>
										<td>Mail:</td>
										<td><input type="email" name="mail" /></td>
									</tr>
									<tr>
										<td>Password:</td>
										<td><input type="password" name="password" /></td>
									</tr>
								</table>
								<input type="submit" name="registra" value="Registra" />
								</form>';
							} else {
								$fields = "mail, nome, cognome, indirizzo, password";
								$values = array($_POST['mail'], $_POST['nome'], $_POST['cognome'], $_POST['indirizzo'], $_POST['password']);
		
								$s = "INSERT INTO final_db.utente(".$fields.") VALUES($1, $2, $3, $4, $5);";
							
								$insres = pg_prepare($db, "i", $s);
								$insres = pg_execute($db, "i", $values);
								
								print "<div> Registrazione avvenuta con successo </div>";
								sleep (4);
								
								header ("Location: alter-test.php");
							}
							
						}
					?>
				</td>
			</tr>
		</table>
	</body>
</html>
