<?php
$amount = '1'; // Amount to transact
$phonenumber = '0768540720'; // Phone number paying
$Account_no = 'UMESKIA SOFTWARES'; // Enter account number (optional)
$url = 'https://tinypesa.com/api/v1/express/initialize';
$data = array(
    'amount' => $amount,
    'msisdn' => $phonenumber,
    'account_no' => $Account_no
);
$headers = array(
    'Content-Type: application/x-www-form-urlencoded',
    'ApiKey: ccA0FnR21koNTA30gLC-nq_fYN1AuO4db-jrUQ7-LojNhkGmfH' // Replace with your API key
);
$info = http_build_query($data);
$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, $url);
curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl, CURLOPT_POSTFIELDS, $info);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
$resp = curl_exec($curl);

if ($resp === false) {
    $error = curl_error($curl);
    curl_close($curl);
    die('Curl error: ' . $error);
}

curl_close($curl);
echo 'Response: ' . $resp;
