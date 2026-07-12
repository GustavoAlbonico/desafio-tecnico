<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\Controller\Controller;
use Cake\Http\Response;
use SwaggerBake\Lib\Service\OpenApiControllerService;

class SwaggerController extends Controller
{
    public function index(OpenApiControllerService $service): Response
    {
        $service->build();

        $config = $service->getConfig();
        $title = $config->getTitleFromYml();
        $url = $config->getWebPath();
        $this->set(compact('title', 'url'));

        $this->viewBuilder()->setLayout('swagger');

        return $this->render('index');
    }
}