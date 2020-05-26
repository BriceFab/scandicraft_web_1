<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class TwigExtension extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter('seo_format', [$this, 'seo_format']),
            new TwigFilter('truncate', [$this, 'truncate']),
        ];
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
}
