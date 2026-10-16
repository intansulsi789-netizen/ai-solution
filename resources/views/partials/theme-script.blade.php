<script>
    (function () {
        try {
            const savedTheme = localStorage.getItem('theme');
            const theme = savedTheme ? savedTheme : 'dark';
            document.documentElement.setAttribute('data-theme', theme);
        } catch (e) {
            document.documentElement.setAttribute('data-theme', 'dark');
        }
    })();

    function toggleTheme() {
        const currentTheme = document.documentElement.getAttribute('data-theme') || 'dark';
        const newTheme = currentTheme === 'light' ? 'dark' : 'light';
        document.documentElement.setAttribute('data-theme', newTheme);
        try {
            localStorage.setItem('theme', newTheme);
        } catch (e) {}
    }
</script>
<style>
    /* Theme Toggle Button Style */
    .theme-toggle-btn {
        width: 38px;
        height: 38px;
        border-radius: 10px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(0, 212, 255, 0.25);
        color: #00d4ff;
        cursor: pointer;
        transition: all 0.25s ease;
        flex-shrink: 0;
        padding: 0;
    }
    .theme-toggle-btn:hover {
        background: rgba(0, 212, 255, 0.15);
        border-color: rgba(0, 212, 255, 0.45);
        transform: translateY(-2px);
        box-shadow: 0 4px 14px rgba(0, 212, 255, 0.25);
    }
    .theme-toggle-btn:active {
        transform: translateY(0);
    }
    .theme-icon-sun {
        display: block;
        color: #facc15;
    }
    .theme-icon-moon {
        display: none;
        color: #0284c7;
    }

    /* In Light Mode: swap icons & button style */
    html[data-theme="light"] .theme-icon-sun {
        display: none !important;
    }
    html[data-theme="light"] .theme-icon-moon {
        display: block !important;
    }
    html[data-theme="light"] .theme-toggle-btn {
        background: #ffffff;
        border-color: rgba(15, 23, 42, 0.15);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
    }
    html[data-theme="light"] .theme-toggle-btn:hover {
        background: #f1f5f9;
        border-color: rgba(15, 23, 42, 0.25);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.10);
    }
</style>
