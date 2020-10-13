<?php

namespace App\Service;

use App\Entity\ForumCategory;
use Doctrine\ORM\EntityManagerInterface;
use Entity\Category;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Serializer\SerializerInterface;

class SitemapService
{
    private $router;
    private $em;
    private $serializer;

    public function __construct(RouterInterface $router, EntityManagerInterface $em, SerializerInterface $serializer)
    {
        $this->router = $router;
        $this->em = $em;
        $this->serializer = $serializer;
    }

    /**
     * Génère les URLs du sitemap
     * @return array
     */
    public function generateUrls()
    {
        $urls = [];

        $routes = $this->router->getRouteCollection()->all();
        foreach ($routes as $route) {
            if (array_key_exists('sitemap', $route->getOptions())) {
                $sitemap_option = $route->getOptions()["sitemap"];
                if (isset($sitemap_option) && ($sitemap_option === true || $sitemap_option === "true")) {
                    $route_params = [];
                    preg_match_all("/\\{(.*?)\\}/", $route->getPath(), $route_params, PREG_OFFSET_CAPTURE);

                    if ($route_params[1] && count($route_params[1]) >= 1) {
                        try {
                            if (isset($route->getOptions()["sitemap_entity_repo"])) {
                                $entity = $route->getOptions()["sitemap_entity_repo"];
                                $entity_repo = $this->em->getRepository("App\\Entity\\" . $entity);
                                if ($entity_repo) {
                                    foreach ($entity_repo->findBy([]) as $entity_item) {
                                        $url = $route->getPath();

                                        foreach ($route_params[1] as $route_param) {
                                            $route_param = (count($route_param) > 1) ? $route_param[0] : $route_param;

                                            $param_value = null;
                                            $entity_method = "get" . ucfirst($route_param);
                                            if (method_exists($entity_item, $entity_method)) {
                                                $param_value = $entity_item->{$entity_method}();
                                                $url = str_replace("{{$route_param}}", $param_value, $url);
                                            } else {
                                                $url = null;
                                                dd("param method not exist $entity_method for $entity", $route);
                                            }
                                        }

                                        if (!is_null($url)) {
                                            $urls[] = $this->addUrl($url);
                                        }
                                    }
                                } else {
                                    dd("le repo $entity n'existe pas", $route);
                                }
                            } else {
                                dd("cette route manque l'option sitemap_entity_repo", $route);
                            }
                        } catch
                        (Exception $e) {
                            dd($e->getMessage(), $route);
                        }
                    } else {
                        $urls[] = $this->addUrl($route->getPath(), 1.0);
                    }
                }
            }
        }

        $urls = array_merge(
            $urls,
            $this->forumUrls(),
            $this->surveyUrls()
        );

        return $urls;
    }

    /**
     * Aide à créer une URL du sitemap
     * @param $url
     * @param float $priority
     * @param null $lastmod
     * @param string $changefreq
     * @return array
     */
    private
    function addUrl($url, $priority = 0.5, $lastmod = null, $changefreq = 'monthly')
    {
        $url = [
            'loc' => $url,
            'priority' => $priority,
            'changefreq' => $changefreq,
        ];

        if (isset($lastmod) && !is_null($lastmod)) {
            $url['lastmod'] = $lastmod;
        }

        return $url;
    }

    /**
     * URLs des sondages
     * @return array
     */
    private
    function surveyUrls()
    {
        return [];
    }

    /**
     * URLs des forum
     * @return array
     */
    private
    function forumUrls()
    {
        $urls = [];

        $categories = $this->em->getRepository(ForumCategory::class)->findBy(['active' => true]);

        /** @var Category $category */
        foreach ($categories as $category) {
            $urls[] = $this->addUrl($category->getSlug());
        }

        return $urls;
    }
}