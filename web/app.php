<?php

$application = require_once('app/index.php');

$application['debug'] = true;

$application->run();
