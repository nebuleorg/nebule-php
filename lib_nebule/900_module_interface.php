<?php
declare(strict_types=1);
namespace Nebule\Library;

interface ModuleInterface {
    public function __construct(nebule $nebuleInstance);
    public function __toString(): string;

    public function getClassName(): string;
    public function getType(): string;
    public function getName(): string;
    public function getMenuName(): string;
    public function getRegisteredViews(): array;
    public function getCommandName(): string;
    public function getDefaultView(): string;
    public function getDescription(): string;
    public function getVersion(): string;
    public function getAuthor(): string;
    public function getLicence(): string;
    public function getLogo(): string;
    public function getHelp(): string;
    public function getInterface(): string;
    public function getAppTitleList(): array;
    public function getAppIconList(): array;
    public function getAppDescList(): array;
    public function getAppViewList(): array;
    public function getHookList(string $hookName, ?Node $nid = null): array;
    public function displayModule(): void;
}

Interface ModuleTranslateInterface {
    public function __construct(nebule $nebuleInstance);
    public function __destruct();
    public function __toString(): string;
}
