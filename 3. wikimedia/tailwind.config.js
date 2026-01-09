/** @type {import('tailwindcss').Config} */
module.exports = {
  darkMode: 'selector',
  content: [
    "./assets/**/*.js",
    "./templates/**/*.html.twig",
    "./vendor/symfony/twig-bridge/Resources/views/Form/*.html.twig",
  ],
  theme: {
    extend: {
      colors: {
        'dark-background': '#0e0e11',
        'light-background': '#e4e4e7',
        'dark-text': '#c8ccd0',
        'light-text': '#27272a',
      },
    },
  },
  plugins: [],
}
