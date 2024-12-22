<?php
header("Content-Type: application/json");

$stkCallbackResponse = file_get_contents('php://input');
$logFile = "stkTinypesaResponse.json";
$log = fopen($logFile, "a");
fwrite($log, $stkCallbackResponse . PHP_EOL);
fclose($log);

$callbackContent = json_decode($stkCallbackResponse);

if (isset($callbackContent->Body->stkCallback->ResultCode)) {
    $ResultCode = $callbackContent->Body->stkCallback->ResultCode;
    $CheckoutRequestID = $callbackContent->Body->stkCallback->CheckoutRequestID;
    $Amount = $callbackContent->Body->stkCallback->CallbackMetadata->Item[0]->Value;
    $MpesaReceiptNumber = $callbackContent->Body->stkCallback->CallbackMetadata->Item[1]->Value;
    $PhoneNumber = $callbackContent->Body->stkCallback->CallbackMetadata->Item[4]->Value;

    if ($ResultCode == 0) {
        $servername = "host";
        $username = "host_username";
        $password = "host_password";
        $dbname = "database_name";

        // Create connection
        $conn = new mysqli($servername, $username, $password, $dbname);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $stmt = $conn->prepare("INSERT INTO payments (CheckoutRequestID, Amount, MpesaReceiptNumber, PhoneNumber) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("sdss", $CheckoutRequestID, $Amount, $MpesaReceiptNumber, $PhoneNumber);

        if ($stmt->execute()) {
            echo json_encode(["ResultCode" => 0, "ResultDesc" => "Success"]);
        } else {
            echo json_encode(["ResultCode" => 1, "ResultDesc" => "Database error"]);
        }

        $stmt->close();
        $conn->close();
    } else {
        echo json_encode(["ResultCode" => 1, "ResultDesc" => "Transaction failed"]);
    }
} else {
    echo json_encode(["ResultCode" => 1, "ResultDesc" => "Invalid callback data"]);
}
?>
