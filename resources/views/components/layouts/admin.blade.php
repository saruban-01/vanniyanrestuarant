<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Admin Dashboard' }} - Vanniyan Restaurant</title>
    
    <!-- Favicon -->
    <link rel="icon" href="{{ asset('favicon.ico') }}" sizes="any">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    <meta name="robots" content="noindex,nofollow">
</head>
<body class="antialiased font-sans bg-[#F7F7F5] text-gray-900" x-data="{ sidebarOpen: false }">

    <!-- Mobile sidebar backdrop -->
    <div x-show="sidebarOpen" class="fixed inset-0 z-40 bg-gray-900 bg-opacity-50 lg:hidden" @click="sidebarOpen = false" x-transition.opacity></div>

    <!-- Sidebar -->
    <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" class="fixed inset-y-0 left-0 z-50 w-64 bg-white border-r border-gray-200 transform transition-transform duration-300 lg:translate-x-0 flex flex-col h-screen">
        <!-- Sidebar Header -->
        <div class="flex items-center justify-between h-16 px-6 border-b border-gray-200 flex-shrink-0">
            <img src="{{ asset('images/logo.png') }}" alt="Vanniyan Restaurant" class="h-9 w-auto">
            <button @click="sidebarOpen = false" class="lg:hidden text-gray-500 hover:text-gray-700 focus:outline-none">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>

        <!-- Sidebar Links -->
        <nav class="flex-1 px-4 py-6 space-y-8 overflow-y-auto">
            
            <div>
                <div class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-3 px-2">Overview</div>
                <a href="{{ route('admin.dashboard') }}" class="flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.dashboard') ? 'bg-vanniyan-green-900 text-white' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                    <svg class="mr-3 w-5 h-5 {{ request()->routeIs('admin.dashboard') ? 'text-vanniyan-gold' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                    Dashboard
                </a>
            </div>

            <div>
                <div class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-3 px-2">Website</div>
                <a href="{{ route('admin.website.home') }}" class="flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.website*') ? 'bg-vanniyan-green-900 text-white' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                    <span class="mr-3 w-5 h-5 flex items-center justify-center {{ request()->routeIs('admin.website*') ? 'text-vanniyan-gold' : 'text-gray-400' }}"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg></span>
                    Website Content
                </a>
                <a href="{{ route('admin.stories') }}" class="flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.stories*') ? 'bg-vanniyan-green-900 text-white' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                    <span class="mr-3 w-5 h-5 flex items-center justify-center {{ request()->routeIs('admin.stories*') ? 'text-vanniyan-gold' : 'text-gray-400' }}"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg></span>
                    Cultural Stories
                </a>
                <a href="{{ route('admin.offers') }}" class="flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.offers*') ? 'bg-vanniyan-green-900 text-white' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                    <span class="mr-3 w-5 h-5 flex items-center justify-center {{ request()->routeIs('admin.offers*') ? 'text-vanniyan-gold' : 'text-gray-400' }}"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg></span>
                    Offers & Promotions
                </a>
                <a href="{{ route('admin.loyalty') }}" class="flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.loyalty') ? 'bg-vanniyan-green-900 text-white' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                    <span class="mr-3 w-5 h-5 flex items-center justify-center {{ request()->routeIs('admin.loyalty') ? 'text-vanniyan-gold' : 'text-gray-400' }}"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg></span>
                    Rewards Program
                </a>
            </div>

            <div>
                <div class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-3 px-2">Operations</div>
                <a href="{{ route('admin.operations') }}" class="flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.operations*') ? 'bg-vanniyan-green-900 text-white' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                    <span class="mr-3 w-5 h-5 flex items-center justify-center {{ request()->routeIs('admin.operations*') ? 'text-vanniyan-gold' : 'text-gray-400' }}"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg></span>
                    Daily Operations
                </a>
                <a href="{{ route('admin.orders') }}" class="flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.orders*') ? 'bg-vanniyan-green-900 text-white' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                    <span class="mr-3 w-5 h-5 flex items-center justify-center {{ request()->routeIs('admin.orders*') ? 'text-vanniyan-gold' : 'text-gray-400' }}"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg></span>
                    Takeaway Orders
                </a>
                <a href="{{ route('admin.bookings.index') }}" class="flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.bookings*') ? 'bg-vanniyan-green-900 text-white' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                    <span class="mr-3 w-5 h-5 flex items-center justify-center {{ request()->routeIs('admin.bookings*') ? 'text-vanniyan-gold' : 'text-gray-400' }}"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg></span>
                    Bookings
                </a>
                <a href="{{ route('admin.tables') }}" class="flex items-center px-2 py-2 pl-10 text-sm font-medium rounded-md {{ request()->routeIs('admin.tables*') ? 'bg-gray-100 text-vanniyan-green-900' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900' }}">
                    Table Management
                </a>
                <a href="{{ route('admin.menu') }}" class="flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.menu*') ? 'bg-vanniyan-green-900 text-white' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                    <span class="mr-3 w-5 h-5 flex items-center justify-center {{ request()->routeIs('admin.menu*') ? 'text-vanniyan-gold' : 'text-gray-400' }}"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg></span>
                    Menu
                </a>
                <a href="{{ route('admin.contact.messages') }}" class="flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.contact*') ? 'bg-vanniyan-green-900 text-white' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                    <span class="mr-3 w-5 h-5 flex items-center justify-center {{ request()->routeIs('admin.contact*') ? 'text-vanniyan-gold' : 'text-gray-400' }}"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg></span>
                    Contact Inbox
                </a>
                <a href="{{ route('admin.reports') }}" class="flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.reports*') ? 'bg-vanniyan-green-900 text-white' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                    <span class="mr-3 w-5 h-5 flex items-center justify-center {{ request()->routeIs('admin.reports*') ? 'text-vanniyan-gold' : 'text-gray-400' }}"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg></span>
                    Reports
                </a>
            </div>

            <div>
                <div class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-3 px-2">Venue Management</div>
                <a href="{{ route('admin.venues.calendar') }}" class="flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.venues.calendar') ? 'bg-vanniyan-green-900 text-white' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                    <span class="mr-3 w-5 h-5 flex items-center justify-center {{ request()->routeIs('admin.venues.calendar') ? 'text-vanniyan-gold' : 'text-gray-400' }}"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg></span>
                    Venue Calendar
                </a>
                <a href="{{ route('admin.venues.settings') }}" class="flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.venues.settings') ? 'bg-vanniyan-green-900 text-white' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                    <span class="mr-3 w-5 h-5 flex items-center justify-center {{ request()->routeIs('admin.venues.settings') ? 'text-vanniyan-gold' : 'text-gray-400' }}"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg></span>
                    Venue Settings
                </a>
            </div>

            <div>
                <div class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-3 px-2">Search & Discovery</div>
                <a href="{{ route('admin.seo.health') }}" class="flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.seo.*') ? 'bg-vanniyan-green-900 text-white' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                    <span class="mr-3 w-5 h-5 flex items-center justify-center {{ request()->routeIs('admin.seo.*') ? 'text-vanniyan-gold' : 'text-gray-400' }}"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg></span>
                    SEO Panel
                </a>
            </div>

            <div>
                <div class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-3 px-2">System</div>
                <a href="{{ route('admin.notifications') }}" class="flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.notifications*') ? 'bg-vanniyan-green-900 text-white' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                    <span class="mr-3 flex-shrink-0 h-6 w-6 {{ request()->routeIs('admin.notifications*') ? 'text-vanniyan-gold' : 'text-gray-400' }}"><svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg></span>
                    Notifications
                </a>
                <a href="{{ route('admin.audit-logs') }}" class="flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.audit-logs*') ? 'bg-vanniyan-green-900 text-white' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                    <span class="mr-3 flex-shrink-0 h-6 w-6 {{ request()->routeIs('admin.audit-logs*') ? 'text-vanniyan-gold' : 'text-gray-400' }}"><svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg></span>
                    Audit Logs
                </a>
                <a href="{{ route('admin.security') }}" class="flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.security') ? 'bg-vanniyan-green-900 text-white' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                    <span class="mr-3 flex-shrink-0 h-6 w-6 {{ request()->routeIs('admin.security') ? 'text-vanniyan-gold' : 'text-gray-400' }}">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                    </span>
                    Security Profile
                </a>
                <a href="{{ route('admin.settings') }}" class="flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.settings*') ? 'bg-vanniyan-green-900 text-white' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                    <span class="mr-3 flex-shrink-0 h-6 w-6 {{ request()->routeIs('admin.settings*') ? 'text-vanniyan-gold' : 'text-gray-400' }}"><svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg></span>
Settings
                </a>
                <a href="{{ route('admin.settings.analytics') }}" class="flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.settings.analytics') ? 'bg-vanniyan-green-900 text-white' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                    <span class="mr-3 flex-shrink-0 h-6 w-6 {{ request()->routeIs('admin.settings.analytics') ? 'text-vanniyan-gold' : 'text-gray-400' }}"><svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg></span>
                    Analytics &amp; Marketing
                </a>
                <a href="{{ route('admin.settings.legal') }}" class="flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.settings.legal') ? 'bg-vanniyan-green-900 text-white' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                    <span class="mr-3 flex-shrink-0 h-6 w-6 {{ request()->routeIs('admin.settings.legal') ? 'text-vanniyan-gold' : 'text-gray-400' }}"><svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg></span>
                    Legal Documents
                </a>
            </div>
            
            <div class="pt-6 border-t border-gray-100">
                <form method="POST" action="{{ route('admin.logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center px-2 py-2 text-sm font-medium text-red-600 rounded-md hover:bg-red-50 hover:text-red-700 transition-colors">
                        <span class="mr-3 w-5 h-5 flex items-center justify-center text-red-500"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg></span>
                        Sign Out
                    </button>
                </form>
            </div>

        </nav>
    </aside>

    <!-- Main Wrapper -->
    <div class="flex-1 flex flex-col min-w-0 lg:pl-64 h-screen">
        
        <!-- Topbar -->
        <header class="flex-shrink-0 bg-white border-b border-gray-200 h-16 flex items-center justify-between px-4 sm:px-6 lg:px-8">
            <div class="flex items-center flex-1">
                <button @click="sidebarOpen = true" class="lg:hidden text-gray-500 hover:text-gray-700 focus:outline-none mr-4">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                </button>
            </div>
            <div class="flex items-center space-x-4">
                
                @php
                    $hoursService = app(\App\Services\RestaurantHoursService::class);
                    $isOpen = $hoursService->isOpenNow();
                @endphp
                
                <div class="hidden md:flex items-center text-xs font-bold uppercase tracking-wider {{ $isOpen ? 'text-green-600' : 'text-red-600' }}">
                    <span class="w-2 h-2 rounded-full mr-2 {{ $isOpen ? 'bg-green-500' : 'bg-red-500' }}"></span>
                    {{ $isOpen ? 'Open Now' : 'Closed Now' }}
                </div>

                <div class="h-6 w-px bg-gray-200 hidden md:block"></div>

                <a href="{{ route('admin.notifications') }}" class="relative text-gray-400 hover:text-gray-500">
                    <span class="sr-only">View notifications</span>
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                </a>

                <div class="h-6 w-px bg-gray-200"></div>

                <div class="flex items-center">
                    <span class="hidden md:block text-sm font-medium text-gray-700">{{ Auth::guard('admin')->user()->name }}</span>
                    <div class="ml-2 w-8 h-8 rounded-full bg-vanniyan-green-900 text-white flex items-center justify-center font-serif font-bold">
                        {{ substr(Auth::guard('admin')->user()->name, 0, 1) }}
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="flex-1 overflow-y-auto">
            <div class="py-8 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto">
                {{ $slot }}
            </div>
        </main>

    </div>

    @livewireScripts
</body>
</html>
