<?php
// Routes
$app->get('/capture', P3RaceTimer\Console\Capture::class);
$app->get('/eventserver', P3RaceTimer\Console\WebSocketServer::class);
