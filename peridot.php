<?php

use Evenement\EventEmitterInterface;
use Peridot\Plugin\Watcher\WatcherPlugin;
use Peridot\Plugin\Prophecy\ProphecyPlugin;

return function(EventEmitterInterface $emitter) {
    $watcher = new WatcherPlugin($emitter);
    $watcher->track(__DIR__ . '/src');

    new ProphecyPlugin($emitter);
};

// to make peridot watch specs dir
// enter this
// ./vendor/bin/peridot specs/ --watch
