<?php

// Define the correct project root path directly
$projectRoot = dirname(getcwd());

// Define the path to the autoload file
$autoloadPath = $projectRoot.'/vendor/autoload.php';

$process = null;

$autoload_exists = false;

$os = null;

function copyEnv($projectRoot)
{
    $envFile = $projectRoot.'/.env';
    $envExample = $projectRoot.'/.env.example';

    // Check if the .env file exists, if not, copy .env.example to .env
    if (! file_exists($envFile)) {
        copy($envExample, $envFile);
    }
}

function redirectIfNotHomepage()
{
    $protocol = (! empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? 'https://' : 'http://';
    $domainName = $_SERVER['HTTP_HOST'];
    $urlPath = $_SERVER['REQUEST_URI'];
    if ($urlPath !== '/') {
        header('Location: '.$protocol.$domainName);
        exit;
    }

    return $protocol.$domainName;
}

function getOperatingSystem()
{
    $os = PHP_OS;

    if (stripos($os, 'DAR') !== false || stripos($os, 'MAC') !== false) {
        return 'Mac';
    } elseif (stripos($os, 'WIN') !== false) {
        return 'Windows';
    } elseif (stripos($os, 'LINUX') !== false) {
        return 'Linux';
    } else {
        return 'Unknown';
    }
}

function convertSlashes($path)
{
    return str_replace('/', '\\', $path);
}

// Check if the autoload file exists
if (! file_exists($autoloadPath)) {

    redirectIfNotHomepage();
    $os = getOperatingSystem();
    copyEnv($projectRoot);

    // Change to the project root directory
    if (! chdir($projectRoot)) {
        http_response_code(500);
        echo 'Failed to change directory to project root.';
        exit(1);
    }

    // Verify the current working directory
    $currentDir = getcwd();
    if ($currentDir !== $projectRoot) {
        http_response_code(500);
        echo "Current working directory is not the project root. Current directory: $currentDir";
        exit(1);
    }

    // Get the current PHP binary path
    $currentPhpBinaryPath = PHP_BINARY;

    // Normalize the path to use forward slashes
    $normalizedPhpBinaryPath = str_replace('\\', '/', $currentPhpBinaryPath);

    $binDir = preg_replace('/\/bin\/.+$/', '/bin', $normalizedPhpBinaryPath);

    // Append 'php' to the directory path, using the correct path separator
    $phpPath = dirname($normalizedPhpBinaryPath).'/php';

    $phpPath = ($os == 'Windows') ? convertSlashes($phpPath).'.exe' : $phpPath;

    // Check if the PHP binary exists
    if (! file_exists($phpPath)) {
        http_response_code(500);
        echo "PHP binary not found at specified path: $phpPath. Please ensure PHP is installed.";
        exit(1);
    }

    $composerPath = $binDir.'/composer';
    $composerPath = ($os == 'Windows') ? convertSlashes($composerPath).'.phar' : $composerPath;

    // Check if the Composer binary exists
    if (! file_exists($composerPath)) {
        http_response_code(500);
        echo "Composer binary not found at specified path: $composerPath. Please ensure Composer is installed.";
        exit(1);
    }

    $commandSeparator = ($os == 'Windows') ? '&' : '&&';
    // Run composer install with explicit working directory
    $command = "cd \"$projectRoot\" $commandSeparator \"$phpPath\" \"$composerPath\" install 2>&1";
    // If we are on a windows machine we need to convert the Paths \ to / in the command
    $command = ($os == 'Windows') ? convertSlashes($command) : $command;

    if ($os == 'Windows') {
        $batFilePath = $projectRoot.'\public\composerinstall.bat';
        $combinedOutputFile = $projectRoot.'\public\combined_output.txt';
        $debugFile = $projectRoot.'\public\debug.txt';

        // Write the command to the batch file with debugging information
        $batchContent = <<<EOT
    @echo off
    echo Starting Composer Install > "$combinedOutputFile"
    echo Command: "$phpPath" "$composerPath" install >> "$combinedOutputFile"
    "$phpPath" "$composerPath" install >> "$combinedOutputFile" 2>&1
    echo Completed Composer Install >> "$combinedOutputFile"
    EOT;
        file_put_contents($batFilePath, $batchContent);

        // Log the batch file creation and content for debugging
        file_put_contents($debugFile, "Batch file created at: $batFilePath\n");
        file_put_contents($debugFile, "Batch file content:\n$batchContent\n", FILE_APPEND);

        function runCmd($cmd)
        {
            $externalProcess = popen($cmd, 'r');

            return $externalProcess;
        }

        // Start the batch file
        $process = runCmd("start /B cmd /C $batFilePath");

        // Check if the batch file was started successfully
        if (! $process) {
            http_response_code(500);
            echo 'Failed to start the batch file process.';
            exit(1);
        }

        // Log the batch file execution for debugging
        file_put_contents($debugFile, "Batch file execution started.\n", FILE_APPEND);
    } else {
        $process = popen($command, 'r');
    }

    if ($os == 'Windows') {
        require_once __DIR__.'/windows.php';
    } elseif ($os == 'Mac') {
        require_once __DIR__.'/mac.php';
    } elseif ($os == 'Linux') {
        // We can use the mac installation for linux machines
        require_once __DIR__.'/mac.php';
    } else {
        exit('OS not supported, Please run composer install and come back to this page.');
    }

    exit(1);
}
