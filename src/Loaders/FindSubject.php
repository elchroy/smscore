<?php

namespace RMS\Loaders;

use Base\Core\Payload;
use RMS\Enums\PayloadKeys;
use RMS\Interfaces\Database;

class FindSubject extends BaseLoader
{
    public function __construct(private Database $database) {}

    public function loadKeys(Payload $payload): void
    {
        $this->identifierKey = PayloadKeys::SUBJECT_ID->value;

        $this->objectKey = PayloadKeys::SUBJECT->value;

        $this->idNotFound = 'Subject ID not set';

        $this->dataNotFound = 'Subject not found';
    }

    public function loadData(Payload $payload): mixed
    {
        $identifier = $payload->getData($this->identifierKey);

        if (! $identifier) {
            throw new \Exception($this->idNotFound);
        }

        return $payload->getData($this->objectKey, function () use ($identifier) {
            return $this->database->findSubject($identifier);
        });
    }
}
