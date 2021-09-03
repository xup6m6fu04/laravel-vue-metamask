<template>
	<div class="vld-parent">
		<loading :active="isLoading" :is-full-page="true"></loading>
	</div>
</template>

<script>
import { inject } from 'vue'
import Loading from 'vue-loading-overlay'
import 'vue-loading-overlay/dist/vue-loading.css'

export default {
	components: {
		Loading
	},
	setup() {
		const isLoading = inject('isLoading')
		
		return {
			isLoading
		}
	}
}
</script>

<style scoped>

</style>