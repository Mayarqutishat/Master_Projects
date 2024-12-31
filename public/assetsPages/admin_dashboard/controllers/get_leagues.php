<?php
$server="localhost";
$username="root";
$password="";
$dbname="ecommerce_db";
$con = new PDO("mysql:host=$server;dbname=$dbname", $username, $password);

$query = "SELECT * FROM leagues";
$stmt = $con->prepare($query);
$stmt->execute();
$leagues = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Send JSON response
header('Content-Type: application/json');
echo json_encode($leagues);
?>