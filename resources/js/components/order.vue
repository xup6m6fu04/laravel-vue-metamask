<template>
	<div class="px-4 py-5 sm:px-6">
		<h3 class="text-lg leading-6 font-medium text-gray-900">
			訂單記錄
		</h3>
	</div>
	<div class="flex flex-col">
		<div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
			<div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
				<div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg" v-if="orders.length > 0">
					<table class="min-w-full divide-y divide-gray-200">
						<thead class="bg-gray-50">
						<tr>
							<th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
								訂單編號
							</th>
							<th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
								支付編號
							</th>
							<th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
								儲值幣別
							</th>
							<th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
								儲值金額
							</th>
							<th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
								訂單狀態
							</th>
							<th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
								建立時間
							</th>
							<th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
								過期時間
							</th>
							<th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
								Etherscan
							</th>
						</tr>
						</thead>
						<tbody class="bg-white divide-y divide-gray-200">
						<tr v-for="order in orders" :key="order.id">
							<td class="px-6 py-4 whitespace-nowrap">
								<div class="flex items-center">
									<div class="text-sm font-medium text-gray-900">
										{{ order.order_id }}
									</div>
								</div>
							</td>
							<td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
								<span v-if="order.bitwin_order_id === null">未產生</span>
								<span v-else>{{ order.bitwin_order_id }}</span>
							</td>
							<td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
								{{ order.symbol }}
							</td>
							<td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
								{{ order.amount }}
							</td>
							<td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
<!--								<span v-if="order.status === 0">未完成</span>-->
								<span v-if="order.status === 1">等待撥款</span>
								<span v-else-if="order.status === 2">等待入帳</span>
								<span v-else-if="order.status === 3">已完成</span>
								<span v-else>狀態異常</span>
<!--								<a :href="this.chainIdHexToUrl(order.chain_id) + order.tx_hash" class="text-indigo-600 hover:text-indigo-900">LINK</a>-->
							</td>
							<td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
								{{ order.created_at }}
							</td>
							<td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
								{{ order.expired_at }}
							</td>
							<td class="px-6 py-4 whitespace-nowrap text-sm font-medium w-40 cursor-pointer">
								<div @click="redirectEtherscan(order.tx_hash)">
									<img src="https://etherscan.io/images/logo-ether.png?v=0.0.2">
								</div>
							</td>
						</tr>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</template>

<script>
import { PaperClipIcon } from '@heroicons/vue/solid'
import { inject, ref } from 'vue'
import getOrders from '../api/order/get'
import payOrder from '../api/order/pay'
import paidOrder from '../api/order/paid'
import detectEthereumProvider from '@metamask/detect-provider'
import ethereum_address from 'ethereum-address'

export default {
	setup() {
		const orders = ref([])
		const bitwin = ref([])
		const depositModal = inject('depositModal')
		const currentChain = inject('currentChain')
		const currentAddress = inject('currentAddress')
		const isLoading = inject('isLoading')
		const currentSign = inject('currentSign')
		return {
			orders,
			depositModal,
			currentChain,
			currentAddress,
			isLoading,
			bitwin,
			currentSign
		}
	},
	watch: {
		currentAddress: function() {
			this.fetchOrders()
		}
	},
	components: {
		PaperClipIcon,
	},
	methods: {
		fetchOrders: async function() {
			this.isLoading = true
			this.orders = []
			if (this.validAddress(this.currentAddress) && this.$cookies.isKey('access_token')) {
				const apiOrder = await getOrders(this.$cookies.get('access_token'))
				if (apiOrder.status === 200) {
					this.orders = apiOrder.orders
					this.isLoading = true
				} else {
					this.isLoading = false
				}
			} else {
				this.isLoading = false
			}
		},
		redirectEtherscan: function(tx_hash) {
			window.open('https://ropsten.etherscan.io/tx/' + tx_hash, '_blank');
		},
		validAddress: function() {
			return ethereum_address.isAddress(this.currentAddress)
		},
	}
}
</script>

<style scoped>

</style>