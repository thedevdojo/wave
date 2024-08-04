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
<body class="flex justify-center items-center py-20 h-screen bg-white">
    <div class="p-10 max-w-2xl h-full max-h-full bg-gray-100 rounded-xl">
        <div class="overflow-scroll w-full h-full">
    <?php

        // Check if the process was opened successfully
    if (is_resource($process)) {
        // Stream the output in real-time
        while (!feof($process)) {
            echo '<p class="block font-mono">' . fread($process, 4096) . '</p>';
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
