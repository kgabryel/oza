module.exports = {
    extends: [
        'plugin:vue/recommended'
    ],
    rules: {
        'vue/valid-v-slot': ['error', {
            allowModifiers: true,
        }],
        'vue/multi-word-component-names': 'off',
        'vue/no-v-html': 'off',
        'vue/require-prop-type-constructor': 'off',
        'vue/no-unused-components': 'off'
    }
}