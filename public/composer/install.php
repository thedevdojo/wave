<?php

// Define the correct project root path directly
$projectRoot = dirname(getcwd());

// Define the path to the autoload file
$autoloadPath = $projectRoot . '/vendor/autoload.php';

$process = null;

$autoload_exists = false;

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
    <style>
        #container {
            scroll-behavior: smooth;
        }
    </style>
    <script>
        function scrollToBottom() {
            const container = document.getElementById('container');
            container.scrollTop = container.scrollHeight;
        }

        function addParagraph(content) {
            const container = document.getElementById('container');
            const newParagraph = document.createElement('p');
            newParagraph.innerHTML = content;
            container.appendChild(newParagraph);
            scrollToBottom();
        }
    </script>
</head>
<body class="flex overflow-hidden relative flex-col justify-start items-start w-screen h-screen bg-black">
    <p class="block fixed top-0 pt-4 pb-3.5 pl-5 w-full font-sans text-xs font-bold text-white bg-black bg-opacity-20 backdrop-blur-sm">Installing Composer Dependencies<span class="absolute bottom-0 left-0 w-screen h-px bg-white opacity-10"></span></p>
    <div class="overflow-y-scroll p-5 pt-10 w-full h-full h-screen font-mono text-xs text-white rounded-xl" id="container">
            
        <?php

            // Check if the process was opened successfully
        if (is_resource($process)) {
            // Stream the output in real-time
            while (!feof($process)) {
                $output = fread($process, 4096);
                echo '<script>addParagraph(' . json_encode(nl2br(htmlspecialchars($output))) . ');</script>';
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
</body>
</html>

<?php
}