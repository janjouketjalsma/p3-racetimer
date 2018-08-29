<?php
// Routes
$app->get('/capture', P3RaceTimer\Console\Capture::class);
$app->get('/eventprocessor', P3RaceTimer\Console\EventProcessor::class);
