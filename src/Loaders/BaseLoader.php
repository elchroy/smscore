<?php

namespace RMS\Loaders;

use Base\Core\BaseHandler;
use Base\Core\Payload;
use Base\Core\State;
use RMS\Interfaces\Database;

abstract class BaseLoader extends BaseHandler
{
    public string $objectKey;

    public string $identifierKey;

    protected string $idNotFound = 'Not found';

    protected string $dataNotFound = 'Data not found';

    public function __construct(private Database $database) {}

    public function handle(Payload $payload): State
    {
        $this->loadKeys($payload);

        $identifier = $payload->getData($this->identifierKey);

        if (! $identifier) {
            throw new \Exception($this->idNotFound);
        }

        // find school
        $instance = $this->loadData($payload, $identifier);

        if (! $instance) {
            throw new \Exception($this->dataNotFound);
        }

        return State::Success;
    }

    abstract public function loadKeys(Payload $payload): void;

    abstract public function loadData(Payload $payload, $identifier): mixed;
}
