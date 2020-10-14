<?php


namespace App\Domain\Service\API\DTO;

use App\Domain\Service\API\APIValidationException;
use Exception;
use Psr\Http\Message\RequestInterface;

class MultipleChoiceQuestionDTO
{
    protected const TEXT_PROP_EXT_NAME = 'text';
    protected const CHOICES_PROP_EXT_NAME = 'choices';
    protected const CREATED_AT_TEXT_PROP_EXT_NAME = 'createdAt';
    protected const MISSED_PROPERTY_ERROR_MESSAGE_TEMPLATE = 'Question should contain %s property';
    protected const REQUIRED_CHOICE_COUNT = 3;

    protected const REQUIRED_PROPS = [
        self::TEXT_PROP_EXT_NAME,
        self::CHOICES_PROP_EXT_NAME,
        self::CREATED_AT_TEXT_PROP_EXT_NAME
    ];

    public $text;
    /** @var ChoiceDTO[] */
    public $choices = [];
    public $createdAt;

    /**
     * @param RequestInterface $request
     * @return MultipleChoiceQuestionDTO
     * @throws APIValidationException
     */
    public static function fromPSRRequest(RequestInterface $request)
    {
        $self = new self();
        try {
            $data = json_decode($request->getBody(), true);
        } catch (Exception $e) {
            throw new APIValidationException('Request does not contain valid JSON object');
        }

        // Todo check datetime and format for createdAt
        foreach (static::REQUIRED_PROPS as $propName) {
            if (!isset($data[$propName])) {
                throw new APIValidationException(sprintf(static::MISSED_PROPERTY_ERROR_MESSAGE_TEMPLATE, $propName));
            }
        }

        $self->text = $data[static::TEXT_PROP_EXT_NAME];
        $self->createdAt = $data[static::CREATED_AT_TEXT_PROP_EXT_NAME];

        if (count($data[static::CHOICES_PROP_EXT_NAME]) !== static::REQUIRED_CHOICE_COUNT) {
            throw new APIValidationException(sprintf('Question can contain only 3 choices'));
        }

        foreach ($data[static::CHOICES_PROP_EXT_NAME] as $key => $choiceAssoc) {
            $self->choices[] = ChoiceDTO::fromArray($choiceAssoc, $key);
        }


        $self->validate();

        return $self;
    }

    private function validate(): bool
    {
        // Todo think how to use symfony/other validator
        //throw new APIValidationException();
        return true;
    }
}