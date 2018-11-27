<?php
namespace Drupal\map_component\Controller;

use Drupal\Core\Controller\ControllerBase;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class MarkerResource extends ControllerBase {
    protected $markerSourceManager;
    private $pageCacheKillSwitch;

    /**
     * Class constructor
     */
    public function __construct($markerSourceManager,$pageCacheKillSwitch) {
        $this->markerSourceManager = $markerSourceManager;
        $this->pageCacheKillSwitch = $pageCacheKillSwitch;
    }

    /**
     * {@inheritdoc}
     */
    public static function create(ContainerInterface $container) {
      return new static(
        $container->get('plugin.manager.nun_marker_source'),
        $container->get('page_cache_kill_switch')
      );
    }

    public function getMarkers(Request $request,$id) {
        $this->pageCacheKillSwitch->trigger();
        if(!$this->markerSourceManager->getDefinition($id,FALSE)) {
            throw new BadRequestHttpException();
        }
        $markerSource = $this->markerSourceManager->createInstance($id);
        $markers = $markerSource->getMarkers();
        $return = [
            'list' => []
        ];
        if($markerSource->getType()) {
            $return['type'] = $markerSource->getType();
        }
        if($markerSource->getIcon()) {
            $return['icon'] = $markerSource->getIcon();
        }
        foreach ($markers as $marker) {
            $return['list'][] = $marker->toJson();
        }
        return new JsonResponse($return);
    }
}
