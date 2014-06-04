<?php
include_once('../../conf.php');
  
function user_check($name, $pass) {
//verifica la correttezza di login e password inserite e imposta il contenuto della sessione

	$db = connection_pgsql();
	
	$sql = "SELECT password FROM final_db.utente WHERE mail = $1";
	$result = pg_prepare($db, "q", $sql);
	$value = array($name);
	$result = pg_execute($db, "q", $value);
	$row = pg_fetch_assoc($result);
	
	pg_free_result($result);
	pg_close($db);
	
	if($row['password'] == $pass){
		$_SESSION['isLogged'] = $name;
		return true;
	}
	else
		return false;
}
  
/* function user_category($name)  {
//restituisce la categoria dell'utente specificato in input
    
global $category;

    return $category[$name]; 

}*/

function print_user_data($username){
	
	$db = connection_pgsql();
	
	$sql = "SELECT nome, cognome, indirizzo, mail FROM final_db.utente WHERE mail = $1";
	$result = pg_prepare($db, "q", $sql);
	$value = array($username);
	$result = pg_execute($db, "q", $value);
	$row = pg_fetch_assoc($result);
	$string = "<pre>
Nome: ".$row['nome']."
Cognome: ".$row['cognome']."
Indirizzo: ".$row['indirizzo']."
E-mail: ".$row['mail']."
			</pre>";
	pg_free_result($result);
	pg_close($db);
	
	return $string;

}

function print_conti($username){
	$db = connection_pgsql();
	
	$sql= "SELECT iban, tipologia FROM final_db.conto WHERE mail = $1";
	$result= pg_prepare($db , "q", $sql);
	$value = array($username);
	$result= pg_execute($db, "q", $value);
	$s="";
	$i=1;
	while($row = pg_fetch_assoc($result)){
		$s.="<pre>Conto".$i.": 
			Iban: ".$row['iban']."
			Tipologia: ".$row['tipologia']."</pre>";
		$i++;
	}
	pg_free_result($result);
	pg_close($db);
	return $s;
}

function print_bilanci($username){
	$db = connection_pgsql();
	
	$sql= "SELECT disponibilita, data_scadenza, iban, nome FROM final_db.bilancio NATURAL JOIN final_db.categoria_bilancio WHERE mail = $1";
	$result= pg_prepare($db , "q", $sql);
	$value = array($username);
	$result= pg_execute($db, "q", $value);
	$s="";
	while($row = pg_fetch_assoc($result)){
		$s.="<pre>Disponibilità: ".$row['disponibilita']." 
			Data Scadenza: ".$row['data_scadenza']."
			Conto Associato: ".$row['iban']."
			Usufrutto: ".$row['nome']."</pre>";
	}
	pg_free_result($result);
	pg_close($db);
	return $s;
}

function print_transazioni($username){
	$db = connection_pgsql();
	
	$sql= "SELECT * FROM final_db.transazione WHERE mail = $1";
	$result= pg_prepare($db , "q", $sql);
	$value = array($username);
	$result= pg_execute($db, "q", $value);
	$s="";
	while($row = pg_fetch_assoc($result)){
		$s.="<pre>
			Data: ".$row['data_transazione']." 
			Iban: ".$row['iban']."
			Ammontare: ".$row['entita_economica']."
			Descrizione: ".$row['descrizione']."</pre>";
	}
	pg_free_result($result);
	pg_close($db);
	return $s;
}

function print_conti_dep($username){
	$db = connection_pgsql();
	
	$sql= "SELECT iban, ammontare FROM final_db.conto WHERE mail = $1 AND tipologia='Deposito'";
	$result= pg_prepare($db , "q", $sql);
	$value = array($username);
	$result= pg_execute($db, "q", $value);
	$s="";
	$i=1;
	while($row = pg_fetch_assoc($result)){
		$s.="<pre>Conto".$i.": 
			Iban: ".$row['iban']."
			Disponibilità: ".$row['ammontare']."
		</pre>";
		$i++;
	}
	pg_free_result($result);
	pg_close($db);
	return $s;	
}

