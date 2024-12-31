<?php
if (isset($_POST['league_id'])) {
    $league_id = $_POST['league_id'];
    $server="localhost";
    $username="root";
    $password="";
    $dbname="ecommerce_db";
    $con = new PDO("mysql:host=$server;dbname=$dbname", $username, $password);

    $query = "SELECT * FROM teams WHERE league_id = :league_id";
    $stmt = $con->prepare($query);
    $stmt->bindParam(':league_id', $league_id);
    $stmt->execute();
    $teams = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Send JSON response
    header('Content-Type: application/json');
    echo json_encode($teams);
}
?>