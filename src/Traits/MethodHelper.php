<?php

declare(strict_types=1);

namespace Netwerkstatt\SilverstripeRector\Traits;

use LogicException;

trait MethodHelper
{
    private function isClassSameOrSubclassOfConfigured(string $actualClass, string $configuredClass): bool
    {
        $actualClass = ltrim($actualClass, '\\');
        $configuredClass = ltrim($configuredClass, '\\');

        if (
            strcasecmp($actualClass, $configuredClass) === 0 ||
            str_ends_with(strtolower($actualClass), '\\' . strtolower($configuredClass))
        ) {
            return true;
        }

        if (!$this->reflectionProvider->hasClass($actualClass)) {
            return false;
        }

        $classReflection = $this->reflectionProvider->getClass($actualClass);

        if (str_contains($configuredClass, '\\')) {
            return $classReflection->isSubclassOf($configuredClass);
        }

        foreach (array_merge([$classReflection->getName()], $classReflection->getParentClassesNames()) as $candidate) {
            if (!is_string($candidate) || $candidate === '') {
                continue;
            }

            if (
                str_ends_with(strtolower($candidate), '\\' . strtolower($configuredClass)) ||
                strcasecmp($candidate, $configuredClass) === 0
            ) {
                return true;
            }
        }

        return false;
    }
}
