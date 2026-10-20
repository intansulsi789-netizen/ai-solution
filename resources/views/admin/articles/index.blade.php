@extends('admin.layouts.app')
@section('title', 'Artikel & Kalender Penjadwalan')

@section('content')
<style>
    /* ============================================================
       BASE TABS & CONTAINER STYLES
       ============================================================ */
    .article-nav-tabs {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 24px;
        border-bottom: 1px solid var(--border);
        padding-bottom: 16px;
    }

    .article-tab-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 18px;
        border-radius: 10px;
        background: rgba(255, 255, 255, 0.03);
        border: 1px solid var(--border);
        color: var(--muted);
        font-size: 13.5px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s ease;
        text-decoration: none;
    }

    .article-tab-btn:hover {
        background: rgba(0, 212, 255, 0.08);
        border-color: var(--border-h);
        color: var(--text);
    }

    .article-tab-btn.active {
        background: linear-gradient(135deg, rgba(0, 212, 255, 0.16), rgba(124, 58, 237, 0.16));
        border-color: var(--cyan);
        color: var(--text);
        box-shadow: 0 4px 14px rgba(0, 212, 255, 0.15);
    }

    .article-tab-btn.active svg {
        stroke: var(--cyan);
    }

    .tab-badge {
        font-size: 11px;
        padding: 2px 7px;
        border-radius: 100px;
        font-weight: 700;
    }

    .tab-badge-default {
        background: rgba(255, 255, 255, 0.08);
        color: var(--muted);
    }

    .article-tab-btn.active .tab-badge-default {
        background: rgba(0, 212, 255, 0.2);
        color: var(--cyan);
    }

    .tab-badge-scheduled {
        background: rgba(0, 212, 255, 0.2);
        color: var(--cyan);
        border: 1px solid rgba(0, 212, 255, 0.35);
    }

    .view-pane {
        display: none;
    }

    .view-pane.active {
        display: block;
        animation: fadeIn 0.2s ease-in-out;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(4px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    /* ============================================================
       TABLE STYLES
       ============================================================ */
    .table-container {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: 16px;
        padding: 32px;
        backdrop-filter: blur(12px);
        max-width: 1300px;
        width: 100%;
    }
    .table-header {
        display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;
    }
    .btn-primary {
        background: linear-gradient(135deg, var(--cyan), var(--purple));
        color: #fff; padding: 10px 20px; border-radius: 8px; text-decoration: none; font-weight: 600; font-size: 14px;
        display: inline-flex; align-items: center; gap: 8px; border: none; cursor: pointer;
        box-shadow: 0 4px 14px rgba(0,212,255,0.25); transition: opacity 0.2s, transform 0.2s;
    }
    .btn-primary:hover { opacity: 0.95; transform: translateY(-1px); }
    table { width: 100%; border-collapse: collapse; text-align: left; }
    th, td { padding: 14px 16px; border-bottom: 1px solid rgba(255,255,255,0.05); vertical-align: middle; }
    th { font-size: 12px; color: var(--muted); font-weight: 700; text-transform: uppercase; letter-spacing: 1px; }
    td { font-size: 13.5px; color: var(--text); }
    tr:last-child td { border-bottom: none; }
    .action-btn { color: var(--cyan); text-decoration: none; font-weight: 600; font-size: 13px; }
    .action-btn.del { color: #fca5a5; background: none; border: none; font-weight: 600; font-size: 13px; cursor: pointer; font-family: 'Inter', sans-serif; padding: 0; }
    .status-badge { padding: 4px 10px; border-radius: 100px; font-size: 11px; font-weight: 700; display: inline-flex; align-items: center; gap: 4px; }
    .status-active   { background: rgba(74,222,128,0.15); color: #4ade80; border: 1px solid rgba(74,222,128,0.3); }
    .status-inactive { background: rgba(239,68,68,0.15); color: #fca5a5; border: 1px solid rgba(239,68,68,0.3); }

    .status-draft     { background: rgba(234,179,8,0.12); color: #facc15; border: 1px solid rgba(234,179,8,0.3); }
    .status-published { background: rgba(16,185,129,0.12); color: #34d399; border: 1px solid rgba(16,185,129,0.3); }
    .status-scheduled { background: rgba(0,212,255,0.15); color: var(--cyan); border: 1px solid rgba(0,212,255,0.35); }

    .thumb { width: 56px; height: 40px; object-fit: cover; border-radius: 6px; opacity: 0.9; border: 1px solid rgba(255,255,255,0.1); }
    .thumb-placeholder { width: 56px; height: 40px; background: rgba(255,255,255,0.05); border-radius: 6px; display: flex; align-items: center; justify-content: center; color: rgba(255,255,255,0.2); font-size: 18px; }
    .cat-pill { display: inline-block; padding: 3px 9px; border-radius: 100px; font-size: 11px; font-weight: 600; background: rgba(124,58,237,0.18); color: #a78bfa; border: 1px solid rgba(124,58,237,0.28); }
    .source-tag { display: inline-flex; align-items: center; gap: 4px; font-size: 11px; color: var(--muted); margin-top: 4px; }
    .source-tag a { color: var(--cyan); text-decoration: none; }
    /* ============================================================
       PAGINATION STYLES (ADMIN)
       ============================================================ */
    .pagination-wrapper {
        margin-top: 24px;
        padding-top: 20px;
        border-top: 1px solid var(--border);
        display: flex;
        flex-direction: row;
        justify-content: space-between;
        align-items: center;
        gap: 16px;
        flex-wrap: wrap;
    }
    .pagination-info {
        font-size: 13px;
        color: var(--muted);
        font-family: 'Inter', sans-serif;
    }
    .pagination-info span {
        color: var(--text);
        font-weight: 600;
    }
    .pagination-nav {
        display: flex;
        align-items: center;
        gap: 8px;
        flex-wrap: wrap;
    }
    .pagination-btn {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 7px 14px;
        border-radius: 8px;
        font-size: 12.5px;
        font-weight: 600;
        font-family: 'Inter', sans-serif;
        text-decoration: none;
        background: rgba(255, 255, 255, 0.04);
        border: 1px solid var(--border);
        color: var(--text);
        transition: all 0.2s ease;
    }
    .pagination-btn:hover:not(.disabled) {
        background: rgba(0, 212, 255, 0.12);
        border-color: var(--cyan);
        color: var(--cyan);
        transform: translateY(-1px);
    }
    .pagination-btn.disabled {
        opacity: 0.35;
        cursor: not-allowed;
        pointer-events: none;
    }
    .pagination-numbers {
        display: flex;
        align-items: center;
        gap: 6px;
    }
    .pagination-number {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 32px;
        height: 32px;
        padding: 0 8px;
        border-radius: 8px;
        font-size: 12.5px;
        font-weight: 600;
        font-family: 'Inter', sans-serif;
        text-decoration: none;
        background: rgba(255, 255, 255, 0.04);
        border: 1px solid var(--border);
        color: var(--muted);
        transition: all 0.2s ease;
    }
    .pagination-number:hover:not(.active) {
        background: rgba(0, 212, 255, 0.1);
        border-color: var(--cyan);
        color: var(--cyan);
        transform: translateY(-1px);
    }
    .pagination-number.active {
        background: linear-gradient(135deg, var(--cyan), var(--purple));
        border-color: transparent;
        color: #ffffff;
        box-shadow: 0 2px 8px rgba(0, 212, 255, 0.3);
    }
    .pagination-ellipsis {
        padding: 0 4px;
        color: var(--muted);
        font-size: 13px;
    }

    html[data-theme="light"] .pagination-btn,
    html[data-theme="light"] .pagination-number:not(.active) {
        background: #f8fafc;
        border-color: rgba(15, 23, 42, 0.12);
        color: #475569;
    }
    html[data-theme="light"] .pagination-btn:hover:not(.disabled),
    html[data-theme="light"] .pagination-number:not(.active):hover {
        background: #e0f2fe;
        border-color: #0284c7;
        color: #0284c7;
    }
    html[data-theme="light"] input[name="q"],
    html[data-theme="light"] select[name="status"] {
        background: #ffffff !important;
        border-color: rgba(15, 23, 42, 0.15) !important;
        color: #0f172a !important;
    }
    html[data-theme="light"] select[name="status"] option {
        background: #ffffff !important;
        color: #0f172a !important;
    }

    /* ============================================================
       CALENDAR SPECIFIC STYLES
       ============================================================ */
    .cal-container {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: 16px;
        padding: 30px;
        backdrop-filter: blur(12px);
        max-width: 1300px;
        width: 100%;
    }

    .cal-toolbar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 16px;
        margin-bottom: 20px;
    }

    .cal-nav-group {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .cal-nav-btn {
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid var(--border);
        color: var(--text);
        width: 36px;
        height: 36px;
        border-radius: 8px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s;
    }

    .cal-nav-btn:hover {
        background: rgba(0, 212, 255, 0.12);
        border-color: var(--cyan);
        color: var(--cyan);
        transform: translateY(-1px);
    }

    .cal-btn-today {
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid var(--border);
        color: var(--text);
        padding: 0 14px;
        height: 36px;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
    }

    .cal-btn-today:hover {
        border-color: var(--cyan);
        color: var(--cyan);
        background: rgba(0, 212, 255, 0.08);
    }

    .cal-month-title {
        font-size: 22px;
        font-weight: 800;
        letter-spacing: -0.3px;
        color: var(--text);
        min-width: 180px;
    }

    .cal-legend-bar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 14px;
        padding: 12px 18px;
        background: rgba(0, 0, 0, 0.18);
        border: 1px solid rgba(255, 255, 255, 0.05);
        border-radius: 10px;
        margin-bottom: 20px;
    }

    .cal-legend-items {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 16px;
    }

    .cal-legend-item {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        font-size: 12.5px;
        color: var(--text);
    }

    .cal-legend-dot {
        width: 10px;
        height: 10px;
        border-radius: 50%;
    }

    .dot-scheduled {
        background: #00d4ff;
        box-shadow: 0 0 8px rgba(0, 212, 255, 0.6);
    }

    .dot-published {
        background: #10b981;
        box-shadow: 0 0 8px rgba(16, 185, 129, 0.6);
    }

    .dot-draft {
        background: #f59e0b;
        box-shadow: 0 0 8px rgba(245, 158, 11, 0.6);
    }

    .cal-hint {
        font-size: 12px;
        color: var(--muted);
    }

    /* GRID */
    .cal-grid-header {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        gap: 8px;
        margin-bottom: 8px;
    }

    .cal-day-name {
        text-align: center;
        padding: 10px 4px;
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: var(--muted);
    }

    .cal-day-name.weekend {
        color: #f87171;
    }

    .cal-grid-body {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        gap: 8px;
    }

    .cal-day-cell {
        min-height: 125px;
        background: rgba(255, 255, 255, 0.02);
        border: 1px solid rgba(255, 255, 255, 0.06);
        border-radius: 12px;
        padding: 8px 9px;
        display: flex;
        flex-direction: column;
        transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
        cursor: pointer;
        position: relative;
    }

    .cal-day-cell:hover {
        background: rgba(0, 212, 255, 0.04);
        border-color: var(--cyan);
        transform: translateY(-2px);
        box-shadow: 0 6px 18px rgba(0, 0, 0, 0.35);
    }

    .cal-day-cell.other-month {
        opacity: 0.35;
        background: rgba(0, 0, 0, 0.15);
    }

    .cal-day-cell.today {
        border-color: var(--cyan);
        background: rgba(0, 212, 255, 0.07);
        box-shadow: 0 0 20px rgba(0, 212, 255, 0.15);
    }

    /* Warna sel kalender saat sudah ada artikel */
    .cal-day-cell.cal-has-published {
        background: rgba(16, 185, 129, 0.12);
        border-color: rgba(16, 185, 129, 0.45);
    }
    .cal-day-cell.cal-has-published:hover {
        background: rgba(16, 185, 129, 0.22);
        border-color: #10b981;
        box-shadow: 0 4px 18px rgba(16, 185, 129, 0.30);
    }

    .cal-day-cell.cal-has-scheduled {
        background: rgba(0, 212, 255, 0.12);
        border-color: rgba(0, 212, 255, 0.45);
    }
    .cal-day-cell.cal-has-scheduled:hover {
        background: rgba(0, 212, 255, 0.22);
        border-color: #00d4ff;
        box-shadow: 0 4px 18px rgba(0, 212, 255, 0.30);
    }

    .cal-day-cell.cal-has-draft {
        background: rgba(245, 158, 11, 0.12);
        border-color: rgba(245, 158, 11, 0.45);
    }
    .cal-day-cell.cal-has-draft:hover {
        background: rgba(245, 158, 11, 0.22);
        border-color: #f59e0b;
        box-shadow: 0 4px 18px rgba(245, 158, 11, 0.30);
    }

    .cal-day-cell.cal-has-mixed {
        background: linear-gradient(135deg, rgba(16, 185, 129, 0.14), rgba(0, 212, 255, 0.14));
        border-color: rgba(0, 212, 255, 0.50);
    }
    .cal-day-cell.cal-has-mixed:hover {
        background: linear-gradient(135deg, rgba(16, 185, 129, 0.24), rgba(0, 212, 255, 0.24));
        border-color: var(--cyan);
        box-shadow: 0 4px 18px rgba(0, 212, 255, 0.30);
    }

    .cal-day-cell.today.cal-has-published {
        border-color: #10b981;
        background: rgba(16, 185, 129, 0.16);
        box-shadow: 0 0 16px rgba(16, 185, 129, 0.35);
    }

    /* Light mode untuk sel berwarna */
    html[data-theme="light"] .cal-day-cell.cal-has-published {
        background: #ecfdf5;
        border-color: #6ee7b7;
    }
    html[data-theme="light"] .cal-day-cell.cal-has-published:hover {
        background: #d1fae5;
        border-color: #10b981;
        box-shadow: 0 4px 14px rgba(16, 185, 129, 0.20);
    }

    html[data-theme="light"] .cal-day-cell.cal-has-scheduled {
        background: #f0f9ff;
        border-color: #7dd3fc;
    }
    html[data-theme="light"] .cal-day-cell.cal-has-scheduled:hover {
        background: #e0f2fe;
        border-color: #0284c7;
        box-shadow: 0 4px 14px rgba(2, 132, 199, 0.20);
    }

    html[data-theme="light"] .cal-day-cell.cal-has-draft {
        background: #fffbeb;
        border-color: #fde68a;
    }
    html[data-theme="light"] .cal-day-cell.cal-has-draft:hover {
        background: #fef3c7;
        border-color: #f59e0b;
        box-shadow: 0 4px 14px rgba(245, 158, 11, 0.20);
    }

    html[data-theme="light"] .cal-day-cell.cal-has-mixed {
        background: linear-gradient(135deg, #ecfdf5, #f0f9ff);
        border-color: #7dd3fc;
    }

    /* Indikator status & jumlah artikel */
    .cal-colored-indicator-wrap {
        display: flex;
        flex-direction: column;
        gap: 6px;
        margin-top: auto;
        margin-bottom: auto;
        padding: 4px 0;
    }

    .cal-status-pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 10px;
        border-radius: 8px;
        font-size: 11.5px;
        font-weight: 700;
        letter-spacing: 0.2px;
        transition: all 0.15s ease;
        white-space: nowrap;
        justify-content: center;
    }

    .cal-status-pill .dot {
        width: 7px;
        height: 7px;
        border-radius: 50%;
        flex-shrink: 0;
    }

    .cal-status-pill.pill-published {
        background: rgba(16, 185, 129, 0.20);
        color: #34d399;
        border: 1px solid rgba(16, 185, 129, 0.40);
    }

    .cal-status-pill.pill-scheduled {
        background: rgba(0, 212, 255, 0.20);
        color: #00d4ff;
        border: 1px solid rgba(0, 212, 255, 0.40);
    }

    .cal-status-pill.pill-draft {
        background: rgba(245, 158, 11, 0.20);
        color: #fbbf24;
        border: 1px solid rgba(245, 158, 11, 0.40);
    }

    html[data-theme="light"] .cal-status-pill.pill-published {
        background: #d1fae5;
        color: #065f46;
        border-color: #a7f3d0;
    }
    html[data-theme="light"] .cal-status-pill.pill-scheduled {
        background: #e0f2fe;
        color: #0369a1;
        border-color: #bae6fd;
    }
    html[data-theme="light"] .cal-status-pill.pill-draft {
        background: #fef3c7;
        color: #92400e;
        border-color: #fde68a;
    }

    .cal-click-detail-hint {
        font-size: 10.5px;
        color: var(--muted);
        text-align: center;
        opacity: 0;
        transition: opacity 0.15s;
        margin-top: 3px;
        font-weight: 500;
    }

    .cal-day-cell:hover .cal-click-detail-hint {
        opacity: 0.95;
        color: var(--cyan);
    }

    html[data-theme="light"] .cal-day-cell:hover .cal-click-detail-hint {
        color: #0284c7;
    }

    .cal-cell-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 6px;
    }

    .cal-cell-date {
        font-size: 13.5px;
        font-weight: 700;
        color: var(--text);
    }

    .cal-cell-today-pill {
        font-size: 9.5px;
        font-weight: 700;
        padding: 2px 6px;
        border-radius: 100px;
        background: rgba(0, 212, 255, 0.2);
        color: var(--cyan);
        border: 1px solid rgba(0, 212, 255, 0.35);
    }

    .cal-quick-add {
        width: 20px;
        height: 20px;
        border-radius: 4px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        font-weight: bold;
        color: var(--muted);
        opacity: 0;
        transition: all 0.15s;
        border: none;
        background: none;
        cursor: pointer;
    }

    .cal-day-cell:hover .cal-quick-add {
        opacity: 1;
        color: var(--cyan);
        background: rgba(0, 212, 255, 0.12);
    }

    .cal-events-wrap {
        display: flex;
        flex-direction: column;
        gap: 4px;
        flex-grow: 1;
        overflow-y: auto;
        max-height: 140px;
        padding-right: 2px;
    }

    .cal-events-wrap::-webkit-scrollbar {
        width: 3px;
    }
    .cal-events-wrap::-webkit-scrollbar-thumb {
        background: rgba(255, 255, 255, 0.15);
        border-radius: 3px;
    }

    .cal-event-chip {
        padding: 4px 6px;
        border-radius: 6px;
        font-size: 11px;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 5px;
        transition: all 0.15s;
        text-decoration: none;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .cal-event-chip:hover {
        filter: brightness(1.15);
        transform: scale(1.02);
    }

    .chip-scheduled {
        background: rgba(0, 212, 255, 0.15);
        color: var(--cyan);
        border: 1px solid rgba(0, 212, 255, 0.35);
    }

    .chip-published {
        background: rgba(16, 185, 129, 0.15);
        color: #34d399;
        border: 1px solid rgba(16, 185, 129, 0.35);
    }

    .chip-draft {
        background: rgba(245, 158, 11, 0.15);
        color: #facc15;
        border: 1px solid rgba(245, 158, 11, 0.35);
    }

    .chip-time {
        font-size: 10px;
        opacity: 0.85;
        flex-shrink: 0;
    }

    .chip-title {
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .cal-empty-hint {
        font-size: 11px;
        color: var(--muted);
        opacity: 0;
        transition: opacity 0.15s;
        text-align: center;
        margin-top: auto;
        margin-bottom: auto;
        padding: 6px 0;
    }

    .cal-day-cell:hover .cal-empty-hint {
        opacity: 0.75;
    }

    /* ============================================================
       MODAL STYLES (DETAIL TANGGAL)
       ============================================================ */
    .cal-modal-overlay {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(2, 8, 24, 0.75);
        backdrop-filter: blur(8px);
        z-index: 9999;
        align-items: center;
        justify-content: center;
        padding: 20px;
    }

    .cal-modal-overlay.active {
        display: flex;
        animation: modalFadeIn 0.25s ease-out;
    }

    @keyframes modalFadeIn {
        from { opacity: 0; transform: scale(0.96); }
        to   { opacity: 1; transform: scale(1); }
    }

    .cal-modal-card {
        background: #07112c;
        border: 1px solid var(--border-h);
        border-radius: 16px;
        width: 100%;
        max-width: 600px;
        max-height: 85vh;
        display: flex;
        flex-direction: column;
        box-shadow: 0 20px 50px rgba(0, 0, 0, 0.6), 0 0 30px rgba(0, 212, 255, 0.15);
        overflow: hidden;
    }

    .cal-modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 20px 24px;
        border-bottom: 1px solid var(--border);
    }

    .cal-modal-title {
        font-size: 17px;
        font-weight: 700;
        color: var(--text);
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .cal-modal-close {
        background: none;
        border: none;
        color: var(--muted);
        font-size: 24px;
        cursor: pointer;
        line-height: 1;
        transition: color 0.15s;
    }

    .cal-modal-close:hover {
        color: #fff;
    }

    .cal-modal-body {
        padding: 20px 24px;
        overflow-y: auto;
        display: flex;
        flex-direction: column;
        gap: 14px;
    }

    .modal-article-item {
        background: rgba(255, 255, 255, 0.03);
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 12px;
        padding: 16px;
        display: flex;
        flex-direction: column;
        gap: 10px;
        transition: border-color 0.2s;
    }

    .modal-article-item:hover {
        border-color: var(--border-h);
    }

    .modal-article-top {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 12px;
    }

    .modal-article-title {
        font-size: 15px;
        font-weight: 700;
        color: var(--text);
        line-height: 1.4;
    }

    .modal-article-meta {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 10px;
        font-size: 12px;
        color: var(--muted);
    }

    .modal-article-actions {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-top: 6px;
        padding-top: 10px;
        border-top: 1px solid rgba(255, 255, 255, 0.06);
    }

    .modal-btn-edit {
        background: rgba(0, 212, 255, 0.12);
        color: var(--cyan);
        border: 1px solid rgba(0, 212, 255, 0.3);
        padding: 6px 14px;
        border-radius: 6px;
        font-size: 12.5px;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        transition: all 0.2s;
    }

    .modal-btn-edit:hover {
        background: var(--cyan);
        color: #020818;
    }

    .modal-btn-view {
        background: rgba(255, 255, 255, 0.05);
        color: var(--text);
        border: 1px solid rgba(255, 255, 255, 0.1);
        padding: 6px 14px;
        border-radius: 6px;
        font-size: 12.5px;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        transition: all 0.2s;
    }

    .modal-btn-view:hover {
        border-color: rgba(255, 255, 255, 0.3);
        background: rgba(255, 255, 255, 0.1);
    }

    .cal-modal-footer {
        padding: 16px 24px;
        border-top: 1px solid var(--border);
        background: rgba(0, 0, 0, 0.2);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .modal-empty-state {
        text-align: center;
        padding: 30px 10px;
        color: var(--muted);
    }

    /* ============================================================
       LIGHT THEME OVERRIDES
       ============================================================ */
    html[data-theme="light"] .article-tab-btn {
        background: #ffffff;
        border-color: rgba(15, 23, 42, 0.12);
        color: #64748b;
    }
    html[data-theme="light"] .article-tab-btn.active {
        background: rgba(2, 132, 199, 0.08);
        border-color: #0284c7;
        color: #0284c7;
        box-shadow: 0 4px 14px rgba(2, 132, 199, 0.12);
    }
    html[data-theme="light"] .article-tab-btn.active svg {
        stroke: #0284c7;
    }
    html[data-theme="light"] .cal-container {
        background: #ffffff;
        border-color: rgba(15, 23, 42, 0.12);
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
    }
    html[data-theme="light"] .cal-nav-btn,
    html[data-theme="light"] .cal-btn-today {
        background: #f8fafc;
        border-color: rgba(15, 23, 42, 0.12);
        color: #0f172a;
    }
    html[data-theme="light"] .cal-nav-btn:hover,
    html[data-theme="light"] .cal-btn-today:hover {
        background: rgba(2, 132, 199, 0.1);
        border-color: #0284c7;
        color: #0284c7;
    }
    html[data-theme="light"] .cal-legend-bar {
        background: #f8fafc;
        border-color: rgba(15, 23, 42, 0.08);
    }
    html[data-theme="light"] .cal-day-cell {
        background: #ffffff;
        border-color: rgba(15, 23, 42, 0.09);
    }
    html[data-theme="light"] .cal-day-cell:hover {
        background: #f8fafc;
        border-color: #0284c7;
        box-shadow: 0 6px 18px rgba(0, 0, 0, 0.08);
    }
    html[data-theme="light"] .cal-day-cell.other-month {
        background: #f1f5f9;
        opacity: 0.5;
    }
    html[data-theme="light"] .cal-day-cell.today {
        border-color: #0284c7;
        background: rgba(2, 132, 199, 0.06);
        box-shadow: 0 0 15px rgba(2, 132, 199, 0.15);
    }
    html[data-theme="light"] .chip-scheduled {
        background: rgba(2, 132, 199, 0.1);
        color: #0284c7;
        border-color: rgba(2, 132, 199, 0.3);
    }
    html[data-theme="light"] .chip-published {
        background: rgba(16, 185, 129, 0.1);
        color: #059669;
        border-color: rgba(16, 185, 129, 0.3);
    }
    html[data-theme="light"] .chip-draft {
        background: rgba(217, 119, 6, 0.1);
        color: #b45309;
        border-color: rgba(217, 119, 6, 0.3);
    }
    html[data-theme="light"] .cal-modal-card {
        background: #ffffff;
        border-color: rgba(15, 23, 42, 0.15);
        box-shadow: 0 20px 50px rgba(0, 0, 0, 0.15);
    }
    html[data-theme="light"] .modal-article-item {
        background: #f8fafc;
        border-color: rgba(15, 23, 42, 0.1);
    }
    html[data-theme="light"] .cal-modal-footer {
        background: #f8fafc;
        border-top-color: rgba(15, 23, 42, 0.1);
    }
    html[data-theme="light"] .modal-btn-view {
        background: #ffffff;
        border-color: rgba(15, 23, 42, 0.15);
        color: #0f172a;
    }
</style>

<div class="page-header">
    <div class="page-label">MANAJEMEN</div>
    <h1 class="page-title">Artikel &amp; Penjadwalan</h1>
    <p class="page-desc">Kelola artikel wawasan dan jadwal publikasi otomatis di frontend.</p>
</div>

@if(session('success'))
<div style="background:rgba(74,222,128,0.1);border:1px solid rgba(74,222,128,0.3);color:#4ade80;padding:12px 16px;border-radius:8px;margin-bottom:24px;font-size:14px;">
    ✓ {{ session('success') }}
</div>
@endif

<!-- SUBMENU / TAB SWITCHER (Artikel vs Kalender) -->
<div class="article-nav-tabs">
    <button type="button" class="article-tab-btn {{ ($activeTab ?? 'table') !== 'calendar' ? 'active' : '' }}" id="tab-btn-table" onclick="switchArticleTab('table')">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <line x1="8" y1="6" x2="21" y2="6"></line>
            <line x1="8" y1="12" x2="21" y2="12"></line>
            <line x1="8" y1="18" x2="21" y2="18"></line>
            <line x1="3" y1="6" x2="3.01" y2="6"></line>
            <line x1="3" y1="12" x2="3.01" y2="12"></line>
            <line x1="3" y1="18" x2="3.01" y2="18"></line>
        </svg>
        <span>Daftar Artikel</span>
        <span class="tab-badge tab-badge-default">{{ $articles->total() }}</span>
    </button>

    <button type="button" class="article-tab-btn {{ ($activeTab ?? 'table') === 'calendar' ? 'active' : '' }}" id="tab-btn-calendar" onclick="switchArticleTab('calendar')">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
            <line x1="16" y1="2" x2="16" y2="6"></line>
            <line x1="8" y1="2" x2="8" y2="6"></line>
            <line x1="3" y1="10" x2="21" y2="10"></line>
        </svg>
        <span>Kalender Penjadwalan</span>
        @if(($scheduledCount ?? 0) > 0)
            <span class="tab-badge tab-badge-scheduled">{{ $scheduledCount }} Terjadwal</span>
        @else
            <span class="tab-badge tab-badge-default">Kalender</span>
        @endif
    </button>
</div>

<!-- ==========================================
     TAB 1: DAFTAR ARTIKEL (TABEL)
     ========================================== -->
<div id="view-table-container" class="view-pane {{ ($activeTab ?? 'table') !== 'calendar' ? 'active' : '' }}">
    <div class="table-container">
        <div class="table-header" style="flex-wrap: wrap; gap: 16px;">
            <div>
                <h3 style="font-size:16px;font-weight:700;">Daftar Artikel <span style="font-size:13px;color:var(--muted);font-weight:400;">({{ $articles->total() }} artikel)</span></h3>
            </div>
            <div style="display:flex; align-items:center; gap:12px; flex-wrap:wrap;">
                <form action="{{ route('admin.articles.index') }}" method="GET" style="display:flex; align-items:center; gap:8px; flex-wrap:wrap; margin:0;">
                    <div style="position:relative;">
                        <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari artikel..." style="background:rgba(255,255,255,0.05); border:1px solid var(--border); border-radius:8px; padding:8px 12px 8px 32px; color:var(--text); font-size:13px; outline:none; min-width:180px;">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="position:absolute; left:10px; top:50%; transform:translateY(-50%); color:var(--muted); pointer-events:none;">
                            <circle cx="11" cy="11" r="8"></circle>
                            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                        </svg>
                    </div>
                    <select name="status" onchange="this.form.submit()" style="background:rgba(255,255,255,0.05); border:1px solid var(--border); border-radius:8px; padding:8px 12px; color:var(--text); font-size:13px; outline:none; cursor:pointer;">
                        <option value="" style="background:#0a143c; color:#fff;">Semua Status</option>
                        <option value="published" {{ request('status') === 'published' ? 'selected' : '' }} style="background:#0a143c; color:#fff;">Published</option>
                        <option value="scheduled" {{ request('status') === 'scheduled' ? 'selected' : '' }} style="background:#0a143c; color:#fff;">Terjadwal</option>
                        <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }} style="background:#0a143c; color:#fff;">Draft</option>
                    </select>
                    @if(request('q') || request('status'))
                        <a href="{{ route('admin.articles.index') }}" style="font-size:12px; color:var(--muted); text-decoration:none; padding:7px 10px; border-radius:8px; border:1px solid var(--border);">Reset</a>
                    @endif
                </form>
                <a href="{{ route('admin.articles.create') }}" class="btn-primary">
                    + Tambah Artikel
                </a>
            </div>
        </div>
        <div style="overflow-x:auto;">
            <table>
                <thead>
                    <tr>
                        <th style="width:70px;">Gambar</th>
                        <th>Judul &amp; Sumber</th>
                        <th>Kategori</th>
                        <th>Tanggal / Jadwal (WIB)</th>
                        <th>Status</th>
                        <th style="text-align:right;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($articles as $a)
                    <tr>
                        <td>
                            @if($a->gambar)
                                @php
                                    $imgSrc = (str_starts_with($a->gambar, 'http://') || str_starts_with($a->gambar, 'https://'))
                                        ? $a->gambar
                                        : asset('storage/' . $a->gambar);
                                @endphp
                                <img src="{{ $imgSrc }}" alt="{{ $a->judul }}" class="thumb">
                            @else
                                <div class="thumb-placeholder">📄</div>
                            @endif
                        </td>
                        <td>
                            <div style="font-weight:600;color:var(--text);margin-bottom:2px;">{{ Str::limit($a->judul, 55) }}</div>
                            <div style="font-size:12px;color:var(--muted);margin-bottom:2px;">/wawasan/{{ $a->slug }}</div>
                            @if($a->source_name)
                                <div class="source-tag">
                                    <span>Sumber:</span>
                                    @if($a->source_url)
                                        <a href="{{ $a->source_url }}" target="_blank" rel="noopener noreferrer">{{ $a->source_name }} ↗</a>
                                    @else
                                        <span>{{ $a->source_name }}</span>
                                    @endif
                                </div>
                            @endif
                        </td>
                        <td><span class="cat-pill">{{ $a->kategori ?: '-' }}</span></td>
                        <td>
                            @if($a->status === 'scheduled')
                                <div style="font-size:12.5px;color:var(--cyan);font-weight:600;">
                                    ⏱️ {{ $a->published_at ? $a->published_at->timezone('Asia/Jakarta')->translatedFormat('d M Y, H:i') . ' WIB' : '-' }}
                                </div>
                                <div style="font-size:11px;color:var(--muted);">Otomatis terbit saat jadwal tercapai</div>
                            @elseif($a->status === 'published')
                                <div style="font-size:12.5px;color:var(--text);">
                                    {{ $a->published_at ? $a->published_at->timezone('Asia/Jakarta')->translatedFormat('d M Y') : ($a->tanggal ?: '-') }}
                                </div>
                                @if($a->published_at)
                                    <div style="font-size:11px;color:var(--muted);">{{ $a->published_at->timezone('Asia/Jakarta')->format('H:i') }} WIB</div>
                                @endif
                            @else
                                <span style="font-size:12px;color:var(--muted);">Belum dipublikasikan</span>
                            @endif
                        </td>
                        <td>
                            <div style="display:flex; flex-direction:column; gap:4px; align-items:flex-start;">
                                @if($a->is_active)
                                    <span class="status-badge status-active">Aktif</span>
                                @else
                                    <span class="status-badge status-inactive">Nonaktif</span>
                                @endif

                                @if($a->status === 'draft')
                                    <span class="status-badge status-draft">Draft</span>
                                @elseif($a->status === 'scheduled')
                                    <span class="status-badge status-scheduled">Terjadwal</span>
                                @else
                                    <span class="status-badge status-published">Published</span>
                                @endif
                            </div>
                        </td>
                        <td style="text-align:right;white-space:nowrap;">
                            <a href="{{ route('admin.articles.edit', $a->id) }}" class="action-btn" style="margin-right:12px;">Edit</a>
                            <form action="{{ route('admin.articles.destroy', $a->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Hapus artikel ini?');">
                                @csrf @method('DELETE')
                                <button type="submit" class="action-btn del">Hapus</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" style="text-align:center;padding:48px;color:var(--muted);">Belum ada artikel.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination Controls --}}
        {{ $articles->links('partials.pagination') }}
    </div>
</div>

<!-- ==========================================
     TAB 2: KALENDER PENJADWALAN ARTIKEL
     ========================================== -->
<div id="view-calendar-container" class="view-pane {{ ($activeTab ?? 'table') === 'calendar' ? 'active' : '' }}">
    <div class="cal-container">
        <!-- Toolbar Kalender -->
        <div class="cal-toolbar">
            <div class="cal-nav-group">
                <button type="button" class="cal-nav-btn" id="cal-prev-btn" title="Bulan Sebelumnya">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"></polyline></svg>
                </button>
                <div class="cal-month-title" id="cal-month-title">Memuat...</div>
                <button type="button" class="cal-nav-btn" id="cal-next-btn" title="Bulan Berikutnya">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>
                </button>
                <button type="button" class="cal-btn-today" id="cal-today-btn">Hari Ini</button>
            </div>

            <div style="display:flex; align-items:center; gap:10px;">
                <a href="{{ route('admin.articles.create') }}" class="btn-primary" id="btn-cal-add">
                    + Jadwalkan Artikel
                </a>
            </div>
        </div>

        <!-- Legend / Petunjuk Warna -->
        <div class="cal-legend-bar">
            <div class="cal-legend-items">
                <div class="cal-legend-item">
                    <span class="cal-legend-dot dot-published"></span>
                    <span><strong>Hijau (Terisi)</strong>: Sudah Diisi Artikel</span>
                </div>
                <div class="cal-legend-item">
                    <span class="cal-legend-dot" style="background: rgba(255,255,255,0.25); border: 1px dashed rgba(255,255,255,0.5);"></span>
                    <span><strong>Kosong</strong>: Belum Diisi Artikel</span>
                </div>
            </div>
            <div class="cal-hint">
                💡 Tanggal berwarna hijau menandakan artikel sudah diisi. Klik tanggal untuk melihat daftar, atau klik tanggal kosong untuk menjadwalkan artikel.
            </div>
        </div>

        <!-- Grid Kalender -->
        <div class="cal-grid-header">
            <div class="cal-day-name weekend">Minggu</div>
            <div class="cal-day-name">Senin</div>
            <div class="cal-day-name">Selasa</div>
            <div class="cal-day-name">Rabu</div>
            <div class="cal-day-name">Kamis</div>
            <div class="cal-day-name">Jumat</div>
            <div class="cal-day-name weekend">Sabtu</div>
        </div>

        <div class="cal-grid-body" id="cal-grid-body">
            <!-- Sel kalender dirender melalui JavaScript -->
        </div>
    </div>
</div>

<!-- ==========================================
     MODAL POPUP DETAIL TANGGAL
     ========================================== -->
<div class="cal-modal-overlay" id="cal-modal-overlay">
    <div class="cal-modal-card">
        <div class="cal-modal-header">
            <div class="cal-modal-title">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="var(--cyan)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                    <line x1="16" y1="2" x2="16" y2="6"></line>
                    <line x1="8" y1="2" x2="8" y2="6"></line>
                    <line x1="3" y1="10" x2="21" y2="10"></line>
                </svg>
                <span id="cal-modal-heading">Detail Tanggal</span>
            </div>
            <button type="button" class="cal-modal-close" id="cal-modal-close-btn">&times;</button>
        </div>

        <div class="cal-modal-body" id="cal-modal-body">
            <!-- Daftar artikel di tanggal ini diinjeksi via JS -->
        </div>

        <div class="cal-modal-footer">
            <button type="button" class="btn-primary" id="cal-modal-create-btn">
                + Jadwalkan Artikel pada Tanggal Ini
            </button>
            <button type="button" class="modal-btn-view" id="cal-modal-dismiss-btn">
                Tutup
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Data artikel dari server untuk kalender
    const calendarArticles = @json($calendarArticles ?? []);
    const createArticleBaseUrl = "{{ route('admin.articles.create') }}";

    // Setup tanggal saat ini (WIB)
    const today = new Date();
    let currentYear = today.getFullYear();
    let currentMonth = today.getMonth(); // 0-indexed (0 = Jan, 9 = Okt)

    const monthNames = [
        'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
    ];

    // Tab Switching
    function switchArticleTab(tab) {
        const tableContainer = document.getElementById('view-table-container');
        const calContainer   = document.getElementById('view-calendar-container');
        const tabBtnTable    = document.getElementById('tab-btn-table');
        const tabBtnCal      = document.getElementById('tab-btn-calendar');

        if (tab === 'calendar') {
            tableContainer.classList.remove('active');
            calContainer.classList.add('active');
            tabBtnTable.classList.remove('active');
            tabBtnCal.classList.add('active');
            renderCalendar();
            history.replaceState(null, '', '?view=calendar');
        } else {
            calContainer.classList.remove('active');
            tableContainer.classList.add('active');
            tabBtnCal.classList.remove('active');
            tabBtnTable.classList.add('active');
            history.replaceState(null, '', '?view=table');
        }
    }

    // Helper format YYYY-MM-DD
    function formatDateKey(year, month, day) {
        const m = String(month + 1).padStart(2, '0');
        const d = String(day).padStart(2, '0');
        return `${year}-${m}-${d}`;
    }

    // Mengelompokkan artikel berdasarkan tanggal (YYYY-MM-DD)
    function getArticlesGroupedByDate() {
        const map = {};
        calendarArticles.forEach(a => {
            if (a.date) {
                if (!map[a.date]) {
                    map[a.date] = [];
                }
                map[a.date].push(a);
            }
        });
        return map;
    }

    // Render Kalender Bulanan
    function renderCalendar() {
        const titleEl = document.getElementById('cal-month-title');
        const gridBody = document.getElementById('cal-grid-body');
        if (!gridBody) return;

        titleEl.textContent = `${monthNames[currentMonth]} ${currentYear}`;
        gridBody.innerHTML = '';

        const articlesMap = getArticlesGroupedByDate();

        // Hari pertama dalam bulan ini (0 = Minggu, 1 = Senin, ...)
        const firstDayIndex = new Date(currentYear, currentMonth, 1).getDay();
        // Jumlah hari dalam bulan ini
        const daysInMonth = new Date(currentYear, currentMonth + 1, 0).getDate();
        // Jumlah hari dalam bulan sebelumnya
        const prevDaysInMonth = new Date(currentYear, currentMonth, 0).getDate();

        const todayKey = formatDateKey(today.getFullYear(), today.getMonth(), today.getDate());

        // 1. Tanggal dari bulan sebelumnya
        for (let i = firstDayIndex - 1; i >= 0; i--) {
            const dayNum = prevDaysInMonth - i;
            const prevMonthDate = new Date(currentYear, currentMonth - 1, dayNum);
            const dateKey = formatDateKey(prevMonthDate.getFullYear(), prevMonthDate.getMonth(), dayNum);
            const cell = createDayCell(dayNum, dateKey, true, articlesMap[dateKey] || []);
            gridBody.appendChild(cell);
        }

        // 2. Tanggal bulan saat ini
        for (let day = 1; day <= daysInMonth; day++) {
            const dateKey = formatDateKey(currentYear, currentMonth, day);
            const isToday = (dateKey === todayKey);
            const cell = createDayCell(day, dateKey, false, articlesMap[dateKey] || [], isToday);
            gridBody.appendChild(cell);
        }

        // 3. Tanggal bulan berikutnya agar genap kelipatan 7
        const totalRendered = firstDayIndex + daysInMonth;
        const nextDays = (7 - (totalRendered % 7)) % 7;
        for (let d = 1; d <= nextDays; d++) {
            const nextMonthDate = new Date(currentYear, currentMonth + 1, d);
            const dateKey = formatDateKey(nextMonthDate.getFullYear(), nextMonthDate.getMonth(), d);
            const cell = createDayCell(d, dateKey, true, articlesMap[dateKey] || []);
            gridBody.appendChild(cell);
        }
    }

    // Membuat DOM elemen untuk 1 sel hari
    function createDayCell(dayNum, dateKey, isOtherMonth, articles, isToday = false) {
        const cell = document.createElement('div');
        cell.className = 'cal-day-cell';
        if (isOtherMonth) cell.classList.add('other-month');
        if (isToday) cell.classList.add('today');
        cell.dataset.date = dateKey;

        // Header sel (Nomor tanggal + tombol quick add + today badge)
        const topBar = document.createElement('div');
        topBar.className = 'cal-cell-header';

        const leftWrap = document.createElement('div');
        leftWrap.style.display = 'flex';
        leftWrap.style.alignItems = 'center';
        leftWrap.style.gap = '6px';

        const numEl = document.createElement('span');
        numEl.className = 'cal-cell-date';
        numEl.textContent = dayNum;
        leftWrap.appendChild(numEl);

        if (isToday) {
            const todayPill = document.createElement('span');
            todayPill.className = 'cal-cell-today-pill';
            todayPill.textContent = 'Hari ini';
            leftWrap.appendChild(todayPill);
        }

        topBar.appendChild(leftWrap);

        // Tombol + untuk langsung jadwal di tanggal ini
        const addBtn = document.createElement('button');
        addBtn.type = 'button';
        addBtn.className = 'cal-quick-add';
        addBtn.innerHTML = '+';
        addBtn.title = 'Jadwalkan artikel pada tanggal ini';
        addBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            window.location.href = `${createArticleBaseUrl}?date=${dateKey}`;
        });
        topBar.appendChild(addBtn);

        cell.appendChild(topBar);

        // Wrap artikel-artikel pada tanggal ini
        const eventsWrap = document.createElement('div');
        eventsWrap.className = 'cal-events-wrap';

        if (articles.length > 0) {
            // Hitung komposisi status artikel pada tanggal ini
            const statusCounts = {
                scheduled: 0,
                published: 0,
                draft: 0
            };
            articles.forEach(art => {
                if (statusCounts[art.status] !== undefined) {
                    statusCounts[art.status]++;
                } else {
                    statusCounts.published++;
                }
            });

            // Selalu beri warna hijau pada sel tanggal yang sudah diisi artikel
            cell.classList.add('cal-has-published');

            // Tampilkan indikator status hijau menandakan tanggal sudah diisi artikel
            const indicatorWrap = document.createElement('div');
            indicatorWrap.className = 'cal-colored-indicator-wrap';

            let labelText = '';
            if (statusCounts.published > 0 && statusCounts.scheduled === 0 && statusCounts.draft === 0) {
                labelText = `${statusCounts.published} Terbit`;
            } else if (statusCounts.scheduled > 0 && statusCounts.published === 0 && statusCounts.draft === 0) {
                labelText = `${statusCounts.scheduled} Terjadwal`;
            } else if (statusCounts.draft > 0 && statusCounts.published === 0 && statusCounts.scheduled === 0) {
                labelText = `${statusCounts.draft} Draft`;
            } else {
                labelText = `${articles.length} Terisi`;
            }

            const pill = document.createElement('div');
            pill.className = 'cal-status-pill pill-published';
            pill.innerHTML = `<span class="dot dot-published"></span> <span>${labelText}</span>`;
            indicatorWrap.appendChild(pill);

            const clickHint = document.createElement('div');
            clickHint.className = 'cal-click-detail-hint';
            clickHint.textContent = 'Klik untuk detail ↗';
            indicatorWrap.appendChild(clickHint);

            eventsWrap.appendChild(indicatorWrap);
            cell.title = `${articles.length} artikel pada tanggal ini (Klik untuk melihat daftar)`;
        } else {
            // Tampilan tanggal kosong: hint saat hover
            const emptyHint = document.createElement('div');
            emptyHint.className = 'cal-empty-hint';
            emptyHint.textContent = '+ Jadwalkan';
            eventsWrap.appendChild(emptyHint);
        }

        cell.appendChild(eventsWrap);

        // Event klik sel:
        cell.addEventListener('click', function() {
            if (articles.length === 0) {
                // Klik tanggal kosong -> buat artikel dengan tanggal penjadwalan otomatis terisi
                window.location.href = `${createArticleBaseUrl}?date=${dateKey}`;
            } else {
                // Klik tanggal dengan artikel -> tampilkan modal detail artikel pada tanggal tersebut
                openDateArticlesModal(dateKey, articles);
            }
        });

        return cell;
    }

    // Modal Detail Tanggal
    const modalOverlay   = document.getElementById('cal-modal-overlay');
    const modalHeading   = document.getElementById('cal-modal-heading');
    const modalBody      = document.getElementById('cal-modal-body');
    const modalCloseBtn  = document.getElementById('cal-modal-close-btn');
    const modalDismiss   = document.getElementById('cal-modal-dismiss-btn');
    const modalCreateBtn = document.getElementById('cal-modal-create-btn');

    let activeModalDate = '';

    function openDateArticlesModal(dateKey, articles) {
        activeModalDate = dateKey;
        const [y, m, d] = dateKey.split('-').map(Number);
        const formattedDate = `${d} ${monthNames[m - 1]} ${y}`;

        modalHeading.textContent = `Artikel — ${formattedDate}`;
        modalBody.innerHTML = '';

        if (articles.length === 0) {
            modalBody.innerHTML = `
                <div class="modal-empty-state">
                    <p style="font-size:14px; margin-bottom:12px;">Belum ada artikel pada tanggal ini.</p>
                </div>
            `;
        } else {
            articles.forEach(art => {
                const item = document.createElement('div');
                item.className = 'modal-article-item';

                let badgeHtml = '';
                if (art.status === 'scheduled') {
                    badgeHtml = `<span class="status-badge status-scheduled">Terjadwal</span>`;
                } else if (art.status === 'published') {
                    badgeHtml = `<span class="status-badge status-published">Published</span>`;
                } else {
                    badgeHtml = `<span class="status-badge status-draft">Draft</span>`;
                }

                const activeHtml = art.is_active
                    ? `<span class="status-badge status-active">Aktif</span>`
                    : `<span class="status-badge status-inactive">Nonaktif</span>`;

                const timeHtml = art.full_datetime
                    ? `<span>⏱️ ${art.full_datetime}</span>`
                    : (art.time ? `<span>⏱️ ${art.time}</span>` : '');

                const viewBtnHtml = art.status === 'published'
                    ? `<a href="${art.view_url}" target="_blank" class="modal-btn-view">👁️ Lihat di Web</a>`
                    : '';

                item.innerHTML = `
                    <div class="modal-article-top">
                        <div>
                            <div class="modal-article-title">${art.judul}</div>
                            <div class="modal-article-meta" style="margin-top:6px;">
                                <span class="cat-pill">${art.kategori}</span>
                                ${badgeHtml}
                                ${activeHtml}
                                ${timeHtml}
                            </div>
                        </div>
                    </div>
                    <div class="modal-article-actions">
                        <a href="${art.edit_url}" class="modal-btn-edit">✏️ Edit Artikel</a>
                        ${viewBtnHtml}
                    </div>
                `;
                modalBody.appendChild(item);
            });
        }

        modalCreateBtn.onclick = function() {
            window.location.href = `${createArticleBaseUrl}?date=${dateKey}`;
        };

        modalOverlay.classList.add('active');
    }

    function closeModal() {
        modalOverlay.classList.remove('active');
    }

    if (modalCloseBtn) modalCloseBtn.addEventListener('click', closeModal);
    if (modalDismiss) modalDismiss.addEventListener('click', closeModal);
    if (modalOverlay) {
        modalOverlay.addEventListener('click', function(e) {
            if (e.target === modalOverlay) closeModal();
        });
    }

    // Keyboard Escape to close modal
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && modalOverlay && modalOverlay.classList.contains('active')) {
            closeModal();
        }
    });

    // Navigasi Bulan Kalender
    const prevBtn = document.getElementById('cal-prev-btn');
    const nextBtn = document.getElementById('cal-next-btn');
    const todayBtn = document.getElementById('cal-today-btn');

    if (prevBtn) {
        prevBtn.addEventListener('click', function() {
            currentMonth--;
            if (currentMonth < 0) {
                currentMonth = 11;
                currentYear--;
            }
            renderCalendar();
        });
    }

    if (nextBtn) {
        nextBtn.addEventListener('click', function() {
            currentMonth++;
            if (currentMonth > 11) {
                currentMonth = 0;
                currentYear++;
            }
            renderCalendar();
        });
    }

    if (todayBtn) {
        todayBtn.addEventListener('click', function() {
            currentYear = today.getFullYear();
            currentMonth = today.getMonth();
            renderCalendar();
        });
    }

    // Inisialisasi awal
    @if(($activeTab ?? 'table') === 'calendar')
        renderCalendar();
    @endif
</script>
@endpush
@endsection
