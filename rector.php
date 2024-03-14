<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\TypeDeclaration\Rector\ClassMethod\AddVoidReturnTypeWhereNoReturnRector;
use Utils\Rector\Rector\ChangeFromToTableQueryBuilder;
use Utils\Rector\Rector\ChangeOrWhereRenameAndRemoveWhereOpenWhereClose;
use Utils\Rector\Rector\ChangeSelectArrayToSelectQueryBuilder;
use Utils\Rector\Rector\ConvertCurrentToFirstRector;
use Utils\Rector\Rector\ConvertExcuteToGetRector;
use Utils\Rector\Rector\ConvertInputParamToRequest;
use Utils\Rector\Rector\ConvertInsertQueryBuilder;
use Utils\Rector\Rector\ConvertMessageFlashToSession;

return RectorConfig::configure()
    ->withPaths([
        __DIR__ . '/app',
        __DIR__ . '/bootstrap',
        __DIR__ . '/config',
        __DIR__ . '/public',
        __DIR__ . '/resources',
        __DIR__ . '/routes',
        __DIR__ . '/tests',
    ])
    // uncomment to reach your current PHP version
    // ->withPhpSets()
    ->withRules([
        // AddVoidReturnTypeWhereNoReturnRector::class,
        ChangeFromToTableQueryBuilder::class,
        ChangeSelectArrayToSelectQueryBuilder::class,
        ChangeOrWhereRenameAndRemoveWhereOpenWhereClose::class,
        ConvertInsertQueryBuilder::class,
        ConvertExcuteToGetRector::class,
        ConvertCurrentToFirstRector::class,
        ConvertMessageFlashToSession::class,
        ConvertInputParamToRequest::class,
    ]);
