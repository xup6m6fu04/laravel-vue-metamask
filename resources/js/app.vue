<template>
	<Head/>
	<Account/>
	<Player/>
	<Order/>
	<Foot/>
	<!--Modal-->
	<Deposit/>
	<Loading/>
	<Alert/>
</template>
<script>
import { inject } from 'vue'
import detectEthereumProvider from '@metamask/detect-provider'
import me from './api/auth/me'

export default {
	setup() {
		const depositModal = inject('depositModal')
		const currentChain = inject('currentChain')
		const currentAddress = inject('currentAddress')
		const isLoading = inject('isLoading')
		const currentSign = inject('currentSign')
		const ethAmount = inject('ethAmount')
		const usdtAmount = inject('usdtAmount')
		const loginType = inject('loginType')
		return {
			depositModal,
			currentChain,
			currentAddress,
			isLoading,
			currentSign,
			ethAmount,
			usdtAmount,
			loginType
		}
	},
	created() {
		this.detect()
	},
	methods: {
		detect: async function() {
			this.isLoading = true
			if (this.$cookies.get('access_token') !== "") {
				// 有登入過
				switch (this.$cookies.get('login_type')) {
					case 'hearts':
						const account = this.$cookies.get('address')
						const apiMe = await me(account, this.$cookies.get('access_token'))
						if (apiMe.status === 200) {
							this.currentAddress = apiMe.resp.address
							this.currentSign = apiMe.resp.sign
							this.ethAmount = apiMe.resp.eth_amount
							this.usdtAmount = apiMe.resp.usdt_amount
							this.loginType = this.$cookies.get('login_type')
							this.isLoading = false
						}
						break;
					case 'metamask':
						if (window.ethereum && window.ethereum.isMetaMask) {
							// metamask is installed
							const provider = await detectEthereumProvider()
							if (provider && provider.isMetaMask) {
								// provider === window.ethereum (true)
								this.currentChain = await provider.request({ method: 'eth_chainId' })
								provider.on('chainChanged', this.handleChainChanged)
								provider
										.request({ method: 'eth_accounts' })
										.then(async (accounts) => {
											const apiMe = await me(accounts[0], this.$cookies.get('access_token'))
											if (apiMe.status === 200) {
												this.currentAddress = apiMe.resp.address
												this.currentSign = apiMe.resp.sign
												this.ethAmount = apiMe.resp.eth_amount
												this.usdtAmount = apiMe.resp.usdt_amount
												this.loginType = this.$cookies.get('login_type')
											}
											this.isLoading = false
										})
										.catch((err) => {
											console.error(err)
											this.isLoading = false
										})
								provider.on('accountsChanged', this.handleAccountsChanged)
								provider.on('disconnect', this.handleDisconnect)
								this.isLoading = false
							}
						}
						break;
					default:
						this.isLoading = false
				}
			}
		},
		handleChainChanged: function(_chainId) {
			window.location.reload()
		},
		handleDisconnect: function(_error) {
			window.location.reload()
		},
		handleAccountsChanged: async function(accounts) {
			if (this.currentAddress != null && accounts.length > 0 && this.currentAddress !== accounts[0]) {
				// 在已經登入的情況下切換帳號
				this.setVariableNull()
			} else if (accounts.length === 0) {
				// 帳號消失了
				this.setVariableNull()
			}
		},
		setVariableNull: function() {
			this.currentAddress = null
			this.ethAmount = ''
			this.usdtAmount = ''
			this.$cookies.remove('access_token')
		}
	},
}
</script>
