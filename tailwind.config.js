/** @type {import('tailwindcss').Config} */
module.exports = {
  content: ["./**/*.{php,js}", "*.php", "./*.php", "./**/*.php"],
  theme: {
    container: {
      center: true,
      padding: "2rem",
      screens: {
        "2xl": "1200px",
      },
    },
    extend: {},
  },
  plugins: [],
};
