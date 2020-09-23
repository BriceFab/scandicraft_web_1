<?php

namespace App\Controller\Store;

use App\Service\PaypalService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
