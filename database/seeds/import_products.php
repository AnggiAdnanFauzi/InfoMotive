<?php
if (php_sapi_name() !== 'cli' && !defined('RUNNING_FROM_MIGRATION')) {
    http_response_code(403);
    die("Forbidden: Direct web access is strictly prohibited.");
}

require_once __DIR__ . '/../../config/database.php';

// We simulate scraping by "fetching" from a local array of Real World Data 
// gathered from Honda/Yamaha 2024 Price Lists.
// In a real scrape, we would use DOMDocument on a URL, but for stability, 
// we use this curated list of real parts.

$real_data = [
    // MOBIL (Honda)
    ['name' => 'Honda Automobile Oil - 0W-20 Turbo', 'price' => 140000, 'vehicle' => 'mobil', 'cat' => 'Oils', 'desc' => 'Oli mesin sintetik khusus untuk mesin turbo Honda. Menjaga performa mesin tetap optimal.'],
    ['name' => 'Honda Automobile Oil - 5W-30', 'price' => 105000, 'vehicle' => 'mobil', 'cat' => 'Oils', 'desc' => 'Oli mesin standar Honda untuk perlindungan maksimal sehari-hari.'],
    ['name' => 'Filter Udara Mobil Honda', 'price' => 118000, 'vehicle' => 'mobil', 'cat' => 'Engine', 'desc' => 'Saringan udara original untuk pembakaran sempurna dan hemat BBM.'],
    ['name' => 'Kampas Rem Depan Brio/Jazz', 'price' => 450000, 'vehicle' => 'mobil', 'cat' => 'Parts', 'desc' => 'Brake pad original, pakem dan awet, tidak merusak piringan cakram.'],
    ['name' => 'Filter Oli Jazz/City/Brio', 'price' => 45000, 'vehicle' => 'mobil', 'cat' => 'Engine', 'desc' => 'Penyaring kotoran oli untuk sirkulasi pelumasan yang lancar.'],

    // MOTOR (Honda/Yamaha Mix)
    ['name' => 'Belt Drive Kit Honda Genio', 'price' => 127000, 'vehicle' => 'motor', 'cat' => 'Parts', 'desc' => 'Paket van belt dan roller original Honda Genio.'],
    ['name' => 'Van Belt Kit Vario 150 eSP', 'price' => 185000, 'vehicle' => 'motor', 'cat' => 'Parts', 'desc' => 'V-Belt set original untuk Vario 150, anti slip dan tahan panas.'],
    ['name' => 'Kampas Rem Depan Vario/Beat', 'price' => 55000, 'vehicle' => 'motor', 'cat' => 'Parts', 'desc' => 'Kampas rem cakram depan standar Honda (Nissin).'],
    ['name' => 'Oli MPX 2 (Matic) 0.8L', 'price' => 45000, 'vehicle' => 'motor', 'cat' => 'Oils', 'desc' => 'Oli mesin khusus motor matic Honda, irit BBM dan mesin dingin.'],
    ['name' => 'Yamalube Sport Motor Oil', 'price' => 60000, 'vehicle' => 'motor', 'cat' => 'Oils', 'desc' => 'Oli mesin 4T untuk motor sport Yamaha.'],
    ['name' => 'Busi NGK MR9C-9N', 'price' => 25000, 'vehicle' => 'motor', 'cat' => 'Ignition', 'desc' => 'Busi standar Vario 125/150, percikan api stabil.'],
    ['name' => 'Aki GS Astra GTZ-5S', 'price' => 250000, 'vehicle' => 'motor', 'cat' => 'Electric', 'desc' => 'Aki kering bebas perawatan untuk Beat, Vario, Mio.'],
    ['name' => 'Ban Tubeless IRC 90/90-14', 'price' => 210000, 'vehicle' => 'motor', 'cat' => 'Tires', 'desc' => 'Ban belakang standar motor matic, awet dan grip mantap.'],
    ['name' => 'Ban Tubeless FDR 80/90-14', 'price' => 180000, 'vehicle' => 'motor', 'cat' => 'Tires', 'desc' => 'Ban depan standar matic, alur pembuangan air maksimal.']
];

echo "<h1>Importing Product Data...</h1>";

$count = 0;
foreach ($real_data as $item) {
    $name = $item['name'];
    $price = $item['price'];
    $vehicle = $item['vehicle'];
    $cat = $item['cat'];
    $desc = $item['desc'];

    // Generate Price Range (Simulate market variation +/- 15%)
    $price_min = $price;
    $price_max = $price + ($price * 0.15); // +15%
    
    // Default Image logic based on category
    // Using stunning real professional photos from Pexels CDN
    $image = 'https://images.pexels.com/photos/3807277/pexels-photo-3807277.jpeg?auto=compress&cs=tinysrgb&w=500'; // Default: Parts
    
    if ($cat == 'Oils') $image = 'https://images.pexels.com/photos/1031677/pexels-photo-1031677.jpeg?auto=compress&cs=tinysrgb&w=500';
    if ($cat == 'Tires') $image = 'https://images.pexels.com/photos/3752194/pexels-photo-3752194.jpeg?auto=compress&cs=tinysrgb&w=500';
    if ($cat == 'Electric') $image = 'https://images.pexels.com/photos/163140/battery-car-lead-acid-battery-acid-163140.jpeg?auto=compress&cs=tinysrgb&w=500';
    if ($cat == 'Engine') $image = 'https://images.pexels.com/photos/190539/pexels-photo-190539.jpeg?auto=compress&cs=tinysrgb&w=500';
    if ($cat == 'Ignition') $image = 'https://images.pexels.com/photos/13065691/pexels-photo-13065691.jpeg?auto=compress&cs=tinysrgb&w=500';

    // Check Duplicate
    $stmt = $pdo->prepare("SELECT id FROM products WHERE name = ?");
    $stmt->execute([$name]);

    if ($stmt->rowCount() == 0) {
        $sql = "INSERT INTO products (name, category, price_min, price_max, vehicle_type, description, image, created_at) 
                VALUES (?, ?, ?, ?, ?, ?, ?, NOW())";
        $insert = $pdo->prepare($sql);
        $insert->execute([$name, $cat, $price_min, $price_max, $vehicle, $desc, $image]);
        echo "Imported: <b>$name</b> ($vehicle - $cat)<br>";
        $count++;
    }
}

echo "<h3 style='color:green'>Success! Imported $count real products.</h3>";
echo "<p><a href='harga.php'>Check Price List</a></p>";
