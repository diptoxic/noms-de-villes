<?php
require 'vendor/autoload.php';

// Connexion MySQL à u2.ensg.eu
$db = new mysqli('localhost', 'root', '', 'geobase');
if ($db->connect_error) {
    die("Erreur connexion : " . $db->connect_error);
}
Flight::set('db', $db);

// Route principale
Flight::route('/', function() {
    readfile('public/index.html');
});

// Route API
Flight::route('/api/villes', function() {
    $db = Flight::get('db');
    
    $type = $_GET['type'] ?? '';
    $q = $_GET['q'] ?? '';
    
    if ($type === 'starts') $pattern = $q . '%';
    elseif ($type === 'ends') $pattern = '%' . $q;
    else $pattern = '%' . $q . '%';
    
    $stmt = $db->prepare("
        SELECT nom,
            ST_X(ST_Centroid(geometry)) AS lon,
            ST_Y(ST_Centroid(geometry)) AS lat
        FROM communes
        WHERE nom LIKE ?
        LIMIT 500
    ");
    $stmt->bind_param('s', $pattern);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $villes = [];
    while ($row = $result->fetch_assoc()) {
        $villes[] = [
            'nom' => $row['nom'],
            'lon' => (float)$row['lon'],
            'lat' => (float)$row['lat']
        ];
    }
    
    Flight::json($villes);
});

Flight::start();