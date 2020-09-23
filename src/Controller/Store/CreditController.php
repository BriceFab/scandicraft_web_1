<?php

namespace App\Controller\Store;

use App\Service\PaypalService;
use Stripe\Exception\ApiErrorException;
use Stripe\PaymentIntent;
use Stripe\Stripe;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CreditController extends AbstractController
{
    private $paypalService;

    public function __construct(PaypalService $paypalService)
    {
        $this->paypalService = $paypalService;
    }

    /**
     * @Route("/crediter", name="crediter")
     */
    public function index()
    {
        return $this->render('store/credit/index.html.twig', [
            'dedipass_public_key' => $this->getParameter('DEDIPASS_PUBLIC_KEY'),
        ]);
    }

    /**
     * @Route("/stripe/payment", methods={"GET"})
     * @param Request $request
     * @return JsonResponse
     * @throws ApiErrorException
     */
    public function getStripePayment(Request $request)
    {
        Stripe::setApiKey('sk_test_51HUbBjJd9qznNTg3AvNwegZqp4OyWcdi2SYIrsFWgUYEdpAZ390Ch1vsKWUeitjP7ERaPDzjDzuADOcbrcVo0Ff400L2QnnuFD');

        $intent = PaymentIntent::create([
            'amount' => 1099,
            'currency' => 'chf',
            // Verify your integration in this guide by including this parameter
            'metadata' => ['integration_check' => 'accept_a_payment'],
        ]);

        return $this->json([
            'client_secret' => $intent->client_secret,
        ]);
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
