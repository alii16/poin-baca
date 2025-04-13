<?php
session_start();
include "../config/db.php";

/**
 * Mengambil kategori berita favorit user dari database.
 *
 * @param mysqli $conn
 * @param int $user_id
 * @return array
 */
function getUserFavoriteCategories($conn, $user_id) {
    $stmt = $conn->prepare("SELECT categories.name FROM user_preferences JOIN categories ON user_preferences.category_id = categories.id WHERE user_preferences.user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $favCategories = [];
    while ($row = $result->fetch_assoc()) {
        $favCategories[] = $row['name'];
    }
    $stmt->close();

    return $favCategories;
}

/**
 * Menentukan kategori berdasarkan sumber berita yang dipilih.
 *
 * @param string $sumber
 * @return string
 */
function getDefaultCategoryBySource($sumber) {
    $categoryMap = [
        "republika" => "internasional",
        "antara" => "hiburan",
        "cnn" => "nasional",
    ];
    return $categoryMap[$sumber] ?? "terbaru";
}

/**
 * Mengambil berita berdasarkan status login user, sumber berita, atau pencarian.
 *
 * @param mysqli $conn
 * @return array
 */
function getNews($conn) {
    // Cek apakah ada parameter pencarian
    if (isset($_GET['q']) && !empty($_GET['q'])) {
        return searchNews($_GET['q']);
    }

    if (isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];
        $favCategories = getUserFavoriteCategories($conn, $user_id);
    
        if (!empty($favCategories)) {
            $kategori = $favCategories[0];
            $sumber = $_GET['sumber'] ?? "cnn"; // Tetapkan sumber jika tidak ada dalam session
        }
    }
    
    if (!isset($kategori)) {
        $sumber = $_GET['sumber'] ?? "cnn";
        $kategori = getDefaultCategoryBySource($sumber);
    }

    $news_api_url = "https://api-berita-indonesia.vercel.app/" . $sumber . "/" . $kategori;

    // Ambil data dari API
    $news_json = @file_get_contents($news_api_url);
    if (!$news_json) {
        return ["error" => "Gagal mengambil data berita"];
    }

    $news_data = json_decode($news_json, true);
    $articles = [];

    if (isset($news_data['data']['posts'])) {
        foreach ($news_data['data']['posts'] as $post) {
            $articles[] = [
                "title" => $post['title'],
                "description" => "",
                "url" => $post['link'],
                "urlToImage" => !empty($post['thumbnail']) ? $post['thumbnail'] : "https://via.placeholder.com/300",
                "publishedAt" => $post['pubDate'],
            ];
        }
    }

    return ["articles" => $articles];
}

/**
 * Mencari berita berdasarkan kata kunci.
 *
 * @param string $query
 * @return array
 */
function searchNews($query) {
    if (empty($query)) {
        return ["error" => "Masukkan kata kunci pencarian."];
    }

    $apiKey = "Your_API_Key"; // Ganti dengan API Key NewsAPI
    // Mengambil berita dalam bahasa Indonesia, diurutkan berdasarkan terbit secara descending
    $search_api_url = "https://newsapi.org/v2/everything?q=" . urlencode($query) . "&language=id&sortBy=publishedAt&apiKey=" . $apiKey;

    // Ambil data dari API
    $search_json = @file_get_contents($search_api_url);

    if (!$search_json) {
        return ["error" => "Gagal mengambil data dari NewsAPI."];
    }

    $search_data = json_decode($search_json, true);
    if (!isset($search_data['articles']) || !is_array($search_data['articles'])) {
        return ["error" => "Format data API tidak valid."];
    }

    if (empty($search_data['articles'])) {
        return ["error" => "Tidak ada berita relevan ditemukan."];
    }

    $articles = [];
    foreach ($search_data['articles'] as $article) {
        $articles[] = [
            "title" => $article['title'] ?? "Judul tidak tersedia",
            "description" => $article['description'] ?? "Deskripsi tidak tersedia",
            "url" => $article['url'] ?? "#",
            "urlToImage" => !empty($article['urlToImage']) ? $article['urlToImage'] : "https://via.placeholder.com/300",
            "publishedAt" => $article['publishedAt'] ?? date("Y-m-d H:i:s"),
        ];
    }

    return ["articles" => $articles];
}

/**
 * Mengambil berita berdasarkan status login user, sumber berita, atau pencarian.
 *
 * @param mysqli $conn
 * @return array
 */

 header("Content-Type: application/json");
echo json_encode(getNews($conn));
?>
