<?php

namespace App\Controller\Store;

use App\Repository\PaymentTypesRepository;
use App\Service\PaypalService;
use Stripe\Exception\ApiErrorException;
use Stripe\PaymentIntent;
use Stripe\Stripe;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Serializer;

class CreditController extends AbstractController
{
    private $paypalService;

    public function __construct(PaypalService $paypalService)
    {
        $this->paypalService = $paypalService;
    }

    /**
     * @Route("/crediter", name="crediter")
     * @param Serializer $serializer
     * @param PaymentTypesRepository $paymentTypes
     * @return Response
     */
    public function index(Serializer $serializer, PaymentTypesRepository $paymentTypes)
    {
        $payment_types_data = $serializer->serialize($paymentTypes->findBy(['enable' => true]), 'json');

        return $this->render('store/credit/index.html.twig', [
            'payment_types' => $payment_types_data,
            'dedipass_public_key' => $this->getParameter('DEDIPASS_PUBLIC_KEY'),
            'stripe_public_key' => $this->getParameter('STRIPE_PUBLIC_KEY'),
            'paypal_public_key' => $this->getParameter('PAYPAL_CLIENT_ID'),
        ]);
    }

    /**
     * @Route("/stripe/pay", methods={"post"})
     * @param Request $request
     * @param ParameterBagInterface $parameter
     * @return JsonResponse
     */
    public function handleStripePayment(Request $request, ParameterBagInterface $parameter)
    {
        Stripe::setApiKey($parameter->get('STRIPE_SECRET_KEY'));

        $intent = null;
        try {
            $request_json = json_decode($request->getContent(), true);
            $payment_method_id = isset($request_json['payment_method_id']) ? $request_json['payment_method_id'] : null;
            $payment_intent_id = isset($request_json['payment_intent_id']) ? $request_json['payment_intent_id'] : null;

            if (!is_null($payment_method_id)) {
                # Create the PaymentIntent
                $intent = PaymentIntent::create([
                    'payment_method' => $payment_method_id,
                    'amount' => 100,
                    'currency' => 'chf',
                    'confirmation_method' => 'manual',
                    'confirm' => true,
                ]);
            }
            if (!is_null($payment_intent_id)) {
                $intent = PaymentIntent::retrieve(
                    $payment_intent_id
                );

                $intent->confirm();
            }

            //generate response
            if ($intent->status == 'requires_action' && $intent->next_action->type == 'use_stripe_sdk') {
                # Tell the client to handle the action
                return $this->json([
                    'requires_action' => true,
                    'payment_intent_client_secret' => $intent->client_secret
                ]);
            } else if ($intent->status == 'succeeded') {
                # The payment didn’t need any additional actions and completed!
                # Handle post-payment fulfillment
                return $this->json([
                    "success" => true,
                    "amount_received" => $intent->amount_received,
                ]);
            } else {
                # Invalid status
                return $this->json([
                    'error' => 'Invalid PaymentIntent status'
                ], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        } catch (ApiErrorException $e) {
            return $this->json([
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * @Route("/crediter/verifier/dedipass", name="credit_verify_dedipass", methods={"POST"})
     * @param Request $request
     * @return RedirectResponse
     */
    public function verifyDedipass(Request $request)
    {
        $request_code = $request->get('code');

        $code = isset($request_code) ? preg_replace('/[^a-zA-Z0-9]+/', '', $request_code) : '';
        if (empty($code)) {
            $this->addFlash('error', 'Vous devez saisir un code');
        } else {
            $public_key = $this->getParameter('DEDIPASS_PUBLIC_KEY');
            $private_key = $this->getParameter('DEDIPASS_PRIVATE_KEY');

            $dedipass = file_get_contents("http://api.dedipass.com/v1/pay/?public_key=$public_key&private_key=$private_key&code=$code");
            $dedipass = json_decode($dedipass);
            if ($dedipass->status == 'success') {
                // Le transaction est validée et payée.
                // Vous pouvez utiliser la variable $virtual_currency
                // pour créditer le nombre de PBs.
                $virtual_currency = $dedipass->virtual_currency;
                $this->addFlash('notice', 'Le code est valide et vous êtes crédité de ' . $virtual_currency . 'PBs');
            } else {
                // Le code est invalide
                $this->addFlash('error', 'Le code ' . $code . ' est invalide');
            }
        }

        return $this->redirectToRoute('crediter');
    }
}
