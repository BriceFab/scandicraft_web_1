<?php

namespace App\Twig;

use App\Service\ScandiCraftService;
use Mobile_Detect;
use Symfony\Component\Security\Core\Security;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class TwigExtension extends AbstractExtension
{

    private $security;
    private $sc_service;

    public function __construct(Security $security, ScandiCraftService $sc_service)
    {
        $this->security = $security;
        $this->sc_service = $sc_service;
    }

    public function getFilters()
    {
        return [
            new TwigFilter('seo_format', [$this, 'seo_format']),
            new TwigFilter('truncate', [$this, 'truncate']),
            new TwigFilter('sc_slug', [$this, 'sc_slug']),
        ];
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('mobileDetect', [$this, 'mobileDetect']),
            new TwigFunction('isAutorized', [$this, 'isAutorized'])
        ];
    }

    public function mobileDetect($type = 'mobile')
    {
        $detect = new Mobile_Detect();
        switch ($type) {
            case 'mobile':
                return $detect->isMobile();
            case 'tablet':
                return $detect->isTablet();
            case 'pc':
                return !$detect->isMobile() && !$detect->isTablet();
            default:
                return false;
        }
    }

    public function seo_format($meta_string, $limit = -1)
    {
        $meta_string = htmlspecialchars_decode($meta_string, ENT_HTML5);       //encode en caractères normaux
        $meta_string = preg_replace("/<[^>]*>/", "", $meta_string); //enlève les balises
        $meta_string = str_replace(["\r", "\n", "\r\n"], ' ', $meta_string);    //enlève les retours à la ligne
        $meta_string = preg_replace('/\s+/', ' ', $meta_string);    //enlève les espaces multiples¨
        if ($limit > 0) {
            $meta_string = $this->truncate($meta_string, $limit);
        }
        $meta_string = html_entity_decode($meta_string);    //remplace le chaine html en utf8
        return $meta_string;
    }

    public function truncate($text, $limit, $pad = "..")
    {
        if (strlen($text) > $limit) {
            $text = $text . ' ';
            $text = substr($text, 0, $limit);
            $text = $text . $pad;
        }
        return $text;
    }

    public function isAutorized($access_roles)
    {
        foreach ($access_roles as $key => $access_role) {
            $can_access = $this->security->isGranted($access_role);
            if ($can_access) {
                return true;
            }
        }
        return false;
    }

    public function sc_slug($text)
    {
        return $this->sc_service->generateSlug($text);
    }
}
