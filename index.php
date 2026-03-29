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
            ST_X(ST_GeomFromText(ST_AsText(ST_Centroid(geometry)),4326)) AS lon,
            ST_Y(ST_GeomFromText(ST_AsText(ST_Centroid(geometry)),4326)) AS lat
        FROM communes
        WHERE nom LIKE ?
        ORDER BY nom
        LIMIT 5000
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