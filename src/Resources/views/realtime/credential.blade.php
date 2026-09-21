<x-admin::layouts.with-history
    :history-id="$credential->id"
    active-tab="realtime"
    :history-url="route('shopify.credentials.edit', $credential->id).'?history=1'"
    :tab-items="$tabItems ?? []"
>
    <x-slot:entityName>
        shopify_credentials
    </x-slot>

    <x-slot:title>
        @lang('shopify::app.shopify.realtime.title')
    </x-slot>

    <x-slot:pageHeader>
        <div class="flex min-h-10 items-center justify-between gap-4 max-sm:flex-wrap">
            <div class="flex flex-col gap-1.5">
                @include('shopify::catalogs._breadcrumbs', [
                    'crumbs' => [
                        ['label' => trans('shopify::app.components.layouts.sidebar.shopify'), 'url' => route('shopify.credentials.index')],
                        ['label' => trans('shopify::app.shopify.credential.index.title'), 'url' => route('shopify.credentials.edit', $credential->id)],
                    ],
                    'leaf' => trans('shopify::app.shopify.realtime.title'),
                ])

                <div class="flex items-center gap-2">
                    <x-admin::heading
                        :title="trans('shopify::app.shopify.realtime.title')"
                        as="h1"
                        size="xl"
                    />

                    <x-shopify::pro-cta variant="badge" />
                </div>
            </div>

            @if ($shopifyProInstalled)
                <button
                    type="submit"
                    form="realtime-credential-form"
                    class="primary-button"
                >
                    @lang('shopify::app.shopify.credential.edit.save')
                </button>
            @endif
        </div>
    </x-slot>

    <x-slot:tabContents>
        <x-shopify::pro-notice variant="page" />

        <x-admin::form
            id="realtime-credential-form"
            :action="route('shopify.credentials.realtime.toggle', $credential->id)"
            method="PUT"
            :ajax="true"
        >
            <fieldset @disabled(! $shopifyProInstalled) class="contents">
            <div class="p-4 bg-white dark:bg-cherry-900 rounded box-shadow flex flex-col gap-4">
                <div class="flex items-start justify-between gap-6 max-sm:flex-col">
                    <div class="flex flex-col gap-1">
                        <p class="text-base text-gray-800 dark:text-white font-semibold">
                            @lang('shopify::app.shopify.realtime.enable')
                        </p>

                        <p class="text-xs text-gray-500 dark:text-gray-300">
                            @lang('shopify::app.shopify.realtime.enable-info')
                        </p>
                    </div>

                    <x-admin::form.control-group class="!mb-0">
                        <x-admin::form.control-group.control
                            type="switch"
                            name="enabled"
                            value="1"
                            :checked="$enabled"
                            :disabled="(bool) $blocker && ! $enabled"
                            :label="trans('shopify::app.shopify.realtime.enable')"
                        />
                    </x-admin::form.control-group>
                </div>

                @if ($blocker && ! $enabled)
                    <p class="text-xs text-red-600">
                        {{ trans($blocker) }}
                    </p>
                @endif
            </div>
            </fieldset>
        </x-admin::form>
    </x-slot>
</x-admin::layouts.with-history>
