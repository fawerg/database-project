<?php
include_once('conf.php');
  
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
