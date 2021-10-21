<template>
	<div class="py-4"></div>
	<div class="px-4 py-5 sm:px-6">
		<h3 class="text-lg leading-6 font-medium text-gray-900">
			帳號資訊
		</h3>
	</div>
	<div class="bg-white shadow overflow-hidden sm:rounded-lg">
		<div class="border-t border-gray-200">
			<dl>
				<div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
					<dt class="text-sm font-medium text-gray-500">
						登入方式
					</dt>
					<dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
						{{ loginType }}
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
				<div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
					<dt class="text-sm font-medium text-gray-500">
						ETH 餘額
					</dt>
					<dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
						{{ ethAmount }}
					</dd>
				</div>
				<div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
					<dt class="text-sm font-medium text-gray-500">
						USDT 餘額
					</dt>
					<dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
						{{ usdtAmount }}
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
		// const currentChain = inject('currentChain')
		const ethAmount = inject('ethAmount')
		const usdtAmount = inject('usdtAmount')
		const loginType = inject('loginType')
		
		return {
			currentAddress,
			// currentChain,
			ethAmount,
			usdtAmount,
			loginType
		}
	}
}
</script>

<style scoped>

</style>