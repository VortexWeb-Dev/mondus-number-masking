<?php
require(__DIR__ . "/utils.php");


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = $_POST;

    logData('event.log', "Received POST Data: " . print_r($data, true));

    $lead_id = $data['data']['FIELDS']['ID'] ?? null;

    if (!$lead_id) {
        logData('error.log', "Missing Lead ID in POST data.");
        echo json_encode(['error' => 'Missing Lead ID']);
        exit;
    }

    try {
        $lead = getLead($lead_id);
        logData('lead.log', "Fetched Lead Data: " . print_r($lead, true));

        $phone = $lead['PHONE'][0]['VALUE'] ?? null;

        if ($phone) {
            $masked_phone = maskNumber($phone);
            $fields = [
                'UF_CRM_1743588922985' => $masked_phone
            ];

            $update_lead = updateLead($lead_id, $fields);
            logData('lead.log', "Updated Lead Data: " . print_r($update_lead, true));
        }

        if ($lead) {
        } else {
            logData('error.log', "Lead Data Missing for Lead ID: $lead_id");
            echo json_encode(['error' => 'Lead Data Missing']);
        }
    } catch (Exception $e) {
        logData('error.log', "Error Fetching Lead Data: " . $e->getMessage());
        echo json_encode(['error' => 'Lead Fetch Error', 'details' => $e->getMessage()]);
    }
} else {
    logData('error.log', "Invalid Request Method. Expected POST, received: " . $_SERVER['REQUEST_METHOD']);
    echo json_encode(['error' => 'Invalid Request Method', 'details' => 'Use POST']);
    exit;
}
