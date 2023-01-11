<div>
    <div class="flex flex-col items-center justify-center w-screen h-screen bg-gray-50">

        <!-- Back to Admin Button -->
        <a href="{{ url('admin') }}" class="absolute top-0 left-0 flex items-center pt-5 pl-5 mb-5 ml-5 font-medium text-gray-900 group lg:w-auto lg:items-center lg:justify-center md:mb-0">
            <span class="w-4 h-4 overflow-hidden transform translate-x-0 group-hover:-translate-x-0.5 absolute left-0 group-hover:w-4 ease-out duration-150 transition">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M7.707 14.707a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l2.293 2.293a1 1 0 010 1.414z" clip-rule="evenodd"></path></svg>
            </span>
            <span class="mx-auto ml-1 text-sm font-bold leading-none text-gray-900 select-none">Back to Admin</span>
        </a>


        <div id="notification" class="flex items-center justify-between hidden w-full max-w-lg px-8 py-5 mb-10 text-white bg-gray-500 rounded-lg">
            <span id="notification_message" class="text-sm"></span>
            <svg class="w-5 h-5 opacity-50 cursor-pointer hover:opacity-75" onclick="document.getElementById('notification').classList.add('hidden');" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
        </div>

        @if(!$app_id)
            <div class="w-full max-w-lg mx-auto overflow-hidden bg-white border border-gray-100 rounded-lg shadow-xl">
                <div class="relative flex items-center w-full h-20 pl-5 text-white bg-center bg-cover bg-gradient-to-br from-blue-500 to-blue-600" style="background-image:url('{{ Storage::url('/settings/April2021/deploy-banner.png') }}')">
                    <img src="{{ Storage::url('/settings/April2021/deploy-to-do.png') }}" class="w-10 h-10">

                    <div class="relative pl-3">
                        <h3 class="text-base font-bold leading-tight text-white">Deploy Your App to Digital Ocean</h3>
                        <p class="text-xs">Easily deploy your Wave app to DigitalOcean.</p>
                    </div>

                </div>
                <div class="p-10 ">
                <p class="pb-10 text-sm text-gray-500">Deploy to the <a href="https://www.digitalocean.com/products/app-platform/" target="_blank" class="underline">DigitalOcean App platform</a> in 3 simple steps.</p>

                <div class="relative mb-8">
                        <label for="api_key" class="block pb-3 text-sm font-medium text-gray-700">
                            1. Enter your Github Repo <span class="font-normal">(exclude https://github.com/)</span>
                        </label>
                        <input type="text" wire:model="repo" placeholder="thedevdojo/wave" class="block w-full px-4 py-3 mb-4 border border-2 border-transparent border-gray-200 rounded-lg focus:ring focus:ring-blue-500 focus:outline-none" name="repo">
                    </div>

                    <div class="relative mb-8">
                        <label for="api_key" class="block pb-3 text-sm font-medium text-gray-700">
                            2. Grant Access
                        </label>
                        <a href="https://cloud.digitalocean.com/apps/github/install" target="_blank" class="flex items-center font-semibold text-blue-500 underline">
                            <span>Grant DigitalOcean Access to your Github Account</span>
                            <svg class="w-4 h-4 ml-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M11 3a1 1 0 100 2h2.586l-6.293 6.293a1 1 0 101.414 1.414L15 6.414V9a1 1 0 102 0V4a1 1 0 00-1-1h-5z"></path><path d="M5 5a2 2 0 00-2 2v8a2 2 0 002 2h8a2 2 0 002-2v-3a1 1 0 10-2 0v3H5V7h3a1 1 0 000-2H5z"></path></svg>
                        </a>
                    </div>

                    <label for="api_key" class="block pb-3 text-sm font-medium text-gray-700">
                        3. Enter Your <a href="https://cloud.digitalocean.com/api_access" class="underline" target="_blank">DigitalOcean API Key</a>
                    </label>
                <input type="password" wire:model="api_key" class="block w-full px-4 py-3 mb-4 border border-2 border-transparent border-gray-200 rounded-lg focus:ring focus:ring-blue-500 focus:outline-none" name="api_key">
                <div class="block">
                        <button wire:click="deploy" class="w-full px-3 py-4 font-medium text-white bg-blue-600 rounded-lg">Deploy</button>
                    </div>
                </div>
            </div>
        @else
            <div class="w-full max-w-lg mx-auto">


                <div class="overflow-hidden bg-white border border-gray-100 rounded-lg shadow-xl">
                    <div class="relative flex items-center w-full h-20 pl-5 text-white bg-center bg-cover bg-gradient-to-br from-blue-500 to-blue-600" style="background-image:url('{{ Storage::url('/settings/April2021/deploy-banner.png') }}')">
                        <img src="{{ Storage::url('/settings/April2021/deploy-to-do.png') }}" class="w-10 h-10">

                        <div class="relative pl-3">
                            <h3 class="text-base font-bold leading-tight text-white">Your App on Digital Ocean</h3>
                            <p class="block text-xs">Deployed to the <a href="https://www.digitalocean.com/products/app-platform/" target="_blank" class="underline">DO App Platform</a></p>
                        </div>

                    </div>


                    <div class="relative flex flex-col h-full p-8">
                        <h2 class="font-bold text-black">{{ $app['app']['spec']['name'] }}</h2>
                        <a href="{{ $app['app']['live_url_base'] ?? '#' }}" target="_blank" class="text-sm font-medium text-blue-500 underline">{{ $app['app']['live_url_base'] ?? 'Deploying...' }}</a>
                        <a href="https://cloud.digitalocean.com/apps/{{ $app_id }}" target="_blank" class="inline-block w-full px-5 py-3 mt-5 text-sm font-bold text-center text-white bg-blue-600 rounded-lg">Configure on DigitalOcean</a>
                    </div>
                </div>

                <h3 class="my-5 text-sm font-bold text-gray-500">Deployments</h3>
                @foreach($deployments['deployments'] as $deployment)
                    @php
                        $success = false;
                        if(isset($deployment['progress']['success_steps']) && isset($deployment['progress']['total_steps'])) {
                            if($deployment['progress']['success_steps'] == $deployment['progress']['total_steps']) {
                                $success = true;
                            } else {
                                $success = true;
                            }
                        }
                    @endphp
                    <div class="flex items-center p-5 overflow-hidden bg-white border border-gray-100 rounded-lg shadow-xl">
                        <div class="relative flex items-center justify-center h-full mr-3">
                            <div class="w-4 h-4 @if($success){{ 'bg-green-400' }}@else{{ 'bg-red-400' }}@endif rounded-full"></div>
                        </div>
                        <div class="relative flex items-center justify-between w-full text-sm">
                            <p class="flex items-center h-full font-medium leading-none text-gray-700">{{ $deployment['cause'] }} on {{ Carbon\Carbon::parse($deployment['created_at'])->format('F jS, h:i A') }}</p>
                            <a href="https://cloud.digitalocean.com/apps/{{ $app_id }}/deployments/{{ $deployment['id'] }}" target="_blank" class="text-blue-500 hover:text-blue-600">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M11 3a1 1 0 100 2h2.586l-6.293 6.293a1 1 0 101.414 1.414L15 6.414V9a1 1 0 102 0V4a1 1 0 00-1-1h-5z"></path><path d="M5 5a2 2 0 00-2 2v8a2 2 0 002 2h8a2 2 0 002-2v-3a1 1 0 10-2 0v3H5V7h3a1 1 0 000-2H5z"></path></svg>
                            </a>
                        </div>
                    </span>
                @endforeach
            </div>
        @endif

    </div>

    <script>
        window.addEventListener('notify', event => {
            document.getElementById('notification').classList.remove('hidden');

            // remove any bg color
            document.getElementById('notification').classList.remove('bg-red-400');
            document.getElementById('notification').classList.remove('bg-green-400');
            document.getElementById('notification').classList.remove('bg-blue-400');

            if(event.detail.type == 'error'){
                document.getElementById('notification').classList.add('bg-red-400');
            } else if(event.detail.type == 'success'){
                document.getElementById('notification').classList.add('bg-green-400');
            } else {
                document.getElementById('notification').classList.remove('bg-blue-400');
            }

            document.getElementById('notification_message').innerText = event.detail.message;
        });
    </script>

</div>
