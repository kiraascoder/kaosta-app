<?php

namespace App\Helpers;

use Google\Client;
use Illuminate\Support\Facades\Http;
use Exception;

class FCMHelperV1
{
    /**
     * Mengirim notifikasi push ke perangkat menggunakan FCM.
     *
     * @param string $fcmToken Token perangkat FCM.
     * @param string $title Judul notifikasi.
     * @param string $body Isi notifikasi.
     * @param string|null $clickAction URL yang akan dibuka saat notifikasi diklik.
     * @param string|null $image URL gambar untuk notifikasi (opsional).
     * @return array|mixed Respon dari FCM API atau array error.
     */
    public static function send(string $fcmToken, string $title, string $body, ?string $clickAction = null, ?string $image = null)
    {
        try {
            // Pastikan file kredensial ada
            $credentialsPath = storage_path('app/firebase/kaosta-472-service-account.json');
            if (!file_exists($credentialsPath)) {
                throw new Exception("File kredensial Firebase tidak ditemukan di: {$credentialsPath}");
            }

            // Inisialisasi Google Client untuk otentikasi
            $client = new Client();
            $client->setAuthConfig($credentialsPath);
            $client->addScope('https://www.googleapis.com/auth/firebase.messaging');
            $accessToken = $client->fetchAccessTokenWithAssertion()['access_token'];

            // Ambil Project ID dari file kredensial
            $projectId = json_decode(file_get_contents($credentialsPath))->project_id;
            $url = "https://fcm.googleapis.com/v1/projects/{$projectId}/messages:send";

            // Bangun payload notifikasi
            $payload = [
                'message' => [
                    'token' => $fcmToken,
                    'notification' => [
                        'title' => $title,
                        'body' => $body,
                        'image' => $image, // Tambahkan gambar jika ada
                    ],
                    'webpush' => [
                        'fcm_options' => [
                            'link' => $clickAction ?? url('/'),
                        ]
                    ]
                ]
            ];

            // Kirim permintaan HTTP POST ke FCM API
            $response = Http::withToken($accessToken)
                ->post($url, $payload);

            // Periksa apakah permintaan berhasil
            if ($response->successful()) {
                return $response->json();
            }

            // Jika gagal, lemparkan Exception dengan detail error
            throw new Exception("Gagal mengirim notifikasi. Respon FCM: " . $response->body());
        } catch (Exception $e) {
            // Tangani error dan log pesan
            \Log::error("FCM Send Error: " . $e->getMessage());
            return ['error' => $e->getMessage()];
        }
    }
}
