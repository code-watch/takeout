<?php

namespace App;

use App\Exceptions\DockerMissingException;
use App\Exceptions\DockerNotAvailableException;
use App\Shell\Docker;

trait InitializesCommands
{
    public function initializeCommand(): void
    {
        app()->bind('console', function () {
            return $this;
        });

        if (! app(Docker::class)->isInstalled()) {
            throw new DockerMissingException;
        }

        if (! app(Docker::class)->isDockerServiceRunning()) {
            throw new DockerNotAvailableException;
        }
    }
}
