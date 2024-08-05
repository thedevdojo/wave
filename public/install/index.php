<?php

// Define the correct project root path directly
$projectRoot = '/Users/tonylea/Sites/wave3new';

$projectRoot = dirname(getcwd());

// Define the path to the autoload file
$autoloadPath = $projectRoot . '/vendor/autoload.php';

$process = null;

$autload_exists = false;

// Check if the autoload file exists
if (!file_exists($autoloadPath)) {

    // Change to the project root directory
    if (!chdir($projectRoot)) {
        http_response_code(500);
        echo "Failed to change directory to project root.";
        exit(1);
    }

    // Verify the current working directory
    $currentDir = getcwd();
    if ($currentDir !== $projectRoot) {
        http_response_code(500);
        echo "Current working directory is not the project root. Current directory: $currentDir";
        exit(1);
    }

    // Manually set the PATH environment variable to include the directory where PHP is installed
    putenv('PATH=' . getenv('PATH') . ':"/Users/tonylea/Library/Application Support/Herd/bin/"');

    // Define the PHP binary path directly
    $phpBinary = '/Users/tonylea/Library/Application Support/Herd/bin/php';

    // Check if the PHP binary exists
    if (!file_exists($phpBinary)) {
        http_response_code(500);
        echo "PHP binary not found at specified path: $phpBinary. Please ensure PHP is installed.";
        exit(1);
    }

    // Define the Composer path
    $composerPath = '/usr/local/bin/composer';

    // Check if the Composer binary exists
    if (!file_exists($composerPath)) {
        http_response_code(500);
        echo "Composer binary not found at specified path: $composerPath. Please ensure Composer is installed.";
        exit(1);
    }

    // Run composer install with explicit working directory
    $command = "cd \"$projectRoot\" && \"$phpBinary\" \"$composerPath\" install 2>&1";
    $process = popen($command, 'r');

    ?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Installer</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="flex flex-col justify-center items-center py-20 h-screen bg-white">
    <div class="flex justify-center items-center mb-10 w-full">
        <svg class="w-9 h-9" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 99 100" height="100" fill="none"><path fill="currentColor" d="M53.878 92.36V75.02h2.046c2.66 0 5.296-.532 7.753-1.563a20.201 20.201 0 0 0 6.566-4.448 20.432 20.432 0 0 0 4.403-6.633 20.598 20.598 0 0 0 1.549-7.831c0-4.822.024-9.456.024-14.257h2.858l-3.474-6.077-.066-.115-3.648-6.381-1.57 2.746a40.199 40.199 0 0 1 4.843 3.4 40.957 40.957 0 0 0-4.87-3.409l-3.94 6.894.024.015-1.781 3.117h3.713v14.067a12.577 12.577 0 0 1-.948 4.782 12.477 12.477 0 0 1-2.693 4.05 12.338 12.338 0 0 1-4.008 2.72c-1.5.63-3.11.956-4.735.957h-2.046v-34c1.193.142 2.376.354 3.545.634l-5.01-8.766-1.998-3.494-.48-.838-1.238 2.166-1.238 2.166.383-.021-.384.023-5.014 8.772a31.723 31.723 0 0 1 3.548-.643v34.001h-2.045a12.238 12.238 0 0 1-4.735-.958 12.34 12.34 0 0 1-4.009-2.72 12.475 12.475 0 0 1-2.692-4.049 12.575 12.575 0 0 1-.949-4.782V40.477h2.95l-1.583-2.77-3.944-6.9-1.767-3.092-3.648 6.381-.87 1.521v.001l-1.126 1.094c.368-.372.744-.737 1.127-1.094l-2.778 4.86h3.754v14.067c0 2.688.526 5.349 1.548 7.831a20.43 20.43 0 0 0 4.403 6.633 20.196 20.196 0 0 0 6.566 4.448 20.03 20.03 0 0 0 7.753 1.563h2.045v24.855c1.159.082 2.328.124 3.508.125a48.92 48.92 0 0 0 18.948-3.795A49.32 49.32 0 0 0 84.5 85.355a49.882 49.882 0 0 0 10.742-16.216A50.282 50.282 0 0 0 99 50a50.285 50.285 0 0 0-3.757-19.14 49.884 49.884 0 0 0-10.742-16.215 49.318 49.318 0 0 0-16.053-10.85A48.918 48.918 0 0 0 49.5 0a48.92 48.92 0 0 0-18.948 3.794 49.32 49.32 0 0 0-16.054 10.85A49.883 49.883 0 0 0 3.757 30.861 50.285 50.285 0 0 0 0 50a50.282 50.282 0 0 0 3.757 19.139 49.881 49.881 0 0 0 10.741 16.216 49.291 49.291 0 0 0 23.666 13.327v-7.654a42.035 42.035 0 0 1-18.477-10.915 42.485 42.485 0 0 1-9.149-13.812A42.827 42.827 0 0 1 7.34 50a42.831 42.831 0 0 1 3.2-16.302 42.487 42.487 0 0 1 9.148-13.812 42.009 42.009 0 0 1 13.675-9.242A41.668 41.668 0 0 1 49.5 7.413a41.666 41.666 0 0 1 16.138 3.232 42.008 42.008 0 0 1 13.674 9.24 42.489 42.489 0 0 1 9.15 13.813A42.83 42.83 0 0 1 91.662 50a42.827 42.827 0 0 1-3.2 16.301 42.487 42.487 0 0 1-9.15 13.812C72.482 87.03 63.5 91.354 53.878 92.36Zm-2.513-67.49.273.012h-.006l-.267-.011Z"></path></svg>
    </div>
    <div class="flex flex-col items-center p-10 mx-auto w-full max-w-4xl max-h-[350px] h-[350px] bg-white rounded-2xl border shadow-sm border-gray-200">
        <h1 class="mb-5 text-2xl font-semibold text-black">Installing Dependencies</h1>
        <div class="overflow-y-scroll p-5 w-full h-full text-white bg-gray-900 rounded-xl">
    <?php

        // Check if the process was opened successfully
    if (is_resource($process)) {
        // Stream the output in real-time
        while (!feof($process)) {
            echo '<p class="block font-mono text-xs text-white">' . fread($process, 4096) . '</p>';
            flush(); // Ensure the output is sent to the browser immediately
        }

        // Close the process
        $returnVar = pclose($process);

        sleep(3);

        $envFile = $projectRoot . '/.env';
        $envExample = $projectRoot . '/.env.example'; 

        // Check if the .env file exists, if not, copy .env.example to .env
        if (!file_exists($envFile)) {
            copy($envExample, $envFile);
        }

        ?>

<script>
    window.location.href = '/install';
</script>
<?php

    die();
        // Check for errors
        if ($returnVar !== 0) {
            http_response_code(500);
            echo "Composer install failed. Please check the output above.";
            exit(1);
        }
    } else {
        http_response_code(500);
        echo "Failed to start composer install process.";
        exit(1);
    }
    ?>
    </div>
    </div>
</body>
</html>

<?php
}
