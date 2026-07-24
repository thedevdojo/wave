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
    wave_install_render_error('Unsupported operating system. Run `composer install` manually, then reload this page.');
}

wave_install_copy_env($projectRoot);

if (! chdir($projectRoot)) {
    wave_install_render_error('Failed to change directory to the project root.');
}

if (getcwd() !== $projectRoot) {
    wave_install_render_error('Current working directory is not the project root.');
}

$phpPath = wave_install_resolve_php_binary($projectRoot);
$composerPath = wave_install_resolve_composer_binary($projectRoot, $phpPath);
$command = wave_install_build_command($projectRoot, $phpPath, $composerPath, $os);

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
        wave_install_render_error('Failed to start the Composer install process on Windows.');
    }

    require_once __DIR__.'/windows.php';
    exit(1);
}

$process = popen($command, 'r');

if (! is_resource($process)) {
    wave_install_render_error('Failed to start the Composer install process.');
}

require_once __DIR__.'/mac.php';
exit(1);
