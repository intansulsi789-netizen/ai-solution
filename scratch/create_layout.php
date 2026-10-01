<?php
$dashboard = file_get_contents('c:/xampp82/htdocs/webbbb/ai-solution/resources/views/admin/dashboard.blade.php');
$pos = strpos($dashboard, '<div class="content">');
$header = substr($dashboard, 0, $pos + strlen('<div class="content">'));
$footer = "\n    </div>\n</main>\n\n<script>\n    function updateClock() {\n        const el = document.getElementById('topbar-date');\n        if (!el) return;\n        const now = new Date();\n        const opts = { weekday: 'short', day: 'numeric', month: 'short', year: 'numeric',\n                       hour: '2-digit', minute: '2-digit' };\n        el.textContent = now.toLocaleDateString('id-ID', opts);\n    }\n    updateClock();\n    setInterval(updateClock, 60000);\n</script>\n@stack('scripts')\n</body>\n</html>";

$header = str_replace(
    '<a href="{{ route(\'admin.dashboard\') }}" class="nav-item active" id="nav-dashboard">',
    '<a href="{{ route(\'admin.dashboard\') }}" class="nav-item {{ request()->routeIs(\'admin.dashboard\') ? \'active\' : \'\' }}" id="nav-dashboard">',
    $header
);
$header = str_replace(
    '<a href="{{ route(\'admin.services.index\') }}" class="nav-item" id="nav-layanan">',
    '<a href="{{ route(\'admin.services.index\') }}" class="nav-item {{ request()->routeIs(\'admin.services.*\') ? \'active\' : \'\' }}" id="nav-layanan">',
    $header
);
$header = preg_replace('/<div class="topbar-title">.*?<\/div>/', '<div class="topbar-title">@yield(\'title\', \'Dashboard\')</div>', $header);

$layout = $header . "\n        @yield('content')\n" . $footer;

@mkdir('c:/xampp82/htdocs/webbbb/ai-solution/resources/views/admin/layouts', 0777, true);
file_put_contents('c:/xampp82/htdocs/webbbb/ai-solution/resources/views/admin/layouts/app.blade.php', $layout);
echo 'Layout created.';
