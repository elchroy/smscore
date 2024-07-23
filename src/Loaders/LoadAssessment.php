<?php

namespace RMS\Loaders;

use Base\Core\Payload;
use RMS\Enums\PayloadKeys;
use RMS\Interfaces\Subject;

class LoadAssessment extends BaseLoader
{
    public function __construct() {}

    public function loadKeys(Payload $payload): void
    {
        $this->objectKey = PayloadKeys::SUBJECT_ASSESSMENTS->value;

        $this->idNotFound = 'Subject not set';

        $this->dataNotFound = 'Subject has no assessment';
    }

    public function getSubject(Payload $payload): ?Subject
    {
        return $payload->getData(PayloadKeys::SUBJECT->value);
    }

    public function loadData(Payload $payload): mixed
    {
        $subject = $this->getSubject($payload);

        if (! $subject) {
            throw new \Exception($this->idNotFound);
        }

        return $payload->getData($this->objectKey, function () use ($subject) {
            return $subject->getAssessment();
        });
    }
}
