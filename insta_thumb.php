<?php
// insta_thumb.php — Place this file on vrmstravels.com
// Usage: insta_thumb.php?code=DUubo6aE0UI
// Returns JSON: {"thumb":"https://..."}

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Cache-Control: public, max-age=3600'); // cache 1 hour

$code = preg_replace('/[^A-Za-z0-9_-]/', '', $_GET['code'] ?? '');
if (!$code) { echo json_encode(['error'=>'no code']); exit; }

$url = "https://www.instagram.com/reel/{$code}/";
$oembed = "https://www.instagram.com/oembed/?url=" . urlencode($url) . "&maxwidth=600";

$ch = curl_init();
curl_setopt_array($ch, [
    CURLOPT_URL => $oembed,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_TIMEOUT => 8,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_USERAGENT => 'Mozilla/5.0 (compatible; vrmstravels.com)',
    CURLOPT_HTTPHEADER => ['Accept: application/json'],
]);
$body = curl_exec($ch);
$err  = curl_error($ch);
curl_close($ch);

if ($err || !$body) {
    echo json_encode(['error' => $err ?: 'empty response']);
    exit;
}

$data = json_decode($body, true);
$thumb = $data['thumbnail_url'] ?? '';

echo json_encode(['thumb' => $thumb, 'title' => $data['title'] ?? '']);
