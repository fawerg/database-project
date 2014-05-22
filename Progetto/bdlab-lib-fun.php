<?php
include_once('../../conf.php');
  
function user_check($name, $pass) {
//verifica la correttezza di login e password inserite e imposta il contenuto della sessione

	$db = connection_pgsql();
	
	$sql = "SELECT pwd FROM progetto_db.utente WHERE mail = $1";
	$result = pg_prepare($db, "q", $sql);
	$value = array($name);
	$result = pg_execute($db, "q", $value);
	$row = pg_fetch_assoc($result);
	
	pg_free_result($result);
	pg_close($db);
	
	print_r ($row);
	
	if($row['pwd'] == $pass){
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
	
	$sql = "SELECT nome, cognome, indirizzo, mail FROM progetto_db.utente WHERE mail = $1";
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
	
	$sql= "SELECT iban, tipologia FROM progetto_db.conto WHERE mail = $1";
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

function print_conti_dep($username){
	$db = connection_pgsql();
	
	$sql= "SELECT iban, disponibilita_denaro FROM progetto_db.conto_deposito WHERE mail = $1";
	$result= pg_prepare($db , "q", $sql);
	$value = array($username);
	$result= pg_execute($db, "q", $value);
	$s="";
	$i=1;
	while($row = pg_fetch_assoc($result)){
		$s.="<pre>Conto".$i.": 
			Iban: ".$row['iban']."
			Disponibilità: ".$row['disponibilita_denaro']."
		</pre>";
		$i++;
	}
	pg_free_result($result);
	pg_close($db);
	return $s;	
}

function print_conti_cred($username){
	$db = connection_pgsql();
	
	$sql= "SELECT iban, disponibilita_denaro,tetto_massimo, iban_deposito FROM progetto_db.conto_credito WHERE mail = $1";
	$result= pg_prepare($db , "q", $sql);
	$value = array($username);
	$result= pg_execute($db, "q", $value);
	$s="";
	$i=1;
	while($row = pg_fetch_assoc($result)){
		$s.="<pre>Conto".$i.": 
			Iban: ".$row['iban']."
			Disponibilità: ".$row['disponibilita_denaro']."
			Tetto: ".$row['tetto_massimo']."
			Iban conto deposito associato: ".$row['iban_deposito']."
		</pre>";
	}
	pg_free_result($result);
	pg_close($db);
	return $s;
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
