/**
 * Component Mixin
 *
 * Reusable Vue component logic for Laravilt components.
 */

export default {
    props: {
        id: String,
        name: String,
        label: String,
        state: null,
        helperText: String,
        hidden: Boolean,
        columnSpan: [Number, String],
        rtl: Boolean,
        theme: String,
        locale: String,
        meta: Object,
    },

    computed: {
        /**
         * Check if component should be visible.
         */
        isVisible() {
            return !this.hidden;
        },

        /**
         * Get CSS classes for RTL support.
         */
        directionClass() {
            return this.rtl ? 'rtl' : 'ltr';
        },

        /**
         * Get CSS classes for theme.
         */
        themeClass() {
            return this.theme || 'light';
        },

        /**
         * Get column span CSS class.
         */
        columnSpanClass() {
            if (!this.columnSpan) return '';

            if (this.columnSpan === 'full') {
                return 'col-span-full';
            }

            return `col-span-${this.columnSpan}`;
        },
    },

    methods: {
        /**
         * Emit component state change.
         */
        updateState(value) {
            this.$emit('update:state', value);
            this.$emit('change', value);
        },

        /**
         * Get translated text.
         */
        trans(key, replacements = {}) {
            let text = key;

            // Replace placeholders
            Object.entries(replacements).forEach(([placeholder, value]) => {
                text = text.replace(`:${placeholder}`, value);
            });

            return text;
        },
    },
};
