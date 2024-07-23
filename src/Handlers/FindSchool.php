<?php

namespace RMS\Handlers;

use Base\Core\BaseHandler;
use Base\Core\Payload;
use Base\Core\State;
use RMS\Enums\PayloadKeys;
use RMS\Interfaces\Database;

class FindSchool extends BaseHandler
{
    public function __construct(private Database $database) {}

    public function handle(Payload $payload): State
    {
        $schoolCode = $payload->getData(PayloadKeys::SCHOOL_CODE->value);

        if (! $schoolCode) {
            throw new \Exception('School code not provided');
        }

        // find school
        $school = $payload->getData(PayloadKeys::SCHOOL->value, function () use ($schoolCode) {
            return $this->database->findSchool($schoolCode);
        });

        if (! $school) {
            throw new \Exception('School not found');
        }

        return State::Success;
    }
}
