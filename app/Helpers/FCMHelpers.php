<?php

namespace App\Helpers;

class FCMHelpers
{
    public static function send($fcmToken, $title, $body, $clickAction = '/')
    {
        $serverKey = env('FCM_SERVER_KEY'); // Simpan di .env

        $data = [
            "to" => $fcmToken,
            "notification" => [
                "title" => $title,
                "body" => $body,
                "click_action" => $clickAction,
                "sound" => "default"
            ]
        ];

        $dataString = json_encode($data);

        $headers = [
            'Authorization: key=' . $serverKey,
            'Content-Type: application/json',
        ];

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);

        $response = curl_exec($ch);

        if ($response === false) {
            throw new \Exception('FCM Send Error: ' . curl_error($ch));
        }

        curl_close($ch);

        return $response;
    }
}
