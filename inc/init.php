<?php

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// start session
session_start();

// init db connection
require_once 'db_connect.php';
require_once 'functions.php';
