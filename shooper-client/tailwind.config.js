module.exports = {
  content: ['./public/**/*.html', './src/**/*.{js,jsx,ts,tsx,vue}'],
  theme: {
    extend: {
      fontFamily: {
        montserrat: ['Montserrat', 'sans-serif']
      }
    }
  },
  variants: {
    extend: {
      fontFamily: ['autofill'],
      borderColor: ['autofill'],
      shadowFill: ['autofill'],
      textFill: ['autofill']
    }
  },
  plugins: [require('tailwindcss-autofill')],
  important: true
}
