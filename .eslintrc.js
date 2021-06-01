module.exports = {
  extends: [
    // add more generic rulesets here, such as:
    // 'eslint:recommended',
    'plugin:vue/recommended',
    // 'plugin:vue/recommended' // Use this if you are using Vue.js 2.x.
  ],
  rules: {
    'vue/valid-v-slot': ['error', {
      'allowModifiers': true,
    }],
    // override/add rules settings here, such as:
    // 'vue/no-unused-vars': 'error'
  }
}
