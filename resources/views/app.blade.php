<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title inertia>{{ config('app.name', 'ClicaFood') }}</title>

    <link rel="icon" type="image/svg+xml" href="/images/favicon.svg">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <!-- Facebook Pixel -->
    @if(config('services.facebook.pixel_id'))
    <script>
        !function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?
        n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;
        n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;
        t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window,
        document,'script','https://connect.facebook.net/en_US/fbevents.js');
        fbq('init', '{{ config("services.facebook.pixel_id") }}');
        fbq('track', 'PageView');
    </script>
    @endif

    <!-- Google Tag Manager -->
    @if(config('services.gtm.container_id'))
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
    new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
    j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
    'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
    })(window,document,'script','dataLayer','{{ config("services.gtm.container_id") }}');</script>
    @endif

    @vite(['resources/js/app.js'])
    @inertiaHead
</head>
<body class="font-sans bg-dark-950 text-white">
    @inertia

    @if(config('services.gtm.container_id'))
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id={{ config('services.gtm.container_id') }}"
    height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    @endif
</body>
</html>
