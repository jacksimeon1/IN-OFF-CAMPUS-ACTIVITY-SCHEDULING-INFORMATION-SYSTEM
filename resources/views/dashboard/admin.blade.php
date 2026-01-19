@extends('layouts.admin')

@section('title', 'Admin Dashboard')

@php
use App\Models\User;
@endphp

@push('styles')
<style>
    /* Hide sidebar and hamburger menu on admin dashboard page */
    .admin-sidebar,
    .admin-sidebar-overlay,
    .admin-toggle-btn,
    aside.admin-sidebar,
    #adminSidebar,
    #adminSidebarOverlay,
    #adminToggleBtn {
        display: none !important;
        visibility: hidden !important;
    }

    /* Adjust main content area to use full width */
    .admin-main,
    .admin-content {
        margin-left: 0 !important;
        width: 100% !important;
        max-width: 100% !important;
    }

    /* Ensure proper spacing without sidebar */
    body {
        padding-left: 0 !important;
    }

    .admin-layout {
        padding-left: 0 !important;
    }

    /* Fix delete button clickability issues */
    .admin-dashboard table button,
    .admin-dashboard table form,
    .admin-dashboard table a {
        position: relative !important;
        z-index: 10 !important;
        pointer-events: auto !important;
    }

    /* Ensure table cells don't interfere with button clicks */
    .admin-dashboard td {
        position: relative;
        z-index: 1;
    }

    /* Make sure action buttons are properly clickable */
    .admin-dashboard .flex.space-x-2 {
        position: relative;
        z-index: 10;
    }

    /* Override any potential overlay issues */
    .admin-dashboard .flex.space-x-2 * {
        pointer-events: auto !important;
    }

    /* Default Admin Statistics Cards */
    .admin-stats-container {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
        margin-bottom: 1.5rem;
    }

    /* Enhanced Statistics Cards with Animations */
    .animated-stat-card {
        position: relative;
        opacity: 0;
        transform: translateY(30px) scale(0.95);
        animation: slideInUp 0.8s ease-out forwards;
        animation-delay: var(--delay);
        perspective: 1000px;
    }

    .animated-stat-card[data-delay="0"] { --delay: 0s; }
    .animated-stat-card[data-delay="100"] { --delay: 0.1s; }
    .animated-stat-card[data-delay="200"] { --delay: 0.2s; }
    .animated-stat-card[data-delay="300"] { --delay: 0.3s; }

    @keyframes slideInUp {
        0% {
            opacity: 0;
            transform: translateY(30px) scale(0.95);
        }
        50% {
            opacity: 0.7;
            transform: translateY(-5px) scale(1.02);
        }
        100% {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    }

    .card-inner {
        position: relative;
        background: linear-gradient(135deg, #059669 0%, #047857 50%, #065f46 100%);
        border-radius: 20px;
        padding: 24px;
        height: 140px;
        overflow: hidden;
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        box-shadow:
            0 10px 30px rgba(5, 150, 105, 0.3),
            0 5px 15px rgba(0, 0, 0, 0.1),
            inset 0 1px 0 rgba(255, 255, 255, 0.1);
    }

    .animated-stat-card:hover .card-inner {
        transform: translateY(-8px) rotateX(5deg);
        box-shadow:
            0 20px 40px rgba(5, 150, 105, 0.4),
            0 15px 25px rgba(5, 150, 105, 0.3),
            0 10px 15px rgba(0, 0, 0, 0.2),
            inset 0 1px 0 rgba(255, 255, 255, 0.2);
    }

    .card-glow {
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(16, 185, 129, 0.3) 0%, transparent 70%);
        opacity: 0;
        transition: all 0.6s ease;
        animation: rotateGlow 4s linear infinite;
    }

    .animated-stat-card:hover .card-glow {
        opacity: 1;
        animation-duration: 2s;
    }

    @keyframes rotateGlow {
        0% { transform: rotate(0deg) scale(0.8); }
        50% { transform: rotate(180deg) scale(1.2); }
        100% { transform: rotate(360deg) scale(0.8); }
    }

    .card-content {
        position: relative;
        z-index: 2;
        height: 100%;
    }

    .stat-info {
        color: white;
        flex: 1;
    }

    .stat-icon-wrapper {
        width: 48px;
        height: 48px;
        background: rgba(255, 255, 255, 0.15);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 12px;
        transition: all 0.3s ease;
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.1);
    }

    .animated-stat-card:hover .stat-icon-wrapper {
        background: rgba(255, 255, 255, 0.25);
        transform: scale(1.1) rotate(5deg);
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
    }

    .stat-icon {
        font-size: 20px;
        color: white;
        transition: all 0.3s ease;
    }

    .animated-stat-card:hover .stat-icon {
        color: #d1fae5;
        transform: scale(1.1);
    }

    .stat-title {
        font-size: 14px;
        font-weight: 600;
        color: rgba(255, 255, 255, 0.9);
        margin-bottom: 4px;
        transition: all 0.3s ease;
    }

    .animated-stat-card:hover .stat-title {
        color: #d1fae5;
    }

    .stat-number {
        font-size: 28px;
        font-weight: 800;
        color: white;
        line-height: 1;
        margin-bottom: 4px;
        transition: all 0.3s ease;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
    }

    .animated-stat-card:hover .stat-number {
        color: #d1fae5;
        transform: scale(1.05);
        text-shadow: 0 4px 8px rgba(0, 0, 0, 0.4);
    }

    .stat-subtitle {
        font-size: 12px;
        color: rgba(255, 255, 255, 0.7);
        transition: all 0.3s ease;
    }

    .animated-stat-card:hover .stat-subtitle {
        color: rgba(209, 250, 229, 0.9);
    }

    .stat-visual {
        position: relative;
        width: 60px;
        height: 60px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .pulse-ring {
        position: absolute;
        width: 40px;
        height: 40px;
        border: 2px solid rgba(255, 255, 255, 0.3);
        border-radius: 50%;
        animation: pulse 2s ease-in-out infinite;
    }

    .pulse-ring-2 {
        position: absolute;
        width: 60px;
        height: 60px;
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 50%;
        animation: pulse 2s ease-in-out infinite 0.5s;
    }

    @keyframes pulse {
        0% {
            transform: scale(0.8);
            opacity: 1;
        }
        50% {
            transform: scale(1.2);
            opacity: 0.5;
        }
        100% {
            transform: scale(0.8);
            opacity: 1;
        }
    }

    .animated-stat-card:hover .pulse-ring {
        animation-duration: 1s;
        border-color: rgba(209, 250, 229, 0.6);
    }

    .animated-stat-card:hover .pulse-ring-2 {
        animation-duration: 1s;
        border-color: rgba(209, 250, 229, 0.4);
    }

    /* Floating animation for cards */
    .animated-stat-card {
        animation: slideInUp 0.8s ease-out forwards, float 6s ease-in-out infinite;
        animation-delay: var(--delay), calc(var(--delay) + 1s);
    }

    @keyframes float {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(-5px); }
    }

    .admin-header {
        background: linear-gradient(135deg, #059669 0%, #047857 50%, #065f46 100%);
        color: white;
        padding: 1.5rem 0;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        position: relative;
        overflow: hidden;
    }

    .admin-header-full {
        background: linear-gradient(135deg, #059669 0%, #047857 50%, #065f46 100%);
        color: white;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        position: relative;
        overflow: hidden;
        width: 100vw;
        margin-left: calc(-50vw + 50%);
        margin-top: calc(-100vh + 100vh);
    }

    .admin-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Ccircle cx='30' cy='30' r='2'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E") repeat;
    }

    .admin-nav-link {
        position: relative;
        border: 1px solid transparent;
        transition: all 0.3s ease;
    }

    .admin-nav-link:hover {
        background: #f0fdf4;
        border-color: #bbf7d0;
        transform: translateY(-1px);
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    /* Ensure FontAwesome icons are always visible */
    .fas, .far, .fab, .fal, .fad {
        display: inline-block !important;
        visibility: visible !important;
        opacity: 1 !important;
        font-family: "Font Awesome 6 Free", "Font Awesome 6 Pro", "Font Awesome 5 Free", "Font Awesome 5 Pro" !important;
        font-weight: 900 !important;
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
    }

    /* Specific styling for dashboard stat card icons */
    .stat-card .fas {
        color: #ffffff !important;
        font-size: 1.5rem !important;
        line-height: 1 !important;
        text-align: center !important;
    }

    /* Fallback for icons if FontAwesome doesn't load */
    .stat-card .fas::before {
        content: attr(data-fallback) !important;
        display: inline-block !important;
        font-family: Arial, sans-serif !important;
        font-weight: bold !important;
    }

    /* Force icon containers to be visible */
    .stat-card .w-16.h-16 {
        background: linear-gradient(135deg, #059669, #047857) !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        min-width: 4rem !important;
        min-height: 4rem !important;
    }

    /* Mobile responsive navigation */
    @media (max-width: 640px) {
        .admin-nav-link {
            min-width: 60px !important;
            padding: 8px 12px !important;
            font-size: 0.75rem !important;
        }

        .admin-nav-link i {
            font-size: 0.875rem !important;
            margin-right: 4px !important;
        }

        /* Ensure navigation is scrollable on mobile */
        nav {
            -webkit-overflow-scrolling: touch;
            scroll-behavior: smooth;
        }

        /* Hide text on very small screens, show only icons */
        @media (max-width: 480px) {
            .admin-nav-link span {
                display: none !important;
            }
            .admin-nav-link {
                min-width: 48px !important;
                padding: 8px !important;
            }
        }
    }

    .admin-nav-link.active {
        background: #eab308;
        color: white;
        border-color: #eab308;
        box-shadow: 0 2px 4px rgba(234, 179, 8, 0.2);
    }

    .admin-nav-link.active::after {
        content: '';
        position: absolute;
        bottom: -1px;
        left: 0;
        right: 0;
        height: 2px;
        background: linear-gradient(90deg, #059669, #047857);
    }

    /* Custom Text Input Styles */
    .textInputWrapper {
        position: relative;
        width: 100%;
        margin: 12px 0;
        --accent-color: #059669;
    }

    .textInputWrapper:before {
        transition: border-bottom-color 200ms cubic-bezier(0.4, 0, 0.2, 1) 0ms;
        border-bottom: 1px solid rgba(0, 0, 0, 0.42);
    }

    .textInputWrapper:before,
    .textInputWrapper:after {
        content: "";
        left: 0;
        right: 0;
        position: absolute;
        pointer-events: none;
        bottom: -1px;
        z-index: 4;
        width: 100%;
    }

    .textInputWrapper:focus-within:before {
        border-bottom: 1px solid var(--accent-color);
        transform: scaleX(1);
    }

    .textInputWrapper:focus-within:after {
        border-bottom: 2px solid var(--accent-color);
        transform: scaleX(1);
    }

    .textInputWrapper:after {
        content: "";
        transform: scaleX(0);
        transition: transform 250ms cubic-bezier(0, 0, 0.2, 1) 0ms;
        will-change: transform;
        border-bottom: 2px solid var(--accent-color);
        border-bottom-color: var(--accent-color);
    }

    .textInput::placeholder {
        transition: opacity 250ms cubic-bezier(0, 0, 0.2, 1) 0ms;
        opacity: 1;
        user-select: none;
        color: rgba(255, 255, 255, 0.582);
    }

    .textInputWrapper .textInput {
        border-radius: 5px 5px 0px 0px;
        box-shadow: 0px 2px 5px rgb(35 35 35 / 30%);
        max-height: 36px;
        background-color: #252525;
        transition-timing-function: cubic-bezier(0.25, 0.8, 0.25, 1);
        transition-duration: 200ms;
        transition-property: background-color;
        color: #e8e8e8;
        font-size: 14px;
        font-weight: 500;
        padding: 12px;
        width: 100%;
        border: none;
        outline: none;
    }

    .textInputWrapper .textInput:focus,
    .textInputWrapper .textInput:active {
        outline: none;
    }

    .textInputWrapper:focus-within .textInput,
    .textInputWrapper .textInput:focus,
    .textInputWrapper .textInput:active {
        background-color: #353535;
    }

    .textInputWrapper:focus-within .textInput::placeholder {
        opacity: 0;
    }

    /* Custom Select Styles */
    .textInputWrapper .textSelect {
        border-radius: 5px 5px 0px 0px;
        box-shadow: 0px 2px 5px rgb(35 35 35 / 30%);
        max-height: 36px;
        background-color: #252525;
        transition-timing-function: cubic-bezier(0.25, 0.8, 0.25, 1);
        transition-duration: 200ms;
        transition-property: background-color;
        color: #e8e8e8;
        font-size: 14px;
        font-weight: 500;
        padding: 12px;
        width: 100%;
        border: none;
        outline: none;
    }

    .textInputWrapper:focus-within .textSelect {
        background-color: #353535;
    }

    .content-card {
        background: #ffffff !important;
        border-radius: 0.5rem; /* match sm:rounded-lg */
        /* Stronger default shadow (approx Tailwind shadow-lg) */
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        border: none;
        transition: none;
    }

    .content-card:hover {
        /* Slightly stronger on hover for depth (approx Tailwind shadow-2xl) */
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        transform: none;
    }

    .content-card.cursor-pointer:hover {
        transform: none;
    }

    .content-card.cursor-pointer:active {
        transform: none;
    }

    /*
     * Override Tailwind-style white cards using shadow-sm to have stronger shadows
     * without changing the markup. Applies to common white containers across the dashboard.
     */
    .bg-white.shadow-sm {
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05) !important;
    }

    .bg-white.shadow-sm:hover {
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04) !important;
    }

    /* Activities Tab Dark Yellow Border */
    .activities-tab-card {
        border: 3px solid #d97706 !important;
        background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%) !important;
        box-shadow: 0 4px 6px -1px rgba(217, 119, 6, 0.1), 0 2px 4px -1px rgba(217, 119, 6, 0.06) !important;
    }

    .activities-tab-card:hover {
        border-color: #b45309 !important;
        box-shadow: 0 10px 15px -3px rgba(217, 119, 6, 0.2), 0 4px 6px -2px rgba(217, 119, 6, 0.1) !important;
    }

    /* Remove all bottom spacing */
    .max-w-7xl {
        margin-bottom: 0 !important;
        padding-bottom: 0 !important;
    }

    .content-card:last-child {
        margin-bottom: 0 !important;
    }

    /* Just ensure body has gradient background */
    body {
        background: linear-gradient(135deg, #f0fdf4 0%, #ecfdf5 50%, #d1fae5 100%) !important;
    }

    /* Keep layout wrappers transparent, but allow section cards to be white */
    .max-w-7xl, .container, main, section, article {
        background-color: transparent !important;
    }

    .gradient-bg {
        background: linear-gradient(135deg, #f0fdf4 0%, #ecfdf5 50%, #d1fae5 100%) !important;
        height: auto !important;
        min-height: auto !important;
    }

    /* Force page to fill viewport */
    html {
        height: 100% !important;
        background: linear-gradient(135deg, #f0fdf4 0%, #ecfdf5 50%, #d1fae5 100%) !important;
    }

    body {
        height: 100vh !important;
        min-height: 100vh !important;
        background: linear-gradient(135deg, #f0fdf4 0%, #ecfdf5 50%, #d1fae5 100%) !important;
    }

    /* Remove forced heights - let content determine height */
    .admin-layout, .admin-main, .gradient-bg {
        height: auto !important;
        min-height: auto !important;
    }

    /* Force all containers to have the gradient background */
    .max-w-7xl {
        background: transparent !important;
    }

    /* Force all containers to have the gradient background */
    .max-w-7xl {
        background: transparent !important;
    }

    /* Quick action card links */
    .quick-action-link {
        text-decoration: none;
        color: inherit;
        display: block;
        height: 100%;
    }

    .quick-action-link:hover {
        text-decoration: none;
        color: inherit;
    }

    /* Ensure all quick action cards have the same height */
    .quick-action-link .content-card {
        height: 100%;
        display: flex;
        flex-direction: column;
        min-height: 200px;
        transition: transform 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275), box-shadow 0.3s ease;
        border: 1px solid rgba(16, 185, 129, 0.25); /* subtle green border */
        opacity: 0;
        transform: translateY(20px);
        animation: fadeInUp 0.6s forwards;
        animation-delay: calc(var(--card-index, 0) * 0.1s);
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .quick-action-link:nth-child(1) .content-card { --card-index: 1; }
    .quick-action-link:nth-child(2) .content-card { --card-index: 2; }
    .quick-action-link:nth-child(3) .content-card { --card-index: 3; }
    .quick-action-link:nth-child(4) .content-card { --card-index: 4; }

    .quick-action-link .content-card > div:last-child {
        margin-top: auto;
    }

    /* Slight grow on hover */
    .quick-action-link .content-card:hover {
        transform: scale(1.03) translateY(-5px);
        box-shadow: 0 12px 25px -5px rgba(5, 150, 105, 0.15), 0 8px 10px -6px rgba(5, 150, 105, 0.1);
        border-color: rgba(16, 185, 129, 0.5);
    }

    .quick-action-link:focus {
        outline: 2px solid #059669;
        outline-offset: 2px;
        border-radius: 16px;
    }

    .gradient-bg {
        background: linear-gradient(135deg, #f0fdf4 0%, #ecfdf5 50%, #d1fae5 100%);
        min-height: auto !important;
        padding-bottom: 0 !important;
    }

    .icon-wrapper {
        background: linear-gradient(135deg, #059669 0%, #047857 100%);
        border-radius: 12px;
        padding: 12px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 6px -1px rgba(5, 150, 105, 0.3),
                    inset 0 1px 0 rgba(255, 255, 255, 0.15);
        position: relative;
        overflow: hidden;
        z-index: 1;
    }
    
    .icon-wrapper::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255, 255, 255, 0.2) 0%, transparent 70%);
        opacity: 0;
        transition: opacity 0.3s ease;
        z-index: -1;
    }
    
    .quick-action-link:hover .icon-wrapper::before {
        opacity: 1;
        animation: rotateGlow 2s linear infinite;
    }
    
    @keyframes rotateGlow {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    .metric-number {
        background: linear-gradient(135deg, #059669 0%, #047857 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        font-weight: bold;
        font-size: 1.5rem;
        display: inline-block;
        position: relative;
        transition: transform 0.3s ease;
    }
    
    .quick-action-link:hover .metric-number {
        transform: scale(1.1);
        animation: pulse 1.5s infinite;
    }
    
    @keyframes pulse {
        0% { transform: scale(1); }
        50% { transform: scale(1.1); }
        100% { transform: scale(1); }
    }

    /* Traditional Calendar Styles - Exact Copy from Student Calendar */
    .calendar-container {
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        overflow: hidden;
    }

    .calendar-header {
        background: linear-gradient(135deg, #059669, #047857);
        color: white;
        padding: 1.5rem;
        text-align: center;
    }

    .calendar-nav-btn {
        background: rgba(255, 255, 255, 0.2);
        border: none;
        color: white;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .calendar-nav-btn:hover {
        background: rgba(255, 255, 255, 0.3);
        transform: scale(1.1);
    }

    .calendar-month-year {
        font-size: 1.5rem;
        font-weight: 600;
        margin: 0;
    }

    .calendar-weekdays {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        background: #f8fafc;
        border-bottom: 1px solid #e2e8f0;
    }

    .calendar-weekday {
        padding: 1rem 0.5rem;
        text-align: center;
        font-weight: 600;
        font-size: 0.875rem;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .calendar-days {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        background: white;
    }

    .calendar-day {
        min-height: 100px;
        border-right: 1px solid #e2e8f0;
        border-bottom: 1px solid #e2e8f0;
        padding: 0.5rem;
        cursor: pointer;
        transition: all 0.2s ease;
        position: relative;
        display: flex;
        flex-direction: column;
    }

    .calendar-day:hover {
        background: #f0fdf4;
    }

    .calendar-day.other-month {
        background: #f8fafc;
        color: #cbd5e1;
    }

    .calendar-day.today {
        background: linear-gradient(135deg, #dcfce7, #bbf7d0);
        border: 2px solid #059669;
    }

    .calendar-day.has-activities {
        background: #fefce8;
    }

    .calendar-day.has-activities.today {
        background: linear-gradient(135deg, #dcfce7, #bbf7d0);
    }

    .day-number {
        font-weight: 600 !important;
        font-size: 1rem !important;
        margin-bottom: 0.25rem !important;
        color: #1f2937 !important;
        text-shadow: none !important;
    }

    .calendar-day.other-month .day-number {
        color: #cbd5e1 !important;
    }

    .calendar-day.today .day-number {
        color: #047857 !important;
        font-weight: 700 !important;
    }

    .day-activities {
        display: flex !important;
        flex-direction: column !important;
        gap: 2px !important;
        flex-grow: 1 !important;
    }

    .activity-item {
        background: #059669 !important;
        color: #ffffff !important;
        padding: 2px 6px !important;
        border-radius: 4px !important;
        font-size: 0.75rem !important;
        line-height: 1.2 !important;
        cursor: pointer !important;
        transition: all 0.2s ease !important;
        text-overflow: ellipsis !important;
        overflow: hidden !important;
        white-space: nowrap !important;
        font-weight: 500 !important;
        text-shadow: none !important;
        border: none !important;
    }

    .activity-item:hover {
        background: #047857 !important;
        color: #ffffff !important;
        transform: translateY(-1px) !important;
    }

    .activity-item.status-approved {
        background: #059669 !important;
        color: #ffffff !important;
        font-weight: 600 !important;
    }

    .more-activities {
        background: #6b7280;
        color: white;
        padding: 2px 6px;
        border-radius: 4px;
        font-size: 0.7rem;
        text-align: center;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .more-activities:hover {
        background: #4b5563;
    }

    /* Calendar Legend */
    .calendar-legend {
        padding: 1rem 1.5rem;
        background: #f8fafc;
        border-top: 1px solid #e2e8f0;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .legend-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .legend-color {
        width: 12px;
        height: 12px;
        border-radius: 50%;
    }

    /* Activity Modal Styles */
    .activity-modal {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.5);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 1000;
        backdrop-filter: blur(4px);
    }

    .activity-modal-content {
        background: white;
        border-radius: 12px;
        max-width: 600px;
        width: 90%;
        max-height: 80vh;
        overflow: hidden;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        animation: modalSlideIn 0.3s ease-out;
    }

    @keyframes modalSlideIn {
        from {
            opacity: 0;
            transform: scale(0.9) translateY(-20px);
        }
        to {
            opacity: 1;
            transform: scale(1) translateY(0);
        }
    }

    .activity-modal-header {
        background: linear-gradient(135deg, #059669, #047857);
        color: white;
        padding: 1.5rem;
    }

    .activity-modal-body {
        padding: 1.5rem;
        max-height: 60vh;
        overflow-y: auto;
    }

    .activity-detail {
        margin-bottom: 1rem !important;
        padding: 1rem !important;
        border-radius: 8px !important;
        border-left: 4px solid #059669 !important;
        background: #f0fdf4 !important;
    }

    .activity-detail h4 {
        font-weight: 600 !important;
        color: #1f2937 !important;
        margin-bottom: 0.5rem !important;
        font-size: 1.1rem !important;
    }

    .activity-detail p {
        color: #047857 !important;
        font-weight: 500 !important;
        margin-bottom: 0.25rem !important;
        font-size: 0.9rem !important;
    }

    .activity-detail span {
        color: #374151 !important;
        font-size: 0.875rem !important;
        font-weight: 500 !important;
    }

    @media (max-width: 768px) {
        .calendar-day {
            min-height: 80px;
            padding: 0.25rem;
        }

        .day-number {
            font-size: 0.875rem;
        }

        .activity-item {
            font-size: 0.7rem;
            padding: 1px 4px;
        }

        .calendar-month-year {
            font-size: 1.25rem;
        }

        .calendar-legend {
            flex-direction: column;
            align-items: flex-start;
        }
    }

    @media (max-width: 768px) {
        .calendar-day {
            min-height: 80px;
            padding: 0.25rem;
        }

        .day-number {
            font-size: 0.875rem;
        }

        .activity-item {
            font-size: 0.7rem;
            padding: 3px 6px;
            min-height: 18px;
        }

        .calendar-month-year {
            font-size: 1.25rem;
        }
    }

    /* Admin Calendar Tab Styles - Exact Copy from Student Calendar */
    .admin-calendar-container {
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        overflow: hidden;
    }

    .admin-calendar-header {
        background: linear-gradient(135deg, #059669, #047857);
        color: white;
        padding: 1.5rem;
        text-align: center;
    }

    .admin-calendar-nav-btn {
        background: rgba(255, 255, 255, 0.2);
        border: none;
        color: white;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .admin-calendar-nav-btn:hover {
        background: rgba(255, 255, 255, 0.3);
        transform: scale(1.1);
    }

    .admin-calendar-month-year {
        font-size: 1.5rem;
        font-weight: 600;
        margin: 0;
    }

    .admin-calendar-weekdays {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        background: #f8fafc;
        border-bottom: 1px solid #e2e8f0;
    }

    .admin-calendar-weekday {
        padding: 1rem 0.5rem;
        text-align: center;
        font-weight: 600;
        font-size: 0.875rem;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .admin-calendar-days {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        background: white;
    }

    .admin-calendar-day {
        min-height: 100px;
        border-right: 1px solid #e2e8f0;
        border-bottom: 1px solid #e2e8f0;
        padding: 0.5rem;
        cursor: pointer;
        transition: all 0.2s ease;
        position: relative;
        display: flex;
        flex-direction: column;
    }

    .admin-calendar-day:hover {
        background: #f0fdf4;
    }

    .admin-calendar-day.other-month {
        background: #f8fafc;
        color: #cbd5e1;
    }

    .admin-calendar-day.today {
        background: linear-gradient(135deg, #dcfce7, #bbf7d0);
        border: 2px solid #059669;
    }

    .admin-calendar-day.has-activities {
        background: #fefce8;
    }

    .admin-calendar-day.has-activities.today {
        background: linear-gradient(135deg, #dcfce7, #bbf7d0);
    }

    .admin-day-number {
        font-weight: 600;
        font-size: 1rem;
        margin-bottom: 0.25rem;
        color: #1f2937;
    }

    .admin-calendar-day.other-month .admin-day-number {
        color: #cbd5e1;
    }

    .admin-calendar-day.today .admin-day-number {
        color: #059669;
        font-weight: 700;
        font-size: 1.1rem;
    }

    .admin-day-activities {
        display: flex;
        flex-direction: column;
        gap: 2px;
        flex-grow: 1;
    }

    .admin-activity-item {
        background: #059669;
        color: white;
        padding: 2px 6px;
        border-radius: 4px;
        font-size: 0.75rem;
        line-height: 1.2;
        cursor: pointer;
        transition: all 0.2s ease;
        text-overflow: ellipsis;
        overflow: hidden;
        white-space: nowrap;
    }

    .admin-activity-item:hover {
        background: #047857;
        transform: translateY(-1px);
    }

    .admin-activity-item.status-approved {
        background: #059669;
    }

    .admin-more-activities {
        background: #6b7280;
        color: white;
        padding: 2px 6px;
        border-radius: 4px;
        font-size: 0.7rem;
        text-align: center;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .admin-more-activities:hover {
        background: #4b5563;
    }

    /* Admin Calendar Legend */
    .admin-calendar-legend {
        padding: 1rem 1.5rem;
        background: #f8fafc;
        border-top: 1px solid #e2e8f0;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .admin-legend-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .admin-legend-color {
        width: 12px;
        height: 12px;
        border-radius: 50%;
    }

    /* Admin Activity Modal Styles */
    .activity-modal {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.5);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 1000;
        backdrop-filter: blur(4px);
    }

    .activity-modal-content {
        background: white;
        border-radius: 12px;
        max-width: 600px;
        width: 90%;
        max-height: 80vh;
        overflow: hidden;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        animation: modalSlideIn 0.3s ease-out;
    }

    @keyframes modalSlideIn {
        from {
            opacity: 0;
            transform: scale(0.9) translateY(-20px);
        }
        to {
            opacity: 1;
            transform: scale(1) translateY(0);
        }
    }

    .activity-modal-header {
        background: linear-gradient(135deg, #059669, #047857);
        color: white;
        padding: 1.5rem;
    }

    .activity-modal-body {
        padding: 1.5rem;
        max-height: 60vh;
        overflow-y: auto;
    }

    .activity-detail {
        margin-bottom: 1rem;
        padding: 1rem;
        border-radius: 8px;
        border-left: 4px solid #059669;
        background: #f0fdf4;
    }

    .activity-detail h4 {
        font-weight: 600;
        color: #1f2937;
        margin-bottom: 0.5rem;
    }

    .activity-detail p {
        color: #059669;
        font-weight: 500;
        margin-bottom: 0.25rem;
    }

    .activity-detail span {
        color: #6b7280;
        font-size: 0.875rem;
    }

    @media (max-width: 768px) {
        .admin-calendar-day {
            min-height: 80px;
            padding: 0.25rem;
        }

        .admin-day-number {
            font-size: 0.875rem;
        }

        .admin-activity-item {
            font-size: 0.7rem;
            padding: 1px 4px;
        }

        .admin-calendar-month-year {
            font-size: 1.25rem;
        }

        .admin-calendar-legend {
            flex-direction: column;
            align-items: flex-start;
        }
    }

    .admin-nav-section {
        padding: 1.5rem 0;
        border-bottom: 1px solid #334155;
    }

    .admin-nav-section:last-child {
        border-bottom: none;
    }

    .admin-nav-section-title {
        color: #64748b;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        padding: 0 1.5rem 0.75rem;
        margin-bottom: 0.5rem;
    }

    .admin-nav-item {
        display: flex;
        align-items: center;
        padding: 0.875rem 1.5rem;
        color: #cbd5e1;
        text-decoration: none;
        transition: all 0.2s ease;
        border-left: 3px solid transparent;
        font-weight: 500;
    }

    .admin-nav-item:hover {
        background: #eab308;
        color: white;
        transform: translateX(4px);
    }

    .admin-nav-item.active {
        background: #eab308;
        color: white;
        font-weight: 600;
    }

    .admin-nav-item i {
        width: 20px;
        text-align: center;
        margin-right: 12px;
        font-size: 1rem;
    }

    .admin-toggle-btn {
        position: fixed;
        left: 20px;
        top: 50%;
        transform: translateY(-50%);
        background: linear-gradient(135deg, #059669, #047857);
        color: white;
        border: none;
        width: 50px;
        height: 50px;
        border-radius: 50%;
        cursor: pointer;
        z-index: 1001;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(5, 150, 105, 0.4);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
    }

    .admin-toggle-btn:hover {
        transform: translateY(-50%) scale(1.1);
        box-shadow: 0 6px 20px rgba(5, 150, 105, 0.6);
    }

    .admin-toggle-btn.sidebar-open {
        left: 300px;
    }



    .admin-stat-mini {
        background: #1e293b;
        border-radius: 8px;
        padding: 1rem;
        margin-bottom: 0.75rem;
        border-left: 3px solid #059669;
    }

    .admin-stat-mini:last-child {
        margin-bottom: 0;
    }

    .admin-stat-mini-number {
        font-size: 1.5rem;
        font-weight: 700;
        color: white;
        line-height: 1;
    }

    .admin-stat-mini-label {
        font-size: 0.75rem;
        color: #94a3b8;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        margin-top: 0.25rem;
    }

    /* Admin User Profile in Sidebar */
    .admin-user-profile {
        padding: 1.5rem;
        background: #0f172a;
        border-top: 1px solid #334155;
        margin-top: auto;
    }

    .admin-user-info {
        display: flex;
        align-items: center;
        margin-bottom: 1rem;
    }

    .admin-user-avatar {
        width: 40px;
        height: 40px;
        background: linear-gradient(135deg, #059669, #047857);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 700;
        font-size: 1rem;
        margin-right: 12px;
        box-shadow: 0 2px 8px rgba(5, 150, 105, 0.3);
    }

    .admin-user-details h4 {
        color: white;
        font-size: 0.875rem;
        font-weight: 600;
        margin: 0;
        line-height: 1.2;
    }

    .admin-user-details p {
        color: #64748b;
        font-size: 0.75rem;
        margin: 0;
        text-transform: capitalize;
    }

    .admin-logout-form {
        margin-top: 0.75rem;
    }

    .admin-logout-btn {
        width: 100%;
        background: linear-gradient(135deg, #dc2626, #b91c1c);
        color: white;
        border: none;
        padding: 0.75rem 1rem;
        border-radius: 8px;
        font-size: 0.875rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }

    .admin-logout-btn:hover {
        background: linear-gradient(135deg, #b91c1c, #991b1b);
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(220, 38, 38, 0.3);
    }

    .admin-logout-btn i {
        font-size: 0.875rem;
    }



    @media (max-width: 768px) {

        .admin-toggle-btn.sidebar-open {
            left: 20px;
        }
    }

    /* Analytics Statistics Cards Styling */
    .stat-card {
        position: relative;
        overflow: hidden;
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        color: white !important;
    }

    .stat-card:hover {
        transform: translateY(-8px) scale(1.02);
    }

    .stat-card .text-shadow-lg {
        text-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
    }

    .stat-card .shadow-3xl {
        box-shadow: 0 35px 60px rgba(0, 0, 0, 0.3);
    }

    .stat-card .backdrop-blur-sm {
        backdrop-filter: blur(8px);
    }

    .stat-card .shadow-inner {
        box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .stat-card .group:hover .scale-110 {
        transform: scale(1.1);
    }






</style>
@endpush





<div class="gradient-bg" style="margin-top: 60px; padding-top: 8px; padding-bottom: 0; height: auto;">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @if(!$tab || $tab === 'dashboard' || !in_array($tab, ['activities', 'calendar', 'analytics', 'reports', 'accounts', 'settings']))
        <!-- Quick Actions Dashboard -->

        <!-- Enhanced Statistics Cards with Animations -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6" id="adminStatsContainer">
            <!-- Total Activities Card -->
            <div class="animated-stat-card group" data-delay="0">
                <div class="card-inner">
                    <div class="card-glow"></div>
                    <div class="card-content">
                        <div class="flex items-center justify-between">
                            <div class="stat-info">
                                <div class="stat-icon-wrapper">
                                    <i class="fas fa-calendar-alt stat-icon"></i>
                                </div>
                                <h3 class="stat-title">Total Activities</h3>
                                <p class="stat-number">{{ $stats['total_activities'] }}</p>
                                <p class="stat-subtitle">All submissions</p>
                            </div>
                            <div class="stat-visual">
                                <div class="pulse-ring"></div>
                                <div class="pulse-ring-2"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pending Activities Card -->
            <div class="animated-stat-card group" data-delay="100">
                <div class="card-inner">
                    <div class="card-glow"></div>
                    <div class="card-content">
                        <div class="flex items-center justify-between">
                            <div class="stat-info">
                                <div class="stat-icon-wrapper">
                                    <i class="fas fa-clock stat-icon"></i>
                                </div>
                                <h3 class="stat-title">Pending</h3>
                                <p class="stat-number">{{ $stats['pending_activities'] }}</p>
                                <p class="stat-subtitle">Awaiting review</p>
                            </div>
                            <div class="stat-visual">
                                <div class="pulse-ring"></div>
                                <div class="pulse-ring-2"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Approved Activities Card -->
            <div class="animated-stat-card group" data-delay="200">
                <div class="card-inner">
                    <div class="card-glow"></div>
                    <div class="card-content">
                        <div class="flex items-center justify-between">
                            <div class="stat-info">
                                <div class="stat-icon-wrapper">
                                    <i class="fas fa-check-circle stat-icon"></i>
                                </div>
                                <h3 class="stat-title">Approved</h3>
                                <p class="stat-number">{{ $stats['approved_activities'] }}</p>
                                <p class="stat-subtitle">Ready to proceed</p>
                            </div>
                            <div class="stat-visual">
                                <div class="pulse-ring"></div>
                                <div class="pulse-ring-2"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Users Card -->
            <div class="animated-stat-card group" data-delay="300">
                <div class="card-inner">
                    <div class="card-glow"></div>
                    <div class="card-content">
                        <div class="flex items-center justify-between">
                            <div class="stat-info">
                                <div class="stat-icon-wrapper">
                                    <i class="fas fa-users stat-icon"></i>
                                </div>
                                <h3 class="stat-title">Total Users</h3>
                                <p class="stat-number">{{ $stats['total_users'] }}</p>
                                <p class="stat-subtitle">System users</p>
                            </div>
                            <div class="stat-visual">
                                <div class="pulse-ring"></div>
                                <div class="pulse-ring-2"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions Section -->
        <div class="content-card mb-6 overflow-hidden animate-fadeIn">
        <style>
            @keyframes fadeIn {
                from { opacity: 0; transform: translateY(20px); }
                to { opacity: 1; transform: translateY(0); }
            }
            .animate-fadeIn {
                animation: fadeIn 0.5s ease-out forwards;
            }
            .quick-action-link:nth-child(1) { animation-delay: 0.1s; }
            .quick-action-link:nth-child(2) { animation-delay: 0.2s; }
            .quick-action-link:nth-child(3) { animation-delay: 0.3s; }
            .quick-action-link:nth-child(4) { animation-delay: 0.4s; }
        </style>
            <div class="p-6 border-b border-gray-100 bg-gradient-to-r from-green-50 to-green-100 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-green-200/30 to-green-400/20 rounded-full -mr-16 -mt-16 blur-xl"></div>
                <h3 class="text-xl font-bold text-gray-900 flex items-center relative z-10">
                    <i class="fas fa-bolt mr-3 text-green-600 animate-pulse"></i>
                    <span class="relative">Quick Actions
                        <span class="absolute -bottom-1 left-0 w-full h-1 bg-gradient-to-r from-green-400 to-green-600 rounded-full"></span>
                    </span>
                </h3>
            </div>
            <div class="p-6 bg-gradient-to-b from-white to-green-50">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Manage Activities -->
                <a href="{{ route('admin.dashboard') }}?tab=activities" class="quick-action-link group">
                    <div class="content-card p-6 hover:shadow-xl transition-all duration-300 cursor-pointer relative overflow-hidden border-2 border-transparent hover:border-green-500 hover:shadow-green-200/50 rounded-xl hover:translate-y-[-8px] hover:bg-gradient-to-br hover:from-white hover:to-green-50 hover:scale-105 hover:ring-4 hover:ring-green-300/30">
                        <div class="absolute inset-0 bg-gradient-to-br from-green-400/5 to-green-600/10 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                        <div class="absolute inset-0 bg-gradient-to-r from-green-500/5 via-transparent to-green-500/5 opacity-0 group-hover:opacity-100 transition-opacity duration-500 animate-pulse"></div>
                        <div class="flex items-center justify-between mb-4 relative z-10">
                            <div class="icon-wrapper transform group-hover:rotate-12 group-hover:scale-110 transition-all duration-300 hover:shadow-lg hover:shadow-green-200/50">
                                <i class="fas fa-tasks text-white text-xl group-hover:animate-bounce"></i>
                            </div>
                            <span class="text-2xl font-bold text-green-600 group-hover:scale-125 group-hover:text-green-700 transition-all duration-300 animate-pulse">{{ $stats['pending_activities'] }}</span>
                        </div>
                        <h4 class="text-lg font-semibold text-gray-900 mb-2 group-hover:text-green-700 transition-colors duration-300">Manage Activities</h4>
                        <p class="text-gray-600 text-sm mb-4 group-hover:text-gray-700">Review, approve, or reject submitted activities</p>
                        <div class="flex items-center text-green-600 font-medium group-hover:translate-x-3 transition-transform duration-300">
                            <span class="group-hover:font-bold">View All Activities</span>
                            <i class="fas fa-arrow-right ml-2 group-hover:ml-4 transition-all duration-300 group-hover:animate-pulse"></i>
                        </div>
                    </div>
                </a>

                <!-- View Calendar -->
                <a href="{{ route('admin.dashboard') }}?tab=calendar" class="quick-action-link group">
                    <div class="content-card p-6 hover:shadow-xl transition-all duration-300 cursor-pointer relative overflow-hidden border-2 border-transparent hover:border-green-500 hover:shadow-green-200/50 rounded-xl hover:translate-y-[-8px] hover:bg-gradient-to-br hover:from-white hover:to-green-50 hover:scale-105 hover:ring-4 hover:ring-green-300/30">
                        <div class="absolute inset-0 bg-gradient-to-br from-green-400/5 to-green-600/10 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                        <div class="absolute inset-0 bg-gradient-to-r from-green-500/5 via-transparent to-green-500/5 opacity-0 group-hover:opacity-100 transition-opacity duration-500 animate-pulse"></div>
                        <div class="flex items-center justify-between mb-4 relative z-10">
                            <div class="icon-wrapper transform group-hover:rotate-12 group-hover:scale-110 transition-all duration-300 hover:shadow-lg hover:shadow-green-200/50">
                                <i class="fas fa-calendar-alt text-white text-xl group-hover:animate-bounce"></i>
                            </div>
                            <span class="text-2xl font-bold text-green-600 group-hover:scale-125 group-hover:text-green-700 transition-all duration-300 animate-pulse">{{ $stats['approved_activities'] }}</span>
                        </div>
                        <h4 class="text-lg font-semibold text-gray-900 mb-2 group-hover:text-green-700 transition-colors duration-300">Activity Calendar</h4>
                        <p class="text-gray-600 text-sm mb-4 group-hover:text-gray-700">View scheduled and approved activities</p>
                        <div class="flex items-center text-green-600 font-medium group-hover:translate-x-3 transition-transform duration-300">
                            <span class="group-hover:font-bold">Open Calendar</span>
                            <i class="fas fa-arrow-right ml-2 group-hover:ml-4 transition-all duration-300 group-hover:animate-pulse"></i>
                        </div>
                    </div>
                </a>

                <!-- Edit Accounts -->
                <a href="{{ route('admin.dashboard') }}?tab=accounts" class="quick-action-link group">
                    <div class="content-card p-6 hover:shadow-xl transition-all duration-300 cursor-pointer relative overflow-hidden border-2 border-transparent hover:border-green-500 hover:shadow-green-200/50 rounded-xl hover:translate-y-[-8px] hover:bg-gradient-to-br hover:from-white hover:to-green-50 hover:scale-105 hover:ring-4 hover:ring-green-300/30">
                        <div class="absolute inset-0 bg-gradient-to-br from-green-400/5 to-green-600/10 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                        <div class="absolute inset-0 bg-gradient-to-r from-green-500/5 via-transparent to-green-500/5 opacity-0 group-hover:opacity-100 transition-opacity duration-500 animate-pulse"></div>
                        <div class="flex items-center justify-between mb-4 relative z-10">
                            <div class="icon-wrapper transform group-hover:rotate-12 group-hover:scale-110 transition-all duration-300 hover:shadow-lg hover:shadow-green-200/50">
                                <i class="fas fa-users-cog text-white text-xl group-hover:animate-bounce"></i>
                            </div>
                            <span class="text-2xl font-bold text-green-600 group-hover:scale-125 group-hover:text-green-700 transition-all duration-300 animate-pulse">{{ $stats['total_users'] }}</span>
                        </div>
                        <h4 class="text-lg font-semibold text-gray-900 mb-2 group-hover:text-green-700 transition-colors duration-300">Edit Accounts</h4>
                        <p class="text-gray-600 text-sm mb-4 group-hover:text-gray-700">Manage user accounts and permissions</p>
                        <div class="flex items-center text-green-600 font-medium group-hover:translate-x-3 transition-transform duration-300">
                            <span class="group-hover:font-bold">Manage Users</span>
                            <i class="fas fa-arrow-right ml-2 group-hover:ml-4 transition-all duration-300 group-hover:animate-pulse"></i>
                        </div>
                    </div>
                </a>

                <!-- Generate Reports -->
                <a href="{{ route('admin.reports.index') }}" class="quick-action-link group">
                    <div class="content-card p-6 hover:shadow-xl transition-all duration-300 cursor-pointer relative overflow-hidden border-2 border-transparent hover:border-green-500 hover:shadow-green-200/50 rounded-xl hover:translate-y-[-8px] hover:bg-gradient-to-br hover:from-white hover:to-green-50 hover:scale-105 hover:ring-4 hover:ring-green-300/30">
                        <div class="absolute inset-0 bg-gradient-to-br from-green-400/5 to-green-600/10 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                        <div class="absolute inset-0 bg-gradient-to-r from-green-500/5 via-transparent to-green-500/5 opacity-0 group-hover:opacity-100 transition-opacity duration-500 animate-pulse"></div>
                        <div class="flex items-center justify-between mb-4 relative z-10">
                            <div class="icon-wrapper transform group-hover:rotate-12 group-hover:scale-110 transition-all duration-300 hover:shadow-lg hover:shadow-green-200/50">
                                <i class="fas fa-chart-bar text-white text-xl group-hover:animate-bounce"></i>
                            </div>
                            <span class="text-2xl font-bold text-green-600 group-hover:scale-125 group-hover:text-green-700 transition-all duration-300 animate-pulse">{{ $stats['total_activities'] }}</span>
                        </div>
                        <h4 class="text-lg font-semibold text-gray-900 mb-2 group-hover:text-green-700 transition-colors duration-300">Generate Reports</h4>
                        <p class="text-gray-600 text-sm mb-4 group-hover:text-gray-700">Create comprehensive reports on activities, users, and statistics</p>
                        <div class="flex items-center text-green-600 font-medium group-hover:translate-x-3 transition-transform duration-300">
                            <span class="group-hover:font-bold">Generate Reports</span>
                            <i class="fas fa-arrow-right ml-2 group-hover:ml-4 transition-all duration-300 group-hover:animate-pulse"></i>
                        </div>
                    </div>
                </a>

                </div>
            </div>
        </div>

        <!-- Recent Activity Summary -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            <!-- Recent Activities -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-4 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-xl font-bold text-gray-900 flex items-center">
                                <i class="fas fa-clock text-green-600 mr-2"></i>
                                Recent Activities
                            </h3>
                            <p class="text-sm text-gray-500 mt-1">Latest activity submissions</p>
                        </div>
                        <div class="icon-wrapper bg-green-600">
                            <i class="fas fa-history text-white text-lg"></i>
                        </div>
                    </div>
                </div>
                <div class="divide-y divide-gray-200">
                    @forelse($recentActivities->take(5) as $activity)
                        <div class="p-4">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ $activity->title }}</p>
                                    <p class="text-sm text-gray-500">
                                        @if($activity->activity_date->format('Y-m-d') === $activity->end_date->format('Y-m-d'))
                                            {{ $activity->activity_date->format('M d, Y') }}
                                        @else
                                            {{ $activity->activity_date->format('M d') }} - {{ $activity->end_date->format('M d, Y') }}
                                        @endif
                                    </p>
                                </div>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-{{ $activity->getStatusBadgeColor() }}-100 text-{{ $activity->getStatusBadgeColor() }}-800">
                                    {{ ucfirst($activity->status) }}
                                </span>
                            </div>
                        </div>
                    @empty
                <div class="p-4 text-center text-gray-500">
                            <i class="fas fa-inbox text-2xl mb-2"></i>
                            <p>No recent activities</p>
                        </div>
                    @endforelse
                </div>
                <div class="p-4 border-t border-gray-100">
                    <a href="{{ route('admin.dashboard') }}?tab=activities" class="text-green-600 hover:text-green-700 font-medium text-sm">
                        View all activities →
                    </a>
                </div>
            </div>

            <!-- System Status -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-4 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-xl font-bold text-gray-900 flex items-center">
                                <i class="fas fa-server text-green-600 mr-2"></i>
                                System Status
                            </h3>
                            <p class="text-sm text-gray-500 mt-1">Current system overview</p>
                        </div>
                        <div class="icon-wrapper bg-green-600">
                            <i class="fas fa-cogs text-white text-lg"></i>
                        </div>
                    </div>
                </div>
                <div class="p-4">
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-gray-700">Total Users</span>
                            <span class="text-sm font-bold text-gray-900">{{ $stats['total_users'] }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-gray-700">Active Activities</span>
                            <span class="text-sm font-bold text-green-600">{{ $stats['approved_activities'] }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-gray-700">Pending Reviews</span>
                            <span class="text-sm font-bold text-yellow-600">{{ $stats['pending_activities'] }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-gray-700">System Status</span>
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                <i class="fas fa-check-circle mr-1"></i>
                                Online
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Enhanced Charts Row (Compact) -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            <!-- Monthly Submissions Chart -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-4">
                <div class="mb-2">
                    <div>
                        <h3 class="text-xl font-bold text-gray-900">Monthly Submissions</h3>
                        <p class="text-sm text-gray-500 mt-1">Activity submission trends over time</p>
                    </div>
                    <div class="icon-wrapper">
                        <i class="fas fa-chart-line text-white text-lg"></i>
                    </div>
                </div>
                <div class="relative">
                    <canvas id="monthlyChart" width="400" height="200"></canvas>
                </div>
            </div>

            <!-- Status Distribution Chart -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-4">
                <div class="mb-2">
                    <div>
                        <h3 class="text-xl font-bold text-gray-900">Status Distribution</h3>
                        <p class="text-sm text-gray-500 mt-1">Current activity status breakdown</p>
                    </div>
                    <div class="icon-wrapper">
                        <i class="fas fa-chart-pie text-white text-lg"></i>
                    </div>
                </div>
                <div class="relative">
                    <canvas id="statusChart" width="400" height="200"></canvas>
                </div>
            </div>
        </div>

        <!-- All Submitted Activities -->
        <div class="mb-4">
            <div class="bg-white overflow-hidden shadow-green sm:rounded-xl">
                <div class="p-4 border-b border-gray-200">
                    <div class="flex justify-between items-center">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                            <i class="fas fa-list-alt text-green-600 mr-2"></i>
                            All Submitted Activities
                        </h3>
                        <a href="{{ route('admin.activities') }}" class="text-green-600 hover:text-green-700 text-sm font-medium">
                            Manage All →
                        </a>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Activity</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @php $dashboardActivities = $allActivities ?? $recentActivities; @endphp
                            @forelse($dashboardActivities as $activity)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">{{ $activity->title }}</div>
                                            <div class="text-sm text-gray-500">{{ Str::limit($activity->description, 50) }}</div>
                                        </div>
                                    </td>

                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            @if($activity->type === 'in-campus') bg-blue-100 text-blue-800
                                            @else bg-purple-100 text-purple-800
                                            @endif">
                                            {{ ucfirst(str_replace('-', ' ', $activity->type)) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            @if($activity->status === 'pending') bg-yellow-100 text-yellow-800
                                            @elseif($activity->status === 'recommended') bg-blue-100 text-blue-800
                                            @elseif($activity->status === 'approved') bg-green-100 text-green-800
                                            @elseif($activity->status === 'rejected') bg-red-100 text-red-800
                                            @endif">
                                            {{ ucfirst($activity->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $activity->created_at->format('M d, Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex space-x-2">
                                            <a href="{{ route('admin.activities.show', $activity) }}" class="text-green-600 hover:text-green-900" title="View Details">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.activities.edit-status', $activity) }}" class="text-blue-600 hover:text-blue-900" title="Edit Status">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                        <i class="fas fa-inbox text-3xl mb-2"></i>
                                        <p>No activities submitted yet</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($allActivities && $allActivities->hasPages())
                    <div class="px-6 py-4 border-t border-gray-200">
                        {{ $allActivities->links() }}
                    </div>
                @endif
            </div>
        </div>

        <!-- Enhanced Calendar and Recent Activities -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Recent Activities -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-4 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                                <i class="fas fa-clock text-green-600 mr-2"></i>
                                Recent Activities
                            </h3>
                            <p class="text-sm text-gray-500 mt-1">Latest activity submissions</p>
                        </div>
                        <div class="icon-wrapper bg-green-600">
                            <i class="fas fa-history text-white text-lg"></i>
                        </div>
                    </div>
                </div>
                <div class="divide-y divide-gray-200">
                    @forelse($recentActivities as $activity)
                        <div class="p-4">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ $activity->title }}</p>
                                    <p class="text-sm text-gray-500">
                                        @if($activity->activity_date->format('Y-m-d') === $activity->end_date->format('Y-m-d'))
                                            {{ $activity->activity_date->format('M d, Y') }}
                                        @else
                                            {{ $activity->activity_date->format('M d') }} - {{ $activity->end_date->format('M d, Y') }}
                                        @endif
                                    </p>
                                </div>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-{{ $activity->getStatusBadgeColor() }}-100 text-{{ $activity->getStatusBadgeColor() }}-800">
                                    {{ ucfirst($activity->status) }}
                                </span>
                            </div>
                        </div>
                    @empty
                        <div class="p-4 text-center text-gray-500">
                            No recent activities found.
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Most Active Departments -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Most Active Departments</h3>
                </div>
                <div class="divide-y divide-gray-200">
                    @forelse($activeOrganizations as $org)
                        <div class="p-4">
                            <div class="flex items-center justify-between">
                                <p class="text-sm font-medium text-gray-900">{{ $org->organization }}</p>
                                <span class="text-sm text-gray-500">{{ $org->count }} activities</span>
                            </div>
                        </div>
                    @empty
                        <div class="p-4 text-center text-gray-500">
                            No organization data available.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
        @endif

        @if($tab === 'activities')

        <!-- Activities Tab -->
        <div id="activities-tab" class="content-card activities-tab-card">
            <div class="p-8 border-b border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-2xl font-bold text-gray-900">All Submitted Activities</h3>
                        <p class="text-sm text-gray-500 mt-1">Total: {{ $allActivities->total() }} activities</p>
                    </div>
                    <div class="flex items-center space-x-4">
                        <form method="GET" action="{{ route('admin.dashboard') }}" class="flex items-center space-x-2">
                            <input type="hidden" name="tab" value="activities">
                            <label for="department" class="text-sm text-gray-700">Department:</label>
                            <select id="department" name="department" class="border-gray-300 rounded-md text-sm" onchange="this.form.submit()">
                                <option value="">All Departments</option>
                                @if(!empty($departments))
                                    @foreach($departments as $dept)
                                        <option value="{{ $dept }}" {{ ($selectedDepartment === $dept) ? 'selected' : '' }}>
                                            {{ $dept }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                        </form>
                        <div class="icon-wrapper">
                            <i class="fas fa-list text-white text-lg"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Activity</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Submitted</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($allActivities as $activity)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $activity->title }}</div>
                                    <div class="text-sm text-gray-500">{{ $activity->organization ?? 'No organization specified' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    @if($activity->formatted_date)
                                        {{ $activity->formatted_date }}
                                        <div class="text-xs text-gray-500">{{ $activity->date_duration }}</div>
                                    @else
                                        <span class="text-gray-400">Date not set</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ ucfirst(str_replace('-', ' ', $activity->type ?? 'N/A')) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        @if($activity->status === 'pending') bg-yellow-100 text-yellow-800
                                        @elseif($activity->status === 'recommended') bg-blue-100 text-blue-800
                                        @elseif($activity->status === 'approved') bg-green-100 text-green-800
                                        @elseif($activity->status === 'rejected') bg-red-100 text-red-800
                                        @else bg-gray-100 text-gray-800
                                        @endif">
                                        {{ ucfirst($activity->status ?? 'Unknown') }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $activity->created_at->format('M d, Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-2">
                                        <a href="{{ route('admin.activities.show', $activity) }}" class="text-green-600 hover:text-green-900" title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.activities.edit-status', $activity) }}" class="text-blue-600 hover:text-blue-900" title="Edit Status">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                    <i class="fas fa-inbox text-3xl mb-2"></i>
                                    <p>No activities submitted yet</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($allActivities->hasPages())
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $allActivities->links() }}
                </div>
            @endif
        </div>
        @endif

        @if($tab === 'calendar')
        <!-- Calendar Tab - Exact Copy from Student Calendar -->
        <div class="pb-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Calendar Container -->
                <div class="calendar-container">
                    <div id="admin-activity-calendar" class="w-full">
                        <!-- Traditional calendar will be rendered here -->
                    </div>

                    <!-- Calendar Legend -->
                    <div class="calendar-legend">
                        <div class="legend-item">
                            <div class="legend-color bg-green-500"></div>
                            <span>Approved Activities</span>
                        </div>
                        <div class="text-sm text-gray-600 italic">
                            <i class="fas fa-info-circle mr-1"></i>
                            Only approved activities are displayed on the calendar
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        @if($tab === 'analytics')

        <!-- Enhanced Analytics Tab -->
        <div class="content-card mb-8">
            <div class="p-8 border-b border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-2xl font-bold text-gray-900">Analytics Dashboard</h3>
                        <p class="text-sm text-gray-500 mt-1">Comprehensive activity analytics and insights</p>
                    </div>
                    <div class="icon-wrapper bg-green-600">
                        <i class="fas fa-chart-pie text-white text-lg"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Minimal Analytics Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Total Activities -->
            <div class="bg-blue-500 rounded-lg p-6 text-white shadow-md">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium opacity-90">Total Activities</p>
                        <p class="text-3xl font-bold">{{ $stats['total_activities'] }}</p>
                        <p class="text-xs opacity-80">All submissions</p>
                    </div>
                    <div class="w-16 h-16 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                        <i class="fas fa-calendar-check text-white text-2xl"></i>
                    </div>
                </div>
            </div>

            <!-- Approval Rate -->
            <div class="bg-green-500 rounded-lg p-6 text-white shadow-md">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium opacity-90">Approval Rate</p>
                        <p class="text-3xl font-bold">
                            @if($stats['total_activities'] > 0)
                                {{ round(($stats['approved_activities'] / $stats['total_activities']) * 100, 1) }}%
                            @else
                                0%
                            @endif
                        </p>
                        <p class="text-xs opacity-80">Activities approved</p>
                    </div>
                    <div class="w-16 h-16 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                        <i class="fas fa-chart-line text-white text-2xl"></i>
                    </div>
                </div>
            </div>

            <!-- Pending Reviews -->
            <div class="bg-yellow-500 rounded-lg p-6 text-white shadow-md">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium opacity-90">Pending Reviews</p>
                        <p class="text-3xl font-bold">{{ $stats['pending_activities'] }}</p>
                        <p class="text-xs opacity-80">Awaiting approval</p>
                    </div>
                    <div class="w-16 h-16 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                        <i class="fas fa-clock text-white text-2xl"></i>
                    </div>
                </div>
            </div>

            <!-- Active Users -->
            <div class="bg-purple-500 rounded-lg p-6 text-white shadow-md">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium opacity-90">Active Users</p>
                        <p class="text-3xl font-bold">{{ $stats['total_users'] }}</p>
                        <p class="text-xs opacity-80">System users</p>
                    </div>
                    <div class="w-16 h-16 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                        <i class="fas fa-users text-white text-2xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Enhanced Charts Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- Monthly Submissions Chart -->
            <div class="content-card p-8">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h3 class="text-xl font-bold text-gray-900">Monthly Activity Trends</h3>
                        <p class="text-sm text-gray-500 mt-1">Activity submission patterns over time</p>
                    </div>
                    <div class="icon-wrapper bg-green-600">
                        <i class="fas fa-chart-line text-white text-lg"></i>
                    </div>
                </div>
                <div class="relative">
                    <canvas id="analyticsMonthlyChart" width="400" height="250"></canvas>
                </div>
            </div>

            <!-- Status Distribution Chart -->
            <div class="content-card p-8">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h3 class="text-xl font-bold text-gray-900">Activity Status Breakdown</h3>
                        <p class="text-sm text-gray-500 mt-1">Current distribution of activity statuses</p>
                    </div>
                    <div class="icon-wrapper bg-green-600">
                        <i class="fas fa-chart-pie text-white text-lg"></i>
                    </div>
                </div>
                <div class="relative">
                    <canvas id="analyticsStatusChart" width="400" height="250"></canvas>
                </div>
            </div>
        </div>

        <!-- Additional Analytics Charts -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- Activity Types Chart -->
            <div class="content-card p-8">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h3 class="text-xl font-bold text-gray-900">Activity Types</h3>
                        <p class="text-sm text-gray-500 mt-1">Distribution of in-campus vs off-campus activities</p>
                    </div>
                    <div class="icon-wrapper bg-green-600">
                        <i class="fas fa-chart-bar text-white text-lg"></i>
                    </div>
                </div>
                <div class="relative">
                    <canvas id="activityTypesChart" width="400" height="250"></canvas>
                </div>
            </div>

            <!-- Weekly Activity Chart -->
            <div class="content-card p-8">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h3 class="text-xl font-bold text-gray-900">Weekly Submissions</h3>
                        <p class="text-sm text-gray-500 mt-1">Activity submissions by day of the week</p>
                    </div>
                    <div class="icon-wrapper bg-green-600">
                        <i class="fas fa-calendar-week text-white text-lg"></i>
                    </div>
                </div>
                <div class="relative">
                    <canvas id="weeklyChart" width="400" height="250"></canvas>
                </div>
            </div>
        </div>

        <!-- Analytics Summary Table -->
        <div class="content-card">
            <div class="p-8 border-b border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-xl font-bold text-gray-900">Analytics Summary</h3>
                        <p class="text-sm text-gray-500 mt-1">Key performance indicators and metrics</p>
                    </div>
                    <div class="icon-wrapper bg-green-600">
                        <i class="fas fa-table text-white text-lg"></i>
                    </div>
                </div>
            </div>
            <div class="p-8">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <!-- Metrics Cards -->
                    <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-lg p-6 border border-green-200">
                        <div class="flex items-center justify-between mb-4">
                            <h4 class="font-semibold text-green-900">Total Submissions</h4>
                            <i class="fas fa-upload text-green-600 text-xl"></i>
                        </div>
                        <p class="text-3xl font-bold text-green-800">{{ $stats['total_activities'] }}</p>
                        <p class="text-sm text-green-600 mt-2">All time submissions</p>
                    </div>

                    <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-lg p-6 border border-green-200">
                        <div class="flex items-center justify-between mb-4">
                            <h4 class="font-semibold text-green-900">Success Rate</h4>
                            <i class="fas fa-trophy text-green-600 text-xl"></i>
                        </div>
                        <p class="text-3xl font-bold text-green-800">
                            @if($stats['total_activities'] > 0)
                                {{ round(($stats['approved_activities'] / $stats['total_activities']) * 100, 1) }}%
                            @else
                                0%
                            @endif
                        </p>
                        <p class="text-sm text-green-600 mt-2">Approval success rate</p>
                    </div>

                    <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-lg p-6 border border-green-200">
                        <div class="flex items-center justify-between mb-4">
                            <h4 class="font-semibold text-green-900">Average Processing</h4>
                            <i class="fas fa-stopwatch text-green-600 text-xl"></i>
                        </div>
                        <p class="text-3xl font-bold text-green-800">2.5</p>
                        <p class="text-sm text-green-600 mt-2">Days average processing</p>
                    </div>
                </div>
            </div>
        </div>
        @endif

        @if($tab === 'reports')
        <!-- Reports Tab -->
        <div class="content-card">
            <div class="p-8 border-b border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-2xl font-bold text-gray-900">Activity Reports</h3>
                        <p class="text-sm text-gray-500 mt-1">Generate and download activity reports</p>
                    </div>
                    <div class="icon-wrapper">
                        <i class="fas fa-file-alt text-white text-lg"></i>
                    </div>
                </div>
            </div>
            <div class="p-8">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <!-- Monthly Report -->
                    <div class="border border-gray-200 rounded-lg p-6 hover:shadow-md transition-all duration-300">
                        <div class="flex items-center mb-4">
                            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mr-4">
                                <i class="fas fa-calendar-month text-blue-600 text-xl"></i>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-900">Monthly Report</h4>
                                <p class="text-sm text-gray-500">Activities by month</p>
                            </div>
                        </div>
                        <button class="w-full bg-blue-600 text-white py-2 px-4 rounded-lg hover:bg-blue-700 transition-colors">
                            Generate Report
                        </button>
                    </div>

                    <!-- Status Report -->
                    <div class="border border-gray-200 rounded-lg p-6 hover:shadow-md transition-all duration-300">
                        <div class="flex items-center mb-4">
                            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mr-4">
                                <i class="fas fa-chart-bar text-green-600 text-xl"></i>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-900">Status Report</h4>
                                <p class="text-sm text-gray-500">Activities by status</p>
                            </div>
                        </div>
                        <button class="w-full bg-green-600 text-white py-2 px-4 rounded-lg hover:bg-green-700 transition-colors">
                            Generate Report
                        </button>
                    </div>

                    <!-- Custom Report -->
                    <div class="border border-gray-200 rounded-lg p-6 hover:shadow-md transition-all duration-300">
                        <div class="flex items-center mb-4">
                            <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mr-4">
                                <i class="fas fa-cog text-purple-600 text-xl"></i>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-900">Custom Report</h4>
                                <p class="text-sm text-gray-500">Custom date range</p>
                            </div>
                        </div>
                        <button class="w-full bg-purple-600 text-white py-2 px-4 rounded-lg hover:bg-purple-700 transition-colors">
                            Configure Report
                        </button>
                    </div>
                </div>
            </div>
        </div>
        @endif

        @if($tab === 'accounts')
        <!-- Accounts Tab -->
        <div class="content-card">
            <div class="p-8 border-b border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-2xl font-bold text-gray-900">Account Management</h3>
                        <p class="text-sm text-gray-500 mt-1">Manage user accounts, roles, and permissions</p>
                    </div>
                    <div class="icon-wrapper">
                        <i class="fas fa-users-cog text-white text-lg"></i>
                    </div>
                </div>
            </div>
            <div class="p-8">
                <!-- Quick Account Actions -->
                <style>
                .account-button {
                    background-color: #059669 !important;
                    color: white !important;
                    border: 2px solid #059669 !important;
                    padding: 12px 24px !important;
                    border-radius: 8px !important;
                    font-weight: 600 !important;
                    width: 100% !important;
                    transition: all 0.3s ease !important;
                    cursor: pointer !important;
                    display: block !important;
                    text-align: center !important;
                }

                .account-button:hover {
                    background-color: #047857 !important;
                    border-color: #047857 !important;
                    transform: translateY(-2px) !important;
                    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05) !important;
                }

                .account-button:active {
                    transform: translateY(0) !important;
                }
                </style>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-user-plus text-blue-600 text-xl"></i>
                            </div>
                            <div class="text-right">
                                <div class="text-2xl font-bold text-gray-800">{{ $stats['total_users'] }}</div>
                                <div class="text-gray-500 text-xs">Total Users</div>
                            </div>
                        </div>
                        <h4 class="text-lg font-semibold mb-2 text-gray-800">Create Account</h4>
                        <p class="text-gray-600 text-sm mb-4">Add new user accounts to the system</p>
                        <button onclick="window.location.href='{{ route('admin.users.create') }}'" class="account-button">
                            <i class="fas fa-plus mr-2"></i>Add User
                        </button>
                    </div>

                    <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-user-edit text-green-600 text-xl"></i>
                            </div>
                            <div class="text-right">
                                <div class="text-2xl font-bold text-gray-800">{{ User::where('role', 'student')->count() }}</div>
                                <div class="text-gray-500 text-xs">Users</div>
                            </div>
                        </div>
                        <h4 class="text-lg font-semibold mb-2 text-gray-800">Edit Accounts</h4>
                        <p class="text-gray-600 text-sm mb-4">Modify existing user information</p>
                        <button onclick="window.location.href='{{ route('admin.users') }}'" class="account-button">
                            <i class="fas fa-edit mr-2"></i>Edit Users
                        </button>
                    </div>

                    <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-user-shield text-purple-600 text-xl"></i>
                            </div>
                            <div class="text-right">
                                <div class="text-2xl font-bold text-gray-800">{{ User::whereIn('role', ['admin', 'adviser', 'osa'])->count() }}</div>
                                <div class="text-gray-500 text-xs">Staff</div>
                            </div>
                        </div>
                        <h4 class="text-lg font-semibold mb-2 text-gray-800">Manage Roles</h4>
                        <p class="text-gray-600 text-sm mb-4">Assign and modify user roles</p>
                        <button onclick="window.location.href='{{ route('admin.users') }}'" class="account-button">
                            <i class="fas fa-shield-alt mr-2"></i>Manage Roles
                        </button>
                    </div>
                </div>


            </div>
        </div>
        @endif

        @if($tab === 'analytics')
        <!-- Analytics Tab -->
        <div class="content-card">
            <div class="p-8">
                <p class="text-center text-gray-500">Analytics content will be displayed here.</p>
            </div>
        </div>
        @endif

        @if($tab === 'settings')
        <!-- Settings Tab -->
        <div class="content-card">
            <div class="p-8 border-b border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-2xl font-bold text-gray-900">System Settings</h3>
                        <p class="text-sm text-gray-500 mt-1">Configure system preferences and options</p>
                    </div>
                    <div class="icon-wrapper">
                        <i class="fas fa-cog text-white text-lg"></i>
                    </div>
                </div>
            </div>
            <div class="p-8">
                <form method="POST" action="#" class="space-y-6">
                    @csrf

                    <!-- System Information -->
                    <div class="border border-gray-200 rounded-lg p-6 bg-gray-50">
                        <h4 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                            <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                            System Information
                        </h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="space-y-2">
                                <div class="flex justify-between">
                                    <span class="text-sm font-medium text-gray-600">System Name:</span>
                                    <span class="text-sm text-gray-900">In/Off Campus Activity Scheduling Information System</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-sm font-medium text-gray-600">Laravel Version:</span>
                                    <span class="text-sm text-gray-900">{{ app()->version() }}</span>
                                </div>
                            </div>
                            <div class="space-y-2">
                                <div class="flex justify-between">
                                    <span class="text-sm font-medium text-gray-600">PHP Version:</span>
                                    <span class="text-sm text-gray-900">{{ PHP_VERSION }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Activity Management Settings -->
                    <div class="border border-gray-200 rounded-lg p-6">
                        <h4 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                            <i class="fas fa-calendar-alt text-green-600 mr-2"></i>
                            Activity Management
                        </h4>
                        <div class="space-y-4">
                            <div class="flex items-center justify-between">
                                <div>
                                    <label class="text-sm font-medium text-gray-700">Enable conflict detection</label>
                                    <p class="text-xs text-gray-500">Check for venue and time conflicts automatically</p>
                                </div>
                                <input type="checkbox" class="toggle-switch" checked>
                            </div>
                        </div>
                    </div>



                    <!-- File Upload Settings -->
                    <div class="border border-gray-200 rounded-lg p-6">
                        <h4 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                            <i class="fas fa-file-upload text-purple-600 mr-2"></i>
                            File Upload Settings
                        </h4>
                        <div class="space-y-4">
                            <div>
                                <label class="text-sm font-medium text-gray-700 mb-2 block">Maximum file size (MB)</label>
                                <input type="number" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500" value="10" min="1" max="100">
                                <p class="text-xs text-gray-500 mt-1">Current limit: {{ ini_get('upload_max_filesize') }}</p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-700 mb-2 block">Allowed file types</label>
                                <div class="flex flex-wrap gap-2">
                                    <span class="px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded">PDF</span>
                                    <span class="px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded">DOC</span>
                                    <span class="px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded">DOCX</span>
                                </div>
                                <p class="text-xs text-gray-500 mt-1">Supported formats for activity documents</p>
                            </div>
                        </div>
                    </div>

                    <!-- Security Settings -->
                    <div class="border border-gray-200 rounded-lg p-6">
                        <h4 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                            <i class="fas fa-shield-alt text-red-600 mr-2"></i>
                            Security Settings
                        </h4>
                        <div class="space-y-4">
                            <div class="flex items-center justify-between">
                                <div>
                                    <label class="text-sm font-medium text-gray-700">Force password reset</label>
                                    <p class="text-xs text-gray-500">Require users to change passwords every 90 days</p>
                                </div>
                                <input type="checkbox" class="toggle-switch">
                            </div>
                            <div class="flex items-center justify-between">
                                <div>
                                    <label class="text-sm font-medium text-gray-700">Two-factor authentication</label>
                                    <p class="text-xs text-gray-500">Enable 2FA for admin accounts</p>
                                </div>
                                <input type="checkbox" class="toggle-switch">
                            </div>
                            <div class="flex items-center justify-between">
                                <div>
                                    <label class="text-sm font-medium text-gray-700">Session timeout</label>
                                    <p class="text-xs text-gray-500">Auto-logout after inactivity</p>
                                </div>
                                <select class="px-3 py-1 border border-gray-300 rounded text-sm">
                                    <option>30 minutes</option>
                                    <option>1 hour</option>
                                    <option>2 hours</option>
                                    <option>4 hours</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Database Settings -->
                    <div class="border border-gray-200 rounded-lg p-6">
                        <h4 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                            <i class="fas fa-database text-indigo-600 mr-2"></i>
                            Database & Maintenance
                        </h4>
                        <div class="space-y-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="text-sm font-medium text-gray-700 mb-2 block">Total Activities</label>
                                    <div class="text-2xl font-bold text-green-600">{{ $stats['total_activities'] }}</div>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-gray-700 mb-2 block">Total Users</label>
                                    <div class="text-2xl font-bold text-blue-600">{{ $stats['total_users'] }}</div>
                                </div>
                            </div>
                            <div class="flex items-center justify-between">
                                <div>
                                    <label class="text-sm font-medium text-gray-700">Auto-backup database</label>
                                    <p class="text-xs text-gray-500">Automatically backup database daily</p>
                                </div>
                                <input type="checkbox" class="toggle-switch" checked>
                            </div>
                            <div class="flex items-center justify-between">
                                <div>
                                    <label class="text-sm font-medium text-gray-700">Maintenance mode</label>
                                    <p class="text-xs text-gray-500">Put system in maintenance mode</p>
                                </div>
                                <button type="button" class="px-3 py-1 bg-yellow-500 text-white text-sm rounded hover:bg-yellow-600">
                                    Enable
                                </button>
                            </div>
                        </div>
                    </div>
                </form>

                <div class="mt-8 flex justify-between">
                    <button type="button" class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                        Reset to Defaults
                    </button>
                    <button type="submit" class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                        <i class="fas fa-save mr-2"></i>Save Settings
                    </button>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Quick action cards are now using direct HTML links for better reliability
    // Delete Activity handler for Activities tab
    function deleteActivity(activityId, activityTitle) {
        const title = typeof activityTitle === 'string' ? activityTitle : '';
        if (!confirm(`Delete activity "${title}"? This cannot be undone.`)) return;

        // Build the delete URL from the named route
        const urlTemplate = "{{ route('admin.activities.delete', 'ACTIVITY_ID') }}";
        const action = urlTemplate.replace('ACTIVITY_ID', activityId);

        // Create and submit a form with method spoofing and CSRF token
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = action;

        const csrf = document.createElement('input');
        csrf.type = 'hidden';
        csrf.name = '_token';
        csrf.value = "{{ csrf_token() }}";

        const method = document.createElement('input');
        method.type = 'hidden';
        method.name = '_method';
        method.value = 'DELETE';

        form.appendChild(csrf);
        form.appendChild(method);
        document.body.appendChild(form);
        form.submit();
    }

    // Initialize charts only if elements exist
    @if($tab === 'dashboard')
    document.addEventListener('DOMContentLoaded', function() {
        // Monthly Submissions Chart (Dashboard)
        const monthlyCtx = document.getElementById('monthlyChart');
        if (monthlyCtx) {
            const monthlyData = @json($monthlySubmissions ?? []);

            new Chart(monthlyCtx, {
                type: 'line',
                data: {
                    labels: monthlyData.map(item => {
                        const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
                        return months[item.month - 1];
                    }),
                    datasets: [{
                        label: 'Submissions',
                        data: monthlyData.map(item => item.count),
                        borderColor: 'rgb(59, 130, 246)',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        tension: 0.1
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        }

        // Status Distribution Chart (Dashboard)
        const statusCtx = document.getElementById('statusChart');
        if (statusCtx) {
            const statusData = @json($statusDistribution ?? []);

            // Create color mapping for each status
            const getStatusColor = (status) => {
                switch(status.toLowerCase()) {
                    case 'pending': return '#eab308'; // yellow
                    case 'recommended': return '#60A5FA'; // blue
                    case 'approved': return '#059669'; // green
                    case 'rejected': return '#EF4444'; // red
                    default: return '#6B7280'; // gray
                }
            };

            new Chart(statusCtx, {
                type: 'doughnut',
                data: {
                    labels: statusData.map(item => item.status.charAt(0).toUpperCase() + item.status.slice(1)),
                    datasets: [{
                        data: statusData.map(item => item.count),
                        backgroundColor: statusData.map(item => getStatusColor(item.status))
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });
        }
    });
    @endif

    // Enhanced Analytics Charts
    @if($tab === 'analytics')
    document.addEventListener('DOMContentLoaded', function() {
        // Analytics Monthly Chart
        const analyticsMonthlyCtx = document.getElementById('analyticsMonthlyChart');
        if (analyticsMonthlyCtx) {
            const monthlyData = @json($monthlySubmissions ?? []);

            new Chart(analyticsMonthlyCtx, {
                type: 'line',
                data: {
                    labels: monthlyData.map(item => {
                        const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
                        return months[item.month - 1];
                    }),
                    datasets: [{
                        label: 'Activity Submissions',
                        data: monthlyData.map(item => item.count),
                        borderColor: '#059669',
                        backgroundColor: 'rgba(5, 150, 105, 0.1)',
                        tension: 0.4,
                        fill: true,
                        pointBackgroundColor: '#059669',
                        pointBorderColor: '#ffffff',
                        pointBorderWidth: 2,
                        pointRadius: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top'
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(0, 0, 0, 0.1)'
                            }
                        },
                        x: {
                            grid: {
                                color: 'rgba(0, 0, 0, 0.1)'
                            }
                        }
                    }
                }
            });
        }

        // Analytics Status Chart
        const analyticsStatusCtx = document.getElementById('analyticsStatusChart');
        if (analyticsStatusCtx) {
            const statusData = @json($statusDistribution ?? []);

            // Create color mapping for each status
            const getStatusColor = (status) => {
                switch(status.toLowerCase()) {
                    case 'pending': return '#eab308'; // yellow
                    case 'recommended': return '#60A5FA'; // blue
                    case 'approved': return '#059669'; // green
                    case 'rejected': return '#EF4444'; // red
                    default: return '#6B7280'; // gray
                }
            };

            new Chart(analyticsStatusCtx, {
                type: 'doughnut',
                data: {
                    labels: statusData.map(item => item.status.charAt(0).toUpperCase() + item.status.slice(1)),
                    datasets: [{
                        data: statusData.map(item => item.count),
                        backgroundColor: statusData.map(item => getStatusColor(item.status)),
                        borderWidth: 3,
                        borderColor: '#ffffff'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                padding: 20,
                                usePointStyle: true
                            }
                        }
                    }
                }
            });
        }

        // Activity Types Chart
        const activityTypesCtx = document.getElementById('activityTypesChart');
        if (activityTypesCtx) {
            // Sample data for activity types - you can replace with actual data
            const typeData = [
                { type: 'In-Campus', count: {{ \App\Models\Activity::where('type', 'in-campus')->count() }} },
                { type: 'Off-Campus', count: {{ \App\Models\Activity::where('type', 'off-campus')->count() }} }
            ];

            new Chart(activityTypesCtx, {
                type: 'bar',
                data: {
                    labels: typeData.map(item => item.type),
                    datasets: [{
                        label: 'Activities',
                        data: typeData.map(item => item.count),
                        backgroundColor: [
                            '#059669',
                            '#eab308'
                        ],
                        borderColor: [
                            '#047857',
                            '#d97706'
                        ],
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(0, 0, 0, 0.1)'
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });
        }

        // Weekly Activity Chart
        const weeklyCtx = document.getElementById('weeklyChart');
        if (weeklyCtx) {
            // Real weekly data from database
            const weeklyDataFromDB = @json($weeklySubmissions ?? []);

            // Ensure all days are represented with 0 count if no data
            const allDays = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
            const weeklyData = allDays.map(day => {
                const found = weeklyDataFromDB.find(item => item.day === day);
                return {
                    day: day,
                    count: found ? found.count : 0
                };
            });

            new Chart(weeklyCtx, {
                type: 'radar',
                data: {
                    labels: weeklyData.map(item => item.day),
                    datasets: [{
                        label: 'Activity Submissions',
                        data: weeklyData.map(item => item.count),
                        backgroundColor: 'rgba(5, 150, 105, 0.2)',
                        borderColor: '#059669',
                        borderWidth: 2,
                        pointBackgroundColor: '#059669',
                        pointBorderColor: '#ffffff',
                        pointBorderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        r: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(0, 0, 0, 0.1)'
                            }
                        }
                    }
                }
            });
        }
    });
    @endif

    // Fix delete button functionality for activities tab
    @if($tab === 'activities')
    document.addEventListener('DOMContentLoaded', function() {
        // Ensure all delete buttons are properly clickable
        function fixDeleteButtons() {
            const deleteButtons = document.querySelectorAll('button[title="Delete Activity"]');
            const deleteForms = document.querySelectorAll('form[action*="/admin/activities/"]');
            
            console.log('Found delete buttons:', deleteButtons.length);
            console.log('Found delete forms:', deleteForms.length);
            
            // Fix button styling and events
            deleteButtons.forEach((button, index) => {
                // Ensure button is clickable
                button.style.pointerEvents = 'auto';
                button.style.position = 'relative';
                button.style.zIndex = '1000';
                button.style.cursor = 'pointer';
                
                // Remove any existing event listeners and add new ones
                const newButton = button.cloneNode(true);
                button.parentNode.replaceChild(newButton, button);
                
                // Add click event listener
                newButton.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    
                    const form = this.closest('form');
                    if (form) {
                        const activityTitle = form.getAttribute('onsubmit').match(/Delete activity \\"([^"]+)\\"/);
                        const title = activityTitle ? activityTitle[1] : 'this activity';
                        
                        if (confirm(`Delete activity "${title}"? This cannot be undone.`)) {
                            form.submit();
                        }
                    }
                });
            });
            
            // Fix form styling
            deleteForms.forEach(form => {
                if (form.getAttribute('action').includes('/admin/activities/')) {
                    form.style.display = 'inline-block';
                    form.style.pointerEvents = 'auto';
                    form.style.position = 'relative';
                    form.style.zIndex = '999';
                }
            });
        }
        
        // Initial fix
        fixDeleteButtons();
        
        // Re-fix after any dynamic content updates
        setTimeout(fixDeleteButtons, 1000);
        
        // Also fix on any table updates
        const observer = new MutationObserver(function(mutations) {
            mutations.forEach(function(mutation) {
                if (mutation.type === 'childList') {
                    fixDeleteButtons();
                }
            });
        });
        
        const tableContainer = document.querySelector('table');
        if (tableContainer) {
            observer.observe(tableContainer, { childList: true, subtree: true });
        }
    });
    @endif

    // Traditional Activity Calendar (for calendar tab only)
    @if($tab === 'calendar')
    // Populate calendar activities from server data (ALL approved activities) - Same as student calendar
    const calendarActivities = [
        @foreach($calendarActivities as $activity)
        {
            id: {{ $activity['id'] }},
            title: "{{ addslashes($activity['title']) }}",
            organization: "{{ addslashes($activity['organization'] ?? '') }}",
            activity_date: "{{ $activity['activity_date'] }}",
            end_date: "{{ $activity['end_date'] }}",
            start_time: "{{ $activity['start_time'] }}",
            end_time: "{{ $activity['end_time'] }}",
            location: "{{ addslashes($activity['location']) }}",
            status: "{{ $activity['status'] }}"
        },
        @endforeach
    ];

    class TraditionalCalendar {
        constructor(containerId, activities) {
            this.container = document.getElementById(containerId);
            this.activities = activities;
            this.currentDate = new Date();
            this.currentMonth = this.currentDate.getMonth();
            this.currentYear = this.currentDate.getFullYear();
            this.render();
        }

        render() {
            const monthNames = [
                'January', 'February', 'March', 'April', 'May', 'June',
                'July', 'August', 'September', 'October', 'November', 'December'
            ];

            this.container.innerHTML = `
                <div class="calendar-header">
                    <div class="flex justify-between items-center">
                        <button onclick="calendar.previousMonth()" class="calendar-nav-btn">
                            <i class="fas fa-chevron-left"></i>
                        </button>
                        <h2 class="calendar-month-year">
                            ${monthNames[this.currentMonth]} ${this.currentYear}
                        </h2>
                        <button onclick="calendar.nextMonth()" class="calendar-nav-btn">
                            <i class="fas fa-chevron-right"></i>
                        </button>
                    </div>
                </div>
                <div class="calendar-weekdays">
                    <div class="calendar-weekday">Sunday</div>
                    <div class="calendar-weekday">Monday</div>
                    <div class="calendar-weekday">Tuesday</div>
                    <div class="calendar-weekday">Wednesday</div>
                    <div class="calendar-weekday">Thursday</div>
                    <div class="calendar-weekday">Friday</div>
                    <div class="calendar-weekday">Saturday</div>
                </div>
                <div class="calendar-days">
                    ${this.renderCalendarDays()}
                </div>
            `;
        }

        renderCalendarDays() {
            const daysInMonth = new Date(this.currentYear, this.currentMonth + 1, 0).getDate();
            const firstDayOfMonth = new Date(this.currentYear, this.currentMonth, 1).getDay();
            const daysInPrevMonth = new Date(this.currentYear, this.currentMonth, 0).getDate();

            let html = '';
            let dayCount = 1;
            let nextMonthDay = 1;

            // Calculate total cells needed (6 rows × 7 days = 42 cells)
            for (let i = 0; i < 42; i++) {
                let dayNumber, dateStr, isCurrentMonth = true, isToday = false;
                let dayClass = 'calendar-day';

                if (i < firstDayOfMonth) {
                    // Previous month days
                    dayNumber = daysInPrevMonth - firstDayOfMonth + i + 1;
                    const prevMonth = this.currentMonth === 0 ? 11 : this.currentMonth - 1;
                    const prevYear = this.currentMonth === 0 ? this.currentYear - 1 : this.currentYear;
                    dateStr = `${prevYear}-${String(prevMonth + 1).padStart(2, '0')}-${String(dayNumber).padStart(2, '0')}`;
                    dayClass += ' other-month';
                    isCurrentMonth = false;
                } else if (dayCount <= daysInMonth) {
                    // Current month days
                    dayNumber = dayCount;
                    dateStr = `${this.currentYear}-${String(this.currentMonth + 1).padStart(2, '0')}-${String(dayNumber).padStart(2, '0')}`;
                    isToday = this.isToday(dayNumber);
                    dayCount++;
                } else {
                    // Next month days
                    dayNumber = nextMonthDay;
                    const nextMonth = this.currentMonth === 11 ? 0 : this.currentMonth + 1;
                    const nextYear = this.currentMonth === 11 ? this.currentYear + 1 : this.currentYear;
                    dateStr = `${nextYear}-${String(nextMonth + 1).padStart(2, '0')}-${String(dayNumber).padStart(2, '0')}`;
                    dayClass += ' other-month';
                    isCurrentMonth = false;
                    nextMonthDay++;
                }

                // Get only approved activities for this date (including multi-day activities)
                const dayActivities = this.activities.filter(activity => {
                    if (activity.status !== 'approved') return false;
                    // Use string comparison to avoid timezone issues
                    return dateStr >= activity.activity_date && dateStr <= activity.end_date;
                });

                if (isToday) {
                    dayClass += ' today';
                }

                // Only mark days that have approved activities
                if (dayActivities.length > 0) {
                    dayClass += ' has-activities';
                }

                html += `<div class="${dayClass}" onclick="calendar.showDayActivities('${dateStr}', '${dayNumber}', ${isCurrentMonth})">
                    <div class="day-number">${dayNumber}</div>
                    <div class="day-activities">
                        ${this.renderDayActivities(dayActivities, dateStr)}
                    </div>
                </div>`;
            }

            return html;
        }

        renderDayActivities(activities, dateStr) {
            // Only show approved activities on the calendar
            const approvedActivities = activities.filter(activity => activity.status === 'approved');

            if (approvedActivities.length === 0) return '';

            let html = '';
            const maxVisible = 3;

            approvedActivities.slice(0, maxVisible).forEach(activity => {
                const title = activity.title.length > 12 ? activity.title.substring(0, 12) + '...' : activity.title;
                const timeInfo = `${activity.start_time} - ${activity.end_time}`;

                html += `<div class="activity-item status-approved"
                            title="${activity.title} (${timeInfo})${activity.organization ? ' — ' + activity.organization : ' — Approved Activity'}"
                            onclick="event.stopPropagation(); calendar.showActivityDetails('${activity.id}')">
                    ${title}
                </div>`;
            });

            if (approvedActivities.length > maxVisible) {
                html += `<div class="more-activities" onclick="event.stopPropagation(); calendar.showAllDayActivities('${dateStr}')">
                    +${approvedActivities.length - maxVisible} more approved
                </div>`;
            }

            return html;
        }

        isToday(day) {
            const today = new Date();
            return day === today.getDate() &&
                   this.currentMonth === today.getMonth() &&
                   this.currentYear === today.getFullYear();
        }

        previousMonth() {
            this.currentMonth--;
            if (this.currentMonth < 0) {
                this.currentMonth = 11;
                this.currentYear--;
            }
            this.render();
        }

        nextMonth() {
            this.currentMonth++;
            if (this.currentMonth > 11) {
                this.currentMonth = 0;
                this.currentYear++;
            }
            this.render();
        }

        showDayActivities(dateStr, dayNumber, isCurrentMonth) {
            // Only show approved activities in calendar modal (including multi-day activities)
            const approvedActivities = this.activities.filter(activity => {
                if (activity.status !== 'approved') return false;
                // Use string comparison to avoid timezone issues
                return dateStr >= activity.activity_date && dateStr <= activity.end_date;
            });
            if (approvedActivities.length === 0) return;

            this.showActivitiesModal(dateStr, approvedActivities, true);
        }

        showAllDayActivities(dateStr) {
            // Only show approved activities in calendar modal (including multi-day activities)
            const approvedActivities = this.activities.filter(activity => {
                if (activity.status !== 'approved') return false;
                // Use string comparison to avoid timezone issues
                return dateStr >= activity.activity_date && dateStr <= activity.end_date;
            });
            this.showActivitiesModal(dateStr, approvedActivities, true);
        }

        showActivitiesModal(dateStr, activities, approvedOnly = false) {
            const date = new Date(dateStr);
            const formattedDate = date.toLocaleDateString('en-US', {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            });

            let activitiesHtml = '';
            const headerText = activities.length === 1 ? '1 approved activity' : `${activities.length} approved activities`;

            if (activities.length === 0) {
                activitiesHtml = '<p class="text-gray-500 text-center py-8">No approved activities on this date.</p>';
            } else {
                activities.forEach(activity => {
                    activitiesHtml += `
                        <div class="activity-detail">
                            <h4>${activity.title}</h4>
                            <p>
                                <i class="fas fa-clock mr-1"></i>
                                ${activity.start_time} - ${activity.end_time}
                            </p>
                            <p>
                                <i class="fas fa-building mr-1"></i>
                                ${activity.organization ? activity.organization : 'Department: N/A'}
                            </p>
                            <span>
                                <i class="fas fa-map-marker-alt mr-1"></i>
                                ${activity.location}
                            </span>
                        </div>
                    `;
                });
            }

            const modal = document.createElement('div');
            modal.className = 'activity-modal';
            modal.innerHTML = `
                <div class="activity-modal-content">
                    <div class="activity-modal-header">
                        <div class="flex items-center justify-between">
                            <h3 class="text-xl font-semibold">${formattedDate}</h3>
                            <button onclick="this.closest('.activity-modal').remove()" class="text-white hover:text-gray-200">
                                <i class="fas fa-times text-xl"></i>
                            </button>
                        </div>
                        <p class="text-green-100 mt-1">${headerText}</p>
                    </div>
                    <div class="activity-modal-body">
                        ${activitiesHtml}
                        ${approvedOnly && activities.length > 0 ? '<div class="mt-4 p-3 bg-green-100 border border-green-300 rounded-lg text-green-800 text-sm"><i class="fas fa-info-circle mr-2"></i>Only approved activities are displayed on the calendar. Use the Activity Management section to view all activities.</div>' : ''}
                    </div>
                </div>
            `;

            // Close modal when clicking outside
            modal.addEventListener('click', (e) => {
                if (e.target === modal) {
                    modal.remove();
                }
            });

            document.body.appendChild(modal);
        }

        showActivityDetails(activityId) {
            console.log('Show activity details for ID:', activityId);
        }
    }

    @endif
    
    // Initialize calendar when page loads
    document.addEventListener('DOMContentLoaded', function() {
        try {
            if (typeof TraditionalCalendar === 'function') {
                window.calendar = new TraditionalCalendar('admin-activity-calendar', calendarActivities);
            }
        } catch (e) {
            console.error('Calendar initialization error:', e);
        }
    });

    document.addEventListener('DOMContentLoaded', function() {
        // Initialize Admin Statistics Cards Animations
        const adminStatsCards = document.querySelectorAll('.admin-animated-stat-card');

        // Add intersection observer for scroll-triggered animations
        const adminObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.animationPlayState = 'running';
                }
            });
        }, {
            threshold: 0.1
        });

        adminStatsCards.forEach(card => {
            adminObserver.observe(card);
        });

        // Add click ripple effect for admin cards
        adminStatsCards.forEach(card => {
            card.addEventListener('click', function(e) {
                const ripple = document.createElement('div');
                const rect = this.getBoundingClientRect();
                const size = Math.max(rect.width, rect.height);
                const x = e.clientX - rect.left - size / 2;
                const y = e.clientY - rect.top - size / 2;

                ripple.style.cssText = `
                    position: absolute;
                    width: ${size}px;
                    height: ${size}px;
                    left: ${x}px;
                    top: ${y}px;
                    background: radial-gradient(circle, rgba(255,255,255,0.3) 0%, transparent 70%);
                    border-radius: 50%;
                    transform: scale(0);
                    animation: adminRipple 0.6s ease-out;
                    pointer-events: none;
                    z-index: 10;
                `;

                this.querySelector('.admin-card-inner').appendChild(ripple);

                setTimeout(() => {
                    ripple.remove();
                }, 600);
            });
        });

        // Add CSS for admin ripple animation
        const adminStyle = document.createElement('style');
        adminStyle.textContent = `
            @keyframes adminRipple {
                to {
                    transform: scale(2);
                    opacity: 0;
                }
            }
        `;
        document.head.appendChild(adminStyle);

        // Delete action removed by request
    });
</script>
@endpush
