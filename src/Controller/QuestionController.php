<?php


namespace App\Controller;


use App\Domain\Service\API\QuestionApiServiceInterface;
use Nyholm\Psr7\Factory\Psr17Factory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Symfony\Bridge\PsrHttpMessage\Factory\HttpFoundationFactory;
use Symfony\Bridge\PsrHttpMessage\Factory\PsrHttpFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class QuestionController extends AbstractController
{
    public function postQuestion (Request $request, QuestionApiServiceInterface $questionApiService) {
        $request = $this->convertRequest($request);
        $response = $questionApiService->save($request);

        return $this->responseFromPSR($response);
    }

    public function getQuestions (Request $request, QuestionApiServiceInterface $questionApiService) {
        $request = $this->convertRequest($request);
        $response = $questionApiService->list($request);

        return $this->responseFromPSR($response);
    }

    /**
     * Creates PSR7 request from SF request
     *
     * @param Request $request
     * @return ServerRequestInterface
     */
    private function convertRequest(Request $request) : ServerRequestInterface
    {
        // Todo move to parent and inherit or better create a separate service
        $psr17Factory = new Psr17Factory();
        $psrHttpFactory = new PsrHttpFactory($psr17Factory, $psr17Factory, $psr17Factory, $psr17Factory);
        return $psrHttpFactory->createRequest($request);
    }

    /**
     * Creates SF HTTP-foundation response from PSR7 response
     *
     * @param ResponseInterface $response
     * @return Response
     */
    private function responseFromPSR(ResponseInterface $response) : Response
    {
        // Todo move to parent and inherit or better create a service
        $httpFoundationFactory = new HttpFoundationFactory();
        $sfResponse = $httpFoundationFactory->createResponse($response);
        /* the bridge doesn't take a reason phrase by default */
        return $sfResponse->setStatusCode($response->getStatusCode(), $response->getReasonPhrase());
    }
}