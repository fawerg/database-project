<?php
session_start();
include("phpgraphlib.php");
$graph = new PHPGraphLib(400,300);

$graph->addData($_SESSION['ciao']);

$graph->setTitle("Test Scores");
$graph->setTextColor("blue");
$graph->createGraph();
?>
