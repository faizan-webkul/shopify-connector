{{--
    The expression field of the schedule card, shared by the create and edit
    screens: a text input that fills itself from the preset that was picked.
--}}
@pushOnce('scripts')
    <script type="text/x-template" id="v-field-cron-template">
        <input
            type="text"
            :id="inputId"
            :name="name"
            :value="modelValue"
            :placeholder="field.placeholder"
            :disabled="disabled"
            :class="inputClass"
            :aria-invalid="hasErrors"
            autocomplete="off"
            @change="setValue($event.target.value)"
        />
    </script>

    <script type="module">
        /** Core defines the shared field base further down the stack, so registration waits for the document. */
        document.addEventListener('DOMContentLoaded', () => {
            const PRESET_FIELD = 'schedule_cron_preset';
            const NO_EXPRESSION = ['disabled', 'custom'];

            /**
             * The expression field is hidden until a preset is picked, so it mounts
             * after the change that should fill it. The pick is remembered here and
             * read again on mount.
             */
            let pickedPreset = null;

            app.config.globalProperties.$emitter.on('filter-value-changed', ({ filterName, value }) => {
                if (filterName === PRESET_FIELD) {
                    pickedPreset = NO_EXPRESSION.includes(value) ? null : value;
                }
            });

            app.component('v-field-cron', {
                template: '#v-field-cron-template',

                mixins: [window.unopim.fieldBase],

                mounted() {
                    this.applyPreset();

                    this.$emitter.on('filter-value-changed', this.onPresetChange);
                },

                beforeUnmount() {
                    this.$emitter.off('filter-value-changed', this.onPresetChange);
                },

                methods: {
                    onPresetChange({ filterName }) {
                        if (filterName === PRESET_FIELD) {
                            this.$nextTick(this.applyPreset);
                        }
                    },

                    /** A named preset carries its own expression; Custom leaves the one already typed. */
                    applyPreset() {
                        if (pickedPreset && pickedPreset !== this.modelValue) {
                            this.setValue(pickedPreset);
                        }
                    },
                },
            });
        });
    </script>
@endPushOnce
