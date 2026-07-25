<script>
    (function () {
        if (window.__waveThemeSyncInitialized) {
            return;
        }

        window.__waveThemeSyncInitialized = true;

        function isLightOnlyLayout() {
            return document.body.hasAttribute('data-marketing-layout');
        }

        function setThemeCookie(theme) {
            document.cookie = 'theme=' + theme + ';path=/;max-age=31536000;SameSite=Lax';
        }

        window.syncThemeFromStorage = function () {
            if (isLightOnlyLayout()) {
                document.documentElement.classList.remove('dark');
                return;
            }

            if (typeof Storage === 'undefined') {
                return;
            }

            const theme = localStorage.getItem('theme') === 'dark' ? 'dark' : 'light';

            document.documentElement.classList.toggle('dark', theme === 'dark');
            setThemeCookie(theme);
        };

        window.syncThemeFromStorage();

        if (typeof MutationObserver !== 'undefined') {
            new MutationObserver(function () {
                if (isLightOnlyLayout()) {
                    if (document.documentElement.classList.contains('dark')) {
                        document.documentElement.classList.remove('dark');
                    }

                    return;
                }

                if (typeof Storage === 'undefined') {
                    return;
                }

                const shouldBeDark = localStorage.getItem('theme') === 'dark';
                const hasDark = document.documentElement.classList.contains('dark');

                if (shouldBeDark && !hasDark) {
                    document.documentElement.classList.add('dark');
                } else if (!shouldBeDark && hasDark) {
                    document.documentElement.classList.remove('dark');
                }
            }).observe(document.documentElement, {
                attributes: true,
                attributeFilter: ['class'],
            });
        }

        document.addEventListener('livewire:navigated', window.syncThemeFromStorage);
    })();
</script>
