<?php

declare(strict_types=1);

use G\Framework\ApplicationFactory;

require dirname(__DIR__) . '/vendor/autoload.php';

ApplicationFactory::fromDirectory(dirname(__DIR__))->run();
