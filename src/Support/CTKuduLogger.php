<?php

namespace Kudu\CTKudu\Support;

use Illuminate\Log\LogManager;
use Psr\Log\LoggerInterface;

class CTKuduLogger
{
    protected LogManager $logManager;
    public function __construct()
    {
    }

    public function logger(): LoggerInterface
    {
        $channel = config('ctkudu.logging.channel');

        return $channel ? $this->logManager->channel($channel) : $this->logManager;
    }

    public function info(string $message, array $context = []): void
    {
        if (!config('ctkudu.logging.enabled', true)) {
            return;
        }

        $this->logger()->info("[CTKUDU] {$message}", $context);
    }

    public function error(string $message, array $context = []): void
    {
        if (!config('ctkudu.logging.enabled', true)) {
            return;
        }

        $this->logger()->error("[CTKUDU] {$message}", $context);
    }

    public function warning(string $message, array $context = []): void
    {
        if (!config('ctkudu.logging.enabled', true)) {
            return;
        }

        $this->logger()->warning("[CTKUDU] {$message}", $context);
    }

    public function alert(string $label, string $message, array $context = []): void
    {
        if (!config('ctkudu.logging.enabled', true)) {
            return;
        }

        $this->logger()->alert("[CTKUDU] [{$label}] {$message}", $context);
    }

    public function notice(string $message, array $context = []): void
    {
        if (!config('ctkudu.logging.enabled', true)) {
            return;
        }

        $this->logger()->notice("[CTKUDU] {$message}", $context);
    }
}
