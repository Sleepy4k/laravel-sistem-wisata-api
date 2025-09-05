<?php

namespace App\Support;

class PermissionPolicy
{
    /**
     * Configure the permissions policy for the application.
     *
     * @return string
     */
    public function configure(): string
    {
        $result = [];

        foreach (config('secure-headers.permissions-policy') as $name => $config) {
            if ($name === 'enable') {
                continue;
            }

            if (empty($val = $this->addgeneralDirective($config))) {
                continue;
            }

            $result[] = sprintf('%s=%s', $name, $val);
        }

        return implode(', ', $result);
    }

    /**
     * Parse specific policy value.
     *
     * @param  array<mixed>  $config
     */
    protected function addgeneralDirective(array $config): string
    {
        if ($config['none'] ?? false) {
            return '()';
        } elseif ($config['*'] ?? false) {
            return '*';
        }

        $origins = $this->origins($config['origins'] ?? []);

        if ($config['self'] ?? false) {
            array_unshift($origins, 'self');
        }

        return sprintf('(%s)', implode(' ', $origins));
    }

    /**
     * Get valid origins.
     *
     * @param  array<string>  $origins
     * @return array<string>
     */
    protected function origins(array $origins): array
    {
        $trimmed = array_map('trim', $origins);

        $filters = filter_var_array($trimmed, FILTER_VALIDATE_URL);

        $passes = array_filter($filters);

        $urls = array_values($passes);

        return array_map(function (string $url) {
            return "'$url'";
        }, $urls);
    }
}
