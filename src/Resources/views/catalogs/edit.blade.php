@php
    $pageTitle = trans('shopify::app.shopify.catalogs.edit-title', ['name' => $catalog->title]);
@endphp

<x-admin::layouts.with-history :history-id="$catalog->id">
    <x-slot:entityName>
        shopify_catalog
    </x-slot>

    <x-slot:title>{{ $pageTitle }}</x-slot>

    <x-slot:pageHeader>
        <div class="flex min-h-10 items-center justify-between gap-4 max-sm:flex-wrap">
            <div class="flex flex-col gap-1.5">
                @include('shopify::catalogs._breadcrumbs', [
                    'crumbs' => [
                        ['label' => trans('shopify::app.components.layouts.sidebar.shopify'), 'url' => route('shopify.credentials.index')],
                        ['label' => trans('shopify::app.shopify.credential.index.title'), 'url' => route('shopify.credentials.edit', $credential->id)],
                        ['label' => trans('shopify::app.shopify.catalogs.breadcrumb'), 'url' => route('shopify.credentials.catalogs.index', $credential->id)],
                    ],
                    'leaf' => $pageTitle,
                ])

                <div class="flex items-center gap-2">
                    <x-admin::heading :title="$pageTitle" as="h1" size="xl" />

                    <x-shopify::pro-cta variant="badge" />
                </div>
            </div>

            <div class="flex items-center gap-2.5">
                <a
                    href="{{ route('shopify.credentials.catalogs.index', $credential->id) }}"
                    class="transparent-button"
                >
                    @lang('admin::app.account.edit.back-btn')
                </a>

                @if ($shopifyProInstalled && ! request()->has('history'))
                    <button
                        type="submit"
                        form="catalog-form"
                        class="primary-button"
                    >
                        @lang('shopify::app.shopify.catalogs.form.save')
                    </button>
                @endif
            </div>
        </div>
    </x-slot>

    <x-admin::form
        id="catalog-form"
        :action="route('shopify.credentials.catalogs.update', [$credential->id, $catalog->id])"
        method="PUT"
        :ajax="true"
    >
        <x-shopify::pro-lock>
            <fieldset @disabled(! $shopifyProInstalled) class="contents">
                @include('shopify::catalogs._form')
            </fieldset>
        </x-shopify::pro-lock>
    </x-admin::form>
</x-admin::layouts.with-history>
