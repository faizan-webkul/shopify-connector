<v-catalog-form></v-catalog-form>

@pushOnce('scripts')
    <script type="text/x-template" id="v-catalog-form-template">
        <div class="flex flex-col gap-2">
            <div class="box-shadow rounded bg-white p-4 dark:bg-cherry-900">
                <p class="mb-4 text-base font-semibold text-gray-800 dark:text-white">
                    @lang('shopify::app.shopify.catalogs.form.general')
                </p>

                <x-admin::form.control-group>
                    <x-admin::form.control-group.label>
                        @lang('shopify::app.shopify.catalogs.form.store')
                    </x-admin::form.control-group.label>

                    <p class="text-sm text-gray-600 dark:text-gray-300">{{ $credential->shopUrl }}</p>
                </x-admin::form.control-group>

                <x-admin::form.control-group>
                    <x-admin::form.control-group.label class="required">
                        @lang('shopify::app.shopify.catalogs.form.title')
                    </x-admin::form.control-group.label>

                    <x-admin::form.control-group.control
                        type="text"
                        name="title"
                        rules="required"
                        :value="old('title', $catalog->title)"
                        :label="trans('shopify::app.shopify.catalogs.form.title')"
                    />

                    <x-admin::form.control-group.error control-name="title" />
                </x-admin::form.control-group>

                <x-admin::form.control-group>
                    <x-admin::form.control-group.label class="required">
                        @lang('shopify::app.shopify.catalogs.form.status')
                    </x-admin::form.control-group.label>

                    <x-admin::form.control-group.control
                        type="select"
                        name="status"
                        rules="required"
                        :value="old('status', $catalog->status)"
                        :options="json_encode($statuses)"
                        track-by="id"
                        label-by="label"
                        :label="trans('shopify::app.shopify.catalogs.form.status')"
                    />

                    <x-admin::form.control-group.error control-name="status" />
                </x-admin::form.control-group>

                <x-admin::form.control-group>
                    <x-admin::form.control-group.label>
                        @lang('shopify::app.shopify.catalogs.form.price-list-name')
                    </x-admin::form.control-group.label>

                    <x-admin::form.control-group.control
                        type="text"
                        name="price_list_name"
                        :value="old('price_list_name', $catalog->price_list_name)"
                        :label="trans('shopify::app.shopify.catalogs.form.price-list-name')"
                    />

                    <x-admin::form.control-group.error control-name="price_list_name" />
                </x-admin::form.control-group>
            </div>

            <div class="box-shadow rounded bg-white p-4 dark:bg-cherry-900">
                <p class="mb-4 text-base font-semibold text-gray-800 dark:text-white">
                    @lang('shopify::app.shopify.catalogs.form.markets')
                </p>

                <x-admin::form.control-group>
                    <x-admin::form.control-group.label class="required">
                        @lang('shopify::app.shopify.catalogs.form.kind')
                    </x-admin::form.control-group.label>

                    <x-admin::form.control-group.control
                        type="select"
                        name="kind"
                        rules="required"
                        ::value="kind"
                        :options="json_encode([
                            ['id' => 'region', 'label' => trans('shopify::app.shopify.catalogs.kinds.region')],
                            ['id' => 'b2b', 'label' => trans('shopify::app.shopify.catalogs.kinds.b2b')],
                        ])"
                        track-by="id"
                        label-by="label"
                        :label="trans('shopify::app.shopify.catalogs.form.kind')"
                        @input="onKind($event)"
                    />

                    <div class="flex items-center gap-1">
                        <span class="icon-information text-lg"></span>

                        <p class="text-xs text-gray-500 dark:text-gray-400">
                            @lang('shopify::app.shopify.catalogs.form.kind-info')
                        </p>
                    </div>

                    <x-admin::form.control-group.error control-name="kind" />
                </x-admin::form.control-group>

                <x-admin::form.control-group>
                    <x-admin::form.control-group.label>
                        @lang('shopify::app.shopify.catalogs.form.markets')
                    </x-admin::form.control-group.label>

                    <div :key="kind">
                        <x-admin::form.control-group.control
                            type="multiselect"
                            name="market_gids"
                            async="true"
                            :value="implode(',', old('market_gids', $catalog->market_gids ?? []))"
                            ::list-route="marketsRoute"
                            track-by="id"
                            label-by="label"
                            :label="trans('shopify::app.shopify.catalogs.form.markets')"
                        />
                    </div>

                    <div class="flex items-center gap-1">
                        <span class="icon-information text-lg"></span>

                        <p class="text-xs text-gray-500 dark:text-gray-400">
                            @lang('shopify::app.shopify.catalogs.form.markets-info')
                        </p>
                    </div>

                    <x-admin::form.control-group.error control-name="market_gids" />
                </x-admin::form.control-group>

                <x-admin::form.control-group class="!mb-0">
                    <x-admin::form.control-group.label>
                        @lang('shopify::app.shopify.catalogs.form.auto-publish')
                    </x-admin::form.control-group.label>

                    <x-admin::form.control-group.control
                        type="switch"
                        name="auto_publish"
                        value="1"
                        :checked="(bool) old('auto_publish', $catalog->auto_publish ?? false)"
                        :label="trans('shopify::app.shopify.catalogs.form.auto-publish')"
                    />
                </x-admin::form.control-group>
            </div>

            <div class="box-shadow rounded bg-white p-4 dark:bg-cherry-900">
                <p class="mb-4 text-base font-semibold text-gray-800 dark:text-white">
                    @lang('shopify::app.shopify.catalogs.form.pricing')
                </p>

                <x-admin::form.control-group>
                    <x-admin::form.control-group.label class="required">
                        @lang('shopify::app.shopify.catalogs.form.currency')
                    </x-admin::form.control-group.label>

                    <x-admin::form.control-group.control
                        type="select"
                        name="currency"
                        rules="required"
                        async="true"
                        :value="old('currency', $catalog->currency ?? '')"
                        list-route="{{ route('shopify.currency.fetch-all') }}"
                        track-by="id"
                        label-by="label"
                        :label="trans('shopify::app.shopify.catalogs.form.currency')"
                    />

                    <x-admin::form.control-group.error control-name="currency" />
                </x-admin::form.control-group>

                <x-admin::form.control-group>
                    <x-admin::form.control-group.label class="required">
                        @lang('shopify::app.shopify.catalogs.form.pricing-strategy')
                    </x-admin::form.control-group.label>

                    <x-admin::form.control-group.control
                        type="select"
                        name="pricing_strategy"
                        rules="required"
                        ::value="strategy"
                        :options="json_encode($pricingStrategies)"
                        track-by="id"
                        label-by="label"
                        :label="trans('shopify::app.shopify.catalogs.form.pricing-strategy')"
                        @input="onStrategy($event)"
                    />

                    <x-admin::form.control-group.error control-name="pricing_strategy" />
                </x-admin::form.control-group>

                <template v-if="strategy !== 'fixed'">
                    <x-admin::form.control-group>
                        <x-admin::form.control-group.label class="required">
                            @lang('shopify::app.shopify.catalogs.form.adjustment-type')
                        </x-admin::form.control-group.label>

                        <x-admin::form.control-group.control
                            type="select"
                            name="adjustment_type"
                            :value="old('adjustment_type', $catalog->adjustment_type ?? 'PERCENTAGE_DECREASE')"
                            :options="json_encode($adjustmentTypes)"
                            track-by="id"
                            label-by="label"
                            :label="trans('shopify::app.shopify.catalogs.form.adjustment-type')"
                        />

                        <x-admin::form.control-group.error control-name="adjustment_type" />
                    </x-admin::form.control-group>

                    <x-admin::form.control-group>
                        <x-admin::form.control-group.label class="required">
                            @lang('shopify::app.shopify.catalogs.form.adjustment-value')
                        </x-admin::form.control-group.label>

                        <x-admin::form.control-group.control
                            type="number"
                            name="adjustment_value"
                            step="0.01"
                            :value="old('adjustment_value', $catalog->adjustment_value ?? '0')"
                            :label="trans('shopify::app.shopify.catalogs.form.adjustment-value')"
                        />

                        <x-admin::form.control-group.error control-name="adjustment_value" />
                    </x-admin::form.control-group>
                </template>

                <template v-if="strategy === 'fixed'">
                    <x-admin::form.control-group>
                        <x-admin::form.control-group.label>
                            @lang('shopify::app.shopify.catalogs.form.price-attribute')
                        </x-admin::form.control-group.label>

                        <x-admin::form.control-group.control
                            type="select"
                            name="price_attribute"
                            async="true"
                            :entity-name="json_encode(['price'])"
                            :value="old('price_attribute', $catalog->price_attribute ?? '')"
                            :list-route="route('admin.shopify.get-attribute')"
                            track-by="code"
                            label-by="label"
                            :label="trans('shopify::app.shopify.catalogs.form.price-attribute')"
                        />

                        <div class="flex items-center gap-1">
                            <span class="icon-information text-lg"></span>

                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                @lang('shopify::app.shopify.catalogs.form.price-attribute-info')
                            </p>
                        </div>

                        <x-admin::form.control-group.error control-name="price_attribute" />
                    </x-admin::form.control-group>
                </template>

                <x-admin::form.control-group class="flex items-center justify-between gap-4">
                    <div class="flex flex-col gap-1">
                        <x-admin::form.control-group.label class="!mb-0">
                            @lang('shopify::app.shopify.catalogs.form.include-compare-at')
                        </x-admin::form.control-group.label>

                        <p class="text-xs text-gray-500 dark:text-gray-400" v-if="strategy === 'fixed'">
                            @lang('shopify::app.shopify.catalogs.form.include-compare-at-info')
                        </p>

                        <p class="text-xs text-gray-500 dark:text-gray-400" v-else>
                            @lang('shopify::app.shopify.catalogs.form.include-compare-at-adjustment-info')
                        </p>
                    </div>

                    <x-admin::form.control-group.control
                        type="switch"
                        name="include_compare_at"
                        value="1"
                        :checked="(bool) old('include_compare_at', $catalog->include_compare_at ?? true)"
                        :label="trans('shopify::app.shopify.catalogs.form.include-compare-at')"
                        @change="includeCompareAt = $event.target.checked"
                    />
                </x-admin::form.control-group>

                <template v-if="strategy === 'fixed'">
                    <x-admin::form.control-group v-show="includeCompareAt">
                        <x-admin::form.control-group.label>
                            @lang('shopify::app.shopify.catalogs.form.compare-at-attribute')
                        </x-admin::form.control-group.label>

                        <x-admin::form.control-group.control
                            type="select"
                            name="compare_at_attribute"
                            async="true"
                            :entity-name="json_encode(['price'])"
                            :value="old('compare_at_attribute', $catalog->compare_at_attribute ?? '')"
                            :list-route="route('admin.shopify.get-attribute')"
                            track-by="code"
                            label-by="label"
                            :label="trans('shopify::app.shopify.catalogs.form.compare-at-attribute')"
                        />

                        <div class="flex items-center gap-1">
                            <span class="icon-information text-lg"></span>

                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                @lang('shopify::app.shopify.catalogs.form.compare-at-attribute-info')
                            </p>
                        </div>

                        <x-admin::form.control-group.error control-name="compare_at_attribute" />
                    </x-admin::form.control-group>
                </template>

            </div>

            @if ($catalog->last_error)
                <div class="box-shadow flex items-center gap-1 rounded border border-red-200 bg-white p-4 dark:border-red-900 dark:bg-cherry-900">
                    <span class="icon-error text-lg text-red-600"></span>

                    <p class="text-sm text-red-600">{{ $catalog->last_error }}</p>
                </div>
            @endif
        </div>
    </script>

    <script type="module">
        app.component('v-catalog-form', {
            template: '#v-catalog-form-template',

            data() {
                return {
                    strategy: @json(old('pricing_strategy', $catalog->pricing_strategy ?? 'adjustment')),
                    includeCompareAt: @json((bool) old('include_compare_at', $catalog->include_compare_at ?? true)),
                    kind: @json(old('kind', $catalog->kind ?? 'region')),
                    marketsBaseRoute: '{{ route('shopify.credentials.catalogs.options.markets', $credential->id) }}',
                };
            },

            computed: {
                marketsRoute() {
                    return this.marketsBaseRoute + '?kind=' + this.kind;
                },
            },

            methods: {
                onKind(value) {
                    this.kind = this.optionId(value) || 'region';
                },

                onStrategy(value) {
                    this.strategy = this.optionId(value) || 'adjustment';
                },

                optionId(value) {
                    try {
                        return JSON.parse(value).id;
                    } catch (error) {
                        return value;
                    }
                },
            },
        });
    </script>
@endPushOnce
