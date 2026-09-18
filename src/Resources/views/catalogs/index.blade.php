<x-admin::layouts.with-history
    :history-id="$credential->id"
    active-tab="catalogs"
    :history-url="route('shopify.credentials.edit', $credential->id).'?history=1'"
    :tab-items="$tabItems ?? []"
>
    <x-slot:entityName>
        shopify_credentials
    </x-slot>

    <x-slot:title>
        @lang('shopify::app.shopify.catalogs.breadcrumb')
    </x-slot>

    <x-slot:pageHeader>
        <div class="flex min-h-10 items-center justify-between gap-4 max-sm:flex-wrap">
            <div class="flex flex-col gap-1.5">
                @include('shopify::catalogs._breadcrumbs', [
                    'crumbs' => [
                        ['label' => trans('shopify::app.components.layouts.sidebar.shopify'), 'url' => route('shopify.credentials.index')],
                        ['label' => trans('shopify::app.shopify.credential.index.title'), 'url' => route('shopify.credentials.edit', $credential->id)],
                    ],
                    'leaf' => trans('shopify::app.shopify.catalogs.breadcrumb'),
                ])

                <div class="flex items-center gap-2">
                    <x-admin::heading
                        :title="trans('shopify::app.shopify.catalogs.breadcrumb')"
                        as="h1"
                        size="xl"
                    />

                    <x-shopify::pro-badge />
                </div>
            </div>

            @if ($shopifyProInstalled
                && bouncer()->hasPermission('shopify.credentials.catalogs.create'))
                <div class="flex items-center gap-2.5">
                    <v-create-catalog-form></v-create-catalog-form>
                </div>
            @endif
        </div>
    </x-slot>

    <x-slot:tabContents>
        @if ($shopifyProInstalled)
            <x-admin::datagrid :src="route('shopify.credentials.catalogs.index', $credential->id)" />
        @else
            <x-shopify::pro-note
                :title="trans('shopify::app.shopify.pro.catalogs')"
                :note="trans('shopify::app.shopify.pro.catalogs-note')"
            />
        @endif
    </x-slot>

    @if ($shopifyProInstalled)
        @pushOnce('scripts')
            <script type="text/x-template" id="v-create-catalog-form-template">
                <div>
                    <button
                        type="button"
                        class="primary-button"
                        @click="$refs.catalogCreateModal.toggle()"
                    >
                        @lang('shopify::app.shopify.catalogs.create')
                    </button>

                    <x-admin::form
                        v-slot="{ meta, errors, handleSubmit }"
                        as="div"
                    >
                        <form @submit="handleSubmit($event, create)" ref="catalogCreateForm">
                            <x-admin::modal ref="catalogCreateModal">
                                <x-slot:header>
                                    <p class="text-lg font-bold text-gray-800 dark:text-white">
                                        @lang('shopify::app.shopify.catalogs.create')
                                    </p>
                                </x-slot>

                                <x-slot:content>
                                    <x-admin::form.control-group>
                                        <x-admin::form.control-group.label class="required">
                                            @lang('shopify::app.shopify.catalogs.form.title')
                                        </x-admin::form.control-group.label>

                                        <x-admin::form.control-group.control
                                            type="text"
                                            name="title"
                                            rules="required"
                                            :label="trans('shopify::app.shopify.catalogs.form.title')"
                                        />

                                        <x-admin::form.control-group.error control-name="title" />
                                    </x-admin::form.control-group>
                                </x-slot>

                                <x-slot:footer>
                                    <button type="submit" class="primary-button">
                                        @lang('shopify::app.shopify.catalogs.form.save')
                                    </button>
                                </x-slot>
                            </x-admin::modal>
                        </form>
                    </x-admin::form>
                </div>
            </script>

            <script type="module">
                app.component('v-create-catalog-form', {
                    template: '#v-create-catalog-form-template',

                    methods: {
                        create(params, { setErrors }) {
                            this.$axios.post("{{ route('shopify.credentials.catalogs.store', $credential->id) }}", new FormData(this.$refs.catalogCreateForm))
                                .then((response) => {
                                    this.$navigate(response.data.data.redirect_url);
                                })
                                .catch(error => {
                                    if (error.response.status == 422) {
                                        setErrors(error.response.data.errors);
                                    }
                                });
                        },
                    },
                });
            </script>
        @endPushOnce
    @endif
</x-admin::layouts.with-history>
