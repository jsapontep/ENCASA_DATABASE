<?php
/**
 * Funciones auxiliares globales para logging
 */

if (!function_exists('log_error')) {
    function log_error($message, $context = []) {
        \App\Helpers\Logger::getInstance()->error($message, $context);
    }
}

if (!function_exists('log_warning')) {
    function log_warning($message, $context = []) {
        \App\Helpers\Logger::getInstance()->warning($message, $context);
    }
}

if (!function_exists('log_info')) {
    function log_info($message, $context = []) {
        \App\Helpers\Logger::getInstance()->info($message, $context);
    }
}

if (!function_exists('log_debug')) {
    function log_debug($message, $context = []) {
        \App\Helpers\Logger::getInstance()->debug($message, $context);
    }
}