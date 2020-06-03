<?php

declare(strict_types=1);

namespace Omikron\FactFinder\Shopware6\Configuration;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Shopware\Core\Framework\Routing\Annotation\RouteScope;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @RouteScope(scopes={"api"})
 */
class TestConnectionController extends AbstractController
{
    /**
     * @Route("/api/v{version}/_action/factfinder/test-connection", name="api.action.factfinder.test-connection", methods={"GET"})
     */
    public function testConnection(Request $request)
    {

        return new JsonResponse(['connectionEstablished' => true]);
    }
}
