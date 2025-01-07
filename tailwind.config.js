/** @type {import("tailwindcss").Config} */
export default {
    content: [
        "./resources/**/**/*.blade.php",
        "./resources/**/**/*.js",
        "./app/View/Components/**/**/*.php",
        "./app/Livewire/**/**/*.php",
        "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
        "./vendor/robsontenorio/mary/src/View/Components/**/*.php",
        "./vendor/wire-elements/pro/config/wire-elements-pro.php",
        "./vendor/wire-elements/pro/**/*.blade.php",
        "./vendor/namu/wirechat/resources/views/**/*.blade.php",
        "./vendor/namu/wirechat/src/Livewire/**/*.php"
    ],
    theme: {
        extend: {}
    },
    safelist: [
        {
            pattern: /max-w-(sm|md|lg|xl|2xl|3xl|4xl|5xl|6xl|7xl)/,
            variants: ["sm", "md", "lg", "xl", "2xl"]
        }
    ],
    plugins: [require("daisyui")],
    daisyui: {
        themes: ["light", "dark"]
    },
    darkMode: "class"
}