@extends('layout')

@section('title', __('mytasks.title'))

{{-- 1. STYLES --}}
<style>
    /* --- General & Layout --- */
    body { background-color: #F3F4F6; font-family: 'Inter', sans-serif; }

    .main-wrapper { 
        max-width: 1200px; 
        margin: 0 auto; 
        padding: 40px 20px; 
        min-height: 85vh;
        display: flex; 
        flex-direction: column; 
    }

    @media (max-width: 768px) {
        .main-wrapper { padding: 20px 16px; }
    }

    /* --- HERO CARD CONTAINER --- */
    .task-hero {
        background-color: #FFFFFF;
        border-radius: 30px;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.1);
        display: flex;
        flex-direction: row;
        overflow: hidden;
        min-height: 600px; 
        border: 1px solid #E5E7EB;
    }

    @media(max-width: 900px) {
        .task-hero { flex-direction: column; min-height: auto; border-radius: 20px; }
    }

    /* --- LEFT SIDE (Interactive Hub) --- */
    .hero-left {
        flex: 1.3;
        padding: 60px;
        display: flex;
        flex-direction: column;
        position: relative;
        background-color: #FFFFFF;
    }

    @media(max-width: 900px) {
        .hero-left { padding: 32px 24px; }
    }

    /* Status & Headers */
    .status-badge {
        display: inline-flex; align-items: center; gap: 8px;
        font-size: 13px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em;
        margin-bottom: 20px;
        color: #6B7280;
    }
    .status-badge.active { color: #059669; } 
    .status-dot { width: 8px; height: 8px; border-radius: 50%; background-color: #D1D5DB; }
    .status-badge.active .status-dot { background-color: #10B981; box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.2); }

    .hero-headline {
        font-size: 42px; font-weight: 800; color: #111827; line-height: 1.1; margin-bottom: 16px;
    }
    @media (max-width: 768px) {
        .hero-headline { font-size: 28px; }
    }

    .hero-subtext {
        font-size: 16px; color: #4B5563; line-height: 1.6; max-width: 90%; margin-bottom: 15px;
    }
    
    .view-count {
        position: absolute; top: 24px; right: 24px;
        display: inline-flex; align-items: center; gap: 6px;
        font-size: 12px; font-weight: 600; color: #6B7280;
        background: #F9FAFB; padding: 6px 12px; border-radius: 999px;
        box-shadow: 0 4px 10px rgba(148, 163, 184, 0.25);
    }
    @media (max-width: 768px) {
        .view-count { top: 16px; right: 16px; }
    }

    /* Offers UI */
    .offers-header { text-align: center; display: flex; flex-direction: column; align-items: center; }
    .illustration-box { margin-bottom: 8px; }
    .questions-copy { font-size: 14px; color: #6B7280; max-width: 360px; }
    
    .custom-scrollbar::-webkit-scrollbar { width: 6px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background-color: #E5E7EB; border-radius: 20px; }
    
    /* --- RIGHT SIDE (Task Details) --- */
    .hero-right {
        flex: 1.2;
        background-color: #F7F2EB;
        padding: 50px;
        color: #111827;
        position: relative;
        display: flex;
        flex-direction: column;
    }
    @media(max-width: 900px) {
        .hero-right { padding: 32px 24px; border-top: 1px solid #E5E7EB; }
    }

    /* --- IMPROVED DROPDOWN --- */
    /* --- MANAGEMENT PILLS --- */
    .management-pills {
        display: flex;
        gap: 8px;
        align-items: center;
    }
    
    .pill-action {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 14px;
        border-radius: 99px;
        font-size: 13px;
        font-weight: 700;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        cursor: pointer;
        text-decoration: none;
        border: 1px solid #E5E7EB;
        background: #FFFFFF;
        color: #64748B;
    }
    
    .pill-action:hover {
        border-color: #3B82F6;
        color: #2563EB;
        background: #F8FAFC;
        transform: translateY(-1px);
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
    }
    
    .pill-action.danger:hover {
        border-color: #FECACA;
        color: #E11D48;
        background: #FFF1F2;
    }

    /* Task Content */
    .task-label { color: #6B7280; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; display: inline-flex; align-items: center; line-height: 1; margin-bottom: 0; }
    .task-main-title { font-size: 32px; font-weight: 800; line-height: 1.2; margin-top: 4px; margin-bottom: 10px; color: #111827; }
    .price-display { font-size: 36px; font-weight: 700; color: #2563EB; margin: 24px 0; letter-spacing: -1px; }

    .data-row { display: flex; gap: 15px; margin-bottom: 20px; align-items: flex-start; }
    .data-icon { width: 40px; height: 40px; background: #E5E7EB; border-radius: 10px; display: flex; align-items: center; justify-content: center; color: #4B5563; flex-shrink: 0; }
    .data-text h4 { font-size: 12px; color: #6B7280; text-transform: uppercase; font-weight: 700; margin-bottom: 2px; }
    .data-text p { font-size: 15px; font-weight: 500; color: #111827; }

    /* Description UI */
    .description-toggle { margin-top: 18px; background: transparent; border: none; cursor: pointer; text-align: left; }
    .task-description-truncated { font-size: 14px; color: #1F2933; line-height: 1.6em; margin-bottom: 4px; }
    .description-arrow { width: 16px; height: 16px; color: #2563EB; transition: transform 0.2s; display: inline-block; }
    .description-toggle:hover .description-arrow { transform: translateY(3px); }

    /* --- MODERN TABS (Posted/Applied) --- */
    .modern-tabs-wrapper {
        display: inline-flex; background: #F1F5F9; padding: 4px; border-radius: 16px;
        box-shadow: inset 0 2px 4px rgba(0,0,0,0.03);
    }
    @media (max-width: 768px) {
        .modern-tabs-wrapper { width: 100%; }
    }
    .modern-tab {
        padding: 10px 28px; font-size: 14px; font-weight: 600; color: #64748B;
        border-radius: 12px; transition: all 0.3s ease; text-decoration: none;
    }
    @media (max-width: 768px) {
        .modern-tab { flex: 1; text-align: center; padding: 10px 8px; font-size: 13px; }
    }
    .modern-tab.active {
        background: #FFFFFF; color: #2563EB; font-weight: 700;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    }

    /* --- CONTROLS BAR (Search & Status) --- */
    .controls-bar {
        background: #FFFFFF; border-radius: 20px; padding: 16px; border: 1px solid #E5E7EB;
        display: flex; justify-content: space-between; align-items: center;
        flex-wrap: wrap; gap: 12px; margin-bottom: 32px;
    }
    @media (max-width: 768px) {
        .controls-bar { padding: 12px; border-radius: 16px; margin-bottom: 24px; justify-content: center; }
    }

    .modern-search-wrapper { position: relative; flex: 1; max-width: 380px; }
    .modern-search-input {
        width: 100%; background: #F8FAFC; border: 1px solid #E2E8F0;
        padding: 12px 16px 12px 44px; border-radius: 12px; font-size: 14px; transition: all 0.2s;
    }
    .modern-search-input:focus { background: #FFFFFF; border-color: #3B82F6; box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1); outline: none; }

    .modern-filter-group { display: flex; gap: 4px; background: #F8FAFC; padding: 4px; border-radius: 12px; border: 1px solid #F1F5F9; }
    @media (max-width: 768px) {
        .modern-filter-group { width: 100%; justify-content: space-between; }
    }
    .filter-btn { padding: 8px 16px; font-size: 13px; font-weight: 600; color: #64748B; border-radius: 8px; transition: all 0.2s; text-decoration: none; }
    @media (max-width: 768px) {
        .filter-btn { flex: 1; text-align: center; padding: 8px 12px; font-size: 12px; }
    }
    .filter-btn.active { background: #FFFFFF; color: #2563EB; box-shadow: 0 1px 3px rgba(0,0,0,0.05); }

    /* --- Modal & Overlays --- */
    .task-details-modal {
        position: fixed; inset: 0; display: flex; justify-content: center; align-items: flex-start;
        padding: 60px 0; opacity: 0; pointer-events: none; transition: opacity 0.2s; z-index: 100; overflow-y: auto;
    }
    .task-details-modal.show { opacity: 1; pointer-events: auto; }
    .task-details-backdrop { position: fixed; inset: 0; background: rgba(15, 23, 42, 0.55); }
    .task-details-panel {
        position: relative; background: #FFFFFF; border-radius: 24px; padding: 32px 36px;
        max-width: 640px; width: 90%; margin: auto; box-shadow: 0 24px 60px rgba(15, 23, 42, 0.35);
    }

    .task-details-close {
        position: absolute; top: 12px; left: 12px;
        width: 32px; height: 32px; border-radius: 50%;
        background: #F3F4F6; border: 1px solid #E5E7EB; color: #4B5563;
        display: flex; align-items: center; justify-content: center;
        cursor: pointer; z-index: 50; transition: all 0.2s;
    }
    .task-details-close:hover { background: #E5E7EB; color: #111827; }

    .task-details-body {
        font-size: 15px; color: #4B5563; line-height: 1.6;
        padding-top: 10px;
    }

    @media (max-width: 640px) {
        .task-details-modal { padding: 20px 0; }
        .task-details-panel { padding: 48px 20px 24px; width: 95%; border-radius: 20px; }
    }

    /* --- High Contrast Overrides --- */
    .high-contrast .task-details-panel {
        background: #ffffff !important;
        border: 4px solid #000000 !important;
        box-shadow: none !important;
    }
    .high-contrast .task-details-close {
        background: #ffffff !important;
        border: 2px solid #000000 !important;
        color: #000000 !important;
    }
    .high-contrast .task-details-body {
        color: #000000 !important;
    }


    /* Modern Empty State */
    .modern-empty-state {
        text-align: center; padding: 80px 24px; background: #FFFFFF;
        border-radius: 32px; border: 2px dashed #E5E7EB;
        display: flex; flex-direction: column; align-items: center; justify-content: center;
    }

    .empty-illustration {
        width: 120px; height: 120px; background: linear-gradient(135deg, #F0F9FF 0%, #DBEAFE 100%);
        border-radius: 50%; display: flex; align-items: center; justify-content: center;
        margin-bottom: 24px; box-shadow: 0 10px 30px -10px rgba(59, 130, 246, 0.3); position: relative;
    }
    
    .empty-illustration i { color: #3B82F6; filter: drop-shadow(0 4px 6px rgba(59, 130, 246, 0.2)); }
    .empty-illustration::before {
        content: ''; position: absolute; inset: -12px; border-radius: 50%;
        border: 2px dashed #E0F2FE; animation: spin 30s linear infinite;
    }
    
    @keyframes spin { from {transform: rotate(0deg);} to {transform: rotate(360deg);} }

    .empty-title { font-size: 24px; font-weight: 800; color: #1E293B; margin-bottom: 12px; }
    .empty-desc { color: #64748B; font-size: 16px; max-width: 400px; line-height: 1.6; margin-bottom: 32px; }

    .cta-button {
        background: linear-gradient(135deg, #2563EB 0%, #1D4ED8 100%);
        color: white; padding: 14px 32px; border-radius: 14px;
        font-weight: 700; font-size: 15px; display: inline-flex;
        align-items: center; gap: 8px; transition: all 0.2s;
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.25); text-decoration: none;
    }
    
    .cta-button:hover {
        transform: translateY(-2px); box-shadow: 0 8px 16px rgba(37, 99, 235, 0.3); color: white;
    }

    /* --- Other Tasks --- */
    .other-tasks-container { margin-top: 50px; }
    .compact-task-row {
        background: white; padding: 18px 24px; border-radius: 16px; border: 1px solid #E5E7EB;
        display: flex; align-items: center; justify-content: space-between; margin-bottom: 12px;
        transition: all 0.2s; cursor: pointer; text-decoration: none; color: inherit;
    }
    .compact-task-row:hover { border-color: #3B82F6; transform: translateX(4px); box-shadow: 0 4px 12px rgba(0,0,0,0.03); }

    @media (max-width: 768px) {
        .compact-task-row { padding: 14px 16px; }
        .compact-task-row .text-base { font-size: 14px; }
    }

    /* --- High Contrast Overrides --- */
    .high-contrast .modern-tab {
        background: #ffffff !important;
        border: 2px solid #000000 !important;
        color: #000000 !important;
    }
    .high-contrast .modern-tab.active {
        background: #000000 !important;
        color: #ffffff !important;
        border-color: #000000 !important;
    }
    .high-contrast .modern-tabs-wrapper {
        background: transparent !important;
        border: none !important;
        box-shadow: none !important;
    }

    .high-contrast .controls-bar {
        border: 3px solid #000000 !important;
    }
    .high-contrast .modern-filter-group {
        background: transparent !important;
        border: none !important;
    }
    .high-contrast .filter-btn {
        background: #ffffff !important;
        border: 2px solid #000000 !important;
        color: #000000 !important;
        margin: 0 2px !important;
    }
    .high-contrast .filter-btn.active {
        background: #000000 !important;
        color: #ffffff !important;
    }

    .high-contrast .task-hero {
        border: 4px solid #000000 !important;
        background-color: #ffffff !important;
    }
    .high-contrast .hero-right {
        background-color: #ffffff !important;
        border-left: 3px solid #000000 !important;
    }
    @media(max-width: 900px) {
        .high-contrast .hero-right {
            border-left: none !important;
            border-top: 3px solid #000000 !important;
        }
    }

    .high-contrast .status-badge.active {
        color: #000000 !important;
    }
    .high-contrast .status-badge.active .status-dot {
        background-color: #000000 !important;
        box-shadow: 0 0 0 3px #000000 !important;
    }

    .high-contrast .view-count {
        background: #ffffff !important;
        border: 2px solid #000000 !important;
        color: #000000 !important;
        box-shadow: none !important;
    }

    .high-contrast .pill-action {
        background: #ffffff !important;
        border: 2px solid #000000 !important;
        color: #000000 !important;
    }
    .high-contrast .pill-action:hover {
        background: #000000 !important;
        color: #ffffff !important;
    }

    .high-contrast .bg-blue-100,
    .high-contrast .bg-indigo-100,
    .high-contrast .bg-blue-50,
    .high-contrast .bg-indigo-50 {
        background-color: #000000 !important;
        color: #ffffff !important;
        border: 2px solid #000000 !important;
    }
    .high-contrast .bg-blue-100 *,
    .high-contrast .bg-indigo-100 *,
    .high-contrast .bg-blue-50 *,
    .high-contrast .bg-indigo-50 * {
        color: #ffffff !important;
    }

    .high-contrast .hero-left .group.cursor-pointer,
    .high-contrast .offers-list .group {
        border: 2px solid #000000 !important;
        background: #ffffff !important;
    }
    .high-contrast .offers-list .group *,
    .high-contrast .price-display,
    .high-contrast .text-blue-600,
    .high-contrast .text-blue-900,
    .high-contrast .text-blue-700,
    .high-contrast .status-badge,
    .high-contrast #offer-details-modal * {
        color: #000000 !important;
    }
    .high-contrast #offer-details-modal .bg-blue-600,
    .high-contrast #offer-details-modal .bg-indigo-600 {
        background-color: #000000 !important;
        color: #ffffff !important;
    }
    .high-contrast #offer-details-modal .bg-blue-600 *,
    .high-contrast #offer-details-modal .bg-indigo-600 * {
        color: #ffffff !important;
    }
    .high-contrast #offer-details-modal a:hover span {
        color: #000000 !important;
        text-decoration: underline !important;
    }

    .high-contrast .modern-empty-state {
        border: 4px dashed #000000 !important;
        background-color: #ffffff !important;
    }
    .high-contrast .empty-illustration {
        background: #ffffff !important;
        border: 3px solid #000000 !important;
        box-shadow: none !important;
    }
    .high-contrast .empty-illustration i {
        color: #000000 !important;
        filter: none !important;
    }
    .high-contrast .empty-illustration::before {
        border-color: #000000 !important;
    }
    .high-contrast .cta-button {
        background: #000000 !important;
        color: #ffffff !important;
        border: 2px solid #000000 !important;
        box-shadow: none !important;
    }
    .high-contrast .cta-button:hover {
        background: #ffffff !important;
        color: #000000 !important;
        text-decoration: underline !important;
    }

    .high-contrast .bg-green-600,
    .high-contrast .bg-indigo-600,
    .high-contrast .bg-blue-600 {
        background-color: #000000 !important;
        color: #ffffff !important;
        border: 2px solid #000000 !important;
    }
    .high-contrast .bg-green-600 *,
    .high-contrast .bg-indigo-600 *,
    .high-contrast .bg-blue-600 * {
        color: #ffffff !important;
    }
    .high-contrast .bg-green-600:hover {
        background-color: #ffffff !important;
        color: #000000 !important;
        text-decoration: underline !important;
    }
    .high-contrast .bg-green-600:hover * {
        color: #000000 !important;
    }

    .high-contrast [style*="color: #F59E0B"],
    .high-contrast [style*="color:#F59E0B"],
    .high-contrast .status-badge[style*="color"] {
        color: #000000 !important;
    }
    .high-contrast [style*="background-color: #FBBF24"],
    .high-contrast [style*="background-color:#FBBF24"] {
        background-color: #000000 !important;
        box-shadow: 0 0 0 3px #000000 !important;
    }

    .high-contrast .data-icon {
        background: #ffffff !important;
        border: 2px solid #000000 !important;
        color: #000000 !important;
    }
    .high-contrast .data-icon i {
        color: #000000 !important;
    }

    .high-contrast .text-gray-500,
    .high-contrast .text-slate-500 {
        color: #000000 !important;
    }

    .high-contrast .bg-gray-100 i {
        color: #000000 !important;
    }

    /* Accessibility & Focus States */
    :focus-visible {
        outline: 3px solid #2563EB !important;
        outline-offset: 2px !important;
        box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.2) !important;
    }

    .high-contrast :focus-visible {
        outline: 4px solid #000000 !important;
        outline-offset: 4px !important;
        box-shadow: 0 0 0 6px #ffffff, 0 0 0 10px #000000 !important;
    }

    .task-details-modal {
        visibility: hidden;
        transition: opacity 0.2s, visibility 0.2s;
    }

    .task-details-modal.show {
        visibility: visible;
    }

    /* ========== DARK MODE OVERRIDES ========== */
    html.dark body { background-color: #0f172a !important; }

    /* Hero Card */
    html.dark .task-hero { background-color: #1e293b !important; border-color: #334155 !important; }
    html.dark .hero-left { background-color: #273449 !important; }
    html.dark .hero-right { background-color: #1e293b !important; border-color: #334155 !important; }
    @media(max-width: 900px) {
        html.dark .hero-right { border-top-color: #334155 !important; }
    }

    /* Tabs */
    html.dark .modern-tabs-wrapper { background-color: #1e293b !important; }
    html.dark .modern-tab { color: #94a3b8 !important; }
    html.dark .modern-tab.active { background-color: #334155 !important; color: #60a5fa !important; }

    /* Controls Bar */
    html.dark .controls-bar { background-color: #1e293b !important; border-color: #334155 !important; }
    html.dark .modern-search-input { background-color: #0f172a !important; border-color: #334155 !important; color: #f8fafc !important; }
    html.dark .modern-search-input:focus { background-color: #1e293b !important; border-color: #3b82f6 !important; }
    html.dark .modern-filter-group { background-color: #0f172a !important; border-color: #334155 !important; }
    html.dark .filter-btn { color: #94a3b8 !important; }
    html.dark .filter-btn.active { background-color: #334155 !important; color: #60a5fa !important; }

    /* Status & Headlines */
    html.dark .hero-headline { color: #f8fafc !important; }
    html.dark .hero-subtext { color: #cbd5e1 !important; }
    html.dark .status-badge { color: #94a3b8 !important; }
    html.dark .view-count { background-color: #334155 !important; color: #94a3b8 !important; box-shadow: none !important; border: 1px solid #475569 !important; }

    /* Price & Data */
    html.dark .price-display { color: #60a5fa !important; }
    html.dark .task-label { color: #94a3b8 !important; }
    html.dark .task-main-title { color: #f8fafc !important; }
    html.dark .data-icon { background-color: #334155 !important; color: #94a3b8 !important; }
    html.dark .data-text h4 { color: #94a3b8 !important; }
    html.dark .data-text p { color: #f1f5f9 !important; }

    /* Description */
    html.dark .task-description-truncated { color: #cbd5e1 !important; }

    /* Offer Cards */
    html.dark .offers-list .group { background-color: #0f172a !important; border-color: #334155 !important; }
    html.dark .offers-list .group:hover { border-color: #3b82f6 !important; }
    html.dark .offers-list .group h4 { color: #f8fafc !important; }
    html.dark .offers-header h3 { color: #f8fafc !important; }
    html.dark .questions-copy { color: #94a3b8 !important; }

    /* Pill Actions (Edit / Cancel) */
    html.dark .pill-action { background-color: #0f172a !important; border-color: #334155 !important; color: #94a3b8 !important; }
    html.dark .pill-action:hover { border-color: #3b82f6 !important; color: #60a5fa !important; background-color: #1e293b !important; }
    html.dark .pill-action.danger:hover { border-color: #f87171 !important; color: #f87171 !important; background-color: #1e293b !important; }

    /* Modals */
    html.dark .task-details-panel { background-color: #1e293b !important; color: #f8fafc !important; }
    html.dark .task-details-close { background-color: #334155 !important; border-color: #475569 !important; color: #94a3b8 !important; }
    html.dark .task-details-close:hover { background-color: #475569 !important; color: #f8fafc !important; }
    html.dark .task-details-body { color: #cbd5e1 !important; }

    /* Empty State */
    html.dark .modern-empty-state { background-color: #1e293b !important; border-color: #334155 !important; }
    html.dark .empty-title { color: #f8fafc !important; }
    html.dark .empty-desc { color: #94a3b8 !important; }
    html.dark .empty-illustration { background: linear-gradient(135deg, #1e293b 0%, #334155 100%) !important; }

    /* Compact Task Rows (Other Tasks) */
    html.dark .compact-task-row { background-color: #1e293b !important; border-color: #334155 !important; color: #f8fafc !important; }
    html.dark .compact-task-row:hover { border-color: #3b82f6 !important; }

    /* Blue badges */
    html.dark .bg-blue-50 { background-color: rgba(37, 99, 235, 0.15) !important; border-color: #1e40af !important; }
    html.dark .bg-blue-100 { background-color: rgba(37, 99, 235, 0.2) !important; }
    html.dark .bg-indigo-100 { background-color: rgba(99, 102, 241, 0.2) !important; }
    html.dark .text-blue-900 { color: #93c5fd !important; }
    html.dark .text-blue-700 { color: #60a5fa !important; }
    html.dark .border-blue-100 { border-color: #1e3a8a !important; }

    /* Text Utilities */
    html.dark .text-gray-900 { color: #f8fafc !important; }
    html.dark .text-gray-800 { color: #f1f5f9 !important; }
    html.dark .text-gray-700 { color: #e2e8f0 !important; }
    html.dark .text-gray-600 { color: #cbd5e1 !important; }
    html.dark .text-gray-500 { color: #94a3b8 !important; }
    html.dark .text-gray-400 { color: #64748b !important; }
    html.dark .text-gray-300 { color: #475569 !important; }

    /* Border Utilities */
    html.dark .border-gray-200, html.dark .border-gray-100 { border-color: #334155 !important; }
    html.dark .bg-white { background-color: #1e293b !important; }
    html.dark .bg-gray-100 { background-color: #334155 !important; }

    /* Offer Details Modal */
    html.dark #offer-details-modal .bg-white { background-color: #1e293b !important; }
    html.dark #offer-details-modal .border-gray-100 { border-color: #334155 !important; }

    /* Complete Task Modal */
    html.dark #complete-task-modal .bg-white { background-color: #1e293b !important; }

    /* Scrollbar */
    html.dark .custom-scrollbar::-webkit-scrollbar-thumb { background-color: #475569 !important; }
</style>

@section('content')

<section class="min-h-screen">
    <div class="main-wrapper">
        
        @php
            $tasksCollection = $tasks ?? collect([]);
            $hasTasks = $tasksCollection->count() > 0;
            
            $activeTask = $focusedTask ?? ($hasTasks ? $tasksCollection->first() : null);
            
            if ($activeTask) {
                $otherTasks = $tasksCollection->where('id', '!=', $activeTask->id)->values();
                $offerCount = $activeTask->offers_count ?? 0; 
                $hasOffers = $offerCount > 0;
                $viewCount = $activeTask->views ?? 0;
                $showOffers = $activeTask->status === 'open' && in_array(($viewMode ?? 'posted'), ['posted', 'direct']);
            }
        @endphp

        <script>
            (function() {
                const hash = window.location.hash;
                if (hash && hash.startsWith('#task-')) {
                    const taskId = hash.replace('#task-', '');
                    const url = new URL(window.location.href);
                    if (url.searchParams.get('task_id') !== taskId) {
                        url.searchParams.set('task_id', taskId);
                        window.location.href = url.toString();
                    }
                }
            })();
        </script>

        {{-- MAIN TABS --}}
        <div class="flex justify-center mb-8">
            <div class="modern-tabs-wrapper">
                <a href="{{ route('my-tasks', ['view' => 'posted']) }}" 
                   class="modern-tab {{ ($viewMode ?? 'posted') === 'posted' ? 'active' : '' }}">
                   {{ __('mytasks.tabs.posted') }}
                </a>
                <a href="{{ route('my-tasks', ['view' => 'direct']) }}" 
                   class="modern-tab {{ ($viewMode ?? 'posted') === 'direct' ? 'active' : '' }}">
                   {{ __('Direct Requests') }}
                </a>
                <a href="{{ route('my-tasks', ['view' => 'applied']) }}" 
                   class="modern-tab {{ ($viewMode ?? 'posted') === 'applied' ? 'active' : '' }}">
                   {{ __('mytasks.tabs.applied') }}
                </a>
            </div>
        </div>

        {{-- CONTROLS BAR --}}
        <div class="controls-bar">
            <form method="GET" action="{{ route('my-tasks') }}" class="modern-search-wrapper hidden md:block">
                <i data-feather="search" class="search-icon" style="position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: #94a3b8; width: 18px; height: 18px;"></i>
                <input name="q" value="{{ $filters['q'] ?? '' }}" type="text" placeholder="{{ __('mytasks.search_placeholder') }}" class="modern-search-input" autocomplete="off">
                <input type="hidden" name="status" value="{{ $filters['status'] ?? 'posted' }}">
                <input type="hidden" name="view" value="{{ $viewMode ?? 'posted' }}">
            </form>

            <div class="modern-filter-group">
                <a href="{{ route('my-tasks', ['view' => $viewMode ?? 'posted', 'status' => 'posted']) }}" class="filter-btn {{ in_array(($filters['status'] ?? 'posted'), ['posted', '']) ? 'active' : '' }}">{{ __('mytasks.filters.posted') }}</a>
                <a href="{{ route('my-tasks', ['view' => $viewMode ?? 'posted', 'status' => 'pending']) }}" class="filter-btn {{ in_array(($filters['status'] ?? ''), ['pending', 'assigned']) ? 'active' : '' }}">{{ __('mytasks.filters.pending') }}</a>
                <a href="{{ route('my-tasks', ['view' => $viewMode ?? 'posted', 'status' => 'completed']) }}" class="filter-btn {{ in_array(($filters['status'] ?? ''), ['completed']) ? 'active' : '' }}">{{ __('mytasks.filters.completed') }}</a>
            </div>
        </div>

        @if($hasTasks)
            
            <div class="task-hero">
                
                {{-- LEFT SIDE --}}
                <div class="hero-left">
                    @if(($viewMode ?? 'posted') === 'applied')
                        @php
                            $myOffer = $activeTask->offers->where('user_id', auth()->id())->first();
                        @endphp

                        @if($activeTask->status === 'assigned')
                            @if($activeTask->employee_id == auth()->id())
                                <div class="status-badge active" style="color:#059669;">
                                    <span class="status-dot" style="background-color:#10B981;"></span> {{ __('mytasks.status.offer_accepted') }}
                                </div>
                                <h1 class="hero-headline">{{ __('mytasks.status.youre_hired') }}</h1>
                                <p class="hero-subtext">{{ __('mytasks.status.hired_desc') }}</p>
                                <div class="mt-6 flex gap-3">
                                    <a href="{{ route('messages', ['user_id' => $activeTask->employer_id]) }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-8 rounded-full shadow-lg transition flex items-center gap-2 no-underline">
                                        <i data-feather="message-circle" class="w-5 h-5"></i> {{ __('mytasks.status.contact_employer') }}
                                    </a>
                                </div>
                            @else
                                <div class="status-badge" style="color:#DC2626;">
                                    <span class="status-dot" style="background-color:#EF4444;"></span> {{ __('mytasks.status.offer_not_selected') }}
                                </div>
                                <h1 class="hero-headline">{{ __('mytasks.status.task_assigned') }}</h1>
                                <p class="hero-subtext">{{ __('mytasks.status.assigned_desc') }}</p>
                                <div class="mt-6">
                                    <a href="{{ route('tasks') }}" class="text-blue-600 font-bold hover:underline">{{ __('mytasks.status.browse_more') }}</a>
                                </div>
                            @endif

                        @elseif($activeTask->status === 'completed')
                            @if($activeTask->employee_id == auth()->id())
                                <div class="status-badge active">
                                    <span class="status-dot"></span> {{ __('mytasks.status.job_done') }}
                                </div>
                                <h1 class="hero-headline">{{ __('mytasks.status.task_completed') }}</h1>
                                <p class="hero-subtext">{{ __('mytasks.status.completed_desc') }}</p>
                            @else
                                <div class="status-badge">
                                    <span class="status-dot"></span> {{ __('mytasks.status.closed') }}
                                </div>
                                <h1 class="hero-headline">{{ __('mytasks.status.task_completed') }}</h1>
                                <p class="hero-subtext">{{ __('mytasks.status.other_completed_desc') }}</p>
                            @endif

                        @else
                            @if($myOffer)
                                <div class="flex items-center gap-2 mb-5">
                                    <div class="status-badge" style="color:#2563EB; margin-bottom: 0;">
                                        <span class="status-dot" style="background-color:#3B82F6;"></span> {{ __('mytasks.status.application_sent') }}
                                    </div>
                                    @if($activeTask->employee_id == auth()->id())
                                    <div class="flex items-center gap-1.5 bg-blue-50 text-blue-700 px-2 py-1 rounded-full border border-blue-100 h-fit">
                                        <i data-feather="user-check" class="w-3.5 h-3.5"></i>
                                        <span class="text-[10px] font-bold uppercase tracking-wider">{{ __('Directly requested from you') }}</span>
                                    </div>
                                    @endif
                                </div>
                                <h1 class="hero-headline">{{ __('mytasks.status.waiting_response') }}</h1>
                                <p class="hero-subtext">
                                    {{ __('mytasks.status.you_offered') }} <strong>€{{ number_format($myOffer->price ?? 0, 0) }}</strong>.
                                    {{ __('mytasks.status.notify_offers') }}
                                </p>
                                <div class="bg-blue-50 border border-blue-100 p-4 rounded-xl mt-6">
                                    <h4 class="font-bold text-blue-900 text-sm mb-1">{{ __('mytasks.status.your_message') }}</h4>
                                    <p class="text-blue-700 text-sm italic">"{{ $myOffer->message ?? '' }}"</p>
                                </div>
                            @elseif($activeTask->employee_id == auth()->id())
                                <div class="status-badge" style="color:#6366f1;">
                                    <span class="status-dot" style="background-color:#6366f1;"></span> {{ __('New Quote Request') }}
                                </div>
                                <h1 class="hero-headline">{{ __('mytasks.status.direct_request_headline') }}</h1>
                                <p class="hero-subtext">{{ __('mytasks.status.direct_request_subtext') }}</p>
                                <div class="mt-8 flex flex-wrap gap-4">
                                    <button type="button" onclick="openDirectQuoteModal()" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3.5 px-8 rounded-full shadow-lg shadow-indigo-200 transition-all flex items-center gap-2 cursor-pointer">
                                        {{ __('mytasks.status.accept_or_counter') }} <i data-feather="arrow-right" class="w-5 h-5"></i>
                                    </button>
                                    <a href="{{ route('messages', ['user_id' => $activeTask->employer_id]) }}" class="bg-white border border-gray-200 hover:bg-gray-50 text-gray-700 font-bold py-3.5 px-8 rounded-full transition-all flex items-center gap-2 no-underline">
                                        <i data-feather="message-circle" class="w-5 h-5"></i> {{ __('Message Employer') }}
                                    </a>
                                </div>
                            @endif
                        @endif

                    @elseif($activeTask->status === 'assigned')
                        <div class="status-badge" style="color: #F59E0B;">
                            <span class="status-dot" style="background-color: #FBBF24;"></span> {{ __('mytasks.status.in_progress') }}
                        </div>
                        
                        @if($activeTask->employer_id == auth()->id())
                            @if($activeTask->is_direct)
                                <h1 class="hero-headline">{{ __('Your request has been accepted!') }}</h1>
                                <p class="hero-subtext">{{ __('The tasker has accepted your direct request. You can now coordinate details and mark the task as completed once done.') }}</p>
                            @else
                                <h1 class="hero-headline">{{ __('mytasks.status.task_underway') }}</h1>
                                <p class="hero-subtext">{{ __('mytasks.status.underway_desc') }}</p>
                            @endif
                            <div class="mt-6">
                                <button type="button" onclick="openCompleteTaskModal()" class="bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-8 rounded-full shadow-lg transition transform hover:-translate-y-1 flex items-center gap-2">
                                    <i data-feather="check-circle" class="w-5 h-5"></i> {{ __('mytasks.status.mark_completed') }}
                                </button>
                            </div>
                        @else
                            <h1 class="hero-headline">{{ __('mytasks.status.waiting_for_completion') }}</h1>
                            <p class="hero-subtext">{{ __('mytasks.status.waiting_for_completion_desc') }}</p>
                            <div class="mt-6">
                                <a href="{{ route('messages', ['user_id' => $activeTask->employer_id]) }}" class="bg-white border border-gray-200 hover:bg-gray-50 text-gray-700 font-bold py-3 px-8 rounded-full transition-all flex items-center gap-2 no-underline inline-flex">
                                    <i data-feather="message-circle" class="w-5 h-5"></i> {{ __('mytasks.status.contact_employer') }}
                                </a>
                            </div>
                        @endif
                    
                    @elseif($activeTask->status === 'completed')
                        <div class="status-badge active">
                            <span class="status-dot"></span> {{ __('mytasks.filters.completed') }}
                        </div>
                        @if($activeTask->employer_id == auth()->id())
                            <h1 class="hero-headline">{{ __('mytasks.status.task_completed_employer') }}</h1>
                            <p class="hero-subtext">{{ __('mytasks.status.posted_completed_desc') }}</p>
                        @else
                            <h1 class="hero-headline">{{ __('mytasks.status.task_completed_employee') }}</h1>
                            <p class="hero-subtext">{{ __('mytasks.status.completed_desc') }}</p>
                        @endif
                    
                    @elseif($hasOffers)
                        @if(($viewMode ?? 'posted') === 'direct')
                            <div class="status-badge active" style="color:#6366f1;">
                                <span class="status-dot" style="background-color:#6366f1;"></span> {{ __('Quote Received') }}
                            </div>
                            <h1 class="hero-headline">{{ __('New response!') }}</h1>
                            <p class="hero-subtext">{{ __('The expert has sent you a price quote for your request. Review it below to proceed.') }}</p>
                        @else
                            <div class="status-badge active">
                                <span class="status-dot"></span> {{ __('mytasks.status.new_activity') }}
                            </div>
                            <h1 class="hero-headline">{{ __('mytasks.status.new_offers') }}</h1>
                            <p class="hero-subtext">{{ __('mytasks.status.offers_desc', ['count' => $offerCount]) }}.</p>
                        @endif
                    @else
                        @if(($viewMode ?? 'posted') === 'direct')
                            <div class="status-badge" style="color:#6B7280;">
                                <span class="status-dot"></span> {{ __('Request Sent') }}
                            </div>
                            <h1 class="hero-headline">{{ __('Waiting for response') }}</h1>
                            <p class="hero-subtext">{{ __('You have requested a quote from :name. We will notify you as soon as they respond with their price.', ['name' => $activeTask->employee->first_name ?? 'the expert']) }}</p>
                        @else
                            <div class="status-badge">
                                <span class="status-dot"></span> {{ __('mytasks.status.task_posted') }}
                            </div>
                            <h1 class="hero-headline">{{ __('mytasks.status.find_taskers') }}</h1>
                            <p class="hero-subtext">{{ __('mytasks.status.notify_offers') }}</p>
                        @endif
                    @endif

                    <div class="offers-container mt-6">
                        @if(($viewMode ?? 'posted') === 'direct' && $activeTask->status === 'open')
                            <div class="offers-header mb-4">
                                <div class="illustration-box">
                                    <div class="bg-indigo-100 p-3 rounded-full inline-block">
                                        <i data-feather="user-check" class="text-indigo-600 w-8 h-8"></i>
                                    </div>
                                </div>
                                @if($hasOffers)
                                    <h3 class="text-lg font-bold text-gray-800">{{ __('Review Quote') }}</h3>
                                    <p class="questions-copy mt-2">{{ __('The requested expert has replied with their proposed price and details.') }}</p>
                                @else
                                    <h3 class="text-lg font-bold text-gray-800">{{ __('Awaiting Response') }}</h3>
                                    <p class="questions-copy mt-2">{{ __('The expert hasn\'t responded to your request yet. We\'ll let you know when they do.') }}</p>
                                @endif
                            </div>
                        @elseif(($viewMode ?? 'posted') === 'posted' && $activeTask->status === 'open')
                            <div class="offers-header mb-4">
                                <div class="illustration-box">
                                    <div class="bg-blue-100 p-3 rounded-full inline-block">
                                        <i data-feather="users" class="text-blue-600 w-8 h-8"></i>
                                    </div>
                                </div>
                                @if($hasOffers)
                                    <h3 class="text-lg font-bold text-gray-800">{{ __('mytasks.offers.header_count', ['count' => $offerCount]) }}</h3>
                                    <p class="questions-copy mt-2">{{ __('mytasks.offers.header_desc') }}</p>
                                @else
                                    <h3 class="text-lg font-bold text-gray-800">{{ __('mytasks.offers.waiting') }}</h3>
                                    <p class="questions-copy mt-2">{{ __('mytasks.offers.waiting_desc') }}</p>
                                @endif
                            </div>
                        @endif

                        <div class="offers-list overflow-y-auto custom-scrollbar" style="max-height: 120px; padding-right: 8px;">
                            @if($hasOffers && $showOffers)
                                <div class="space-y-3">
                                    @foreach($activeTask->offers as $offer)
                                        <div onclick="openOfferModal({
                                            id: '{{ $offer->id }}',
                                            userId: '{{ $offer->user_id }}',
                                            initials: '{{ substr($offer->user->first_name ?? 'T', 0, 1) }}',
                                            avatarUrl: '{{ $offer->user->avatar_url }}',
                                            name: '{{ $offer->user->first_name ?? 'Tasker' }} {{ $offer->user->last_name ?? '' }}',
                                            rating: '{{ $offer->user->rating }}',
                                            time: '{{ $offer->created_at?->diffForHumans(null, true, true) }}',
                                            price: '{{ number_format($offer->price, 0) }}',
                                            message: `{{ addslashes($offer->message) }}`
                                        })" role="button" tabindex="0" aria-label="{{ __('Review offer from :name', ['name' => $offer->user->first_name ?? 'Tasker']) }}" class="group w-full p-3 rounded-xl border border-gray-200 bg-white hover:border-blue-400 hover:shadow-sm transition-all cursor-pointer relative overflow-hidden">
                                            <div class="absolute left-0 top-0 bottom-0 w-1 bg-blue-500 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                                            <div class="flex items-start gap-3">
                                                <img src="{{ $offer->user->avatar_url }}" alt="Avatar" class="w-10 h-10 rounded-full object-cover border border-gray-200 shrink-0">
                                                <div class="flex-1 min-w-0">
                                                    <div class="flex justify-between items-start">
                                                        <div>
                                                            <h4 class="text-sm font-bold text-gray-900 leading-tight">
                                                                {{ $offer->user->first_name ?? 'Tasker' }} {{ $offer->user->last_name ?? '' }}
                                                            </h4>
                                                            <div class="flex items-center gap-1 mt-1">
                                                                <i data-feather="star" class="w-3 h-3 text-yellow-400 fill-current"></i>
                                                                <span class="text-xs font-bold text-gray-800">{{ $offer->user->rating }}</span>
                                                                <span class="text-gray-300 mx-1">•</span>
                                                                <span class="text-[10px] text-gray-400 font-medium uppercase tracking-wide">
                                                                    {{ $offer->created_at?->diffForHumans(null, true, true) }} {{ __('mytasks.stats.ago') }}
                                                                </span>
                                                            </div>
                                                        </div>
                                                        <div class="text-right">
                                                            <div class="text-base font-bold text-blue-600 leading-tight">
                                                                €{{ number_format($offer->price, 0) }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <p class="text-xs text-gray-600 mt-2 leading-relaxed">
                                                        {{ \Illuminate\Support\Str::limit($offer->message, 50, '...') }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="view-count">
                        <i data-feather="eye" style="width:14px;"></i> {{ $viewCount }} {{ __('mytasks.stats.views') }}
                    </div>
                </div>

                {{-- RIGHT SIDE --}}
                <div class="hero-right">

                    <div>
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center gap-2">
                                <span class="task-label">{{ __('mytasks.details.status_label', ['status' => ucfirst($activeTask->status)]) }}</span>
                                @if(($viewMode ?? 'posted') === 'posted' && $activeTask->employee_id)
                                <div class="flex items-center gap-1.5 bg-blue-50 text-blue-700 px-2 py-0.5 rounded-full border border-blue-100">
                                    <i data-feather="user" class="w-3 h-3"></i>
                                    <span class="text-[10px] font-bold uppercase tracking-wider">{{ __('Sent a quote to') }} {{ $activeTask->employee->first_name }}</span>
                                </div>
                                @endif
                            </div>

                            @if(($viewMode ?? 'posted') === 'posted' && $activeTask->status === 'open')
                            <div class="management-pills">
                                <button type="button" class="pill-action" onclick="openEditTaskModal()">
                                    <i data-feather="edit-2" style="width:12px; height:12px;"></i>
                                    {{ __('Edit') }}
                                </button>
                                <form action="{{ route('advertisements.destroy', $activeTask->id) }}" method="POST" class="m-0 p-0" onsubmit="return confirm('{{ __('Are you sure you want to cancel this task? This cannot be undone.') }}');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="pill-action danger">
                                        <i data-feather="trash-2" style="width:12px; height:12px;"></i>
                                        {{ __('Cancel') }}
                                    </button>
                                </form>
                            </div>
                            @endif
                        </div>
                        <h2 class="task-main-title">{{ $activeTask->title }}</h2>
                        
                        @if(($viewMode ?? 'posted') === 'applied' && $activeTask->employer)
                            <div class="mb-5">
                                <a href="{{ route('public-profile', $activeTask->employer->id) }}" class="inline-flex items-center gap-2 group no-underline">
                                    <div class="relative">
                                        <img src="{{ $activeTask->employer->avatar_url }}" class="w-7 h-7 rounded-full object-cover border-2 border-white shadow-sm group-hover:border-blue-100 transition-all" alt="{{ $activeTask->employer->first_name }}">
                                        <div class="absolute -bottom-0.5 -right-0.5 w-2.5 h-2.5 bg-green-500 border-2 border-white rounded-full"></div>
                                    </div>
                                    <span class="text-sm font-bold text-gray-600 group-hover:text-blue-600 transition-colors uppercase tracking-tight">
                                        {{ $activeTask->employer->first_name }} {{ $activeTask->employer->last_name }}
                                    </span>
                                </a>
                            </div>
                        @endif
                        
                        @if(!empty($activeTask->description))
                            <button type="button" class="description-toggle" onclick="openTaskDetailsModal()" title="Click to read full description">
                                <p class="task-description-truncated">
                                    {{ \Illuminate\Support\Str::limit($activeTask->description, 57, '...') }}
                                </p>
                                <div class="flex items-center gap-1 text-sm font-semibold text-blue-600 mt-1">
                                    {{ __('mytasks.actions.read_more') }} <i data-feather="chevron-down" class="description-arrow"></i>
                                </div>
                            </button>
                        @endif

                        <div class="price-display">€{{ number_format($activeTask->price, 0) }}</div>
                    </div>

                    <div class="hero-right-details">
                        <div class="data-row">
                            <div class="data-icon"><i data-feather="calendar"></i></div>
                            <div class="data-text">
                                <h4>{{ __('mytasks.details.due_date') }}</h4>
                                <p>
                                    @if($activeTask->is_date_flexible)
                                        {{ __('mytasks.details.flexible') }}
                                    @elseif($activeTask->required_date)
                                        {{ \Carbon\Carbon::parse($activeTask->required_date)->format('M d, Y') }}
                                    @elseif($activeTask->required_before_date)
                                      Before {{ \Carbon\Carbon::parse($activeTask->required_before_date)->format('M d, Y') }}
                                    @else
                                        {{ __('mytasks.details.flexible') }}
                                    @endif
                                </p>
                            </div>
                        </div>
                        <div class="data-row">
                            <div class="data-icon"><i data-feather="map-pin"></i></div>
                            <div class="data-text">
                                <h4>{{ __('mytasks.details.location') }}</h4>
                                <p>{{ optional(optional($activeTask->employer)->city)->name ?? 'Remote' }}</p>
                            </div>
                        </div>
                        <div class="data-row">
                            <div class="data-icon"><i data-feather="tag"></i></div>
                            <div class="data-text">
                                <h4>{{ __('mytasks.details.category') }}</h4>
                                <p>{{ optional($activeTask->category)->name ?? 'General' }}</p>
                            </div>
                        </div>
                        <div class="data-row">
                            <div class="data-icon"><i data-feather="file-text"></i></div>
                            <div class="data-text">
                                <h4>{{ __('mytasks.details.job') }}</h4>
                                <p>{{ optional($activeTask->job)->name ?? ('Job #'.$activeTask->job_id) }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- MODAL: Full Description --}}
                    <div id="task-details-modal" class="task-details-modal">
                        <div id="task-details-backdrop" class="task-details-backdrop" onclick="closeTaskDetailsModal()"></div>
                        <div class="task-details-panel">
                            <button type="button" class="task-details-close" onclick="closeTaskDetailsModal()" aria-label="Close modal">
                                <i data-feather="x" style="width:16px; height:16px;"></i>
                            </button>
                            @if(!empty($activeTask->description))
                                <div id="task-details-body" class="task-details-body">{{ $activeTask->description }}</div>
                            @endif
                        </div>
                    </div>

                    {{-- MODAL: Complete & Review --}}
                    <div id="complete-task-modal" class="task-details-modal">
                        <div class="task-details-backdrop" onclick="closeCompleteTaskModal()"></div>
                        <div class="task-details-panel" style="max-width: 450px;">
                            <button type="button" class="task-details-close" onclick="closeCompleteTaskModal()" aria-label="{{ __('Close modal') }}">
                                <i data-feather="x" style="width:16px; height:16px;"></i>
                            </button>
                            <h3 class="text-2xl font-bold text-gray-900 mb-2">{{ __('mytasks.status.task_completed') }}</h3>
                            <p class="text-gray-500 mb-6">{{ __('Would you like to leave a review for the Tasker?') }}</p>
                            <div id="complete-choice-buttons" class="grid grid-cols-1 gap-3">
                                <button onclick="showReviewForm()" class="h-14 w-full flex items-center justify-center gap-2 px-4 rounded-xl bg-blue-600 text-white font-bold hover:bg-blue-700 transition">
                                    <i data-feather="star" class="w-4 h-4"></i> {{ __('Yes, leave a review') }}
                                </button>
                                <form action="{{ route('advertisements.complete', $activeTask->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="h-14 w-full flex items-center justify-center gap-2 px-4 rounded-xl bg-gray-100 text-gray-600 font-bold hover:bg-gray-200 transition">
                                        {{ __('No, just complete task') }}
                                    </button>
                                </form>
                            </div>
                            <div id="complete-review-form" class="hidden">
                                <form action="{{ route('advertisements.complete', $activeTask->id) }}" method="POST">
                                    @csrf
                                    <div class="mb-4">
                                        <label class="block text-sm font-bold text-gray-700 mb-2">{{ __('Rating') }}</label>
                                        <div class="flex gap-2 text-2xl" id="star-rating-input">
                                            @for($i=1; $i<=5; $i++)
                                                <i role="button" tabindex="0" aria-label="{{ __('Rate :count stars', ['count' => $i]) }}" data-feather="star" class="cursor-pointer text-gray-300 hover:text-yellow-400" onclick="setRating({{ $i }})" id="star-{{ $i }}"></i>
                                            @endfor
                                        </div>
                                        <input type="hidden" name="stars" id="rating-value" required>
                                    </div>
                                    <div class="mb-6">
                                        <label class="block text-sm font-bold text-gray-700 mb-2">{{ __('Comment') }}</label>
                                        <textarea name="comment" rows="3" class="w-full border border-gray-300 rounded-xl p-3 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="{{ __('Write a short review...') }}"></textarea>
                                    </div>
                                    <button type="submit" class="h-14 w-full flex items-center justify-center gap-2 px-4 rounded-xl bg-blue-600 text-white font-bold hover:bg-blue-700 transition shadow-lg shadow-blue-200">
                                        {{ __('Complete & Review') }}
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    {{-- MODAL: Offer Details --}}
                    <div id="offer-details-modal" class="task-details-modal" style="z-index: 60;">
                        <div class="task-details-backdrop" onclick="closeOfferModal()"></div>
                        <div class="task-details-panel" style="max-width: 500px;">
                            <button type="button" class="task-details-close" onclick="closeOfferModal()" aria-label="{{ __('Close modal') }}">
                                <i data-feather="x" style="width:16px; height:16px;"></i>
                            </button>
                            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-6">
                                <a id="modal-profile-link" href="#" class="flex items-center gap-3 group text-decoration-none">
                                    <div id="modal-offer-avatar" class="w-14 h-14 sm:w-16 sm:h-16 rounded-full bg-gray-100 flex items-center justify-center text-gray-500 font-bold text-xl sm:text-2xl border border-gray-200 group-hover:border-blue-400 transition-colors shrink-0"></div>
                                    <div class="min-w-0">
                                        <h3 id="modal-offer-name" class="text-lg sm:text-xl font-bold text-gray-900 leading-tight group-hover:text-blue-600 transition-colors truncate"></h3>
                                        <div class="flex items-center gap-1 mt-1">
                                            <i data-feather="star" class="w-3.5 h-3.5 text-yellow-400 fill-current"></i>
                                            <span id="modal-offer-rating" class="text-xs sm:text-sm font-bold text-gray-800"></span>
                                            <span class="text-gray-300 mx-1">•</span>
                                            <span id="modal-offer-time" class="text-[10px] sm:text-xs text-gray-400 font-medium uppercase tracking-wide"></span>
                                        </div>
                                    </div>
                                </a>
                                <div class="w-full sm:w-auto flex sm:flex-col justify-between items-center sm:items-end border-t sm:border-t-0 pt-4 sm:pt-0 border-gray-100">
                                    <div id="modal-offer-price" class="text-2xl font-bold text-blue-600 order-2 sm:order-1"></div>
                                    <div class="text-[10px] text-gray-400 uppercase font-bold sm:mt-1 order-1 sm:order-2">{{ __('Offer Price') }}</div>
                                </div>
                            </div>
                            <div class="bg-gray-50 rounded-2xl p-4 sm:p-5 mb-6 border border-gray-100">
                                <h4 class="text-[10px] sm:text-xs font-bold text-gray-500 uppercase tracking-wider mb-3">{{ __('Message from Tasker') }}</h4>
                                <p id="modal-offer-message" class="text-gray-700 text-sm leading-relaxed whitespace-pre-wrap break-words" style="overflow-wrap: break-word; word-break: break-word;"></p>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <a id="message-tasker-btn" href="#" class="h-14 w-full flex items-center justify-center gap-2 px-4 rounded-xl bg-white border border-gray-200 text-gray-700 font-bold hover:bg-gray-50 transition text-center no-underline">
                                    <i data-feather="message-circle" class="w-4 h-4"></i> {{ __('Message') }}
                                </a>
                                <form id="accept-offer-form" method="POST" action="" class="h-14">
                                    @csrf
                                    @if($activeTask->status === 'open')
                                        <button type="submit" class="h-full w-full flex items-center justify-center gap-2 px-4 rounded-xl bg-blue-600 border border-blue-600 text-white font-bold hover:bg-blue-700 transition shadow-lg shadow-blue-200">
                                            <i data-feather="check" class="w-4 h-4"></i> {{ __('Accept Offer') }}
                                        </button>
                                    @else
                                        <button type="button" disabled class="h-full w-full flex items-center justify-center gap-2 px-4 rounded-xl bg-gray-200 border border-gray-200 text-gray-400 font-bold cursor-not-allowed">
                                            <i data-feather="check" class="w-4 h-4"></i> {{ __('mytasks.status.offer_accepted') }}
                                        </button>
                                    @endif
                                </form>
                            </div>
                        </div>
                    </div>

                    {{-- MODAL: Direct Quote Response --}}
                    @if($activeTask && $activeTask->employee_id == auth()->id() && $activeTask->status === 'open' && !$myOffer)
                    <div id="direct-quote-modal" class="task-details-modal" style="z-index: 65;">
                        <div class="task-details-backdrop" onclick="closeDirectQuoteModal()"></div>
                        <div class="task-details-panel" style="max-width: 500px;">
                            <button type="button" class="task-details-close" onclick="closeDirectQuoteModal()" aria-label="{{ __('Close modal') }}">
                                <i data-feather="x" style="width:16px; height:16px;"></i>
                            </button>

                            <div class="flex items-center gap-3 mb-6">
                                <div class="w-12 h-12 rounded-full bg-indigo-100 flex items-center justify-center">
                                    <i data-feather="send" class="w-5 h-5 text-indigo-600"></i>
                                </div>
                                <div>
                                    <h3 class="text-xl font-bold text-gray-900">{{ __('Respond to Quote Request') }}</h3>
                                    <p class="text-sm text-gray-500">{{ $activeTask->employer->first_name ?? '' }} · {{ $activeTask->title }}</p>
                                </div>
                            </div>

                            <div class="bg-indigo-50 border border-indigo-100 rounded-xl p-4 mb-6">
                                <div class="flex items-center justify-between">
                                    <span class="text-sm font-medium text-indigo-700">{{ __('Their budget') }}</span>
                                    <span class="text-lg font-bold text-indigo-800">€{{ number_format($activeTask->price ?? 0, 0) }}</span>
                                </div>
                            </div>

                            {{-- Direct Accept: no offer needed, goes straight to assigned --}}
                            <form action="{{ route('tasks.accept-direct', $activeTask->id) }}" method="POST" class="mb-4">
                                @csrf
                                <button type="submit" class="h-14 w-full flex items-center justify-center gap-2 px-4 rounded-xl bg-green-600 text-white font-bold hover:bg-green-700 transition shadow-lg shadow-green-200 cursor-pointer">
                                    <i data-feather="check" class="w-4 h-4"></i> {{ __('Accept at budget') }} (€{{ number_format($activeTask->price ?? 0, 0) }})
                                </button>
                            </form>

                            <div class="relative flex items-center my-5">
                                <div class="flex-grow border-t border-gray-200"></div>
                                <span class="mx-4 text-xs font-bold text-gray-400 uppercase">{{ __('or counter offer') }}</span>
                                <div class="flex-grow border-t border-gray-200"></div>
                            </div>

                            {{-- Counter offer form --}}
                            <form action="{{ route('tasks.offers.store', $activeTask->id) }}" method="POST">
                                @csrf
                                <div class="mb-4">
                                    <label class="block text-sm font-bold text-gray-700 mb-2">{{ __('Your price (€)') }}</label>
                                    <input type="number" name="offer_price" id="direct-quote-price" min="1" class="w-full border border-gray-300 rounded-xl p-3 text-lg font-bold focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition" required placeholder="Enter your price">
                                </div>
                                <div class="mb-6">
                                    <label class="block text-sm font-bold text-gray-700 mb-2">{{ __('Message') }}</label>
                                    <textarea name="message" rows="3" class="w-full border border-gray-300 rounded-xl p-3 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition" required placeholder="{{ __('Describe your experience and how you can help...') }}"></textarea>
                                </div>
                                <button type="submit" class="h-14 w-full flex items-center justify-center gap-2 px-4 rounded-xl bg-indigo-600 text-white font-bold hover:bg-indigo-700 transition shadow-lg shadow-indigo-200 cursor-pointer">
                                    <i data-feather="send" class="w-4 h-4"></i> {{ __('Send counter offer') }}
                                </button>
                            </form>
                        </div>
                    </div>
                    @endif

                    {{-- MODAL: Edit Task --}}
                    @if($activeTask)
                    <div id="edit-task-modal" class="task-details-modal" style="z-index: 100;">
                        <div class="task-details-backdrop" onclick="closeEditTaskModal()"></div>
                        <div class="task-details-panel" style="max-width: 600px; max-height: 90vh; overflow-y: auto;">
                            <button type="button" class="task-details-close" onclick="closeEditTaskModal()" aria-label="{{ __('Close modal') }}">
                                <i data-feather="x" style="width:16px; height:16px;"></i>
                            </button>
                            <h3 class="text-2xl font-bold text-gray-900 mb-6">{{ __('Edit Task') }}</h3>
                            
                            @php
                                $allCategories = \App\Models\Category::with('jobs')->orderBy('name')->get();
                            @endphp

                            <form action="{{ route('advertisements.update', $activeTask->id) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                
                                <div class="grid grid-cols-1 gap-4">
                                    {{-- Title --}}
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-1">{{ __('Task Title') }}</label>
                                        <input type="text" name="title" value="{{ old('title', $activeTask->title) }}" class="w-full border border-gray-300 rounded-lg p-3 outline-none focus:ring-2 focus:ring-blue-600 transition" required>
                                    </div>
                                    
                                    {{-- Category & Job --}}
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm font-semibold text-gray-700 mb-1">{{ __('Category') }}</label>
                                            <select id="editCategorySelect" class="w-full border border-gray-300 rounded-lg p-3 bg-white outline-none focus:ring-2 focus:ring-blue-600 transition" required>
                                                <option value="">{{ __('Select Category') }}</option>
                                                @foreach($allCategories as $cat)
                                                    <option value="{{ $cat->id }}" {{ ($activeTask->job->categories_id ?? null) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-semibold text-gray-700 mb-1">{{ __('Service') }}</label>
                                            <select id="editJobSelect" name="jobs_id" class="w-full border border-gray-300 rounded-lg p-3 bg-white outline-none focus:ring-2 focus:ring-blue-600 transition" required>
                                                <option value="{{ $activeTask->jobs_id }}" selected>{{ $activeTask->job->name ?? 'Select Service' }}</option>
                                            </select>
                                        </div>
                                    </div>

                                    {{-- Description --}}
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-1">{{ __('Details') }}</label>
                                        <textarea name="description" rows="4" class="w-full border border-gray-300 rounded-lg p-3 outline-none focus:ring-2 focus:ring-blue-600 transition" required>{{ old('description', $activeTask->description) }}</textarea>
                                    </div>

                                    {{-- Type & Location --}}
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm font-semibold text-gray-700 mb-1">{{ __('Task Type') }}</label>
                                            <select id="editTypeSelect" name="task_type" class="w-full border border-gray-300 rounded-lg p-3 bg-white outline-none focus:ring-2 focus:ring-blue-600 transition" required>
                                                <option value="in-person" {{ $activeTask->task_type === 'in-person' ? 'selected' : '' }}>{{ __('In Person') }}</option>
                                                <option value="online" {{ $activeTask->task_type === 'online' ? 'selected' : '' }}>{{ __('Online') }}</option>
                                            </select>
                                        </div>
                                        <div id="editLocationContainer" class="{{ $activeTask->task_type === 'online' ? 'hidden' : '' }}">
                                            <label class="block text-sm font-semibold text-gray-700 mb-1">{{ __('Location') }}</label>
                                            <input type="text" name="location" id="editLocationInput" value="{{ old('location', $activeTask->location) }}" class="w-full border border-gray-300 rounded-lg p-3 outline-none focus:ring-2 focus:ring-blue-600 transition">
                                        </div>
                                    </div>
                                    
                                    {{-- Budget --}}
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-1">{{ __('Budget') }} (€)</label>
                                        <input type="number" name="price" min="10" max="9999" value="{{ old('price', $activeTask->price) }}" class="w-full border border-gray-300 rounded-lg p-3 outline-none focus:ring-2 focus:ring-blue-600 transition" required>
                                    </div>
                                    
                                    {{-- Options: Date & Time Miniature --}}
                                    <style>
                                        .modal-pill-btn { border: 1px solid #1e3a8a; color: #1e3a8a; border-radius: 9999px; padding: 0.4rem 1rem; font-size: 0.875rem; transition: background-color .2s, color .2s, border-color .2s; cursor: pointer; background: transparent; font-weight: 600; white-space: nowrap; outline: none; }
                                        .modal-pill-btn:hover, .modal-pill-btn[data-active="true"] { background-color: #1e3a8a; color: #fff; border-color: #1e3a8a; }
                                        .modal-date-btn { border: 1px solid #d1d5db; border-radius: 0.5rem; padding: 0.5rem 0.75rem; background: white; cursor: pointer; transition: all .2s; width: 100%; text-align: left; display: flex; justify-content: space-between; align-items: center; font-size: 0.875rem; color: #374151; }
                                        .modal-date-btn:hover { border-color: #1e3a8a; }
                                        .modal-date-btn.active { background-color: #1e3a8a; color: #fff; border-color: #1e3a8a; }
                                        .modal-date-btn.active svg { stroke: #fff; }
                                        .modal-time-option { border: 2px solid #e5e7eb; border-radius: 0.5rem; padding: 0.75rem 0.5rem; cursor: pointer; transition: all .2s; display: flex; flex-direction: column; align-items: center; text-align: center; gap: 0.25rem; }
                                        .modal-time-option:hover { border-color: #93c5fd; background-color: #eff6ff; }
                                        .modal-time-option.selected { border-color: #1e3a8a; background-color: #dbeafe; }
                                        .modal-time-option .icon { width: 1.5rem; height: 1.5rem; color: #1e3a8a; }
                                    </style>

                                    <div class="border-b border-gray-100 pb-5">
                                        <div class="mb-4">
                                            <label class="block text-sm font-semibold text-gray-700 mb-2">{{ __('post-task.step1.date_label') ?? 'When do you need this done?' }}</label>
                                            
                                            <input type="hidden" name="is_date_flexible" id="edit_input_is_date_flexible" value="{{ $activeTask->is_date_flexible ? '1' : '0' }}" />

                                            <div class="flex flex-wrap gap-2">
                                                <div class="relative flex-1 min-w-[140px]">
                                                    <button type="button" class="modal-date-btn {{ $activeTask->required_before_date && !$activeTask->is_date_flexible ? 'active' : '' }}" id="editBeforeDateBtn">
                                                        <span id="editBeforeDateLabel">{{ $activeTask->required_before_date && !$activeTask->is_date_flexible ? Carbon\Carbon::parse($activeTask->required_before_date)->format('M d, Y') : __('post-task.step1.before_date') }}</span>
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                                                    </button>
                                                    <input type="date" name="required_before_date" class="absolute inset-0 opacity-0 w-full h-full cursor-pointer z-10" id="editBeforeDateValue" value="{{ $activeTask->required_before_date?->format('Y-m-d') }}" style="{{ $activeTask->required_before_date && !$activeTask->is_date_flexible ? '' : 'pointer-events: none;' }}" />
                                                </div>

                                                <div class="relative flex-1 min-w-[140px]">
                                                    <button type="button" class="modal-date-btn {{ $activeTask->required_date && !$activeTask->is_date_flexible ? 'active' : '' }}" id="editOnDateBtn">
                                                        <span id="editOnDateLabel">{{ $activeTask->required_date && !$activeTask->is_date_flexible ? Carbon\Carbon::parse($activeTask->required_date)->format('M d, Y') : __('post-task.step1.on_date') }}</span>
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                                                    </button>
                                                    <input type="date" name="required_date" class="absolute inset-0 opacity-0 w-full h-full cursor-pointer z-10" id="editOnDateValue" value="{{ $activeTask->required_date?->format('Y-m-d') }}" style="{{ $activeTask->required_date && !$activeTask->is_date_flexible ? '' : 'pointer-events: none;' }}" />
                                                </div>

                                                <button type="button" class="modal-pill-btn" id="editFlexibleBtn" data-active="{{ $activeTask->is_date_flexible ? 'true' : 'false' }}">
                                                    {{ __('post-task.step1.flexible') ?? "I'm flexible" }}
                                                </button>
                                            </div>
                                        </div>

                                        @php
                                            $hasTime = is_array($activeTask->preferred_time) && count($activeTask->preferred_time) > 0;
                                        @endphp

                                        <div class="mt-4">
                                            <label class="flex items-center gap-2 text-sm font-semibold text-gray-700 mb-3 cursor-pointer">
                                                <input type="checkbox" id="editNeedTimeCheckbox" class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500" {{ $hasTime ? 'checked' : '' }} />
                                                <span>{{ __('post-task.step1.certain_time') ?? 'I need a certain time of day' }}</span>
                                            </label>

                                            <div id="editTimeOfDayOptions" class="grid grid-cols-2 sm:grid-cols-4 gap-2 {{ $hasTime ? '' : 'hidden' }}">
                                                @php $ptimes = $activeTask->preferred_time ?? []; @endphp
                                                <label class="modal-time-option {{ in_array('morning', $ptimes) ? 'selected' : '' }}" data-time="morning">
                                                    <input type="checkbox" name="preferred_time[]" value="morning" class="hidden" {{ in_array('morning', $ptimes) ? 'checked' : '' }}>
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 18a5 5 0 0 0-10 0"></path><line x1="12" y1="2" x2="12" y2="9"></line><line x1="4.22" y1="10.22" x2="5.64" y2="11.64"></line><line x1="1" y1="18" x2="3" y2="18"></line><line x1="21" y1="18" x2="23" y2="18"></line><line x1="18.36" y1="11.64" x2="19.78" y2="10.22"></line><line x1="23" y1="22" x2="1" y2="22"></line><polyline points="8 6 12 2 16 6"></polyline></svg>
                                                    <span class="font-bold text-xs text-gray-800">{{ __('post-task.step1.morning') ?? 'Morning' }}</span>
                                                </label>
                                                <label class="modal-time-option {{ in_array('midday', $ptimes) ? 'selected' : '' }}" data-time="midday">
                                                    <input type="checkbox" name="preferred_time[]" value="midday" class="hidden" {{ in_array('midday', $ptimes) ? 'checked' : '' }}>
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="5"></circle><line x1="12" y1="1" x2="12" y2="3"></line><line x1="12" y1="21" x2="12" y2="23"></line><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"></line><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"></line><line x1="1" y1="12" x2="3" y2="12"></line><line x1="21" y1="12" x2="23" y2="12"></line><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"></line><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"></line></svg>
                                                    <span class="font-bold text-xs text-gray-800">{{ __('post-task.step1.midday') ?? 'Midday' }}</span>
                                                </label>
                                                <label class="modal-time-option {{ in_array('afternoon', $ptimes) ? 'selected' : '' }}" data-time="afternoon">
                                                    <input type="checkbox" name="preferred_time[]" value="afternoon" class="hidden" {{ in_array('afternoon', $ptimes) ? 'checked' : '' }}>
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 18a5 5 0 0 0-10 0"></path><line x1="12" y1="9" x2="12" y2="2"></line><line x1="4.22" y1="10.22" x2="5.64" y2="11.64"></line><line x1="1" y1="18" x2="3" y2="18"></line><line x1="21" y1="18" x2="23" y2="18"></line><line x1="18.36" y1="11.64" x2="19.78" y2="10.22"></line><line x1="23" y1="22" x2="1" y2="22"></line><polyline points="16 5 12 9 8 5"></polyline></svg>
                                                    <span class="font-bold text-xs text-gray-800">{{ __('post-task.step1.afternoon') ?? 'Afternoon' }}</span>
                                                </label>
                                                <label class="modal-time-option {{ in_array('evening', $ptimes) ? 'selected' : '' }}" data-time="evening">
                                                    <input type="checkbox" name="preferred_time[]" value="evening" class="hidden" {{ in_array('evening', $ptimes) ? 'checked' : '' }}>
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path></svg>
                                                    <span class="font-bold text-xs text-gray-800">{{ __('post-task.step1.evening') ?? 'Evening' }}</span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Photos --}}
                                    <div class="mt-2">
                                        <label class="block text-sm font-semibold text-gray-700 mb-1">{{ __('Update Photos') }}</label>
                                        <input type="file" name="photos[]" multiple accept="image/*" class="w-full border border-gray-300 rounded-lg p-3 bg-gray-50 text-sm focus:outline-none focus:ring-2 focus:ring-blue-600 transition">
                                        <p class="text-xs text-gray-500 mt-2 font-medium">{{ __('Uploading new photos will add to existing photos. (Max 5MB each)') }}</p>
                                    </div>

                                    <button type="submit" class="w-full h-14 mt-6 bg-blue-600 text-white font-bold text-lg rounded-xl hover:bg-blue-700 transition shadow-lg shadow-blue-200 flex items-center justify-center gap-2">
                                        <i data-feather="save" class="w-5 h-5"></i>
                                        {{ __('Save Changes') }}
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                    
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            const allCategories = @json($allCategories);
                            const editCategorySelect = document.getElementById('editCategorySelect');
                            const editJobSelect = document.getElementById('editJobSelect');
                            const editTypeSelect = document.getElementById('editTypeSelect');
                            const editLocationContainer = document.getElementById('editLocationContainer');
                            const editLocationInput = document.getElementById('editLocationInput');

                            if (editCategorySelect && editJobSelect) {
                                editCategorySelect.addEventListener('change', function() {
                                    const catId = this.value;
                                    editJobSelect.innerHTML = '<option value="">{{ __('Select Service') }}</option>';
                                    if (!catId) return;
                                    
                                    const category = allCategories.find(c => c.id == catId);
                                    if (category && category.jobs) {
                                        const uniqueJobs = new Map();
                                        category.jobs.forEach(j => uniqueJobs.set(j.id, j));
                                        uniqueJobs.forEach(job => {
                                            const option = document.createElement('option');
                                            option.value = job.id;
                                            option.textContent = job.name;
                                            editJobSelect.appendChild(option);
                                        });
                                    }

                                    // Global Keyboard Support for for [role="button"] and [tabindex="0"]
                                    document.addEventListener('keydown', function(e) {
                                        if (e.key === 'Enter' || e.key === ' ') {
                                            const target = e.target;
                                            if (target.getAttribute('role') === 'button' || target.getAttribute('tabindex') === '0') {
                                                if (target.tagName !== 'BUTTON' && target.tagName !== 'A' && target.tagName !== 'INPUT') {
                                                    e.preventDefault();
                                                    target.click();
                                                }
                                            }
                                        }
                                    });
                                });
                            }
                            
                            if (editTypeSelect && editLocationContainer) {
                                        editTypeSelect.addEventListener('change', function() {
                                            if (this.value === 'online') {
                                                editLocationContainer.classList.add('hidden');
                                                editLocationInput.value = 'Online';
                                            } else {
                                                editLocationContainer.classList.remove('hidden');
                                                if (editLocationInput.value === 'Online') editLocationInput.value = '';
                                            }
                                        });
                                    }

                                    // --- Edit Task Modal: Date & Time Logic ---
                                    const editBeforeBtn = document.getElementById('editBeforeDateBtn');
                                    const editOnBtn = document.getElementById('editOnDateBtn');
                                    const editFlexibleBtn = document.getElementById('editFlexibleBtn');
                                    const editBeforeVal = document.getElementById('editBeforeDateValue');
                                    const editOnVal = document.getElementById('editOnDateValue');
                                    const editBeforeLabel = document.getElementById('editBeforeDateLabel');
                                    const editOnLabel = document.getElementById('editOnDateLabel');
                                    const editFlexInput = document.getElementById('edit_input_is_date_flexible');

                                    function resetEditDateOptions() {
                                        if(editBeforeBtn) editBeforeBtn.classList.remove('active');
                                        if(editOnBtn) editOnBtn.classList.remove('active');
                                        if(editFlexibleBtn) editFlexibleBtn.setAttribute('data-active', 'false');
                                        
                                        if(editBeforeVal) {
                                            editBeforeVal.value = '';
                                            editBeforeVal.style.pointerEvents = 'none';
                                        }
                                        if(editOnVal) {
                                            editOnVal.value = '';
                                            editOnVal.style.pointerEvents = 'none';
                                        }
                                        
                                        if(editBeforeLabel) editBeforeLabel.textContent = "{{ __('post-task.step1.before_date') }}";
                                        if(editOnLabel) editOnLabel.textContent = "{{ __('post-task.step1.on_date') }}";
                                        if(editFlexInput) editFlexInput.value = '0';
                                    }

                                    if(editBeforeBtn && editBeforeVal) {
                                        // When user clicks the button, enable the invisible date picker overlay
                                        editBeforeBtn.addEventListener('click', () => {
                                            resetEditDateOptions();
                                            editBeforeBtn.classList.add('active');
                                            editBeforeVal.style.pointerEvents = 'auto'; // enable picking
                                            if ('showPicker' in HTMLInputElement.prototype) {
                                                try { editBeforeVal.showPicker(); } catch(e) {}
                                            }
                                        });
                                        editBeforeVal.addEventListener('change', (e) => {
                                            if(e.target.value) {
                                                const d = new Date(e.target.value);
                                                editBeforeLabel.textContent = d.toLocaleDateString(undefined, { month: 'short', day: 'numeric', year: 'numeric' });
                                            }
                                        });
                                    }

                                    if(editOnBtn && editOnVal) {
                                        editOnBtn.addEventListener('click', () => {
                                            resetEditDateOptions();
                                            editOnBtn.classList.add('active');
                                            editOnVal.style.pointerEvents = 'auto';
                                            if ('showPicker' in HTMLInputElement.prototype) {
                                                try { editOnVal.showPicker(); } catch(e) {}
                                            }
                                        });
                                        editOnVal.addEventListener('change', (e) => {
                                            if(e.target.value) {
                                                const d = new Date(e.target.value);
                                                editOnLabel.textContent = d.toLocaleDateString(undefined, { month: 'short', day: 'numeric', year: 'numeric' });
                                            }
                                        });
                                    }

                                    if(editFlexibleBtn) {
                                        editFlexibleBtn.addEventListener('click', () => {
                                            resetEditDateOptions();
                                            editFlexibleBtn.setAttribute('data-active', 'true');
                                            if(editFlexInput) editFlexInput.value = '1';
                                        });
                                    }

                                    // Form submission ensures logic stays correct
                                    const editTaskForm = document.querySelector('#edit-task-modal form');
                                    if(editTaskForm) {
                                        editTaskForm.addEventListener('submit', () => {
                                            // Ensure inputs that aren't active are wiped out before submit
                                            if (!editBeforeBtn.classList.contains('active') && editBeforeVal) editBeforeVal.value = '';
                                            if (!editOnBtn.classList.contains('active') && editOnVal) editOnVal.value = '';
                                            if (editFlexibleBtn.getAttribute('data-active') === 'true' && editFlexInput) editFlexInput.value = '1';
                                        });
                                    }

                                    // Time Checkbox Logic
                                    const editNeedTimeCheckbox = document.getElementById('editNeedTimeCheckbox');
                                    const editTimeOptions = document.getElementById('editTimeOfDayOptions');
                                    if(editNeedTimeCheckbox && editTimeOptions) {
                                        editNeedTimeCheckbox.addEventListener('change', function() {
                                            if(this.checked) {
                                                editTimeOptions.classList.remove('hidden');
                                            } else {
                                                editTimeOptions.classList.add('hidden');
                                                // uncheck all time toggles
                                                document.querySelectorAll('.modal-time-option input').forEach(ip => { ip.checked = false; });
                                                document.querySelectorAll('.modal-time-option').forEach(lb => { lb.classList.remove('selected'); });
                                            }
                                        });
                                    }
                                    
                                    // Time Pill Click Logic
                                    document.querySelectorAll('.modal-time-option').forEach(label => {
                                        label.addEventListener('click', function(e) {
                                            // Let the native checkbox handle its own checked state 
                                            // We just toggle the 'selected' visual class instantly
                                            setTimeout(() => {
                                                const checkbox = this.querySelector('input[type="checkbox"]');
                                                if (checkbox.checked) {
                                                    this.classList.add('selected');
                                                } else {
                                                    this.classList.remove('selected');
                                                }
                                            }, 10);
                                        });
                                    });

                            window.openEditTaskModal = function() {
                                const modal = document.getElementById('edit-task-modal');
                                const dropdown = document.getElementById('more-menu');
                                if (dropdown) dropdown.classList.remove('show');
                                if (modal) {
                                    modal.classList.add('show');
                                    document.body.style.overflow = 'hidden';
                                    if (window.feather) feather.replace();
                                }
                            };

                            window.closeEditTaskModal = function() {
                                const modal = document.getElementById('edit-task-modal');
                                if (modal) {
                                    modal.classList.remove('show');
                                    document.body.style.overflow = '';
                                }
                            };
                        });
                    </script>
                    @endif

                </div>
            </div>

            {{-- OTHER TASKS LIST --}}
            @if($otherTasks->count() > 0)
                <div class="other-tasks-container">
                    <h3 class="text-xl font-bold text-gray-800 mb-4 px-2">{{ __('Other Tasks') }}</h3>
                    @foreach($otherTasks as $task)
                        <a href="{{ request()->fullUrlWithQuery(['task_id' => $task->id]) }}" class="compact-task-row">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center text-gray-500">
                                    <i data-feather="clipboard" style="width:18px;"></i>
                                </div>
                                <div>
                                    <div class="text-xs text-gray-400 font-bold uppercase flex items-center gap-2">
                                        {{ $task->status ?? 'Posted' }}
                                        @if((($viewMode ?? 'posted') === 'posted' || ($viewMode ?? 'posted') === 'direct') && $task->employee_id)
                                            <div class="flex items-center gap-1 bg-blue-50 text-blue-600 px-1.5 py-0.5 rounded-full border border-blue-100" title="{{ __('Sent to') }} {{ $task->employee->first_name }}">
                                                <i data-feather="user" style="width:10px; height:10px;"></i>
                                                <span class="text-[8px] tracking-tighter">{{ $task->employee->first_name }}</span>
                                            </div>
                                        @endif
                                    </div>
                                    <span class="text-base font-bold text-gray-800 hover:text-blue-600 transition">{{ $task->title }}</span>
                                </div>
                            </div>
                            <div class="font-bold text-gray-600">€{{ number_format($task->price, 0) }}</div>
                        </a>
                    @endforeach
                </div>
            @endif

        @else
            {{-- EMPTY STATE --}}
            <div class="modern-empty-state">
                <div class="empty-illustration">
                    <i data-feather="clipboard" style="width:48px; height:48px;"></i>
                </div>
                <h3 class="empty-title">
                    @if(($filters['status'] ?? 'posted') === 'posted' && empty($filters['q']) && ($viewMode ?? 'posted') === 'posted')
                        {{ __('No tasks yet') }}
                    @elseif(($viewMode ?? 'posted') === 'applied')
                        {{ __('No applications found') }}
                    @elseif(($viewMode ?? 'posted') === 'direct')
                        {{ __('No direct requests yet') }}
                    @else
                        {{ __('No tasks found') }}
                    @endif
                </h3>
                <p class="empty-desc">
                    @if(($viewMode ?? 'posted') === 'applied')
                        {{ __("You haven't applied to any tasks in this category yet. Browse available tasks to get started.") }}
                    @elseif(($viewMode ?? 'posted') === 'direct')
                        {{ __("You haven't sent any direct quote requests to specific experts yet.") }}
                    @else
                        @if(($filters['status'] ?? 'posted') === 'posted')
                            @if(!empty($filters['q']))
                                {{ __("We couldn't find any tasks matching your search.") }}
                            @else
                                {{ __("Put your task in front of thousands of people and get it done quickly.") }}
                            @endif
                        @else
                            {{ __("No tasks found with this status.") }}
                        @endif
                    @endif
                </p>
                @if(($viewMode ?? 'posted') === 'applied')
                    <a href="{{ route('tasks') }}" class="cta-button">
                        <i data-feather="search" style="width:18px;"></i> {{ __('Browse Tasks') }}
                    </a>
                @else
                    <a href="{{ route('post-task') }}" class="cta-button">
                        <i data-feather="plus" style="width:18px;"></i> {{ __('Post a New Task') }}
                    </a>
                @endif
            </div>
        @endif

    </div>
</section>

<script src="https://unpkg.com/feather-icons"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (window.feather) window.feather.replace();

        // More Options Toggle
        const moreBtn = document.getElementById('more-btn');
        const moreMenu = document.getElementById('more-menu');
        
        if (moreBtn && moreMenu) {
            moreBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                moreMenu.classList.toggle('show');
            });
            document.addEventListener('click', () => moreMenu.classList.remove('show'));
        }

        // Task details modal
        window.openTaskDetailsModal = function () {
            const modal = document.getElementById('task-details-modal');
            if (modal) { modal.classList.add('show'); document.body.style.overflow = 'hidden'; }
        };
        window.closeTaskDetailsModal = function () {
            const modal = document.getElementById('task-details-modal');
            if (modal) { modal.classList.remove('show'); document.body.style.overflow = ''; }
        };
        const backdrop = document.getElementById('task-details-backdrop');
        if (backdrop) backdrop.addEventListener('click', () => window.closeTaskDetailsModal());

        // Offer Modal
        window.openOfferModal = function(data) {
            const modal = document.getElementById('offer-details-modal');
            if(!modal) return;

            const avatarEl = document.getElementById('modal-offer-avatar');
            if (data.avatarUrl) {
                avatarEl.innerHTML = `<img src="${data.avatarUrl}" class="w-full h-full rounded-full object-cover">`;
            } else {
                avatarEl.innerHTML = data.initials;
            }

            document.getElementById('modal-offer-name').innerText = data.name;
            document.getElementById('modal-offer-rating').innerText = data.rating;
            document.getElementById('modal-offer-time').innerText = data.time + ' {{ __("mytasks.stats.ago") }}';
            document.getElementById('modal-offer-price').innerText = '€' + data.price;
            document.getElementById('modal-offer-message').innerText = data.message;

            const profileLink = document.getElementById('modal-profile-link');
            if (profileLink) profileLink.href = '/profile/' + data.userId;

            const form = document.getElementById('accept-offer-form');
            if(form) form.action = `/offers/${data.id}/accept`;

            const msgBtn = document.getElementById('message-tasker-btn');
            if(msgBtn) msgBtn.href = `/messages?user_id=${data.userId}`;

            modal.classList.add('show');
            document.body.style.overflow = 'hidden';
        }
        window.closeOfferModal = function() {
            const modal = document.getElementById('offer-details-modal');
            if(modal) { modal.classList.remove('show'); document.body.style.overflow = ''; }
        }

        // Direct Quote Response Modal
        window.openDirectQuoteModal = function() {
            const modal = document.getElementById('direct-quote-modal');
            if(modal) {
                modal.classList.add('show');
                document.body.style.overflow = 'hidden';
                if(window.feather) window.feather.replace();
            }
        }
        window.closeDirectQuoteModal = function() {
            const modal = document.getElementById('direct-quote-modal');
            if(modal) { modal.classList.remove('show'); document.body.style.overflow = ''; }
        }

        // Complete Modal
        window.openCompleteTaskModal = function() {
            const modal = document.getElementById('complete-task-modal');
            if(modal) {
                modal.classList.add('show');
                document.body.style.overflow = 'hidden';
                document.getElementById('complete-choice-buttons').classList.remove('hidden');
                document.getElementById('complete-review-form').classList.add('hidden');
                setRating(0);
            }
        }
        window.closeCompleteTaskModal = function() {
            const modal = document.getElementById('complete-task-modal');
            if(modal) { modal.classList.remove('show'); document.body.style.overflow = ''; }
        }
        window.showReviewForm = function() {
            document.getElementById('complete-choice-buttons').classList.add('hidden');
            document.getElementById('complete-review-form').classList.remove('hidden');
        }
        window.setRating = function(value) {
            document.getElementById('rating-value').value = value;
            for(let i=1; i<=5; i++) {
                const icon = document.getElementById('star-'+i);
                if(i <= value) {
                    icon.classList.add('text-yellow-400');
                    icon.classList.remove('text-gray-300');
                    icon.style.fill = 'currentColor';
                } else {
                    icon.classList.remove('text-yellow-400');
                    icon.classList.add('text-gray-300');
                    icon.style.fill = 'none';
                }
            }
            if(window.feather) window.feather.replace();
        }
    });
</script>
@endsection