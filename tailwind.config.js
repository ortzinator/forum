const defaultTheme = require('tailwindcss/defaultTheme');

module.exports = {
    purge: [
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php'
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Nunito', ...defaultTheme.fontFamily.sans]
            }
        }
    },

    variants: {
        extend: {
            opacity: ['disabled'],
            padding: ['first']
        }
    },

    plugins: [require('@tailwindcss/forms'), require('@tailwindcss/typography')]
};
