<?php
declare(strict_types=1);
namespace Nebule\Library;

interface ModuleInterface {
    public function __construct(nebule $nebuleInstance);
    public function __toString(): string;

    public function getClassName(): string;
    public function getHookList(string $hookName, ?Node $nid = null): array;
    public function displayModule(): void;
}

Interface ModuleTranslateInterface {
    public function __construct(nebule $nebuleInstance);
    public function __destruct();
    public function __toString(): string;
}
