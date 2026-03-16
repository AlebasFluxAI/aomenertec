module.exports = {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
    ],
    theme: {
        extend: {
            fontFamily: {
                // FluxAI Typography
                sans: ["Inter", "system-ui", "-apple-system", "sans-serif"], // Body text
                heading: [
                    "Poppins",
                    "system-ui",
                    "-apple-system",
                    "sans-serif",
                ], // Headings
            },
            colors: {
                // ------------------------------------------------------------------
                // FluxAI Brand Colors
                // ------------------------------------------------------------------
                "flux-primary": "#0044A4", // Azul oscuro principal
                "flux-secondary": "#0C62DC", // Azul medio
                "flux-accent": "#00C781", // Verde/turquesa
                "flux-primary-dark": "#003380",
                "flux-primary-light": "#0055CC",
                "flux-accent-dark": "#00A56B",
                "flux-accent-light": "#00E599",
                "flux-bg-light": "#F5F7FA",
                "flux-bg-medium": "#E4E9F0",
                "flux-bg-dark": "#2D3748",

                // ------------------------------------------------------------------
                // UI / Layout colors  →  use: border-ui-border, bg-ui-page, etc.
                // ------------------------------------------------------------------
                "ui-border": "#e2e8f0", // border-ui-border   → todos los bordes
                "ui-page": "#f8fafc", // bg-ui-page         → fondo de página/main
                "ui-hover": "#f6f7f9", // hover:bg-ui-hover  → hover en items/botones
                "ui-text": "#0f172a", // text-ui-text       → texto primario oscuro
                "ui-muted": "#64748b", // text-ui-muted      → texto secundario
                "ui-active": "#ebf8ff", // bg-ui-active       → fondo activo (azul tenue)
                "ui-active-hover": "#d1f0ff", // hover:bg-ui-active-hover
            },
            boxShadow: {
                // Material Design Elevations
                "elevation-1":
                    "0 1px 3px rgba(0, 0, 0, 0.12), 0 1px 2px rgba(0, 0, 0, 0.24)",
                "elevation-2":
                    "0 3px 6px rgba(0, 0, 0, 0.15), 0 2px 4px rgba(0, 0, 0, 0.12)",
                "elevation-3":
                    "0 10px 20px rgba(0, 0, 0, 0.15), 0 3px 6px rgba(0, 0, 0, 0.10)",
                "elevation-4":
                    "0 15px 25px rgba(0, 0, 0, 0.15), 0 5px 10px rgba(0, 0, 0, 0.05)",
                "elevation-5": "0 20px 40px rgba(0, 0, 0, 0.2)",
            },
        },
    },
    safelist: [
        'lg:pl-16',
        'lg:pl-[272px]',
        'lg:ml-12',
        'lg:ml-[260px]',
        'lg:hidden',
    ],
    plugins: [],
};
