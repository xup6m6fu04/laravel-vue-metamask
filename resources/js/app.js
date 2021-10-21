require('./bootstrap')

import { createApp, inject, ref } from 'vue'
import App from './app.vue'
import Head from './components/head.vue'
import Foot from './components/foot.vue'
import Deposit from './components/deposit.vue'
import Account from './components/account.vue'
import Loading from './components/loading.vue'
import Order from './components/order.vue'
import Alert from './components/alert.vue'
import Player from './components/player.vue'
import VueCookies from 'vue3-cookies'

const app = createApp({
  provide: {
    currentAddress: ref(''),
    currentChain: ref(''),
    currentSign: ref(''),
    isLoading: ref(false),
    depositModal: ref(false),
    alertModal: ref(false),
    alertWord: ref('ERROR'),
    ethAmount: ref(''),
    usdtAmount: ref(''),
    loginType: ref('')
  },
})
app.use(VueCookies)
app.component('App', App)
app.component('Head', Head)
app.component('Foot', Foot)
app.component('Deposit', Deposit)
app.component('Account', Account)
app.component('Loading', Loading)
app.component('Order', Order)
app.component('Alert', Alert)
app.component('Player', Player)
app.mount('#app')
