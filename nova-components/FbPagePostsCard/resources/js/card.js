import Vue from 'vue'
import {  ValidationProvider, ValidationObserver, extend, localize } from 'vee-validate'
import en from 'vee-validate/dist/locale/en.json'
import * as rules from 'vee-validate/dist/rules'
Object.keys(rules).forEach(rule => {
  extend(rule, rules[rule])
})
localize('en', en)



Nova.booting((Vue, router, store, ValidationObserver, ValidationProvider) => {
  Vue.component('ValidationObserver', ValidationObserver)
  Vue.component('ValidationProvider', ValidationProvider)
  Vue.component('fb-page-posts-card', require('./components/Card'))
})