{{--
    Names a feature the connector does not carry and the note that says where it
    lives. It renders nothing once Pro is installed, because the feature itself
    is on the screen by then.
--}}
@props(['title', 'note'])

@unless (resolve(\Webkul\Shopify\Support\ProFeatures::class)->isInstalled())
    <div class="bg-white dark:bg-cherry-900 rounded box-shadow">
        <div class="grid grid-cols-2 gap-2.5 items-center px-4 py-4 border-b dark:border-cherry-800 text-gray-600 dark:text-gray-300">
            <p class="flex items-center gap-2 text-base text-gray-800 dark:text-white font-semibold">
                {{ $title }}

                <x-shopify::pro-badge />
            </p>
        </div>

        <div class="px-4 py-4 text-gray-600 dark:text-gray-300">
            <p class="break-words text-sm">{{ $note }}</p>
        </div>
    </div>
@endunless
