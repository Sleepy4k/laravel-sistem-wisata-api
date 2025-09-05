<?php

namespace Modules\Storage;

use App\Enums\ReportLogType;
use Illuminate\Support\Facades\Log;

class SystemLogManager
{
    /**
     * Log debug message to system log
     *
     * @param string $message
     * @param array $context
     *
     * @return bool
     */
    public function debug(string $message, array $context = []): bool
    {
        return $this->sendReportLog(ReportLogType::DEBUG, $message, $context);
    }

    /**
     * Log error message to system log
     *
     * @param string $message
     * @param array $context
     *
     * @return bool
     */
    public function error(string $message, array $context = []): bool
    {
        return $this->sendReportLog(ReportLogType::ERROR, $message, $context);
    }

    /**
     * Log alert message to system log
     *
     * @param string $message
     * @param array $context
     *
     * @return bool
     */
    public function alert(string $message, array $context = []): bool
    {
        return $this->sendReportLog(ReportLogType::ALERT, $message, $context);
    }

    /**
     * Log info message to system log
     *
     * @param string $message
     * @param array $context
     *
     * @return bool
     */
    public function info(string $message, array $context = []): bool
    {
        return $this->sendReportLog(ReportLogType::INFO, $message, $context);
    }

    /**
     * Log warning message to system log
     *
     * @param string $message
     * @param array $context
     *
     * @return bool
     */
    public function warning(string $message, array $context = []): bool
    {
        return $this->sendReportLog(ReportLogType::WARNING, $message, $context);
    }

    /**
     * Send report to system log
     *
     * @param ReportLogType $type
     * @param string $message
     * @param array $context
     *
     * @return bool
     */
    private function sendReportLog(ReportLogType $type, string $message, array $context = []): bool
    {
        try {
            match ($type) {
                ReportLogType::DEBUG => Log::debug($message, $context),
                ReportLogType::ERROR => Log::error($message, $context),
                ReportLogType::ALERT => Log::alert($message, $context),
                ReportLogType::INFO => Log::info($message, $context),
                ReportLogType::WARNING => Log::warning($message, $context),
                default => Log::info($message, $context),
            };

            return true;
        } catch (\Throwable $th) {
            return false;
        }
    }
}
