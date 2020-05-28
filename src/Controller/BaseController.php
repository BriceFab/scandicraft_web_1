<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class BaseController extends AbstractController
{
    protected function retirectToPreviousRoute(Request $request, $flashError = null, $defaultRoute = 'accueil')
    {
        $previous = $request->headers->get('referer');
        if ($flashError !== null) {
            $this->addFlash('error', $flashError);
        }
        if ($previous) {
            return $this->redirect($previous);
        } else {
            return $this->redirectToRoute($defaultRoute);
        }
    }
}
