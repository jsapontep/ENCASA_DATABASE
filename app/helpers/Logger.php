<?php
namespace App\Helpers;

class Logger {
    private static $instance = null;
    private $logPath;
    private $logLevels = ['ERROR', 'WARNING', 'INFO', 'DEBUG'];
    private $currentLevel = 3; // Por defecto: DEBUG (todos los niveles)

    private function __construct() {
        $this->logPath = dirname(__DIR__) . '/logs/';
        if (!is_dir($this->logPath)) {
            mkdir($this->logPath, 0755, true);
        }
        $env = getenv('APP_ENV') ?: 'development';
        if ($env === 'production') {
            $this->currentLevel = 1; // Solo ERROR y WARNING
        }
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function error($message, $context = []) { $this->log(0, $message, $context); }
    public function warning($message, $context = []) { $this->log(1, $message, $context); }
    public function info($message, $context = []) { $this->log(2, $message, $context); }
    public function debug($message, $context = []) { $this->log(3, $message, $context); }

    private function log($level, $message, $context = []) {
        if ($level > $this->currentLevel) return;

        $date = date('Y-m-d H:i:s');
        $logLevel = $this->logLevels[$level];
        
        $backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2);
        $caller = isset($backtrace[1]['class']) ? $backtrace[1]['class'] . '::' . $backtrace[1]['function'] : 'unknown';
        
        $contextStr = !empty($context) ? ' ' . json_encode($context, JSON_UNESCAPED_UNICODE) : '';
        $logMessage = "[$date] [$logLevel] [$caller] $message$contextStr" . PHP_EOL;
        
        $filename = $this->logPath . date('Y-m-d') . '.log';
        file_put_contents($filename, $logMessage, FILE_APPEND);
    }
}