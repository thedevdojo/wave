import preset from '../../../../vendor/filament/filament/tailwind.config.preset'

export default {
    presets: [preset],
    content: [
        './app/Filament/**/*.php',
        './resources/views/filament/**/*.blade.php',
        './vendor/filament/**/*.blade.php',
        './resources/views/**/*.blade.php',
        './wave/resources/views/**/*.blade.php',
        './resources/views/filament/pages/*.blade.php',
        './app/Http/Middleware/WaveEditTab.php'
    ],
}
