<?php
session_start();
include("phpgraphlib.php");
$graph3=new PHPGraphLib(800,600);

$graph3->addData($_SESSION['array'],$_SESSION['array1']);
$p=0;
$p=$p-$_SESSION['disp'];
$graph3->setTitle("Saldo bilanci");
$graph3->setBars(false);
$graph3->setLine(true);
$graph3->setLineColor('red','blue');
$graph3->setDataPoints(true);
$graph3->setDataPointColor('blue', 'green');
$graph3->setGrid(true);
$graph3->setGridColor('black');
$graph3->setDataValueColor('black');
$graph3->setXValuesHorizontal(true);
$graph3->createGraph();
?>
