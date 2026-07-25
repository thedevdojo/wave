<?php

require_once __DIR__.'/helpers.php';

$projectRoot = wave_install_project_root();
$autoloadPath = $projectRoot.'/vendor/autoload.php';

if (file_exists($autoloadPath)) {
    return;
}

wave_install_redirect_home_if_needed();

$os = wave_install_os();

if ($os === 'Unknown') {
    wave_install_render_error('OS not supported. Please run composer install and come back to this page.');
}

wave_install_copy_env($projectRoot);

if (! chdir($projectRoot)) {
    wave_install_render_error('Failed to change directory to project root.');
}

if (getcwd() !== $projectRoot) {
    wave_install_render_error('Current working directory is not the project root. Current directory: '.getcwd());
}

$normalizedPhpBinaryPath = str_replace('\\', '/', PHP_BINARY);
$binDir = preg_replace('/\/bin\/.+$/', '/bin', $normalizedPhpBinaryPath);
$phpPath = dirname($normalizedPhpBinaryPath).'/php';
$phpPath = $os === 'Windows' ? wave_install_convert_slashes($phpPath).'.exe' : $phpPath;

if (! file_exists($phpPath)) {
    wave_install_render_error("PHP binary not found at specified path: {$phpPath}. Please ensure PHP is installed.");
}

$composerPath = $binDir.'/composer';
$composerPath = $os === 'Windows' ? wave_install_convert_slashes($composerPath).'.phar' : $composerPath;

if (! file_exists($composerPath)) {
    wave_install_render_error("Composer binary not found at specified path: {$composerPath}. Please ensure Composer is installed.");
}

$commandSeparator = $os === 'Windows' ? '&' : '&&';
$command = 'cd '.escapeshellarg($projectRoot)." {$commandSeparator} ".escapeshellarg($phpPath).' '.escapeshellarg($composerPath).' install 2>&1';
$command = $os === 'Windows' ? wave_install_convert_slashes($command) : $command;

if ($os === 'Windows') {
    $batFilePath = $projectRoot.'\public\composerinstall.bat';
    $combinedOutputFile = $projectRoot.'\public\combined_output.txt';
    $debugFile = $projectRoot.'\public\debug.txt';

    $batchContent = <<<EOT
@echo off
echo Starting Composer Install > "$combinedOutputFile"
echo Command: "$phpPath" "$composerPath" install >> "$combinedOutputFile"
"$phpPath" "$composerPath" install >> "$combinedOutputFile" 2>&1
echo Completed Composer Install >> "$combinedOutputFile"
EOT;

    file_put_contents($batFilePath, $batchContent);
    file_put_contents($debugFile, "Batch file created at: $batFilePath\nBatch file content:\n$batchContent\n");

    $process = popen("start /B cmd /C $batFilePath", 'r');

    if (! $process) {
        wave_install_render_error('Failed to start the batch file process.');
    }

    file_put_contents($debugFile, "Batch file execution started.\n", FILE_APPEND);

    require_once __DIR__.'/windows.php';
    exit(1);
}

$process = popen($command, 'r');

if ($os === 'Windows') {
    require_once __DIR__.'/windows.php';
} elseif ($os === 'Mac') {
    require_once __DIR__.'/mac.php';
} elseif ($os === 'Linux') {
    require_once __DIR__.'/mac.php';
} else {
    wave_install_render_error('OS not supported. Please run composer install and come back to this page.');
}

exit(1);
