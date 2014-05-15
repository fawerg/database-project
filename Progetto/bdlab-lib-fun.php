<?php
include_once('conf.php');

$users=array(
	'Marco' => 'lollipop',
    'steve' => '123',
    'john' => 'abc');

$category=array(
	'Marco' => 'admin',
    'steve' => 'admin',
    'john' => 'user');
  
function user_check($name,$pass) {
//verifica la correttezza di login e password inserite e imposta il contenuto della sessione

global $users;

    if ($users[$name] && $users[$name] == $pass){
        $_SESSION['isLogged'] = $name;
        return(true);
    }
    else
        return(false);

}
  
function user_category($name)  {
//restituisce la categoria dell'utente specificato in input
    
global $category;

    return $category[$name]; 

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
