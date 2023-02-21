module.exports = {
    content: [
        './**/*.php',
        './*.php',
        './assets/**/*.scss',
        './assets/**/*.js',
    ],
    theme: {
        extend: {
            rotate: {
                '-1': '-1deg',
                '-2': '-2deg',
                '-3': '-3deg',
                '1': '1',
                '2': '2deg',
                '3': '3deg',
            },
            borderRadius: {
                'xl': '0.8rem',
                'xxl': '1rem',
            },
            height: {
                '1/2': '0.125rem',
                '2/3': '0.1875rem',
            },
            maxHeight: {
                '16': '16rem',
                '20': '20rem',
                '24': '24rem',
                '32': '32rem',
            },
            inset: {
                '1/2': '50%',
            },
            width: {
                '96': '24rem',
                '104': '26rem',
                '128': '32rem',
            },
            transitionDelay: {
                '450': '450ms',
            },
            colors: {
                'wave': {
                    50: '#001219',
                    100: '#005F73',
                    200: '#0A9396',
                    300: '#94D2BD',
                    400: '#E9D8A6',
                    500: '#EE9B00',
                    600: '#CA6702',
                    700: '#BB3E03',
                    800: '#AE2012',
                    900: '#9B2226',
                },
            }
        }
    },
    plugins: [
        require('@tailwindcss/forms'),
        require('@tailwindcss/typography')
    ]
}
