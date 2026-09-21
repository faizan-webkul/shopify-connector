{{--
    What the screens running without Pro wear: the sidebar entry turned into a
    call to action, and the button that carries the same ask everywhere else.
    Core renders both; only their look lives here.
--}}
@php
    $upgradeUrl = resolve(\Webkul\Shopify\Support\ProFeatures::class)->upgradeUrl();
    $menuPath = parse_url(route('shopify.upgrade'), PHP_URL_PATH);
@endphp

@unless ($shopifyProInstalled)
    <style>
        #unopim-sidebar a[href$="{{ $menuPath }}"],
        #unopim-sidebar a[data-shopify-pro-upgrade] {
            display: block;
            margin-block: 0.625rem 0.25rem;
            margin-inline-end: 1rem;
            padding: 0.5rem 0.75rem;
            border-radius: 0.375rem;
            font-size: 0.875rem;
            line-height: 1.25rem;
            text-align: center;
        }

        /** The fly-out panel drops the menu's indent, so the button brings its own. */
        #unopim-sidebar [data-menu-item].inactive a[data-shopify-pro-upgrade],
        .sidebar-collapsed #unopim-sidebar a[data-shopify-pro-upgrade] {
            margin-block: 0.5rem 0.75rem;
            margin-inline: 0.75rem;
        }

        #unopim-sidebar a[href$="{{ $menuPath }}"],
        #unopim-sidebar a[data-shopify-pro-upgrade],
        .shopify-pro-cta {
            background-color: #fbbf24;
            color: #422006;
            font-weight: 600;
            white-space: nowrap;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.08);
            transition: background-color 150ms ease-in-out;
        }

        #unopim-sidebar a[data-shopify-pro-upgrade]:hover,
        .shopify-pro-cta:hover {
            background-color: #f59e0b;
            color: #422006;
        }

        .shopify-pro-lock {
            position: relative;
        }

        .shopify-pro-lock__body {
            pointer-events: none;
            user-select: none;
        }

        .shopify-pro-lock__offer {
            position: absolute;
            inset: 0;
            background-color: rgba(255, 255, 255, 0.72);
        }

        .dark .shopify-pro-lock__offer {
            background-color: rgba(24, 22, 30, 0.72);
        }

        .shopify-pro-lock__offer,
        .shopify-pro-offer {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
            padding: 1.5rem 1rem;
            text-align: center;
        }

        .shopify-pro-lock__offer p,
        .shopify-pro-offer p {
            max-width: 34rem;
            margin: 0;
            font-size: 0.875rem;
            color: #4b5563;
        }

        .dark .shopify-pro-lock__offer p,
        .dark .shopify-pro-offer p {
            color: #d1d5db;
        }

        .shopify-pro-cta {
            display: inline-flex;
            align-items: center;
            padding: 0.375rem 0.875rem;
            border-radius: 0.375rem;
            font-size: 0.875rem;
            line-height: 1.25rem;
        }
    </style>

    <script>
        /**
         * A menu entry can only name a route, so the sidebar link is pointed at
         * the store itself once it is on the page and the app is never asked to
         * forward the visit.
         */
        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('#unopim-sidebar a[href$="{{ $menuPath }}"]').forEach((link) => {
                link.href = @json($upgradeUrl);
                link.target = '_blank';
                link.rel = 'noopener noreferrer';
                link.dataset.shopifyProUpgrade = '';
            });
        });
    </script>
@endunless
