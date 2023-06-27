<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Set\ValueObject\LevelSetList;
use Rector\Set\ValueObject\SetList;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->paths([
        __DIR__ . '/config',
        __DIR__ . '/src',
        __DIR__ . '/tests',
    ]);

    $rectorConfig->importNames();
    $rectorConfig->importShortClasses(false);

    $rectorConfig->skip([
        __DIR__ . '/src/AliGreen.php',
    ]);

    // define sets of rules
    $rectorConfig->sets([
        LevelSetList::UP_TO_PHP_80,
        SetList::TYPE_DECLARATION,
        SetList::EARLY_RETURN,
        SetList::PHP_80,
    ]);
};
