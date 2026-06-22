/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    './resources/**/*.blade.php',
    './resources/**/*.js',
  ],
  theme: {
    extend: {
      colors: {
        primary: {
          50:  '#eef2ff',
          100: '#e0e7ff',
          200: '#c7d2fe',
          300: '#a5b4fc',
          400: '#818cf8',
          500: '#1a3264',
          600: '#162a54',
          700: '#122244',
          800: '#0e1b36',
          900: '#0a1428',
        },
        navy: '#1a3264',
        'navy-dark': '#0e1b36',
        'navy-light': '#253d7a',
        orange: '#f97316',
        'orange-dark': '#ea580c',
        solar: '#f59e0b',
      },
      fontFamily: {
        sans: ['Poppins', 'ui-sans-serif', 'system-ui'],
      },
      boxShadow: {
        'card': '0 2px 8px rgba(0,0,0,0.08)',
        'card-hover': '0 8px 24px rgba(0,0,0,0.12)',
      }
    },
  },
  plugins: [
    require('@tailwindcss/forms'),
    require('@tailwindcss/typography'),
  ],
}
