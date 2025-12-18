import defaultTheme from "tailwindcss/defaultTheme";
import forms from "@tailwindcss/forms";

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: "class",

    content: [
        "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
        "./storage/framework/views/*.php",
        "./resources/views/**/*.blade.php",
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ["Inter", "Figtree", ...defaultTheme.fontFamily.sans],
            },
            colors: {
                // Primary Colors - Deep Indigo/Purple
                primary: {
                    50: "#EEF2FF",
                    100: "#E0E7FF",
                    200: "#C7D2FE",
                    300: "#A5B4FC",
                    400: "#818CF8",
                    500: "#6366F1",
                    600: "#4F46E5",
                    700: "#4338CA",
                    800: "#3730A3",
                    900: "#312E81",
                    950: "#1E1B4B",
                },
                // Secondary Colors - Teal
                secondary: {
                    50: "#F0FDFA",
                    100: "#CCFBF1",
                    200: "#99F6E4",
                    300: "#5EEAD4",
                    400: "#2DD4BF",
                    500: "#14B8A6",
                    600: "#0D9488",
                    700: "#0F766E",
                    800: "#115E59",
                    900: "#134E4A",
                    950: "#042F2E",
                },
                // Accent Colors - Coral/Orange
                accent: {
                    50: "#FFF7ED",
                    100: "#FFEDD5",
                    200: "#FED7AA",
                    300: "#FDBA74",
                    400: "#FB923C",
                    500: "#F97316",
                    600: "#EA580C",
                    700: "#C2410C",
                    800: "#9A3412",
                    900: "#7C2D12",
                    950: "#431407",
                },
                // Success Colors - Emerald
                success: {
                    50: "#ECFDF5",
                    100: "#D1FAE5",
                    200: "#A7F3D0",
                    300: "#6EE7B7",
                    400: "#34D399",
                    500: "#10B981",
                    600: "#059669",
                    700: "#047857",
                    800: "#065F46",
                    900: "#064E3B",
                    950: "#022C22",
                },
                // Warning Colors - Amber
                warning: {
                    50: "#FFFBEB",
                    100: "#FEF3C7",
                    200: "#FDE68A",
                    300: "#FCD34D",
                    400: "#FBBF24",
                    500: "#F59E0B",
                    600: "#D97706",
                    700: "#B45309",
                    800: "#92400E",
                    900: "#78350F",
                    950: "#451A03",
                },
                // Danger Colors - Rose
                danger: {
                    50: "#FFF1F2",
                    100: "#FFE4E6",
                    200: "#FECDD3",
                    300: "#FDA4AF",
                    400: "#FB7185",
                    500: "#F43F5E",
                    600: "#E11D48",
                    700: "#BE123C",
                    800: "#9F1239",
                    900: "#881337",
                    950: "#4C0519",
                },
                // Dark Mode specific
                dark: {
                    bg: "#111827",
                    card: "#1F2937",
                    border: "#374151",
                    hover: "#374151",
                },
                // Sidebar specific
                sidebar: {
                    bg: "#111827",
                    hover: "#1F2937",
                    active: "#312E81",
                    text: "#94A3B8",
                    "text-active": "#FFFFFF",
                },
            },
            animation: {
                "fade-in": "fadeIn 0.5s ease-out",
                "slide-in-left": "slideInLeft 0.3s ease-out",
                "slide-in-right": "slideInRight 0.3s ease-out",
                "slide-up": "slideUp 0.3s ease-out",
                "pulse-slow": "pulse 3s cubic-bezier(0.4, 0, 0.6, 1) infinite",
            },
            keyframes: {
                fadeIn: {
                    "0%": { opacity: "0" },
                    "100%": { opacity: "1" },
                },
                slideInLeft: {
                    "0%": { transform: "translateX(-100%)", opacity: "0" },
                    "100%": { transform: "translateX(0)", opacity: "1" },
                },
                slideInRight: {
                    "0%": { transform: "translateX(100%)", opacity: "0" },
                    "100%": { transform: "translateX(0)", opacity: "1" },
                },
                slideUp: {
                    "0%": { transform: "translateY(20px)", opacity: "0" },
                    "100%": { transform: "translateY(0)", opacity: "1" },
                },
            },
            backdropBlur: {
                xs: "2px",
            },
            boxShadow: {
                glow: "0 0 20px rgba(99, 102, 241, 0.3)",
                "glow-lg": "0 0 40px rgba(99, 102, 241, 0.4)",
            },
        },
    },

    plugins: [forms],
};
