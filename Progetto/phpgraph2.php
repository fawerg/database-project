<?php
session_start();
include("phpgraphlib.php");
$graph2=new PHPGraphLib(800,600);

$graph2->addData($_SESSION['array']);
$p=0;
$p=$p-$_SESSION['disp'];
$graph2->setTitle("Saldo bilancio");
$graph2->setBars(false);
$graph2->setLine(true);
$graph2->setDataPoints(true);
$graph2->setDataPointColor('blue');
$graph2->setGrid(true);
$graph2->setGridColor('black');
$graph2->setDataValueColor('black');
$graph2->setXValuesHorizontal(true);
$graph2->createGraph();
?>
