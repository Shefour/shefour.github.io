<?php

namespace App\Controller;

use App\Exception\NotFoundException;
use App\Model\Tea;
use App\Service\Router;
use App\Service\Templating;

class TeaController
{
    public function indexAction(Templating $templating, Router $router): ?string
    {
        $teas = Tea::findAll();
        $html = $templating->render('tea/index.html.php', [
            'teas' => $teas,
            'router' => $router,
        ]);
        return $html;
    }

    public function createAction(?array $requestTea, Templating $templating, Router $router): ?string
    {
        if ($requestTea) {
            $tea = Tea::fromArray($requestTea);
            // @todo missing validation
            $tea->save();

            $path = $router->generatePath('tea-index');
            $router->redirect($path);
            return null;
        } else {
            $tea = new Tea();
        }

        $html = $templating->render('tea/create.html.php', [
            'tea' => $tea,
            'router' => $router,
        ]);
        return $html;
    }

    public function editAction(int $teaId, ?array $requestTea, Templating $templating, Router $router): ?string
    {
        $tea = Tea::find($teaId);
        if (! $tea) {
            throw new NotFoundException("Missing tea with id $teaId");
        }

        if ($requestTea) {
            $tea->fill($requestTea);
            // @todo missing validation
            $tea->save();

            $path = $router->generatePath('tea-index');
            $router->redirect($path);
            return null;
        }

        $html = $templating->render('tea/edit.html.php', [
            'tea' => $tea,
            'router' => $router,
        ]);
        return $html;
    }

    public function showAction(int $teaId, Templating $templating, Router $router): ?string
    {
        $tea = Tea::find($teaId);
        if (! $tea) {
            throw new NotFoundException("Missing tea with id $teaId");
        }

        $html = $templating->render('tea/show.html.php', [
            'tea' => $tea,
            'router' => $router,
        ]);
        return $html;
    }

    public function deleteAction(int $teaId, Router $router): ?string
    {
        $tea = Tea::find($teaId);
        if (! $tea) {
            throw new NotFoundException("Missing tea with id $teaId");
        }

        $tea->delete();
        $path = $router->generatePath('tea-index');
        $router->redirect($path);
        return null;
    }
}