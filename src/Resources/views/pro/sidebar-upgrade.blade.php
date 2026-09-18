{{--
    The sidebar entry that sells Pro, wearing the same pill the Pro badge wears
    everywhere else. Core renders the link; only its look is decorated here.
--}}
@unless ($shopifyProInstalled)
    <style>
        #unopim-sidebar a[href="{{ route('shopify.upgrade') }}"] {
            display: inline-flex;
            align-items: center;
            margin-block: 0.375rem 0.25rem;
            margin-inline: -0.625rem 0;
            padding: 0.25rem 0.625rem;
            border-radius: 9999px;
            background-color: #fef3c7;
            color: #b45309;
            font-size: 0.75rem;
            font-weight: 500;
            line-height: 1;
            white-space: nowrap;
        }

        .dark #unopim-sidebar a[href="{{ route('shopify.upgrade') }}"] {
            background-color: #78350f;
            color: #fde68a;
        }
    </style>
@endunless
