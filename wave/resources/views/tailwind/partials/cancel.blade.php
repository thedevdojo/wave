<div class="flex items-center justify-between p-5 m-8 bg-red-200 border border-red-400 rounded-lg">
	<div class="relative">
		<h5 class="mb-2 text-xl font-bold text-red-700">Cancel Subscription Plan</h5>
		<p class="text-red-600">Danger Zone! This will cancel your subscription</p>
	</div>
	<div @click="$store.confirmCancel.open = true" class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-lg cursor-pointer">Cancel</div>
</div>