<?php
$combinedOutputFile = $projectRoot.'\public\combined_output.txt';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wave Installer</title>
    <style><?= wave_install_base_styles(); ?></style>
    <script>
        let lastPosition = 0;

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

            if (content.includes('Completed Composer Install')) {
                window.location.href = '/install';
            }
        }

        function replaceLoadingText() {
            const container = document.getElementById('container');
            const loadingText = container.querySelector('p');

            if (loadingText && loadingText.innerText === 'Loading...') {
                container.removeChild(loadingText);
            }
        }

        async function fetchOutput() {
            const response = await fetch('combined_output.txt');

            if (! response.ok) {
                return;
            }

            const text = await response.text();
            const newContent = text.substring(lastPosition);

            if (! newContent) {
                return;
            }

            replaceLoadingText();
            newContent.split('\n').forEach(line => addParagraph(line));
            lastPosition = text.length;
        }

        setInterval(fetchOutput, 1000);
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
        <p>Loading...</p>
    </div>
</body>
</html>
