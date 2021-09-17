<template>
	<!--Modal-->
	<TransitionRoot as="template" :show="depositModal">
		<Dialog as="div" auto-reopen="true" class="fixed z-10 inset-0 overflow-y-auto" @close="depositModal = false">
			<div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
				<TransitionChild as="template" enter="ease-out duration-300" enter-from="opacity-0" enter-to="opacity-100" leave="ease-in duration-200" leave-from="opacity-100" leave-to="opacity-0">
					<DialogOverlay class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"/>
				</TransitionChild>
				<!-- This element is to trick the browser into centering the modal contents. -->
				<span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
				<TransitionChild
						as="template"
						enter="ease-out duration-300"
						enter-from="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
						enter-to="opacity-100 translate-y-0 sm:scale-100"
						leave="ease-in duration-200"
						leave-from="opacity-100 translate-y-0 sm:scale-100"
						leave-to="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
				>
					<div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
						<div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
							<div class="sm:flex sm:items-start">
								<div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
									<Listbox as="div" v-model="symbolSelected" class="pt-2">
										<ListboxLabel class="block text-lg font-medium text-gray-700">
											儲值幣別
										</ListboxLabel>
										<div class="mt-1 relative">
											<ListboxButton
													class="relative w-full bg-white border border-gray-300 rounded-md shadow-sm pl-3 pr-10 py-2 text-left cursor-default focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
											>
                        <span class="flex items-center">
                          <img :src="symbolSelected.avatar" alt="" class="flex-shrink-0 h-6 w-6 rounded-full"/>
                          <span class="ml-3 block truncate">{{ symbolSelected.name }}</span>
                        </span>
												<span class="ml-3 absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none">
                          <SelectorIcon class="h-5 w-5 text-gray-400" aria-hidden="true"/>
                        </span>
											</ListboxButton>
											
											<transition leave-active-class="transition ease-in duration-100" leave-from-class="opacity-100" leave-to-class="opacity-0">
												<ListboxOptions class="absolute z-10 mt-1 w-full bg-white shadow-lg max-h-56 rounded-md py-1 text-base ring-1 ring-black ring-opacity-5 overflow-auto focus:outline-none sm:text-sm">
													<ListboxOption as="template" v-for="symbol in symbols" :key="symbol.id" :value="symbol" :disabled="symbol.disabled" v-slot="{ active, symbolSelected }">
														<li :class="[active ? 'text-white bg-indigo-600' : 'text-gray-900', 'cursor-default select-none relative py-2 pl-3 pr-9']">
															<div class="flex items-center">
																<img :src="symbol.avatar" alt="" class="flex-shrink-0 h-6 w-6 rounded-full"/>
																<span :class="[symbolSelected ? 'font-semibold' : 'font-normal', 'ml-3 block truncate']">
                                  {{ symbol.name }}
                                </span>
															</div>
															
															<span v-if="symbolSelected" :class="[active ? 'text-white' : 'text-indigo-600', 'absolute inset-y-0 right-0 flex items-center pr-4']">
                                <CheckIcon class="h-5 w-5" aria-hidden="true"/>
                              </span>
														</li>
													</ListboxOption>
												</ListboxOptions>
											</transition>
										</div>
									</Listbox>
									<Listbox as="div" v-model="networkSelected" class="pt-6">
										<ListboxLabel class="block text-lg font-medium text-gray-700">
											鏈網路
										</ListboxLabel>
										<div class="mt-1 relative">
											<ListboxButton
													class="relative w-full bg-white border border-gray-300 rounded-md shadow-sm pl-3 pr-10 py-2 text-left cursor-default focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
											>
                        <span class="flex items-center">
                          <span class="ml-3 block truncate">{{ networkSelected.name }}</span>
                        </span>
												<span class="ml-3 absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none">
                          <SelectorIcon class="h-5 w-5 text-gray-400" aria-hidden="true"/>
                        </span>
											</ListboxButton>
											
											<transition leave-active-class="transition ease-in duration-100" leave-from-class="opacity-100" leave-to-class="opacity-0">
												<ListboxOptions class="absolute z-10 mt-1 w-full bg-white shadow-lg max-h-56 rounded-md py-1 text-base ring-1 ring-black ring-opacity-5 overflow-auto focus:outline-none sm:text-sm">
													<ListboxOption as="template" v-for="network in networks" :key="network.id" :value="network" :disabled="network.disabled" v-slot="{ active, networkSelected }">
														<li :class="[active ? 'text-white bg-indigo-600' : 'text-gray-900', 'cursor-default select-none relative py-2 pl-3 pr-9']">
															<div class="flex items-center">
																<span :class="[networkSelected ? 'font-semibold' : 'font-normal', 'ml-3 block truncate']">
                                  {{ network.name }}
                                </span>
															</div>
															
															<span v-if="networkSelected" :class="[active ? 'text-white' : 'text-indigo-600', 'absolute inset-y-0 right-0 flex items-center pr-4']">
                                <CheckIcon class="h-5 w-5" aria-hidden="true"/>
                              </span>
														</li>
													</ListboxOption>
												</ListboxOptions>
											</transition>
										</div>
									</Listbox>
									<!--									<DialogTitle as="h3" class="pt-6 text-lg leading-6 font-medium text-gray-700 pb-1">-->
									<!--										Address-->
									<!--									</DialogTitle>-->
									<!--									<div>-->
									<!--										<div class="mt-1 relative rounded-md shadow-sm">-->
									<!--											<div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">-->
									<!--                        <span class="text-gray-500 sm:text-sm">-->
									<!--                          Ξ-->
									<!--                        </span>-->
									<!--											</div>-->
									<!--											<input type="text" v-model="address" name="address" id="address" class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-7 pr-12 sm:text-sm border-gray-300 rounded-md" placeholder="Address" />-->
									<!--										</div>-->
									<!--									</div>-->
									<!--									<div class="pt-3 pb-3" v-show="addressError === true">-->
									<!--										<div class="border-red-400 text-red-700 px-1 py-0.5 rounded relative" role="alert">-->
									<!--											<strong class="font-bold">Oops! </strong>-->
									<!--											<span class="block sm:inline">address is not valid</span>-->
									<!--										</div>-->
									<!--									</div>-->
									<DialogTitle as="h3" class="pt-6 text-lg leading-6 font-medium text-gray-700 pb-1">
										儲值金額
									</DialogTitle>
									<div>
										<div class="mt-1 relative rounded-md shadow-sm">
											<div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <span class="text-gray-500 sm:text-sm">
                          Ξ
                        </span>
											</div>
											<input
													type="text"
													name="amount"
													id="amount"
													v-model="amount"
													@keypress="isNumber($event)"
													class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-7 pr-12 sm:text-sm border-gray-300 rounded-md"
													placeholder="0.00"
											/>
											<!--											<div class="absolute inset-y-0 right-0 flex items-center">-->
											<!--												<label for="currency" class="sr-only">Currency</label>-->
											<!--												<select id="currency" name="currency" class="focus:ring-indigo-500 focus:border-indigo-500 h-full py-0 pl-2 pr-7 border-transparent bg-transparent text-gray-500 sm:text-sm rounded-md">-->
											<!--													<option>ETH</option>-->
											<!--												</select>-->
											<!--											</div>-->
										</div>
									</div>
									<div class="pt-3 pb-3" v-show="amountError === true">
										<div class="border-red-400 text-red-700 px-1 py-0.5 rounded relative" role="alert">
											<strong class="font-bold">Oops! </strong>
											<span class="block sm:inline">amount is not valid</span>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
							<button
									type="button"
									@click="this.handleDeposit"
									class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm"
							>
								儲值
							</button>
							<button
									type="button"
									class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
									@click="this.closeDeposit"
							>
								取消
							</button>
						</div>
					</div>
				</TransitionChild>
			</div>
		</Dialog>
	</TransitionRoot>
