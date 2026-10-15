@php
    if (! function_exists('cs_esc')) {
        function cs_esc($v) { return htmlspecialchars((string)($v ?? ''), ENT_QUOTES, 'UTF-8'); }
    }
@endphp
