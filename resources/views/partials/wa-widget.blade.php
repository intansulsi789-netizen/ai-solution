{{--
    ============================================================
    WHATSAPP CHAT WIDGET
    ============================================================
    Nomor WhatsApp diambil otomatis dari database.
    Atur nomor di: Admin → CMS → Kontak
--}}
@php
    $cmsContact = \App\Models\CmsContact::first();
    $waNumber = preg_replace('/[^0-9]/', '', optional($cmsContact)->whatsapp ?? '628XXXXXXXXXX');
    if (str_starts_with($waNumber, '0')) {
        $waNumber = '62' . substr($waNumber, 1);
    }
@endphp

{{-- ===== WIDGET STYLES ===== --}}
<style>
/* === WA Widget Container === */
#wa-widget {
    position: fixed;
    bottom: 28px;
    right: 28px;
    z-index: 9999;
    font-family: 'Inter', 'Segoe UI', sans-serif;
    --wa-cyan: #00d4ff;
    --wa-green: #25D366;
    --wa-bg: rgba(4, 10, 32, 0.92);
    --wa-surface: rgba(10, 22, 60, 0.88);
    --wa-border: rgba(0, 212, 255, 0.18);
    --wa-border-h: rgba(0, 212, 255, 0.40);
    --wa-text: #f0f4ff;
    --wa-muted: rgba(200, 215, 255, 0.55);
}

/* === Floating Button === */
#wa-toggle-btn {
    width: 58px;
    height: 58px;
    border-radius: 50%;
    background: linear-gradient(135deg, #1adb6e 0%, #128c4c 100%);
    border: none;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow:
        0 0 0 0 rgba(37, 211, 102, 0.5),
        0 8px 32px rgba(37, 211, 102, 0.35);
    transition: transform 0.2s ease, box-shadow 0.3s ease;
    position: relative;
    animation: wa-pulse 2.5s infinite;
    margin-left: auto;
}
#wa-toggle-btn:hover {
    transform: scale(1.08);
    box-shadow: 0 0 0 6px rgba(37, 211, 102, 0.18), 0 10px 36px rgba(37, 211, 102, 0.45);
    animation: none;
}
#wa-toggle-btn svg {
    width: 30px;
    height: 30px;
    fill: #fff;
    transition: transform 0.3s ease, opacity 0.2s ease;
    flex-shrink: 0;
}
#wa-toggle-btn .icon-close {
    position: absolute;
    display: none;
}
#wa-toggle-btn .icon-close svg {
    fill: none;
    stroke: #fff;
    stroke-width: 2.5;
    stroke-linecap: round;
}
#wa-widget.open #wa-toggle-btn .icon-wa  { display: none; }
#wa-widget.open #wa-toggle-btn .icon-close { display: flex; }

@keyframes wa-pulse {
    0%, 100% { box-shadow: 0 0 0 0 rgba(37,211,102,0.45), 0 8px 32px rgba(37,211,102,0.3); }
    50%       { box-shadow: 0 0 0 10px rgba(37,211,102,0.0), 0 8px 32px rgba(37,211,102,0.3); }
}

/* Notification dot */
#wa-notif-dot {
    position: absolute;
    top: 2px;
    right: 2px;
    width: 14px;
    height: 14px;
    background: #f97316;
    border-radius: 50%;
    border: 2px solid #020818;
    animation: wa-bounce 1s infinite;
}
@keyframes wa-bounce {
    0%, 100% { transform: scale(1); }
    50%       { transform: scale(1.2); }
}
#wa-widget.open #wa-notif-dot { display: none; }

/* === Chat Panel === */
#wa-panel {
    position: absolute;
    bottom: 70px;
    right: 0;
    width: 320px;
    border-radius: 20px;
    background: var(--wa-bg);
    border: 1px solid var(--wa-border);
    backdrop-filter: blur(24px);
    -webkit-backdrop-filter: blur(24px);
    box-shadow:
        0 0 0 1px rgba(0, 212, 255, 0.06),
        0 24px 64px rgba(0, 0, 0, 0.6),
        inset 0 1px 0 rgba(255,255,255,0.06);
    overflow: hidden;
    transform: translateY(16px) scale(0.96);
    opacity: 0;
    pointer-events: none;
    transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1),
                opacity 0.25s ease;
}
#wa-widget.open #wa-panel {
    transform: translateY(0) scale(1);
    opacity: 1;
    pointer-events: all;
}

