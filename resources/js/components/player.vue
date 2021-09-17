<template>
	<div class="px-4 py-5 sm:px-6">
		<h3 class="text-lg leading-6 font-medium text-gray-900">
			遊戲帳號
		</h3>
	</div>
	<div>
		<button
				@click="this.createPlayer"
				type="button"
				class="inline-flex justify-center px-4 py-2 text-sm font-medium text-black bg-gray-200 border border-transparent rounded-md hover:bg-gray-800 hover:text-white focus:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 focus-visible:ring-blue-500"
		>
			<span class="pt-0.5">建立遊戲帳號</span>
		</button>
	</div>
	<div class="flex flex-col py-4">
		<div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
			<div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
				<div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg" v-if="players.length > 0">
					<table class="min-w-full divide-y divide-gray-200">
						<thead class="bg-gray-50">
						<tr>
							<th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
								帳號
							</th>
							<th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
								編號
							</th>
							<th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
								暱稱
							</th>
							<th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
								幣別
							</th>
							<th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
								餘額
							</th>
							<th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
								狀態
							</th>
						</tr>
						</thead>
						<tbody class="bg-white divide-y divide-gray-200">
						<tr v-for="player in players" :key="player.id">
							<td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
								{{ player.account }}
							</td>
							<td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
								{{ player.userUuid }}
							</td>
							<td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
								{{ player.nickname }}
							</td>
							<td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
								{{ player.symbol }}
							</td>
							<td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
								{{ balance }}
								<!--								<svg viewBox="0 0 20 20" fill="none" class="w-5 h-5 opacity-70 cursor-pointer" @click="this.fetchBalance(player.account)" style="display: inline-block">-->
								<!--									<path-->
								<!--											d="M14.9497 14.9498C12.2161 17.6835 7.78392 17.6835 5.05025 14.9498C2.31658 12.2162 2.31658 7.784 5.05025 5.05033C7.78392 2.31666 12.2161 2.31666 14.9497 5.05033C15.5333 5.63385 15.9922 6.29475 16.3266 7M16.9497 2L17 7H16.3266M12 7L16.3266 7"-->
								<!--											stroke="currentColor"-->
								<!--											stroke-width="1.5"-->
								<!--									/>-->
								<!--								</svg>-->
							</td>
							<td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
								<div v-if="player.status === 1">啟用</div>
								<div v-else-if="player.status === 2">停用</div>
								<div v-else-if="player.status === 3">鎖定</div>
								<div v-else>狀態異常</div>
							</td>
						</tr>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
	<div class="py-4"></div>
</template>

<script>
import { inject, ref } from 'vue'
import getPlayers from '../api/player/get'
import getBalance from '../api/player/balance/get'
import createPlayer from '../api/player/create'
import ethereum_address from 'ethereum-address'

export default {
	setup() {
		const currentAddress = inject('currentAddress')
		const currentChain = inject('currentChain')
		const players = ref([])
		const alertModal = inject('alertModal')
		const alertWord = inject('alertWord')
		const balance = ref('')
		const isLoading = inject('isLoading')
		
		return {
			currentAddress,
			currentChain,
			players,
			alertModal,
			alertWord,
			balance,
			isLoading
		}
	},
	watch: {
		currentAddress: function() {
			this.fetchPlayers()
		}
	},
	methods: {
		fetchPlayers: async function() {
			this.isLoading = true
			this.players = []
			if (this.validAddress(this.currentAddress) && this.$cookies.isKey('access_token')) {
				const apiPlayer = await getPlayers(this.$cookies.get('access_token'))
				if (apiPlayer.status === 200) {
					if (apiPlayer.players.length > 0) {
						await this.fetchBalance(apiPlayer.players[0].account)
					}
					this.players = apiPlayer.players
					this.isLoading = false
				} else {
					this.isLoading = false
				}
			} else {
				this.isLoading = false
			}
		},
		createPlayer: async function() {
			if (this.validAddress(this.currentAddress) && this.$cookies.isKey('access_token')) {
				const apiPlayer = await createPlayer(this.$cookies.get('access_token'))
				if (apiPlayer.status === 200) {
					await this.fetchPlayers()
				} else {
					this.alertModal = true
					this.alertWord = apiPlayer.message
				}
			} else {
				this.alertModal = true
				this.alertWord = "請先登入"
			}
		},
		fetchBalance: async function(account) {
			if (this.validAddress(this.currentAddress) && this.$cookies.isKey('access_token')) {
				const apiBalance = await getBalance(account, this.$cookies.get('access_token'))
				if (apiBalance.status === 200) {
					this.balance = apiBalance.balance
				}
			}
		},
		validAddress: function() {
			return ethereum_address.isAddress(this.currentAddress)
		},
	}
}
</script>

<style scoped>

</style>