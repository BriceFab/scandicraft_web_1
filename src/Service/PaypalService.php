<?php

namespace App\Service;

use PayPalCheckoutSdk\Core\PayPalHttpClient;
use PayPalCheckoutSdk\Core\SandboxEnvironment;
use PayPalCheckoutSdk\Orders\OrdersCaptureRequest;
use PayPalHttp\HttpResponse;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class PaypalService
{
    private $parameter;

    public function __construct(ParameterBagInterface $parameter)
    {
        $this->parameter = $parameter;
    }

    /**
     * Returns PayPal HTTP client instance with environment that has access
     * credentials context. Use this instance to invoke PayPal APIs, provided the
     * credentials have access.
     */
    public function client()
    {
        $clientId = $this->parameter->get('PAYPAL_CLIENT_ID');
        $clientSecret = $this->parameter->get('PAYPAL_SECRET_ID');

        /**
         * Set up and return PayPal PHP SDK environment with PayPal access credentials.
         * This sample uses SandboxEnvironment. In production, use LiveEnvironment.
         */
        $environnement = new SandboxEnvironment($clientId, $clientSecret);
        return new PayPalHttpClient($environnement);
    }

    /**
     *This function can be used to capture an order payment by passing the approved
     *order ID as argument.
     *
     * @param $orderId
     * @param bool $debug
     * @return HttpResponse
     */
    public function captureOrder($orderId, $debug = false)
    {
        $request = new OrdersCaptureRequest($orderId);

        // 3. Call PayPal to capture an authorization
        $client = $this->client();
        $response = $client->execute($request);

        // 4. Save the capture ID to your database. Implement logic to save capture to your database for future reference.
        if ($debug) {
            print "Status Code: {$response->statusCode}\n";
            print "Status: {$response->result->status}\n";
            print "Order ID: {$response->result->id}\n";
            print "Links:\n";
            foreach ($response->result->links as $link) {
                print "\t{$link->rel}: {$link->href}\tCall Type: {$link->method}\n";
            }
            print "Capture Ids:\n";
            foreach ($response->result->purchase_units as $purchase_unit) {
                foreach ($purchase_unit->payments->captures as $capture) {
                    print "\t{$capture->id}";
                }
            }

            dd($response->result);
            // To print the whole response body, uncomment the following line
            // echo json_encode($response->result, JSON_PRETTY_PRINT);
        }

        return $response;
    }
}
