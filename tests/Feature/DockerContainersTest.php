<?php

namespace Tests\Feature;

use App\Shell\Docker;
use App\Shell\Shell;
use Mockery as M;
use Symfony\Component\Process\Process;
use Tests\TestCase;

class DockerContainersTest extends TestCase
{
    function fakeContainerList()
    {
        return <<<EOD
CONTAINER ID|NAMES|STATUS|PORTS
123456789abc|TO-meilisearch|Up 15 Minutes|7700:7700
EOD;
    }

    /** @test */
    function it_formats_output_to_tables()
    {
        $this->mock(Shell::class, function ($mock) {
            $process = M::mock(Process::class);
            $process->shouldReceive('getOutput')->andReturn($this->fakeContainerList());
            $mock->shouldReceive('execQuietly')->andReturn($process);
        });

        $output = app(Docker::class)->takeoutContainers();

        $expectedTable = [[
            'container_id' => '123456789abc',
            'names' => 'TO-meilisearch',
            'status' => 'Up 15 Minutes',
            'ports' => '7700:7700',
        ],];

        $this->assertEquals($expectedTable, $output->toArray());
    }
}