function print_conti_cred($username){
	$db = connection_pgsql();
	
	$sql= "SELECT iban, ammontare , tetto_max, deposito_riferimento  FROM final_db.conto_credito NATURAL JOIN final_db.conto WHERE mail = $1";
	$result= pg_prepare($db , "q", $sql);
	$value = array($username);
	$result= pg_execute($db, "q", $value);
	$s="";
	$i=1;
	while($row = pg_fetch_assoc($result)){
		$s.="<pre>Conto".$i.": 
			Iban: ".$row['iban']."
			Disponibilità: ".$row['ammontare']."
			Tetto: ".$row['tetto_max']."
			Iban conto deposito associato: ".$row['deposito_riferimento']."
		</pre>";
	}
	pg_free_result($result);
	pg_close($db);
	return $s;
}

function insert_conto($ammontare, $tetto, $deprif, $mail){
	$db = connection_pgsql();
	
	$string = 'IT';
	for($i=0; $i<30; $i++)
		$string .= rand(0, 9);
	if($tetto != NULL && $deprif != NULL){
		$sql = "INSERT INTO final_db.conto VALUES ($1, $2, $3, $4)";
		$result = pg_prepare($db, 'q1', $sql);
		$value = array($string, $ammontare, 'Credito', $mail);
		$result = pg_execute($db, 'q1', $value);
		$sql = "INSERT INTO final_db.conto_credito VALUES ($1, $2, $3)";
		$result = pg_prepare($db, 'q2', $sql);
		$value = array($string, $tetto, $deprif);
		$result = pg_execute($db, 'q2', $value);
	} else {
		$sql = "INSERT INTO final_db.conto VALUES ($1, $2, $3, $4)";
		$result = pg_prepare($db, 'q', $sql);
		$value = array($string, $ammontare, 'Deposito', $mail);
		$result = pg_execute($db, 'q', $value);
	}
	pg_free_result($result);
	pg_close($db);
}

function lista_iban_deposito($mail){
	$db = connection_pgsql();
	
	$sql= "SELECT iban FROM final_db.conto WHERE (mail = $1 AND tipologia = $2)";
	$result= pg_prepare($db , "q", $sql);
	$value = array($mail, 'Deposito');
	$result = pg_execute($db, "q", $value);
	$string = '';
	while($row = pg_fetch_assoc($result)){
		$string .= '<option>'.$row['iban'].'</option>';
	}
	pg_free_result($result);
	pg_close($db);
	return $string;
}

function change_name($username, $name){
	$db=connection_pgsql();
	$sql="UPDATE final_db.utente SET nome=$2 WHERE mail=$1";
	$result= pg_prepare($db, "q", $sql);
	$value=array($username, $name);
	$result=pg_execute($db, "q", $value);
	pg_free_result($result);
	pg_close($db);
	
}

function change_surname($username, $surname){
	$db=connection_pgsql();
	$sql="UPDATE final_db.utente SET cognome=$2 WHERE mail=$1";
	$result= pg_prepare($db, "q", $sql);
	$value=array($username, $surname);
	$result=pg_execute($db, "q", $value);
	pg_free_result($result);
	pg_close($db);
	
}

function change_address($username, $address){
	$db=connection_pgsql();
	$sql="UPDATE final_db.utente SET indirizzo=$2 WHERE mail=$1";
	$result= pg_prepare($db, "q", $sql);
	$value=array($username, $address);
	$result=pg_execute($db, "q", $value);
	pg_free_result($result);
	pg_close($db);
	
}

function change_mail($username, $mail){
	$db=connection_pgsql();
	$sql="UPDATE final_db.utente SET mail=$2 WHERE mail=$1";
	$result= pg_prepare($db, "q", $sql);
	$value=array($username, $mail);
	$result=pg_execute($db, "q", $value);
	$_SESSION['isLogged']=$mail;
	pg_free_result($result);
	pg_close($db);
	
}

function user_logout() {
//disconnette l'utente eliminando il contenuto della sessione
    
    unset ($_SESSION['isLogged']);

}

function connection_pgsql() {
//apre una connessione con il DBMS postgreSQL le cui coordinate sono definite in conf.php
    
    $connection = "host=".myhost." dbname=".mydb." user=".myuser." password=".mypsw;
    
    return pg_connect ($connection);
    
}
?>
