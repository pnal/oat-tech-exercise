<?php


namespace App\Domain\Service\API;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

interface QuestionApiServiceInterface
{
    /**
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     * @throws
     */
    public function list(ServerRequestInterface $request): ResponseInterface;

    /**
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    public function save(ServerRequestInterface $request): ResponseInterface;
}