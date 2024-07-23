<?php

namespace RMS\Loaders;

use Base\Core\Payload;
use RMS\Enums\PayloadKeys;
use RMS\Interfaces\Database;

class FindSchool extends BaseLoader
{
    public function __construct(private Database $database) {}

    public function loadKeys(Payload $payload): void
    {
        $this->identifierKey = PayloadKeys::SCHOOL_CODE->value;

        $this->objectKey = PayloadKeys::SCHOOL->value;

        $this->idNotFound = 'School code not provided';

        $this->dataNotFound = 'School not found';
    }

    public function loadData(Payload $payload, $identifier): mixed
    {
        return $payload->getData($this->objectKey, function () use ($identifier) {
            return $this->database->findSchool($identifier);
        });
    }
}
