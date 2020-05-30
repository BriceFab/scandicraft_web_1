<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class BaseController extends AbstractController
{
    protected function retirectToPreviousRoute($request, $flashError = null, $defaultRoute = 'accueil')
    {
        /** @var Request $request */
        if ($request !== null) {
            $previous = $request->headers->get('referer');
        }
        if ($flashError !== null) {
            $this->addFlash('error', $flashError);
        }
        if (isset($previous) && $previous !== null) {
            return $this->redirect($previous);
        } else {
            return $this->redirectToRoute($defaultRoute);
        }
    }
}
