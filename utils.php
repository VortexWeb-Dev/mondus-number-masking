<?php
require(__DIR__ . "/crest/crest.php");

date_default_timezone_set('Asia/Dubai');

function getLead($leadId)
{
    $response = CRest::call("crm.lead.get", ["ID" => $leadId]);
    return $response["result"];
}

function logData($logfile, $data)
{
    date_default_timezone_set('Asia/Kolkata');

    $logFile = __DIR__ . '/logs/' . $logfile;
    file_put_contents($logFile, date('Y-m-d H:i:s') . " - $data\n", FILE_APPEND);
}

function updateLead($leadId, $leadData)
{
    $response = CRest::call('crm.lead.update', [
        'ID' => $leadId,
        'fields' => $leadData
    ]);

    return $response['result'];
}

function maskNumber($phone_number)
{
    if (!$phone_number || strlen($phone_number) < 4) {
        return "Invalid number";
    }

    return str_repeat('x', strlen($phone_number) - 4) . substr($phone_number, -4);
}
