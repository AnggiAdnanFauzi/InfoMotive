<?php
if (php_sapi_name() !== 'cli' && !defined('RUNNING_FROM_MIGRATION')) {
    http_response_code(403);
    die("Forbidden: Direct web access is strictly prohibited.");
}

require_once __DIR__ . '/../../config/database.php';

// Source configurations
$sources = [
    [
        'url' => 'https://www.antaranews.com/rss/otomotif.xml',
        'category' => 'News',
        'selectors' => ['//div[contains(@class, "post-content")]', '//div[contains(@class, "text-body")]', '//article']
    ],
    [
        'url' => 'https://rss.gridoto.com/feed/social?apikey=06824dda62f8cb1c4e68f2cf5f6c17f9932b81d9', // GridOto
        'category' => 'Tips & Trik', // Default, will refine below
        'selectors' => ['//div[contains(@class, "read__content")]', '//div[contains(@class, "article__content")]', '//div[contains(@id, "article-content")]']
    ]
];

// HELPER: Fetch Article Body
function fetchAndParseArticle($url, $selectors) {
    if(!filter_var($url, FILTER_VALIDATE_URL)) return false;
    
    // User Agent to avoid 403
    $context = stream_context_create([
        'http' => [
            'header' => "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36\r\n"
        ]
    ]);

    $html = @file_get_contents($url, false, $context);
    if (!$html) return false;

    $dom = new DOMDocument();
    @$dom->loadHTML($html, LIBXML_NOERROR);
    $xpath = new DOMXPath($dom);

    // Try specific selectors for this source
    foreach($selectors as $query) {
        $nodes = $xpath->query($query);
        if ($nodes->length > 0) {
            $content = '';
            foreach ($nodes->item(0)->childNodes as $child) {
                // Remove garbage
                if (in_array($child->nodeName, ['script', 'style', 'iframe', 'div'])) continue; 
                // GridOto often has 'baca juga' divs inside content, skip them
                
                $content .= $dom->saveHTML($child);
            }
            return cleanContent($content); // Basic cleaning
        }
    }
    
    return false;
}

function cleanContent($html) {
    // Remove empty tags or ads
    $html = preg_replace('/<div class="ads-.*?>.*?<\/div>/', '', $html);
    return $html;
}

echo "<h1>Importing Full Articles from Multiple Sources...</h1>";
echo "<p>Please wait...</p>";

try {
    $total_imported = 0;

    foreach ($sources as $source) {
        echo "<h3>Processing Source: " . htmlspecialchars($source['url']) . "</h3>";
        
        $rss_content = @file_get_contents($source['url']);
        if (!$rss_content) { echo "Failed to fetch feed.<br>"; continue; }
        
        $rss = simplexml_load_string($rss_content);
        if ($rss === false) { echo "Invalid XML.<br>"; continue; }

        if (!isset($rss->channel) || !isset($rss->channel->item)) {
            echo "Skipping source (No content found or incompatible format).<br>";
            continue;
        }

        foreach ($rss->channel->item as $item) {
            $title = (string)$item->title;
            $link = (string)$item->link;
            $description = (string)$item->description;
            
            // Image Extraction
            $image = 'https://placehold.co/600x400?text=News'; // Default
            if (isset($item->enclosure['url'])) {
                 $image = (string)$item->enclosure['url'];
            } elseif (preg_match('/src="([^"]+)"/', $description, $match)) {
                $image = $match[1];
            }

            // Keyword-based Category Refinement
            $cat = $source['category'];
            $lc_title = strtolower($title);
            if (strpos($lc_title, 'tips') !== false || strpos($lc_title, 'cara') !== false) {
                $cat = 'Tips & Trik';
            } elseif (strpos($lc_title, 'rawat') !== false || strpos($lc_title, 'oli') !== false || strpos($lc_title, 'servis') !== false) {
                $cat = 'Maintenance';
            } elseif (strpos($lc_title, 'aman') !== false || strpos($lc_title, 'safety') !== false || strpos($lc_title, 'bahaya') !== false) {
                $cat = 'Safety';
            }

            // Vehicle Type Detection
            $vehicle_type = 'umum';
            if (strpos($lc_title, 'mobil') !== false || strpos($lc_title, 'toyota') !== false || strpos($lc_title, 'honda') !== false && strpos($lc_title, 'civic') !== false) {
                 $vehicle_type = 'mobil';
            } elseif (strpos($lc_title, 'motor') !== false || strpos($lc_title, 'yamaha') !== false || strpos($lc_title, 'kawasaki') !== false) {
                 $vehicle_type = 'motor';
            }

            // Check Duplicates
            $check = $pdo->prepare("SELECT id FROM articles WHERE title = ?");
            $check->execute([$title]);
            
            if ($check->rowCount() == 0) {
                // SCRAPE FULL CONTENT
                $full_content = fetchAndParseArticle($link, $source['selectors']);
                
                if (!$full_content || strlen($full_content) < 50) {
                    $full_content = $description . "<br><br><a href='$link' target='_blank'>Baca selengkapnya di sumber asli</a>";
                } else {
                     $full_content .= "<br><br><small>Sumber: <a href='$link' target='_blank'>Original Post</a></small>";
                }

                $stmt = $pdo->prepare("INSERT INTO articles (title, category, content, image, video_url, vehicle_type, created_at) VALUES (?, ?, ?, ?, NULL, ?, NOW())");
                $stmt->execute([$title, $cat, $full_content, $image, $vehicle_type]);
                $total_imported++;
                echo "Imported ($cat - $vehicle_type): <b>$title</b><br>";
                flush();
            }
        }
    }
    
    echo "<h2 style='color:green'>Done! Total Imported: $total_imported</h2>";
    echo "<a href='index.php'>Go to Home</a>";

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
