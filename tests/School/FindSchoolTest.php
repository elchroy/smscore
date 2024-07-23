<?php

namespace RMS\Tests\School;

use Base\Core\Handler;
use Base\Core\Payload;
use Base\Core\State;
use Mockery;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;
use RMS\Enums\PayloadKeys;
use RMS\Handlers\FindSchool;
use RMS\Interfaces\Database;
use RMS\Interfaces\School;

class FindSchoolTest extends TestCase
{
    private Handler $handler;

    private MockInterface|Database $database;

    private MockInterface|School $school;

    private string $schoolCode = 'SCH-123';

    private Payload $payload;

    public function setUp(): void
    {
        parent::setUp();

        $this->database = Mockery::mock(Database::class);

        $this->handler = new FindSchool($this->database);

        $this->payload = new Payload();

        $this->school = Mockery::mock(School::class);
        // $school = $this->createMock(School::class);
    }

    public function testFindSchoolNoSchoolCode()
    {
        try {
            // Act
            $this->handler->run($this->payload);
        } catch (\Exception $e) {
            // Assert
            $this->assertEquals($e->getMessage(), 'School code not provided');
        }
    }

    public function testFindSchoolNoSchool()
    {
        // Arrange
        $this->payload->setData(
            PayloadKeys::SCHOOL_CODE->value,
            $this->schoolCode
        );

        $this->initialize([
            'school' => null,
        ]);

        try {
            // Act
            $state = $this->handler->run($this->payload);
        } catch (\Exception $e) {
            // Assert
            $this->assertEquals($e->getMessage(), 'School not found');
        }
    }

    public function testFindSchoolSuccess()
    {
        // Arrange
        $this->payload->setData(
            PayloadKeys::SCHOOL_CODE->value,
            $this->schoolCode
        );

        $this->initialize([
            'school' => $this->school,
        ]);

        $state = $this->handler->run($this->payload);

        $this->assertEquals(State::Success, $state);
    }

    private function initialize(array $initData = []): void
    {
        $this->database
            ->shouldReceive('findSchool')
            ->with($this->schoolCode)
            ->andReturn($initData['school']);
    }
}
