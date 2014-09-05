<?php
session_start();
include("phpgraphlib.php");
$graph = new PHPGraphLib(800,600);

$graph->addData($_SESSION['array']);

$graph->setTitle("Saldo contabile");
$graph->setBars(false);
$graph->setLine(true);
$graph->setDataPoints(true);
$graph->setDataPointColor('blue');
$graph->setGrid(true);
$graph->setGridColor('black');
$graph->setDataValueColor('black');
$graph->setXValuesHorizontal(true);
$graph->setGoalLine(0);
$graph->setGoalLineColor('red');
$graph->createGraph();
?>
