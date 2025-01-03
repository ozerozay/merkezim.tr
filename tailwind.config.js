/** @type {import("tailwindcss").Config} */
export default {
    content: [
        // You will probably also need these lines
        "./resources/**/**/*.blade.php",
        "./resources/**/**/*.js",
        "./app/View/Components/**/**/*.php",
        "./app/Livewire/**/**/*.php",


        // Add mary
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

    // Add daisyUI
    plugins: [require("daisyui")],
    daisyui: {
        themes: [

            {
                morTema: {
                    primary: "#6b4aa1",
                    "primary-focus": "#55337c",
                    "primary-content": "#ffffff",

                    secondary: "#8566b1",
                    "secondary-focus": "#6b4aa1",
                    "secondary-content": "#ffffff",

                    accent: "#a065ff",
                    "accent-focus": "#8d4ed0",
                    "accent-content": "#ffffff",

                    neutral: "#f6f4fc",
                    "neutral-focus": "#eae6f7",
                    "neutral-content": "#4b367c",

                    "base-100": "#ffffff",
                    "base-200": "#f6f4fc",
                    "base-300": "#eae6f7",
                    "base-content": "#4b367c",

                    // Renk + Renk İçeriği (Metin Rengi)
                    info: "#3b82f6",
                    "info-content": "#ffffff",

                    success: "#16a34a",
                    "success-content": "#ffffff",

                    warning: "#facc15",
                    "warning-content": "#4b367c",
                    // Sarı arka plan üstünde koyu mor (#4b367c) veya siyah (#000000) gibi bir renk seçebilirsiniz

                    error: "#dc2626",
                    "error-content": "#ffffff"
                }
            },
            {
                morTemaDark: {
                    primary: "#6b4aa1",
                    "primary-focus": "#55337c",
                    "primary-content": "#ffffff",

                    secondary: "#8566b1",
                    "secondary-focus": "#6b4aa1",
                    "secondary-content": "#ffffff",

                    accent: "#a065ff",
                    "accent-focus": "#8d4ed0",
                    "accent-content": "#ffffff",

                    neutral: "#2d2740",
                    "neutral-focus": "#1e1b29",
                    "neutral-content": "#e0d7ff",

                    "base-100": "#1e1b29",
                    "base-200": "#2d2740",
                    "base-300": "#3e3659",
                    "base-content": "#e0d7ff",

                    // Dark Mode Renk + İçerik
                    info: "#3b82f6",
                    "info-content": "#ffffff",

                    success: "#16a34a",
                    "success-content": "#ffffff",

                    warning: "#facc15",
                    "warning-content": "#e0d7ff",
                    // Karanlık modda sarı arka plan üstünde açık renkte yazı

                    error: "#dc2626",
                    "error-content": "#ffffff"
                }
            }


        ]
    },
    darkMode: "class"
};
