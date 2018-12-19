<?php declare(strict_types=1);

namespace Hippiemedia\Infra\UrlGenerator;

use Symfony\Component\Routing\RouterInterface;
use Hippiemedia\UrlGenerator;


final class SymfonyRouter implements UrlGenerator
{
    private $router;

    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    function url(string $route, array $params = []): string
    {
        return $this->router->generate($route, $params, RouterInterface::ABSOLUTE_URL);
    }

    function template(string $route, array $params = []): string
    {
        $route = $this->router->getRouteCollection()->get($route);

        return $route->getPath().(empty($params) ? '' : '?'.urldecode(http_build_query($params)));
    }
}
