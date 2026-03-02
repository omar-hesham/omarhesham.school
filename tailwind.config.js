/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './resources/**/*.blade.php',
        './resources/**/*.js',
    ],
    theme: {
        extend: {
            colors: {
                primary: {
                    DEFAULT: '#1B4332',
                    light:   '#2D6A4F',
                    dark:    '#0A1F14',
                },
                gold: {
                    DEFAULT: '#D4AF37',
                    light:   '#F0C040',
                },
            },
            fontFamily: {
                sans:   ['Inter', 'sans-serif'],
                arabic: ['Amiri', 'serif'],
            },
        },
    },
    plugins: [
        require('@tailwindcss/forms'),
    ],
}
