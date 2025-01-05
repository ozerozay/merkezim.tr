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
                    primary: "#2563eb", // Güven veren mavi
                    "primary-focus": "#1d4ed8", // Daha koyu mavi
                    "primary-content": "#ffffff", // Beyaz yazı

                    secondary: "#6b7280", // Gri tonlar
                    "secondary-focus": "#4b5563",
                    "secondary-content": "#ffffff",

                    accent: "#10b981", // Canlı yeşil
                    "accent-focus": "#059669",
                    "accent-content": "#ffffff",

                    neutral: "#f3f4f6", // Açık gri (nötr)
                    "neutral-focus": "#e5e7eb",
                    "neutral-content": "#1f2937", // Koyu metin

                    "base-100": "#ffffff", // Beyaz taban
                    "base-200": "#f3f4f6", // Daha açık gri taban
                    "base-300": "#e5e7eb", // Hafif gri
                    "base-content": "#111827", // Koyu metin rengi

                    info: "#2563eb", // Mavi
                    "info-content": "#ffffff",

                    success: "#10b981", // Yeşil
                    "success-content": "#ffffff",

                    warning: "#f59e0b", // Sarı
                    "warning-content": "#111827", // Koyu metin

                    error: "#ef4444", // Kırmızı
                    "error-content": "#ffffff"
                }
            },
            {
                morTemaDark: {
                    primary: "#2563eb", // Güven veren mavi
                    "primary-focus": "#1d4ed8",
                    "primary-content": "#ffffff",

                    secondary: "#6b7280", // Gri tonlar
                    "secondary-focus": "#4b5563",
                    "secondary-content": "#ffffff",

                    accent: "#10b981", // Canlı yeşil
                    "accent-focus": "#059669",
                    "accent-content": "#ffffff",

                    neutral: "#1f2937", // Koyu gri (nötr arka plan)
                    "neutral-focus": "#111827",
                    "neutral-content": "#e5e7eb", // Açık metin rengi

                    "base-100": "#111827", // Siyah-gri taban
                    "base-200": "#1f2937", // Koyu gri
                    "base-300": "#374151", // Daha koyu gri
                    "base-content": "#e5e7eb", // Açık metin rengi

                    info: "#3b82f6", // Daha açık mavi
                    "info-content": "#ffffff",

                    success: "#22c55e", // Parlak yeşil
                    "success-content": "#ffffff",

                    warning: "#f59e0b", // Sarı
                    "warning-content": "#e5e7eb",

                    error: "#dc2626", // Kırmızı
                    "error-content": "#ffffff"
                }
            }
        ]
    },
    darkMode: "class"
};
