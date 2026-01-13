<?php
require_once '../vendor/autoload.php';

use Twilio\Rest\Client;

function sendMessage($to, $msg)
{
    $sid = "YOUR_TWILIO_SID";
    $token = "YOUR_TWILIO_TOKEN";
    $from = "whatsapp:+14155238886"; // Twilio sandbox number

    $client = new Client($sid, $token);
    $client->messages->create(
        "whatsapp:$to",
        ["from" => $from, "body" => $msg]
    );
}
