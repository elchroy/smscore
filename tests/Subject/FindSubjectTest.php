<?php

namespace RMS\Tests\Subject;

use Base\Core\Handler;
use Base\Core\Payload;
use Base\Core\State;
use Mockery;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;
use RMS\Enums\PayloadKeys;
use RMS\Interfaces\Database;
use RMS\Interfaces\Subject;
use RMS\Loaders\FindSubject;

class FindSubjectTest extends TestCase
{
    private Handler $handler;

    private MockInterface|Database $database;

    private MockInterface|Subject $subject;

    private int $subjectId = 234;

    private Payload $payload;

    public function setUp(): void
    {
        parent::setUp();

        $this->database = Mockery::mock(Database::class);

        $this->handler = new FindSubject($this->database);

        $this->payload = new Payload();

        $this->subject = Mockery::mock(Subject::class);
    }

    public function testFindSubjectNosubjectId()
    {
        try {
            // Act
            $this->handler->run($this->payload);
        } catch (\Exception $e) {
            // Assert
            $this->assertEquals($e->getMessage(), 'Subject ID not set');
        }
    }

    public function testFindSubjectNoSubject()
    {
        // Arrange
        $this->payload->setData(
            PayloadKeys::SUBJECT_ID->value,
            $this->subjectId
        );

        $this->initialize([
            'subject' => null,
        ]);

        try {
            // Act
            $state = $this->handler->run($this->payload);
        } catch (\Exception $e) {
            // Assert
            $this->assertEquals($e->getMessage(), 'Subject not found');
        }
    }

    public function testFindSubjectSuccess()
    {
        // Arrange
        $this->payload->setData(
            PayloadKeys::SUBJECT_ID->value,
            $this->subjectId
        );

        $this->initialize([
            'subject' => $this->subject,
        ]);

        $state = $this->handler->run($this->payload);

        $this->assertEquals(State::Success, $state);
    }

    private function initialize(array $initData = []): void
    {
        $this->database
            ->shouldReceive('findSubject')
            ->with($this->subjectId)
            ->andReturn($initData['subject']);
    }
}
