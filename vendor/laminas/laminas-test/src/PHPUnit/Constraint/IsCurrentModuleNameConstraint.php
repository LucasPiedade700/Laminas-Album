<?php

declare(strict_types=1);

namespace Laminas\Test\PHPUnit\Constraint;

use Override;

use function ltrim;
use function str_contains;
use function strrpos;
use function strtolower;
use function substr;

final class IsCurrentModuleNameConstraint extends LaminasConstraint
{
    #[Override]
    public function toString(): string
    {
        return 'is the actual module name';
    }

    /** @param mixed $other */
    #[Override]
    public function failureDescription($other): string
    {
        $other = (string) $other;
        return "\"$other\" {$this->toString()}, actual module name is \"{$this->getCurrentModuleName()}\"";
    }

    /** @param mixed $other */
    #[Override]
    public function matches($other): bool
    {
        $other = (string) $other;
        return strtolower($other) === $this->getCurrentModuleName();
    }

    public function getCurrentModuleName(): string
    {
        $controllerClass = $this->getControllerFullClassName();
        $match           = '';

        $applicationConfig = $this->getTestCase()->getApplicationConfig();

        // Find Module from Controller
        /** @var string $appModules */
        foreach ($applicationConfig['modules'] as $appModules) {
            if (str_contains($controllerClass, $appModules . '\\')) {
                if (str_contains($appModules, '\\')) {
                    $match = ltrim(substr($appModules, strrpos($appModules, '\\')), '\\');
                } else {
                    $match = ltrim($appModules);
                }
            }
        }

        return strtolower($match);
    }
}
