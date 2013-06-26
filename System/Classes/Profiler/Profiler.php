<?php

class Profiler {

    private static $time_start = null;
    private static $time_end = null;
    protected static $requests = array();
    protected static $instance;

    protected function __construct() {

    }

    
    public static function getInstance()
    {
        if (!(self::$instance instanceof Profiler) or self::$instance == null)
            self::$instance = new Profiler();
        return self::$instance;
    }

    public static function startTimer() { self::$time_start = microtime(true); }

    public static function endTimer() { self::$time_end = microtime(true); }

    public static function getElapsedTime() { return self::$time_end-self::$time_start; }

    public static function addRequest($string)
    {
        self::$requests[] = $string;
    }

    public static function getRequestCount() { return count(self::$requests); }

    public static function getRequests() { return self::$requests; }

}
