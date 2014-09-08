<?php
session_start();
include("phpgraphlib.php");
$graph = new PHPGraphLib(900,700);

$graph->addData($_SESSION['array']);

$graph->setTitle("Saldo contabile");
$graph->setBars(false);
$graph->setLine(true);
$graph->setDataPoints(true);
$graph->setDataPointColor('blue');
$graph->setGrid(true);
$graph->setGridColor('black');
$graph->setDataValueColor('black');
$graph->setXValuesHorizontal(false);
$graph->setGoalLine(0);
$graph->setGoalLineColor('red');
$graph->createGraph();
?>
