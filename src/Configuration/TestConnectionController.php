<?php

declare(strict_types=1);

namespace Omikron\FactFinder\Shopware6\Configuration;

use Omikron\FactFinder\Shopware6\Configuration\Service\TestConnectionService;
use Omikron\FactFinder\Shopware6\Credentials\Credentials;
use Shopware\Core\Framework\Routing\Annotation\RouteScope;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\RequestException;

/**
 * @RouteScope(scopes={"api"})
 */
class TestConnectionController extends AbstractController
{
    /** @var TestConnectionService */
    private $testConnectionService;

    public function __construct(TestConnectionService $testConnectionService)
    {
        $this->testConnectionService = $testConnectionService;
    }

    /**
     * @Route("/api/v{version}/_action/factfinder/test-connection", name="api.action.factfinder.test-connection", methods={"GET"})
     */
    public function testConnection(Request $request)
    {
        $error = null;

        try {
            $this->testConnectionService->execute($request->query->get('serverUrl'), $request->query->get('channel'), new Credentials(
                $request->query->get('user'),
                $request->query->get('password')
            ));
        } catch (ConnectException| RequestException $e) {
            $error = $e->getMessage();
        }

        return new JsonResponse(['connectionEstablished' => !$error, 'error' => $error]);
    }
}
