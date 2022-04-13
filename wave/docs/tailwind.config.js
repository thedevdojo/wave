module.exports = {
    purge: [
        './**/*.php',
        './*.php',
        './assets/**/*.scss',
        './assets/**/*.js',
    ],
    darkMode: false, // or 'media' or 'class'
    theme: {
        extend: {

            colors: {
                'wave': {
                    50: '#F2F8FF',
                    100: '#E6F0FF',
                    200: '#BFDAFF',
                    300: '#99C3FF',
                    400: '#4D96FF',
                    500: '#0069FF',
                    600: '#005FE6',
                    700: '#003F99',
                    800: '#002F73',
                    900: '#00204D',
                },
            }

        },
    },
    variants: {
        extend: {},
    },
    plugins: [
        require('@tailwindcss/forms'),
        require('@tailwindcss/typography')
    ]
}