</template>


<script>
import { inject, ref } from 'vue'
import { Dialog, DialogOverlay, DialogTitle, TransitionChild, TransitionRoot } from '@headlessui/vue'
import { ExclamationIcon } from '@heroicons/vue/outline'
import detectEthereumProvider from '@metamask/detect-provider'
import ethereum_address from 'ethereum-address'
import { Listbox, ListboxButton, ListboxLabel, ListboxOption, ListboxOptions } from '@headlessui/vue'
import { CheckIcon, SelectorIcon } from '@heroicons/vue/solid'
import createOrder from '../api/order/create'
import payOrder from '../api/order/pay'
import paidOrder from '../api/order/paid'
import getContract from '../api/contract/usdt'
import '@metamask/legacy-web3'

const symbols = [
	{
		id: 1,
		name: 'ETH',
		avatar: '/images/icon-eth.svg',
		disabled: false
	},
	{
		id: 2,
		name: 'USDT',
		avatar: '/images/icon-usdt.svg',
		disabled: false
	},
	{
		id: 3,
		name: 'BTC',
		avatar: '/images/icon-btc.svg',
		disabled: true
	},
]

const btcNetworks = [
	{
		id: 1,
		name: 'Bitcoin Network',
		chain: 'BTC',
		disabled: false,
	}
]

const ethNetworks = [
	{
		id: 1,
		name: 'ERC 20 (Ethereum)',
		chain: 'ERC20',
		disabled: false,
	},
]

const usdtNetworks = [
	{
		id: 1,
		name: 'ERC 20 (Ethereum)',
		chain: 'ERC20',
		disabled: false,
	},
	{
		id: 2,
		name: 'TRC 20 (Tron)',
		chain: 'TRC20',
		disabled: true,
	},
]

