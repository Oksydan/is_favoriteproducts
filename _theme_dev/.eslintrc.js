module.exports = {
  root: true,
  env: {
    browser: true,
    node: false,
    es6: true,
    jquery: true,
  },
  globals: {
    google: true,
    document: true,
    navigator: false,
    window: true,
    prestashop: true,
    bootstrap: false,
    each: false,
    useHttpRequest: false,
    DOMReady: false,
    eventHandlerOn: false,
  },
  extends: ['airbnb-base'],
  rules: {
    'max-len': ['error', {code: 140}],
    'no-underscore-dangle': 'off',
    'no-restricted-syntax': 'off',
    'no-param-reassign': 'off',
    'import/no-unresolved': 'off',
  },
  parserOptions: {
    ecmaVersion: 2022
  },
}
