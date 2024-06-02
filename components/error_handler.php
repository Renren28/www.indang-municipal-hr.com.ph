<?php
// Turn off error reporting for display
ini_set('display_errors', 0);
error_reporting(0);

// Set a custom error handler to log errors
set_error_handler(function ($errno, $errstr, $errfile, $errline) {
    // You can log errors to a file here
    error_log("Error: $errstr in $errfile on line $errline");
});
?>