/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
    ],
    theme: {
        extend: {
            colors: {
                'boa': {
                    50: '#eff6ff',
                    100: '#dbeafe',
                    200: '#bfdbfe',
                    300: '#93c5fd',
                    400: '#60a5fa',
                    500: '#3b82f6',
                    600: '#2563eb', // Primary BOA Blue
                    700: '#1d4ed8',
                    800: '#1e40af',
                    900: '#1e3a8a',
                }
            },
            fontFamily: {
                sans: ['Inter', 'sans-serif'],
            },
            animation: {
                'fade-in': 'fadeIn 0.5s ease-out',
                'slide-up': 'slideUp 0.3s ease-out',
            },
            keyframes: {
                fadeIn: {
                    '0%': { opacity: '0', transform: 'translateY(10px)' },
                    '100%': { opacity: '1', transform: 'translateY(0)' },
                },
                slideUp: {
                    '0%': { opacity: '0', transform: 'translateY(20px)' },
                    '100%': { opacity: '1', transform: 'translateY(0)' },
                }
            }
        },
    },
    plugins: [
        require("daisyui"),
        require("@tailwindcss/forms"),
        require("@tailwindcss/typography"),
    ],
    daisyui: {
        themes: [
            {
                'boa-corporate': {
                    'primary': '#2563eb',
                    'primary-focus': '#1d4ed8',
                    'primary-content': '#ffffff',
                    'secondary': '#64748b',
                    'secondary-focus': '#475569',
                    'secondary-content': '#ffffff',
                    'accent': '#06b6d4',
                    'accent-focus': '#0891b2',
                    'accent-content': '#ffffff',
                    'neutral': '#374151',
                    'neutral-focus': '#1f2937',
                    'neutral-content': '#ffffff',
                    'base-100': '#ffffff',
                    'base-200': '#f8fafc',
                    'base-300': '#e2e8f0',
                    'base-content': '#1e293b',
                    'info': '#0ea5e9',
                    'success': '#22c55e',
                    'warning': '#f59e0b',
                    'error': '#ef4444',
                },
            },
            "light",
            "dark"
        ],
    },
}
