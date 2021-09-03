<template>
	<div class="py-4"></div>
	<div class="bg-white shadow overflow-hidden sm:rounded-lg">
		<div class="px-4 py-5 sm:px-6">
			<h3 class="text-lg leading-6 font-medium text-gray-900">
				帳號資訊
			</h3>
		</div>
		<div class="border-t border-gray-200">
			<dl>
				<div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
					<dt class="text-sm font-medium text-gray-500">
						鏈網路
					</dt>
					<dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
						{{ this.getChainName(currentChain) }}
					</dd>
				</div>
				<div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
					<dt class="text-sm font-medium text-gray-500">
						地址
					</dt>
					<dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
						{{ currentAddress }}
					</dd>
				</div>
			</dl>
		</div>
	</div>
	<div class="py-4"></div>
</template>

<script>
import { inject } from 'vue'

export default {
	setup() {
		const currentAddress = inject('currentAddress')
		const currentChain = inject('currentChain')
		
		return {
			currentAddress,
			currentChain
		}
	},
	methods: {
		getChainName: function(chainIdHex) {
			let chain = chainIdHex
			switch (chainIdHex) {
				case '0x1':
					chain = chainIdHex + ' (Ethereum Main Network)'
					break
				case '0x3':
					chain = chainIdHex + ' (Ropsten Test Network)'
					break
				case '0x4':
					chain = chainIdHex + ' (Rinkeby Test Network)'
					break
				case '0x5':
					chain = chainIdHex + ' (Goerli Test Network)'
					break
				case '0x2a':
					chain = chainIdHex + ' (Kovan Test Network)'
					break
			}
			return chain
		},
	}
}
</script>

<style scoped>

</style>