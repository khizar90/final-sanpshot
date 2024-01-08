<?php

namespace App\Actions\Push;


class FirebaseNotification
{
    public static function handle($tokens, $title, $body, $arr)
    {

        $fcmUrl = 'https://fcm.googleapis.com/fcm/send';

        $notification = [
            'title' => $title,
            'body' => $body,
        ];

        $extraNotificationData = $arr;

        $fcmNotification = [
            'registration_ids'        => $tokens, //single token
            'notification' => $notification,
            'data' => $extraNotificationData
        ];

        $headers = [
            'Authorization: key= AAAAc-yBjIg:APA91bFozvAjyZ9TA9kryJT4E7tZZVNvjqoH9b3dE3AMYenYI7jjeYCwqoSANWOSledym9B9qqIeIImVHw2Vl9JXD2jFnAyRtKAPYesl8chAH8Ob2gxNiIWZuNKYVP8JbNZub4o1D2wf',
            'Content-Type: application/json'
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $fcmUrl);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fcmNotification));
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }
}
