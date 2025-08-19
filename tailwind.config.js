import defaultTheme from "tailwindcss/defaultTheme";
import forms from "@tailwindcss/forms";

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
        "./storage/framework/views/*.php",
        "./resources/views/**/*.blade.php",
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        // './resources/**/*.vue', // Uncomment if you are actually using Vue components
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ["Inter", ...defaultTheme.fontFamily.sans], // Mengganti Figtree dengan Inter sesuai layout Anda
            },
            // Tambahkan ini untuk minWidth kustom jika dibutuhkan
            minWidth: {
                40: "10rem", // 160px
                60: "15rem", // 240px
                80: "20rem", // 320px
                // Anda bisa menambahkan lebih banyak nilai di sini jika diperlukan
            },
        },
    },

    plugins: [forms],
};
