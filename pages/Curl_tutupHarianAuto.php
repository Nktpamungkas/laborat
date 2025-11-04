<?php
// URL target file PHP yang ingin dijalankan (yang kamu kasih di atas)
$url = "http://10.0.5.29/laborat1/pages/TutupHarianAuto.php";

// Inisialisasi cURL
$ch = curl_init($url);

// Set opsi dasar cURL
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 600);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); 

// Jika perlu kirim data POST (opsional)
$data = [
    'trigger' => 'auto_run',
];
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));

// Eksekusi dan ambil hasilnya
$response = curl_exec($ch);

// Cek error
if (curl_errno($ch)) {
    echo "Curl error: " . curl_error($ch);
} else {
    echo "Response dari server:\n";
    echo $response;
}

// Tutup koneksi
curl_close($ch);
?>