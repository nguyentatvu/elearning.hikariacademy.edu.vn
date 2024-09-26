<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Throwable;

class Logger
{
    /**
     * Log info
     *
     * @param string $message
     * @param array $context
     * @return void
     */
    public function info(string $message, array $context = [])
    {
        $this->log('info', $message, $context);
    }

    /**
     * Log error
     *
     * @param string $message
     * @param array $context
     * @param Throwable|null $exception
     * @return void
     */
    public function error(string $message, array $context = [], ?Throwable $exception = null)
    {
        $this->log('error', $message, $context, $exception);
    }

    /**
     * Log warning
     *
     * @param string $message
     * @param array $context
     * @return void
     */
    public function warning(string $message, array $context = [])
    {
        $this->log('warning', $message, $context);
    }

    /**
     * Log debug
     *
     * @param string $message
     * @param array $context
     * @return void
     */
    public function debug(string $message, array $context = [])
    {
        $this->log('debug', $message, $context);
    }

    /*
     * @param string $message
     *
     * @param string $level
     * @param string $message
     * @param array $context
     * @param Throwable|null $exception
     * @return void
     */
    protected function log(string $level, string $message, array $context = [], ?Throwable $exception = null)
    {
        $trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2);
        $caller = $trace[1] ?? [];
        $logContext = array_merge([
            'file' => $caller['file'] ?? 'unknown',
            'line' => $caller['line'] ?? 'unknown',
            'function' => $caller['function'] ?? 'unknown',
        ], $context);

        if ($exception) {
            $logContext['exception'] = [
                'message' => $exception->getMessage(),
                'code' => $exception->getCode(),
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
                'trace' => $exception->getTraceAsString(),
            ];
        }

        $formattedMessage = $this->formatMessage($message, $logContext);
        Log::$level($formattedMessage, $logContext);
    }

    /**
     * Format message
     *
     * @param string $message
     * @param array $context
     * @return string
     */
    protected function formatMessage(string $message, array $context = [])
    {
        return sprintf(
            "%s in %s:%s - %s",
            $context['function'],
            $context['file'],
            $context['line'],
            $message
        );
    }
}
