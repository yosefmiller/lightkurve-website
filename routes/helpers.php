<?php
defined('MAX_PROCESS_MINUTES')  or define('MAX_PROCESS_MINUTES', 15);
defined('MAX_OUTPUTS_DAYS')     or define('MAX_OUTPUTS_DAYS', 7);

function cleanupCalculations () {
    /* Get list of running calculations and their info */
    $running_calculations = getRunningCalcInfo();
    foreach ($running_calculations as $info) {
        $pid     = $info["pid"];
        $calc_id = $info["calc_id"];
        $age     = $info["age"];
        
        /* Terminate long-running processes */
        if ($age < MAX_PROCESS_MINUTES * 60) continue;
        exec("kill $pid");
        echo "\n\tKilled calculation with ID: $calc_id";
        
        /* Then notify the user using logfile */
        $filename = "outputs/".$calc_id.".log";
        $msg = "\n--------";
        $msg .= "\nCALCULATION AUTOMATICALLY TERMINATED";
        $msg .= "\nafter running for " . round($age/60) . " minutes";
        $msg .= "\n--------";
        if ( file_exists($filename) ) {
            file_put_contents($filename, $msg, FILE_APPEND | LOCK_EX);
            echo " and modified the log file.";
        }
    }
    
    /* Delete old calculation files */
    foreach (glob("outputs/*") as $filename) {
        /* Skip directories */
        if (is_dir($filename)) continue;
        
        /* Get age of file since last modification */
        $age = time() - filemtime($filename);
        
        /* Delete old files */
        if ($age < MAX_OUTPUTS_DAYS * 24 * 60 * 60) continue;
        if (!unlink($filename)) continue;
        echo "\n\tDeleted filename: $filename (after ". round($age/60) ." minutes)";
    }
}

function getRunningCalcInfo () {
    /* Return a list of running processes */
    exec("pgrep -a -f tracking_id", $running);
    
    /* Get info about each running process */
    $output = [];
    foreach ($running as $process) {
        /* Isolate information */
        $pattern = "/(\d+).+tracking_id.:.(\w+)/";
        preg_match($pattern, $process, $process_info);
        
        /* Skip empty matches */
        if (!count($process_info)) continue;
        
        /* Isolate info */
        $pid      = (int) $process_info[1];
        $calculation_id = $process_info[2];
        
        /* Find process age in seconds */
        exec("ps -o etimes= -p ".$pid, $process_age);
        if (!count($process_age)) continue;
        $process_age = (int) $process_age[0];
        
        $output[] = [
            "pid"     => $pid,
            "calc_id" => $calculation_id,
            "age"     => $process_age
        ];
    }
    
    return $output;
}

/* Run cleanup if called from command-line: */
if (PHP_SAPI === "cli") {
    echo "\nCurrent time: " . date(DATE_COOKIE);
    cleanupCalculations();
}