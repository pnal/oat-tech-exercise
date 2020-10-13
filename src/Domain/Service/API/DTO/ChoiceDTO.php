<?php


namespace App\Domain\Service\API\DTO;


use App\Domain\Service\API\APIValidationException;

class ChoiceDTO
{
    protected const TEXT_PROP_EXT_NAME = 'text';
    protected const MISSED_PROPERTY_ERROR_MESSAGE_TEMPLATE = 'Question choice #%d should contain %s property';


    public $text;

    public static function fromArray(array $choiceAssoc, int $choiceNumber)
    {
        if (!isset($choiceAssoc[static::TEXT_PROP_EXT_NAME])) {
            throw new APIValidationException(
                sprintf(static::MISSED_PROPERTY_ERROR_MESSAGE_TEMPLATE, $choiceNumber, static::TEXT_PROP_EXT_NAME)
            );
        }

        $self = new self();
        $self->text = $choiceAssoc[static::TEXT_PROP_EXT_NAME];
        $self->validate();

        return $self;
    }

    private function validate(): bool
    {
        //throw new APIValidationException();
        return true;
    }
}