<?php
session_start();
include('phpgraphlib.php');
include('phpgraphlib_pie.php');
$graph=new PHPGraphLibPie(600, 300);
$graph->addData($_SESSION['array']);
$graph->setTitle('Percentuali spese');
$graph->setTitleLocation('left');
$graph->createGraph();
?>