/* Panel Header */
.wa-header {
    padding: 16px 18px;
    background: linear-gradient(135deg,
        rgba(0, 30, 80, 0.95) 0%,
        rgba(0, 50, 100, 0.90) 100%);
    border-bottom: 1px solid var(--wa-border);
    display: flex;
    align-items: center;
    gap: 12px;
    position: relative;
    overflow: hidden;
}
.wa-header::before {
    content: '';
    position: absolute;
    top: 0; left: 0; right: 0; height: 2px;
    background: linear-gradient(90deg, var(--wa-cyan), #7c3aed, transparent);
}
.wa-avatar {
    width: 42px;
    height: 42px;
    border-radius: 50%;
    background: linear-gradient(135deg, #1adb6e, #128c4c);
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    box-shadow: 0 0 12px rgba(37, 211, 102, 0.4);
}
.wa-avatar svg {
    width: 22px;
    height: 22px;
    fill: #fff;
}
.wa-header-info { flex: 1; min-width: 0; }
.wa-header-name {
    font-size: 14px;
    font-weight: 700;
    color: #fff;
    line-height: 1.2;
}
.wa-header-status {
    font-size: 11px;
    color: #4ade80;
    display: flex;
    align-items: center;
    gap: 4px;
    margin-top: 2px;
}
.wa-header-status::before {
    content: '';
    width: 6px;
    height: 6px;
    border-radius: 50%;
    background: #4ade80;
    box-shadow: 0 0 5px #4ade80;
    flex-shrink: 0;
}

/* Chat Bubble */
.wa-body {
    padding: 16px 16px 12px;
}
.wa-bubble {
    background: var(--wa-surface);
    border: 1px solid var(--wa-border);
    border-radius: 4px 16px 16px 16px;
    padding: 12px 14px;
    margin-bottom: 6px;
    position: relative;
}
.wa-bubble-time {
    font-size: 10px;
    color: var(--wa-muted);
    text-align: right;
    margin-top: 6px;
}
.wa-bubble p {
    font-size: 13.5px;
    color: var(--wa-text);
    line-height: 1.5;
    margin: 0;
}
.wa-bubble p + p {
    margin-top: 4px;
    color: var(--wa-muted);
    font-size: 12.5px;
}

/* Options */
.wa-options {
    display: flex;
    flex-direction: column;
    gap: 7px;
    padding: 0 16px 16px;
}
.wa-option-btn {
    display: flex;
    align-items: center;
    gap: 10px;
    width: 100%;
    padding: 10px 14px;
    background: rgba(0, 212, 255, 0.05);
    border: 1px solid var(--wa-border);
    border-radius: 12px;
    color: rgba(200, 220, 255, 0.85);
    font-size: 13px;
    font-weight: 500;
    font-family: inherit;
    cursor: pointer;
    text-align: left;
    text-decoration: none;
    transition: all 0.18s ease;
    position: relative;
    overflow: hidden;
}
.wa-option-btn::before {
    content: '';
    position: absolute;
    inset: 0;
    background: linear-gradient(90deg, rgba(0,212,255,0.06), transparent);
    opacity: 0;
    transition: opacity 0.2s;
}
.wa-option-btn:hover {
    background: rgba(0, 212, 255, 0.10);
    border-color: var(--wa-border-h);
    color: #fff;
    transform: translateX(2px);
}
.wa-option-btn:hover::before { opacity: 1; }
.wa-option-icon {
    width: 28px;
    height: 28px;
    border-radius: 8px;
    background: rgba(37, 211, 102, 0.12);
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}
.wa-option-icon svg {
    width: 14px;
    height: 14px;
    stroke: #4ade80;
    stroke-width: 2;
    fill: none;
    stroke-linecap: round;
    stroke-linejoin: round;
}
.wa-option-arrow {
    margin-left: auto;
    color: var(--wa-muted);
    font-size: 12px;
    transition: transform 0.2s;
}
.wa-option-btn:hover .wa-option-arrow { transform: translateX(3px); color: var(--wa-cyan); }

/* Footer */
.wa-footer {
    padding: 10px 16px;
    border-top: 1px solid rgba(255,255,255,0.05);
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    font-size: 11px;
    color: var(--wa-muted);
}
.wa-footer svg {
    width: 12px;
    height: 12px;
    fill: #25D366;
    flex-shrink: 0;
}

/* === Responsive === */
@media (max-width: 480px) {
    #wa-widget { bottom: 32px; right: 16px; }
    #wa-panel  { width: calc(100vw - 32px); right: 0; }
}
</style>

{{-- ===== WIDGET HTML ===== --}}
<div id="wa-widget">

    {{-- Chat Panel --}}
    <div id="wa-panel" role="dialog" aria-label="Chat WhatsApp">

        {{-- Header --}}
        <div class="wa-header">
            <div class="wa-avatar">
                <svg viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
            </div>
            <div class="wa-header-info">
                <div class="wa-header-name">AI Solution</div>
                <div class="wa-header-status">Online sekarang</div>
            </div>
        </div>

        {{-- Bubble chat --}}
        <div class="wa-body">
            <div class="wa-bubble">
                <p>Halo! Ada yang bisa kami bantu? 👋</p>
                <p>Apa yang Anda butuhkan?</p>
                <div class="wa-bubble-time" id="wa-time">Sekarang</div>
            </div>
        </div>

        {{-- Pilihan / Options --}}
        <div class="wa-options">

            <a class="wa-option-btn" id="wa-opt-1" href="#" target="_blank" rel="noopener"
               data-msg="Halo, saya ingin konsultasi tentang Layanan AI yang tersedia. Bisa bantu saya?">
                <span class="wa-option-icon">
                    <svg viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                </span>
                Konsultasi Layanan AI
                <span class="wa-option-arrow">›</span>
            </a>

            <a class="wa-option-btn" id="wa-opt-2" href="#" target="_blank" rel="noopener"
               data-msg="Halo, saya ingin bertanya tentang jasa yang tersedia di AI Solution. Bisa ceritakan lebih lanjut?">
                <span class="wa-option-icon">
                    <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                </span>
                Tanya Tentang Jasa
                <span class="wa-option-arrow">›</span>
            </a>

            <a class="wa-option-btn" id="wa-opt-3" href="#" target="_blank" rel="noopener"
               data-msg="Halo, saya ingin meminta penawaran harga untuk layanan AI Solution. Bisa bantu saya?">
                <span class="wa-option-icon">
                    <svg viewBox="0 0 24 24"><rect x="2" y="3" width="20" height="14" rx="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg>
                </span>
                Minta Penawaran
                <span class="wa-option-arrow">›</span>
            </a>

            <a class="wa-option-btn" id="wa-opt-4" href="#" target="_blank" rel="noopener"
               data-msg="Halo, saya ingin mengkonsultasikan kebutuhan bisnis saya dengan tim AI Solution. Bisakah kita berdiskusi?">
                <span class="wa-option-icon">
                    <svg viewBox="0 0 24 24"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                </span>
                Konsultasikan Kebutuhan
                <span class="wa-option-arrow">›</span>
            </a>

        </div>

        {{-- Footer --}}
        <div class="wa-footer">
            <svg viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
            Terhubung via WhatsApp
        </div>

    </div>

    {{-- Toggle Button --}}
    <button id="wa-toggle-btn" aria-label="Buka chat WhatsApp" title="Chat WhatsApp">
        <span id="wa-notif-dot"></span>
        <span class="icon-wa">
            <svg viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
        </span>
        <span class="icon-close">
            <svg viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
        </span>
    </button>

</div>

{{-- ===== WIDGET SCRIPT ===== --}}
<script>
(function () {
    // ─── Nomor WhatsApp (otomatis diambil dari PHP) ───
    const WA_NUMBER = '{{ $waNumber }}';

    const widget  = document.getElementById('wa-widget');
    const toggleBtn = document.getElementById('wa-toggle-btn');

    // Set waktu bubble
    const waTimeEl = document.getElementById('wa-time');
    if (waTimeEl) {
        const now = new Date();
        waTimeEl.textContent = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
    }

    // Assign link ke setiap opsi berdasarkan data-msg
    document.querySelectorAll('.wa-option-btn').forEach(function (btn) {
        const msg = btn.getAttribute('data-msg');
        if (msg) {
            btn.href = 'https://wa.me/' + WA_NUMBER + '?text=' + encodeURIComponent(msg);
        }
    });

    // Toggle buka / tutup panel
    toggleBtn.addEventListener('click', function () {
        widget.classList.toggle('open');
        const isOpen = widget.classList.contains('open');
        toggleBtn.setAttribute('aria-label', isOpen ? 'Tutup chat WhatsApp' : 'Buka chat WhatsApp');
    });

    // Tutup panel jika klik di luar widget
    document.addEventListener('click', function (e) {
        if (!widget.contains(e.target)) {
            widget.classList.remove('open');
        }
    });

    // Cegah panel ikut tertutup saat klik di dalamnya
    document.getElementById('wa-panel').addEventListener('click', function (e) {
        e.stopPropagation();
    });
})();
</script>
