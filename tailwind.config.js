const defaultTheme = require('tailwindcss/defaultTheme');

module.exports = {
    purge: [
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php'
    ],

    variants: {
        extend: {
            opacity: ['disabled'],
            padding: ['first']
        }
    },

    plugins: [require('@tailwindcss/forms'), require('@tailwindcss/typography')]
};
