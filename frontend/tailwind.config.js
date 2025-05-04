/** @type {import('tailwindcss').Config} */
export default {
  content: ['./index.html', './src/**/*.{vue,js,ts,jsx,tsx}'],
  theme: {
    extend: {
      colors: {
        'clash': {
          'blue': '#1CA5F7',
          'red': '#EC133D',
          'gold': '#FFCC00',
          'dark': '#191919',
          'light': '#F9F9F9'
        }
      },
      fontFamily: {
        'clash': ['Supercell-Magic', 'sans-serif']
      }
    },
  },
  plugins: [],
}
