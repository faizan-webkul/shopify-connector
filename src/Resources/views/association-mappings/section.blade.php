@if (request()->routeIs('admin.shopify.export-mappings', 'admin.shopify.import-mappings'))
    @php($mediaMappingTitle = trans(request()->routeIs('admin.shopify.export-mappings') ? 'shopify::app.shopify.export.mapping.images.title' : 'shopify::app.shopify.import.mapping.images.title'))

    <v-shopify-pro-association-mapping></v-shopify-pro-association-mapping>

    @pushOnce('scripts')
        <script type="text/x-template" id="v-shopify-pro-association-mapping-template">
            <fieldset @disabled(! $shopifyProInstalled) class="bg-white dark:bg-cherry-900 rounded box-shadow block">
                <div class="grid grid-cols-2 gap-2.5 items-center px-4 py-4 border-b dark:border-cherry-800 text-gray-600 dark:text-gray-300 transition-all hover:bg-violet-50 hover:bg-opacity-30 dark:hover:bg-cherry-800">
                    <p class="flex items-center gap-2 text-base text-gray-800 dark:text-white font-semibold">
                        @lang('shopify::app.shopify.association-mapping.title')

                        <x-shopify::pro-badge />
                    </p>
                </div>

                @unless ($shopifyProInstalled)
                    <div class="px-4 py-4 text-gray-600 dark:text-gray-300 border-b dark:border-cherry-800">
                        <p class="break-words text-sm">@lang('shopify::app.shopify.pro.association-note')</p>
                    </div>
                @endunless
                <div class="grid grid-cols-2 gap-2.5 items-center px-4 py-4 border-b dark:border-cherry-800 text-gray-600 dark:text-gray-300">
                    <p class="break-words">@lang('shopify::app.shopify.association-mapping.related-products')</p>
                    <x-admin::form.control-group class="!mb-0"><x-admin::form.control-group.control type="select" name="association_mapping_related" :value="data_get($associationMapping, 'related_products')" :options="json_encode($associationTypes)" track-by="id" label-by="label" :label="trans('shopify::app.shopify.association-mapping.unopim-association')" :placeholder="trans('shopify::app.shopify.association-mapping.unopim-association')" /></x-admin::form.control-group>
                </div>
                <div class="grid grid-cols-2 gap-2.5 items-center px-4 py-4 border-b dark:border-cherry-800 text-gray-600 dark:text-gray-300">
                    <p class="break-words">@lang('shopify::app.shopify.association-mapping.complementary-products')</p>
                    <x-admin::form.control-group class="!mb-0"><x-admin::form.control-group.control type="select" name="association_mapping_complementary" :value="data_get($associationMapping, 'complementary_products')" :options="json_encode($associationTypes)" track-by="id" label-by="label" :label="trans('shopify::app.shopify.association-mapping.unopim-association')" :placeholder="trans('shopify::app.shopify.association-mapping.unopim-association')" /></x-admin::form.control-group>
                </div>
                <div class="grid grid-cols-2 gap-2.5 items-center px-4 py-4 text-gray-600 dark:text-gray-300">
                    <p class="break-words">@lang('shopify::app.shopify.association-mapping.bundle-products')</p>
                    <x-admin::form.control-group class="!mb-0"><x-admin::form.control-group.control type="select" name="association_mapping_bundle" :value="data_get($associationMapping, 'bundle_products')" :options="json_encode($associationTypes)" track-by="id" label-by="label" :label="trans('shopify::app.shopify.association-mapping.unopim-association')" :placeholder="trans('shopify::app.shopify.association-mapping.unopim-association')" /></x-admin::form.control-group>
                </div>
            </fieldset>
        </script>
        <script type="module">
            app.component('v-shopify-pro-association-mapping', {
                template: '#v-shopify-pro-association-mapping-template',
                mounted() {
                    this.$nextTick(() => {
                        const mediaHeading = [...document.querySelectorAll('p')].find((element) => element.textContent.trim() === @json($mediaMappingTitle));

                        mediaHeading?.closest('.bg-white')?.after(this.$el);
                    });
                },
            });
        </script>
    @endPushOnce
@endif
