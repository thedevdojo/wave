<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wave Installer</title>
    <style><?= wave_install_base_styles(); ?></style>
    <script>
        function scrollToBottom() {
            const container = document.getElementById('container');
            container.scrollTop = container.scrollHeight;
        }

        function addParagraph(content) {
            const container = document.getElementById('container');
            const paragraph = document.createElement('p');
            paragraph.innerHTML = content;
            container.appendChild(paragraph);
            scrollToBottom();
        }
    </script>
</head>
<body class="relative flex flex-col items-start justify-start w-screen h-screen overflow-hidden bg-black">
    <p class="block fixed top-0 z-30 pt-4 pb-3 pl-5 w-full font-sans text-xs font-bold text-white bg-black bg-opacity-20 backdrop-blur-sm">
        Installing Composer dependencies for Wave
        <span class="fixed left-0 bottom-0 w-screen" style="height:1px;background:rgba(255,255,255,.1)"></span>
    </p>
    <div class="fixed inset-0 z-10 flex items-center justify-center w-screen h-screen">
        <?= wave_install_wave_logo_svg(); ?>
    </div>
    <div class="relative z-20 w-full h-screen p-5 pt-16 overflow-y-scroll font-mono text-xs text-white rounded-xl" id="container">
        <?php
            $returnVar = 0;

            while (! feof($process)) {
                $output = fread($process, 4096);

                if ($output === false || $output === '') {
                    usleep(100000);
                    continue;
                }

                echo '<script>addParagraph('.json_encode(nl2br(htmlspecialchars($output))).');</script>';
                flush();
            }

            $returnVar = pclose($process);

            if ($returnVar !== 0) {
                echo "<p class='text-red-500'>Composer install failed. Check PHP 8.2+, Composer, and network access to GitHub (produktive/auth).</p>";
                exit(1);
            }
        ?>
        <script>window.location.href = '/install';</script>
    </div>
</body>
</html>
