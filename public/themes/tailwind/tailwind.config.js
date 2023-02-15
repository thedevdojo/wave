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
        }
    },
    plugins: [
        require('@tailwindcss/forms'),
        require('@tailwindcss/typography')
    ]
}
