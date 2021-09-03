<template>
	<Head/>
	<Account/>
	<Order/>
	<Foot/>
	<!--Modal-->
	<Deposit/>
	<Loading/>
</template>
<script>
import { inject, ref } from 'vue'
import MetaMaskOnboard from '@metamask/onboarding'
import { Disclosure, DisclosureButton, DisclosurePanel, Menu, MenuButton, MenuItem, MenuItems } from '@headlessui/vue'
import { BellIcon, MenuIcon, XIcon } from '@heroicons/vue/outline'
import { PaperClipIcon } from '@heroicons/vue/solid'
import detectEthereumProvider from '@metamask/detect-provider'
import { Dialog, DialogOverlay, DialogTitle, TransitionChild, TransitionRoot } from '@headlessui/vue'
import { ExclamationIcon } from '@heroicons/vue/outline'
import ethereum_address from 'ethereum-address'
import axios from 'axios'
import me from './api/auth/me'

export default {
	
	setup() {
		const depositModal = inject('depositModal')
		const currentChain = inject('currentChain')
		const currentAddress = inject('currentAddress')
		const isLoading = inject('isLoading')
		const currentSign = inject('currentSign')
		return {
			depositModal,
			currentChain,
			currentAddress,
			isLoading,
			currentSign
		}
	},
	created() {
		this.detect()
	},
	methods: {
		detect: async function() {
			this.isLoading = true
			const provider = await detectEthereumProvider()
			if (provider && provider.isMetaMask) {
				// provider === window.ethereum (true)
				this.currentChain = await provider.request({ method: 'eth_chainId' })
				provider.on('chainChanged', this.handleChainChanged)
				provider
						.request({ method: 'eth_accounts' })
						.then(async (accounts) => {
							console.log(accounts)
							const apiMe = await me(accounts[0], this.$cookies.get('access_token'))
							if (apiMe.status === 200) {
								this.currentAddress = apiMe.resp.address
								this.currentSign = apiMe.resp.sign
							}
							this.isLoading = false
						})
						.catch((err) => {
							console.error(err)
							this.isLoading = false
						})
				provider.on('accountsChanged', this.handleAccountsChanged)
				provider.on('disconnect', this.handleDisconnect)
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
			this.$cookies.remove('access_token')
		},
		chainIdHexToUrl: function(chainIdHex) {
			let chain = chainIdHex
			switch (chainIdHex) {
				case '0x1':
					chain = 'https://etherscan.io/tx/'
					break
				case '0x3':
					chain = 'https://ropsten.etherscan.io/tx/'
					break
				case '0x4':
					chain = 'https://rinkeby.etherscan.io/tx/'
					break
				case '0x5':
					chain = 'https://goerli.etherscan.io/tx/'
					break
				case '0x2a':
					chain = 'https://kovan.etherscan.io/tx/'
					break
			}
			return chain
		},
		installMetaMask: function() {
			const onboard = new MetaMaskOnboard()
			onboard.startOnboarding()
		}
	},
}
</script>
