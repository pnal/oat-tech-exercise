<?php


namespace App\Service;


use App\Domain\Entity\Choice;
use App\Domain\Entity\MultipleChoiceQuestion;
use App\Domain\Service\API\APIValidationException;
use App\Domain\Service\API\DTO\MultipleChoiceQuestionDTO;
use App\Domain\Service\API\QuestionApiServiceInterface;
use App\Repository\Question\QuestionRepository;
use App\Service\Translator\TranslationException;
use App\Service\Translator\TranslatorService;
use Nyholm\Psr7\Factory\Psr17Factory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class QuestionApiService implements QuestionApiServiceInterface
{
    /**
     * @var QuestionRepository
     */
    private $questionRepository;
    /**
     * @var TranslatorService
     */
    private $translatorService;

    /**
     * QuestionApiService constructor.
     * @param QuestionRepository $questionRepository
     * @param TranslatorService $translatorService
     */
    public function __construct(QuestionRepository $questionRepository, TranslatorService $translatorService)
    {
        $this->questionRepository = $questionRepository;
        $this->translatorService = $translatorService;
    }

    /**
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    public function list(ServerRequestInterface $request): ResponseInterface
    {
        $queryParams = $request->getQueryParams();
        $questions = $this->questionRepository->getAll();

        if (isset($queryParams['lang'])) {
            try {
                $response = [];
                foreach ($questions as $question) {
                    $response[] = $this->translatorService->translateEntity($question, $queryParams['lang']);
                }
            } catch (TranslationException $e) {
                return $this->errorCodeResponse(400, $e->getMessage());
            }
        } else {
            $response = $questions->toArray();
        }

        return $this->jsonResponse($response, 200);
    }

    /**
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    public function save(ServerRequestInterface $request): ResponseInterface
    {
        try {
            $questionDTO = MultipleChoiceQuestionDTO::fromPSRRequest($request);
        } catch (APIValidationException $e) {
            return $this->errorCodeResponse(400, $e->getMessage());
        }
        $choices = [];
        foreach ($questionDTO->choices as $choice) {
            $choices[] = new Choice($choice->text);
        }
        $question = new MultipleChoiceQuestion($questionDTO->text, $questionDTO->createdAt, ...$choices);
        $savedQuestion = $this->questionRepository->storeOne($question);

        return $this->jsonResponse($savedQuestion, 200);
    }

    /**
     * @param $data
     * @param $code
     * @return ResponseInterface
     */
    private function jsonResponse($data, $code): ResponseInterface
    {
        $psr17Factory = new Psr17Factory();

        $stream = $psr17Factory->createStream(json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
        return $psr17Factory->createResponse($code)->withHeader('Content-type', 'application/json')->withBody($stream);
    }

    /**
     * @param int $code
     * @param string|null $reason
     * @return ResponseInterface
     */
    private function errorCodeResponse(int $code, string $reason = null): ResponseInterface
    {
        $psr17Factory = new Psr17Factory();
        return $psr17Factory->createResponse($code, $reason);
    }
}