export default {
	components: {
		Dialog,
		DialogOverlay,
		DialogTitle,
		TransitionChild,
		TransitionRoot,
		ExclamationIcon,
		Listbox,
		ListboxButton,
		ListboxLabel,
		ListboxOption,
		ListboxOptions,
		CheckIcon,
		SelectorIcon,
	},
	setup() {
		const address = ref('')
		const amount = ref(0)
		const amountError = ref(false)
		const depositModal = inject('depositModal')
		const currentChain = inject('currentChain')
		const currentAddress = inject('currentAddress')
		const currentSign = inject('currentSign')
		const isLoading = inject('isLoading')
		const symbolSelected = ref(symbols[0])
		const networkSelected = ref(ethNetworks[0])
		const networks = ref(ethNetworks)
		const order = ref([])
		const alertModal = inject('alertModal')
		const alertWord = inject('alertWord')
		
		return {
			address,
			amount,
			amountError,
			depositModal,
			currentChain,
			currentAddress,
			currentSign,
			isLoading,
			symbols,
			symbolSelected,
			networkSelected,
			networks,
			order,
			alertModal,
			alertWord,
		}
	},
	watch: {
		symbolSelected: function() {
			switch (this.symbolSelected.name) {
				case 'BTC':
					this.networks = btcNetworks
					this.networkSelected = btcNetworks[0]
					break
				case 'ETH':
					this.networks = ethNetworks
					this.networkSelected = ethNetworks[0]
					break
				case 'USDT':
					this.networks = usdtNetworks
					this.networkSelected = usdtNetworks[0]
					break
			}
		}
	},
	methods: {
		handleDeposit: function() {
			this.amountError = false
			let reg = new RegExp(/^-?\d+(\.\d+)?$/)
			if (!reg.test(this.amount) || this.amount <= 0) {
				this.amountError = true
				return false
			}
			// 建單
			this.deposit()
		},
		deposit: async function() {
			
			this.isLoading = true
			this.amountError = false
			this.depositModal = false
			
			const apiOrder = await createOrder({
				symbol: this.symbolSelected.name,
				chain: this.networkSelected.chain,
				amount: this.amount,
				sign: this.currentSign,
				address: this.currentAddress,
			}, this.$cookies.get('access_token'))
			
			if (apiOrder.status !== 200) {
				this.isLoading = false
				this.alertModal = true
				this.alertWord = apiOrder.message
				return false
			}
			
			this.order = apiOrder.order
			
			const apiPayOrder = await payOrder({
				order_id: this.order.order_id,
				sign: this.currentSign,
				address: this.currentAddress,
			}, this.$cookies.get('access_token'))
			
			if (apiPayOrder.status !== 200) {
				this.isLoading = false
				this.alertModal = true
				this.alertWord = apiPayOrder.message
				return false
			}
			
			this.bitwin = apiPayOrder.bitwin
			
			let transactionParameters = {}
			switch (this.order.symbol) {
				case 'ETH':
					transactionParameters = {
						to: this.bitwin['CryptoWallet'], // Required except during contract publications.
						from: this.currentAddress, // must match user's active address.
						value: '0x' + ((this.bitwin['RealAmount'] / 100000000) * 1000000000000000000).toString(16), // Only required to send ether to the recipient from the initiating external account.
						chainId: this.currentChain, // Used to prevent transaction reuse across blockchains. Auto-filled by MetaMask.
					}
					break
				case 'USDT_ERC20':
					const { web3 } = window
					const contract = web3.eth.contract(getContract().abi)
					const contractInstance = contract.at(getContract().address)
					transactionParameters = {
						to: getContract().address, // Required except during contract publications.
						from: this.currentAddress, // must match user's active address.
						chainId: this.currentChain, // Used to prevent transaction reuse across blockchains. Auto-filled by MetaMask.
						data: contractInstance.transfer.getData(this.bitwin['CryptoWallet'], web3.toHex(this.bitwin['RealAmount'] / 100), {from: this.currentAddress}),
					}
					break
				default:
					console.log('No Support: ' + this.bitwin['Symbol'])
			}
			
			const provider = await detectEthereumProvider()
			
			await provider.request({
				method: 'eth_sendTransaction',
				params: [transactionParameters],
			}).then(async (txHash) => {
				const apiPaidOrder = await paidOrder({
					order_id: this.order.order_id,
					tx_hash: txHash
				}, this.$cookies.get('access_token'))
				
				if (apiPaidOrder.status !== 200) {
					this.isLoading = false
					this.alertModal = true
					this.alertWord = apiPaidOrder.message
					return false
				}
				window.location.reload()
				
			}).catch((error) => {
				this.isLoading = false
				console.log(error)
			})
			
		},
		closeDeposit: function() {
			this.amountError = false
			this.depositModal = false
		},
		checkDepositModal: function() {
			return this.depositModal
		},
		validAddress: function(address) {
			return ethereum_address.isAddress(address)
		},
		isNumber: function(evt) {
			evt = (evt) ? evt : window.event
			let charCode = (evt.which) ? evt.which : evt.keyCode
			if ((charCode > 31 && (charCode < 48 || charCode > 57)) && charCode !== 46) {
				evt.preventDefault()
			} else {
				return true
			}
		}
	},
	name: 'deposit'
}
</script>

<style scoped>

</style>