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
                shadcnTema: {
                    primary: "#09090B",
                    "primary-focus": "#18181B",
                    "primary-content": "#ffffff",
                    secondary: "#3F3F46",
                    "secondary-focus": "#52525B",
                    "secondary-content": "#ffffff",
                    accent: "#27272A",
                    "accent-focus": "#3F3F46",
                    "accent-content": "#ffffff",
                    "base-100": "#ffffff",
                    "base-200": "#fafafa",
                    "base-300": "#f4f4f5"
                }
            },
            {
                shadcnTemaDark: {
                    primary: "#18181B",
                    "primary-focus": "#27272A",
                    "primary-content": "#ffffff",
                    secondary: "#3F3F46",
                    "secondary-focus": "#52525B",
                    "secondary-content": "#ffffff",
                    accent: "#27272A",
                    "accent-focus": "#3F3F46",
                    "accent-content": "#ffffff",
                    "base-100": "#09090B",
                    "base-200": "#18181B",
                    "base-300": "#27272A"
                }
            },
            {
                morTema: {
                    primary: "#FF69B4",        // Pembe
                    "primary-focus": "#FF1493",
                    "primary-content": "#ffffff",
                    secondary: "#9370DB",       // Mor
                    "secondary-focus": "#8A2BE2",
                    "secondary-content": "#ffffff",
                    accent: "#FFB6C1",         
                    "accent-focus": "#FF69B4",
                    "accent-content": "#ffffff",
                    "base-100": "#ffffff",     
                    "base-200": "#faf5ff",     
                    "base-300": "#f3e8ff"
                }
            },
            {
                lavanderTema: {
                    primary: "#9B7EDE",        // Lavanta
                    "primary-focus": "#8A6FD3",
                    "primary-content": "#ffffff",
                    secondary: "#E6E6FA",      // Açık Lavanta
                    "secondary-focus": "#D8BFD8",
                    "secondary-content": "#2D1B69",
                    accent: "#DCD0FF",         
                    "accent-focus": "#9B7EDE",
                    "accent-content": "#2D1B69",
                    "base-100": "#ffffff",
                    "base-200": "#F8F7FF",
                    "base-300": "#F0EBFF"
                }
            },
            {
                mintTema: {
                    primary: "#98FF98",        // Nane Yeşili
                    "primary-focus": "#90EE90",
                    "primary-content": "#1F4B1F",
                    secondary: "#E0FFE0",      // Açık Nane
                    "secondary-focus": "#98FF98",
                    "secondary-content": "#1F4B1F",
                    accent: "#BDFFBD",         
                    "accent-focus": "#98FF98",
                    "accent-content": "#1F4B1F",
                    "base-100": "#ffffff",
                    "base-200": "#F0FFF0",
                    "base-300": "#E0FFE0"
                }
            },
            {
                peachTema: {
                    primary: "#FFDAB9",        // Şeftali
                    "primary-focus": "#FFB6A1",
                    "primary-content": "#4B2B20",
                    secondary: "#FFE4C4",      // Açık Şeftali
                    "secondary-focus": "#FFDAB9",
                    "secondary-content": "#4B2B20",
                    accent: "#FFE4E1",         
                    "accent-focus": "#FFDAB9",
                    "accent-content": "#4B2B20",
                    "base-100": "#ffffff",
                    "base-200": "#FFF5EE",
                    "base-300": "#FFE4E1"
                }
            },
            {
                skyTema: {
                    primary: "#87CEEB",        // Gök Mavisi
                    "primary-focus": "#00BFFF",
                    "primary-content": "#003366",
                    secondary: "#B0E2FF",      // Açık Mavi
                    "secondary-focus": "#87CEEB",
                    "secondary-content": "#003366",
                    accent: "#E0FFFF",         
                    "accent-focus": "#87CEEB",
                    "accent-content": "#003366",
                    "base-100": "#ffffff",
                    "base-200": "#F0FFFF",
                    "base-300": "#E0FFFF"
                }
            },
            {
                roseTema: {
                    primary: "#FFB6C1",        // Gül Pembesi
                    "primary-focus": "#FF69B4",
                    "primary-content": "#4B0082",
                    secondary: "#FFC0CB",      // Açık Pembe
                    "secondary-focus": "#FFB6C1",
                    "secondary-content": "#4B0082",
                    accent: "#FFE4E1",         
                    "accent-focus": "#FFB6C1",
                    "accent-content": "#4B0082",
                    "base-100": "#ffffff",
                    "base-200": "#FFF0F5",
                    "base-300": "#FFE4E1"
                }
            },
            {
                sunsetTema: {
                    primary: "#FFA07A",        // Turuncu
                    "primary-focus": "#FF7F50",
                    "primary-content": "#ffffff",
                    secondary: "#FFB6C1",      // Pembe
                    "secondary-focus": "#FFA07A",
                    "secondary-content": "#4B0082",
                    accent: "#FFE4E1",         
                    "accent-focus": "#FFA07A",
                    "accent-content": "#4B0082",
                    "base-100": "#ffffff",
                    "base-200": "#FFF5EE",
                    "base-300": "#FFE4E1"
                }
            },
            // Koyu Temalar
            {
                morTemaDark: {
                    primary: "#FF69B4",
                    "primary-focus": "#FF1493",
                    "primary-content": "#ffffff",
                    secondary: "#9370DB",
                    "secondary-focus": "#8A2BE2",
                    "secondary-content": "#ffffff",
                    accent: "#FFB6C1",
                    "accent-focus": "#FF69B4",
                    "accent-content": "#ffffff",
                    "base-100": "#0f172a",
                    "base-200": "#1e1b4b",
                    "base-300": "#312e81"
                }
            },
            {
                lavanderTemaDark: {
                    primary: "#9B7EDE",
                    "primary-focus": "#8A6FD3",
                    "primary-content": "#ffffff",
                    secondary: "#E6E6FA",
                    "secondary-focus": "#D8BFD8",
                    "secondary-content": "#ffffff",
                    accent: "#DCD0FF",
                    "accent-focus": "#9B7EDE",
                    "accent-content": "#ffffff",
                    "base-100": "#1a1625",
                    "base-200": "#2d1b69",
                    "base-300": "#3d2a80"
                }
            },
            {
                mintTemaDark: {
                    primary: "#98FF98",
                    "primary-focus": "#90EE90",
                    "primary-content": "#1F4B1F",
                    secondary: "#E0FFE0",
                    "secondary-focus": "#98FF98",
                    "secondary-content": "#1F4B1F",
                    accent: "#BDFFBD",
                    "accent-focus": "#98FF98",
                    "accent-content": "#1F4B1F",
                    "base-100": "#1a2e1a",
                    "base-200": "#1F4B1F",
                    "base-300": "#2d692d"
                }
            }
        ]
    },
    darkMode: "class"
};
