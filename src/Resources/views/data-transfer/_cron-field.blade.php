{{--
    The expression field of the schedule card, shared by the create and edit
    screens. It is core's own text control: a preset fills it and holds it,
    Custom hands it back to the merchant. The field belongs to the schedule
    section, so it is only on screen once a schedule is picked.
--}}
@php($shopifyPresets = \Webkul\Shopify\Support\ShopifySchedule::presets())

@pushOnce('scripts')
    <script type="text/x-template" id="v-field-cron-template">
        <input
            type="text"
            :id="inputId"
            :name="name"
            :value="modelValue"
            :placeholder="field.placeholder"
            :disabled="disabled"
            :readonly="!editable"
            :class="[inputClass, editable ? '' : 'bg-gray-50 dark:bg-cherry-800 cursor-not-allowed']"
            :aria-invalid="hasErrors"
            autocomplete="off"
            @change="setValue($event.target.value)"
        />
    </script>

    <script type="module">
        /**
         * Core defines the shared field base further down the stack, so registration waits
         * for the document. Ajax navigation re-runs this script long after that event has
         * fired, so an already-loaded document registers straight away.
         */
        const registerCronField = () => {
            const PRESET_FIELD = 'schedule_cron_preset';
            const EXPRESSIONS = @json(array_keys($shopifyPresets));
            const CUSTOM = @json(\Webkul\Shopify\Support\ShopifySchedule::CUSTOM);

            app.component('v-field-cron', {
                template: '#v-field-cron-template',

                mixins: [window.unopim.fieldBase],

                data() {
                    /** The field engine scopes the selected preset in as a query param, so the field knows it the moment it mounts. */
                    return { preset: ((this.field.query_params || {}).preset || []).join(',') || null };
                },

                computed: {
                    /** Only Custom is typed by hand; a preset keeps the expression it names. */
                    editable() {
                        return this.preset === null || this.preset === CUSTOM;
                    },
                },

                mounted() {
                    this.$emitter.on('filter-value-changed', this.onFilterChange);

                    /**
                     * The field is part of the schedule section, so the first preset mounts
                     * it: that change was emitted before this listener existed, and the
                     * expression is filled here instead of waiting for a second one.
                     */
                    this.applyPreset(this.preset);
                },

                beforeUnmount() {
                    this.$emitter.off('filter-value-changed', this.onFilterChange);
                },

                methods: {
                    /** A preset owns its expression; Custom and Disabled leave the value alone. */
                    applyPreset(preset) {
                        if (EXPRESSIONS.includes(preset) && preset !== this.modelValue) {
                            this.setValue(preset);
                        }
                    },

                    onFilterChange({ filterName, value }) {
                        if (filterName !== PRESET_FIELD) {
                            return;
                        }

                        this.preset = value ?? null;

                        this.applyPreset(this.preset);
                    },
                },
            });
        };

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', registerCronField, { once: true });
        } else {
            registerCronField();
        }
    </script>
@endPushOnce
