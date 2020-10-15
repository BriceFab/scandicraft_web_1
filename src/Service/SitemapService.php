<?php

namespace App\Service;

use App\Entity\ForumCategory;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\ORM\EntityManagerInterface;
use Entity\Category;
use Exception;
use Psr\Container\ContainerInterface;
use ReflectionClass;
use ReflectionMethod;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Serializer\SerializerInterface;

class SitemapService
{
    private $router;
    private $em;
    private $serializer;
    private $container;

    public function __construct(RouterInterface $router, EntityManagerInterface $em, SerializerInterface $serializer, ContainerInterface $container)
    {
        $this->router = $router;
        $this->em = $em;
        $this->serializer = $serializer;
        $this->container = $container;
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
            $route_options = $route->getOptions();
            if (array_key_exists('sitemap', $route_options) && isset($route_options["sitemap"])) {
                $sitemap_option = $route_options["sitemap"];

                if (isset($sitemap_option) && ($sitemap_option === true || $sitemap_option === "true")) {
                    $route_params = [];
                    preg_match_all("/\\{(.*?)\\}/", $route->getPath(), $route_params, PREG_OFFSET_CAPTURE);

                    if ($route_params[1] && count($route_params[1]) >= 1) {
                        try {
                            if (array_key_exists('sitemap_options', $route_options) && isset($route_options["sitemap_options"])) {
                                $sitemap_options = $route_options["sitemap_options"];
                                if (isset($sitemap_options["entity_repo"])) {
                                    $entity = $sitemap_options["entity_repo"];
                                    $entity_repo = $this->em->getRepository("App\\Entity\\" . $entity);
                                    if ($entity_repo) {
                                        foreach ($entity_repo->findBy([]) as $entity_item) {
                                            $url = $route->getPath();

                                            foreach ($route_params[1] as $route_param) {
                                                $route_param = (count($route_param) > 1) ? $route_param[0] : $route_param;

                                                $param_value = null;

                                                //entity_method param guesser
                                                $entity_method = null;

                                                $annotationReader = new AnnotationReader();
                                                $defaults = $route->getDefaults();
                                                if (isset($defaults['_controller'])) {
                                                    list($controllerService, $controllerMethod) = explode('::', $defaults['_controller']);

                                                    $controllerObject = $this->container->get($controllerService);
                                                    $reflectedMethod = new ReflectionMethod($controllerObject, $controllerMethod);

                                                    // the annotations
                                                    $annotations = $annotationReader->getMethodAnnotations($reflectedMethod);

                                                    $paramConverterAnnotations = array_filter($annotations, function ($annotation) {
                                                        return $annotation instanceof ParamConverter;
                                                    });

                                                    /** @var ParamConverter $paramConverter */
                                                    foreach ($paramConverterAnnotations as $paramConverter) {
                                                        $paramConverterOptions = $paramConverter->getOptions();
                                                        $paramConverterName = $paramConverter->getName();

                                                        if (isset($paramConverterOptions['mapping'])) {
                                                            $paramConverterMapping = $paramConverterOptions['mapping'];
                                                            $entity_method_guesser = array_filter(array_keys($paramConverterMapping), function ($annotation_mapping) use ($route_param) {
                                                                return $annotation_mapping == $route_param;
                                                            });

                                                            if (!is_null($entity_method_guesser) && is_array($entity_method_guesser) && count($entity_method_guesser) === 1) {
                                                                $entity_method = $paramConverterMapping[$entity_method_guesser[0]];

                                                                foreach ($reflectedMethod->getParameters() as $param) {
                                                                    $name = $param->getName();

                                                                    if ($name === $paramConverterName) {

                                                                        $type = $param->getType();
                                                                        $class = null !== $type && !$type->isBuiltin() ? $type->getName() : null;
                                                                        if ($class === get_class($entity_item)) {
                                                                            $reflectedClass = new ReflectionClass($class);
                                                                            $reflectedClassProperty = $reflectedClass->getProperty('slug');

                                                                            if (isset($reflectedClassProperty)) {
                                                                                $reflectedClassProperty->setAccessible(true);
                                                                                dd($entity_item, $reflectedClassProperty);
                                                                                dd($reflectedClassProperty->getValue($object));
                                                                            }
                                                                        }
                                                                    }
                                                                }

                                                            }
                                                        }
                                                    }
                                                } else {
                                                    $entity_method = "get" . ucfirst($route_param);
                                                }

//                                                dd($route_param, $entity_method);

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
                                    dd("cette route manque l'option entity_repo", $route);
                                }
                            }
                        } catch (Exception $e) {
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
    private function addUrl($url, $priority = 0.5, $lastmod = null, $changefreq = 'monthly')
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
    private function surveyUrls()
    {
        return [];
    }

    /**
     * URLs des forum
     * @return array
     */
    private function forumUrls()
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