<?php

it('ships a valid module manifest and service provider', function (): void {
    $root = dirname(__DIR__, 2);
    $manifest = json_decode(file_get_contents($root.'/module.json'), true, 512, JSON_THROW_ON_ERROR);
    $composer = json_decode(file_get_contents($root.'/composer.json'), true, 512, JSON_THROW_ON_ERROR);

    expect($manifest['name'] ?? null)->toBeString()->not->toBeEmpty()
        ->and($composer['name'] ?? null)->toStartWith('liberusoftware/module-')
        ->and(glob($root.'/src/*ServiceProvider.php'))->not->toBeEmpty();
});
