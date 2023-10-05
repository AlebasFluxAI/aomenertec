<?php

namespace App\Http\Controllers\V1\Wompi;


use App\Models\V1\Invoice;
use Illuminate\Http\Request;

class WompiController
{


    public function processPayment(Request $request)
    {
        $jsonEvent = $request->input();

        if ($jsonEvent) {
            $eventData = $jsonEvent;
            if ($eventData) {
                $properties = $eventData['signature']['properties'];
                $receivedChecksum = $eventData['signature']['checksum'];
                $timestamp = $eventData['timestamp'];
                $reference = $eventData['data']['transaction']["reference"];
                $status = $eventData['data']['transaction']["status"];
                $invoice = Invoice::whereCode($reference)->first();
                try {
                    $wompiSecret = $invoice->client->networkOperator->wompiCredentials->wompiSecret;

                } catch (\Throwable $error) {
                    $wompiSecret = config("wompi.wompi_default_secret");
                }
                $transactionData = $eventData['data']['transaction'];
                $concatenatedValues = [];
                foreach ($properties as $property) {
                    $propertyMapped = explode(".", $property)[1];
                    if (isset($transactionData[$propertyMapped])) {
                        $concatenatedValues[] = $transactionData[$propertyMapped];
                    }
                }
                $concatenatedData = implode('', $concatenatedValues) . $timestamp . $wompiSecret;

                $computedChecksum = hash("sha256", $concatenatedData);

                if ($computedChecksum === $receivedChecksum) {
                    http_response_code(200);

                    $invoice->update([
                        "payment_status" => strtolower($status)
                    ]);

                    echo 'Evento recibido y validado correctamente';
                } else {
                    http_response_code(400);
                    echo 'Error: Checksum no válido';
                }
            } else {
                http_response_code(400);
                echo 'Error: JSON no válido';
            }
        } else {
            http_response_code(400);
            echo 'Error: No se recibió JSON';
        }
    }
}
