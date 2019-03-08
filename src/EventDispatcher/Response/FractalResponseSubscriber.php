<?php

namespace App\EventDispatcher\Response;

use App\Response\FractalResponse;
use League\Fractal\Manager;
use League\Fractal\Pagination\PagerfantaPaginatorAdapter;
use League\Fractal\Resource\Collection;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;

class FractalResponseSubscriber implements EventSubscriberInterface
{
    /** @var Manager */
    private $fractal;

    /** @var RouterInterface */
    private $router;

    public function __construct(Manager $fractal, RouterInterface $router)
    {
        $this->fractal = $fractal;
        $this->router = $router;
    }

    public function transformFractalResponse(GetResponseForControllerResultEvent $event)
    {
        $value = $event->getControllerResult();

        if (!$value instanceof FractalResponse) {
            return;
        }

        if ($value instanceof Collection && $paginator = $value->getPagination()) {
            $request = $event->getRequest();
            $router = $this->router;

            $value->setPaginator(new PagerfantaPaginatorAdapter($paginator, function (int $page) use ($request, $router) {
                $route = $request->attributes->get('_route');
                $inputParams = $request->attributes->get('_route_params');
                $newParams = array_merge($inputParams, $request->query->all());
                $newParams['page'] = $page;

                return $router->generate($route, $newParams, UrlGeneratorInterface::ABSOLUTE_URL);
            }));
        }

        $event->setResponse(
            new JsonResponse(
                $this->fractal->createData($value->getData())->toArray()
            )
        );
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => 'transformFractalResponse',
        ];
    }
}
