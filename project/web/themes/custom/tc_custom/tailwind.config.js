/** @type {import('tailwindcss').Config} */
module.exports = {
  content: ['./{templates,components}/**/*.{js,twig}'],
  theme: {
    extend: {},
    fontFamily: {
      'tilt-neon': '"Tilt Neon", sans-serif;'
    }
  },
  plugins: [],
}

