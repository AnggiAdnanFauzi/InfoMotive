<?php
require_once '../config/database.php';
require_once '../config/ai_config.php';

header('Content-Type: application/json');

// Get POST data
$data = json_decode(file_get_contents('php://input'), true);
$userMessage = $data['message'] ?? '';

if (empty($userMessage)) {
    echo json_encode(['error' => 'No message provided']);
    exit;
}

$cleanMessage = strtolower(trim($userMessage));

// 1. SMART INTENT & QUERY EXPANSION (RAG ADVANCED)
$articles = [];
$products = [];
$workshop = null;
$detectedIntent = 'general'; // default intent

// A. Intent: Termurah / Murah / Harga / Sparepart / Sepertpart / Barang
if (preg_match('/(termurah|murah|harga|sparepart|sepertpart|seperpat|barang|beli|katalog|onderdil|part|komponen|diskon|murmer)/i', $cleanMessage)) {
    $detectedIntent = 'sparepart_murah';
    // Ambil produk dengan harga termurah
    $stmt = $pdo->query("SELECT id, name, price_min, image FROM products ORDER BY price_min ASC LIMIT 3");
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
} 
// B. Intent: Termahal / Mahal / Premium
elseif (preg_match('/(termahal|mahal|tinggi|premium|mewah)/i', $cleanMessage)) {
    $detectedIntent = 'sparepart_mahal';
    $stmt = $pdo->query("SELECT id, name, price_min, image FROM products ORDER BY price_min DESC LIMIT 3");
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
// C. Intent: Keyword matching untuk spesifik nama part (oli, ban, aki, busi, kampas, dll)
else {
    $stopWords = ['apa', 'yang', 'dan', 'itu', 'ini', 'dari', 'pada', 'untuk', 'dengan', 'di', 'ke', 'bagaimana', 'siapa', 'mengapa', 'buat', 'cara', 'hal', 'ada', 'bisa', 'mau', 'tahu', 'kasih', 'mana', 'enak', 'makanan', 'minuman', 'resep', 'bikin', 'kue', 'politik', 'game', 'film'];
    $words = explode(' ', preg_replace('/[^\w\s]/', '', $cleanMessage));
    $keywords = array_filter($words, function($w) use ($stopWords) {
        return strlen($w) >= 3 && !in_array($w, $stopWords);
    });

    if (!empty($keywords)) {
        $sql = "SELECT id, name, price_min, image FROM products WHERE ";
        $conditions = [];
        $params = [];
        foreach ($keywords as $word) {
            $conditions[] = "name LIKE ?";
            $params[] = "%$word%";
        }
        $sql .= implode(' OR ', $conditions) . " LIMIT 2";
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if (!empty($products)) $detectedIntent = 'sparepart_specific';
    }
}

// D. Intent: Edukasi / Tips / Cara / Perawatan / BBM / Mogok
if (preg_match('/(edukasi|tips|cara|rawat|perawatan|ilmu|belajar|baca|artikel|hemat|bbm|solusi|mesin bunyi|berisik|getar|panas|overheat)/i', $cleanMessage)) {
    if ($detectedIntent === 'general') $detectedIntent = 'edukasi';
    $stmt = $pdo->query("SELECT id, title, image, created_at FROM articles ORDER BY created_at DESC LIMIT 1");
    $articles = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// E. Intent: Bengkel / Servis / Lokasi / Dimana / Darurat
if (preg_match('/(bengkel|servis|service|lokasi|tempat|mogok|rusak|darurat|repair|benerin|ganti|pasang|bantuan|dekat|terdekat|dimana|alamat|bantu)/i', $cleanMessage)) {
    if ($detectedIntent === 'general') $detectedIntent = 'bengkel';
    $stmt = $pdo->query("SELECT id, name, address, lat, lng FROM workshops LIMIT 1");
    $workshop = $stmt->fetch(PDO::FETCH_ASSOC);
}

// F. Deteksi Topik Luar Konteks (Non-Automotive)
$isOutOfContext = false;
if (preg_match('/(makanan|minuman|enak|resep|kue|masak|gorengan|bakso|mie|politik|presiden|gubernur|partai|pemilu|game|film|bioskop|anime|drakor|selebriti|gosip|bola|badminton|cuaca|hujan|banjir|gempa|crypto|saham|judi|slot|togel|skincare|makeup)/i', $cleanMessage)) {
    $isOutOfContext = true;
    $detectedIntent = 'out_of_context';
}


// 2. GENERATE AI EXPLANATION & SMART FALLBACK ENGINE
$explanation = "";

if (defined('AI_API_KEY') && !empty(AI_API_KEY)) {
    $context = "User message: \"$userMessage\".\nDetected Intent: $detectedIntent.\n";
    if (!empty($articles)) $context .= "Database Article Available: " . $articles[0]['title'] . "\n";
    if (!empty($products)) {
        $context .= "Database Products Available:\n";
        foreach ($products as $p) {
            $context .= "- " . $p['name'] . " (Rp " . number_format($p['price_min'], 0, ',', '.') . ")\n";
        }
    }
    if (!empty($workshop)) $context .= "Database Workshop Available: " . $workshop['name'] . " (" . $workshop['address'] . ")\n";
    
    $prompt = "Kamu adalah BotMotif, asisten virtual pintar resmi dari InfoMotive (platform layanan otomotif digital, transparansi harga sparepart, edukasi perawatan kendaraan, dan direktori bengkel terpercaya di Indonesia).

Pesan Pengguna: \"$userMessage\"

Konteks Data InfoMotive terdeteksi dari Database:
$context

ATURAN KETAT (WAJIB DIPATUHI):
1. TUGAS UTAMA & RUANG LINGKUP: Kamu BEBAS dan WAJIB menjawab pertanyaan APAPUN yang berkaitan dengan InfoMotive, dunia otomotif, kendaraan (mobil/motor), cara kerja mesin, tips perawatan, estimasi biaya servis, perbandingan sparepart, barang termurah/termahal, keselamatan berkendara, fitur InfoMotive, atau sapaan umum (Halo, Hai, selamat pagi, siapa kamu, dll). Berikan penjelasan yang cerdas, ramah, dan antusias.
2. JIKA USER BERTANYA TENTANG BARANG TERMURAH/SPAREPART: Wajib sebutkan barang-barang yang ada di 'Konteks Data InfoMotive' di atas beserta harganya!
3. LUAR KONTEKS (OUT OF CONTEXT): Jika Pengguna menanyakan hal yang SAMA SEKALI TIDAK BERKAITAN dengan InfoMotive atau dunia otomotif (misal: urusan politik, resep masakan, makanan, minuman, game, film, gosip selebriti, dll), KAMU WAJIB MENOLAK SECARA HALUS. Awali penolakanmu dengan kalimat: \"Maaf, pertanyaan tersebut di luar cakupan InfoMotive.\" lalu jelaskan dengan ramah bahwa kamu adalah BotMotif yang hanya diprogram untuk melayani informasi seputar dunia otomotif, perawatan kendaraan, harga sparepart, dan direktori bengkel.
4. GAYA BAHASA: Jawab dalam BAHASA INDONESIA yang profesional, ramah, antusias, dan informatif. Gunakan emoji yang sesuai (seperti 🚗, 🔧, 💡, 🛠️).
5. Jangan cantumkan URL mentah di dalam pesanmu.";

    $request = [
        "contents" => [[
            "parts" => [["text" => $prompt]]
        ]]
    ];
    
    // Model Fallback Chain to prevent "High Demand" / Quota errors
    $models = ['gemini-1.5-flash', 'gemini-1.5-flash-8b', 'gemini-1.5-pro', 'gemini-2.5-flash'];
    $success = false;
    
    foreach ($models as $model) {
        $url = "https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key=" . AI_API_KEY;
        
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($request));
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        
        $response = curl_exec($ch);
        $curlError = curl_error($ch);
        curl_close($ch);
        
        if (!$curlError) {
            $json = json_decode($response, true);
            if (isset($json['candidates'][0]['content']['parts'][0]['text'])) {
                $explanation = $json['candidates'][0]['content']['parts'][0]['text'];
                $success = true;
                break; // Successfully got response
            }
        }
    }
    
    if ($success) {
        // Check if AI deemed it out of context
        if (stripos($explanation, 'di luar cakupan InfoMotive') !== false || stripos($explanation, 'luar cakupan') !== false || stripos($explanation, 'tidak berkaitan') !== false) {
            $articles = [];
            $products = [];
            $workshop = null;
        }
    } else {
        // AI API Failed (Quota Exceeded / High Demand) -> SMART LOCAL FALLBACK ENGINE
        if ($isOutOfContext) {
            $explanation = "Maaf, pertanyaan tersebut di luar cakupan InfoMotive. Saya adalah BotMotif yang hanya diprogram untuk melayani informasi seputar dunia otomotif, perawatan kendaraan, harga sparepart, dan direktori bengkel. 🚗🔧";
            $articles = [];
            $products = [];
            $workshop = null;
        } elseif ($detectedIntent === 'sparepart_murah') {
            $explanation = "Halo! Berikut adalah daftar sparepart dan oli dengan harga paling terjangkau dari katalog InfoMotive. Silakan klik kartu produk di bawah ini untuk melihat detailnya! 🛒🔧";
        } elseif ($detectedIntent === 'sparepart_mahal') {
            $explanation = "Halo! Berikut adalah jajaran suku cadang premium berkualitas tinggi dari InfoMotive. Silakan cek pilihan produk di bawah ini! 🛒✨";
        } elseif ($detectedIntent === 'sparepart_specific') {
            $explanation = "Halo! Kami menemukan suku cadang yang cocok dengan pencarian Anda di database InfoMotive. Silakan cek kartu produk di bawah ini! 🚗🛠️";
        } elseif ($detectedIntent === 'edukasi') {
            $explanation = "Halo! Terkait pertanyaan perawatan dan kendala kendaraan Anda, tim InfoMotive memiliki panduan edukasi yang tepat. Selamat membaca tautan di bawah ini! 📚🚗";
        } elseif ($detectedIntent === 'bengkel') {
            $explanation = "Halo! Untuk menangani kendala servis atau kebutuhan darurat kendaraan Anda, kami merekomendasikan bengkel mitra terdekat berikut. 📍🛠️";
        } else {
            // General greeting or other automotive/InfoMotive topic
            $explanation = "Halo! Selamat datang di InfoMotive. Saya BotMotif, asisten pintar Anda. Silakan tanyakan apa saja seputar harga sparepart, tips perawatan kendaraan, atau informasi bengkel terdekat, saya siap membantu! 🚗🔧";
            // Jika tidak ada produk/bengkel terdeteksi, berikan produk rekomendasi default
            if (empty($products) && empty($articles) && empty($workshop)) {
                $stmt = $pdo->query("SELECT id, name, price_min, image FROM products LIMIT 2");
                $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
            }
        }
    }
} else {
    // Fallback Manual Logic if No API Key
    $explanation = "Halo! Selamat datang di InfoMotive. Saya BotMotif, asisten pintar Anda. Silakan tanyakan seputar harga sparepart, tips perawatan kendaraan, atau informasi bengkel terdekat! 🚗🔧";
}

// 3. Return Structure
echo json_encode([
    'explanation' => $explanation,
    'article' => $articles[0] ?? null,
    'products' => $products,
    'workshop' => $workshop
]);
?>
