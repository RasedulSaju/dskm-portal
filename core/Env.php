<?php
// core/Env.php

class Env
{
    public static function load(string $path): void
    {
        if (!file_exists($path)) {
            return;
        }

        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        foreach ($lines as $line) {
            // Skip comments
            $line = trim($line);
            if ($line === '' || $line[0] === '#') {
                continue;
            }

            // Split on first = only
            $pos = strpos($line, '=');
            if ($pos === false) {
                continue;
            }

            $key   = trim(substr($line, 0, $pos));
            $value = trim(substr($line, $pos + 1));

            // Remove surrounding quotes if present
            if (strlen($value) >= 2) {
                if (($value[0] === '"' && $value[-1] === '"') ||
                    ($value[0] === "'" && $value[-1] === "'")) {
                    $value = substr($value, 1, -1);
                }
            }

            // Set in environment
            if (!array_key_exists($key, $_ENV)) {
                $_ENV[$key] = $value;
                putenv("{$key}={$value}");
            }
        }
    }

    public static function get(string $key, $default = null)
    {
        return $_ENV[$key] ?? getenv($key) ?: $default;
    }
}
