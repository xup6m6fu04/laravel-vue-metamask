<template>
	<Disclosure as="nav" class="bg-gray-600" v-slot="{ open }">
		<div class="max-w-7xl mx-auto px-2 sm:px-6 lg:px-8">
			<div class="relative flex items-center justify-between h-16">
				<div class="absolute inset-y-0 left-0 flex items-center sm:hidden">
					<!-- Mobile menu button-->
					<DisclosureButton class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-white hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white">
						<span class="sr-only">Open main menu</span>
						<MenuIcon v-if="!open" class="block h-6 w-6" aria-hidden="true"/>
						<XIcon v-else class="block h-6 w-6" aria-hidden="true"/>
					</DisclosureButton>
				</div>
				<div class="flex-1 flex items-center justify-center sm:items-stretch sm:justify-start">
					<div class="flex-shrink-0 flex items-center">
						<!--<img class="block lg:hidden h-8 w-auto" src="../image/metamask.svg" alt="Workflow" />-->
						<!--<img class="hidden lg:block h-8 w-auto" src="../image/metamask.svg" alt="Workflow" />-->
					</div>
				</div>
				<div class="absolute inset-y-0 right-0 flex items-center pr-2 sm:static sm:inset-auto sm:ml-6 sm:pr-0">
					<div>
						<div v-if="!this.validAddress()">
							<button
									@click="this.handleConnect"
									type="button"
									class="inline-flex justify-center px-4 py-2 text-sm font-medium text-black bg-gray-200 border border-transparent rounded-md hover:bg-gray-800 hover:text-white focus:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 focus-visible:ring-blue-500"
							>
								<span class="pt-0.5">登入</span>
							</button>
						</div>
						<div v-else>
							<button
									@click="this.openDepositModal"
									type="button"
									class="inline-flex justify-center px-4 py-2 text-sm font-medium text-black bg-gray-200 border border-transparent rounded-md hover:bg-gray-800 hover:text-white focus:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 focus-visible:ring-blue-500"
							>
								<span class="pt-0.5">儲值</span>
							</button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</Disclosure>
</template>

<script>
import MetaMaskOnboard from '@metamask/onboarding'
import { Disclosure, DisclosureButton, DisclosurePanel, Menu, MenuButton, MenuItem, MenuItems } from '@headlessui/vue'
import { BellIcon, MenuIcon, XIcon } from '@heroicons/vue/outline'
import ethereum_address from 'ethereum-address'
import detectEthereumProvider from '@metamask/detect-provider'
import me from '../api/auth/me'
import nonce from '../api/auth/nonce'
import login from '../api/auth/login'
import { inject, ref } from 'vue'

export default {
	components: {
		Disclosure,
		DisclosureButton,
		DisclosurePanel,
		Menu,
		MenuButton,
		MenuItem,
		MenuItems,
		BellIcon,
		MenuIcon,
		XIcon,
	},
	setup() {
		
		const depositModal = inject('depositModal')
		const currentAddress = inject('currentAddress')
		const isLoading = inject('isLoading')
		const currentSign = inject('currentSign')
		const alertModal = inject('alertModal')
		const alertWord = inject('alertWord')
		const currentChain = inject('currentChain')
		const ethAmount = inject('ethAmount')
		const usdtAmount = inject('usdtAmount')
		
		return {
			depositModal,
			currentAddress,
			isLoading,
			currentSign,
			alertModal,
			alertWord,
			currentChain,
			ethAmount,
			usdtAmount
		}
	},
	methods: {
		handleConnect: async function() {
			this.isLoading = true
			const provider = await detectEthereumProvider()
			if (provider && provider.isMetaMask) {
				provider
						.request({ method: 'eth_requestAccounts' })
						.then(async (accounts) => {
							const provider = await detectEthereumProvider()
							const apiNonce = await nonce(accounts[0])
							const msg = `0x${ Buffer.from(apiNonce.nonce, 'utf8').toString('hex') }`
							await provider.request({
								method: 'personal_sign',
								params: [msg, accounts[0], ''],
							})
									.then(async (sign) => {
										const apiLogin = await login(sign, accounts[0])
										// console.log(apiLogin)
										if (apiLogin.status === 200 && apiLogin.message === 'SUCCESS') {
											this.$cookies.set('access_token', apiLogin.access_token, '3h')
											this.currentSign = apiLogin.sign
											const apiMe = await me(accounts[0], this.$cookies.get('access_token'))
											if (apiMe.status === 200 && apiMe.message === 'SUCCESS') {
												this.currentAddress = apiMe.resp.address
												this.ethAmount = apiMe.resp.eth_amount
												this.usdtAmount = apiMe.resp.usdt_amount
											}
										}
										this.isLoading = false
									})
									.catch((err) => {
										this.isLoading = false
										this.currentAddress = null
										this.$cookies.remove('access_token')
										console.error(err)
									})
						})
						.catch((err) => {
							this.isLoading = false
							console.error(err)
							if (err.code === 4001) {
								// EIP-1193 userRejectedRequest error
								// If this happens, the user rejected the connection request.
								this.currentAddress = null
								this.$cookies.remove('access_token')
							} else {
								this.currentAddress = null
								this.$cookies.remove('access_token')
							}
						})
			} else {
				this.installMetaMask()
			}
		},
		validAddress: function() {
			return ethereum_address.isAddress(this.currentAddress)
		},
		openDepositModal: function() {
			if (this.currentChain === '0x3') {
				this.depositModal = true
			} else {
				this.alertModal = true
				this.alertWord = "目前本站僅支援 0x3 (Ropsten Test Network)"
			}
		},
		installMetaMask: function () {
			const onboard = new MetaMaskOnboard();
			onboard.startOnboarding();
		}
	}
}
</script>