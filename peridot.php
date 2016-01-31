<?php

use Evenement\EventEmitterInterface;
use Peridot\Plugin\Watcher\WatcherPlugin;

return function(EventEmitterInterface $emitter) {
    $watcher = new WatcherPlugin($emitter);
    $watcher->track(__DIR__ . '/src');
};

// to make peridot watch specs dir
// enter this
// ./vendor/bin/peridot specs/ --watch
