<?php

namespace RMS\Tests\Subject;

use Base\Core\Handler;
use Base\Core\Payload;
use Base\Core\State;
use Mockery;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;
use RMS\Enums\PayloadKeys;
use RMS\Interfaces\Assessment;
use RMS\Interfaces\Subject;
use RMS\Loaders\LoadAssessment;

class LoadAssessmentTest extends TestCase
{
    private Handler $handler;

    private MockInterface|Subject $subject;

    private MockInterface|Assessment $assessment;

    private Payload $payload;

    public function setUp(): void
    {
        parent::setUp();

        $this->subject = Mockery::mock(Subject::class);

        $this->handler = new LoadAssessment();

        $this->payload = new Payload();

        $this->subject = Mockery::mock(Subject::class);

        $this->assessment = Mockery::mock(Assessment::class);
    }

    public function testFindSubjectNoSubject()
    {
        try {
            // Act
            $this->handler->run($this->payload);
        } catch (\Exception $e) {
            // Assert
            $this->assertEquals($e->getMessage(), 'Subject not set');
        }
    }

    public function testFindSubjectNoAssessment()
    {
        // Arrange
        $this->payload->setData(
            PayloadKeys::SUBJECT->value,
            $this->subject
        );

        $this->initialize([
            'assessment' => null,
        ]);

        try {
            // Act
            $state = $this->handler->run($this->payload);
        } catch (\Exception $e) {
            // Assert
            $this->assertEquals($e->getMessage(), 'Subject has no assessment');
        }
    }

    public function testFindSubjectSuccess()
    {
        // Arrange
        $this->payload->setData(
            PayloadKeys::SUBJECT->value,
            $this->subject
        );

        $this->initialize([
            'assessment' => $this->assessment,
        ]);

        $state = $this->handler->run($this->payload);

        $this->assertEquals(State::Success, $state);
    }

    private function initialize(array $initData = []): void
    {
        $this->subject
            ->shouldReceive('getAssessment')
            ->andReturn($initData['assessment']);
    }
}
