<div class="fixed pin-t pin-x z-40">
    <div class="bg-gradient-primary text-white h-1"></div>

    <nav class="flex items-center justify-between text-black bg-navbar shadow-xs h-16">
        <div class="flex items-center flex-no-shrink">
            <a href="{{ url('/') }}" class="flex items-center flex-no-shrink text-black mx-4 h-8 block">
                <img src="/wave/img/logo.png" alt="Wave logo" class="h-full w-auto">
            </a>

            <div class="switch">
                <input type="checkbox" name="1" id="1" v-model="sidebar" class="switch-checkbox" />
                <label class="switch-label" for="1"></label>
            </div>
        </div>

        <div class="block mx-4 flex items-center">
            @if(config('larecipe.search.enabled'))
                <larecipe-button id="search-button"
                    :type="searchBox ? 'primary' : 'link'"
                    @click="searchBox = ! searchBox"
                    class="px-4">
                    <i class="fas fa-search" id="search-button-icon"></i>
                </larecipe-button>
            @endif


            {{-- versions dropdown --}}
            <larecipe-dropdown>
                <larecipe-button type="primary" class="flex">
                    {{ $currentVersion }} <i class="mx-1 fa fa-angle-down"></i>
                </larecipe-button>

                <template slot="list">
                    <ul class="list-reset">
                        @foreach ($versions as $version)
                            <li class="py-2 hover:bg-grey-lightest">
                                <a class="px-6 text-grey-darkest" href="{{ route('larecipe.show', ['version' => $version, 'page' => $currentSection]) }}">{{ $version }}</a>
                            </li>
                        @endforeach
                    </ul>
                </template>
            </larecipe-dropdown>
            {{-- /versions dropdown --}}

            @auth
                {{-- account --}}
                <larecipe-dropdown>
                    <larecipe-button type="white" class="ml-2">
                        {{ auth()->user()->name ?? 'Account' }} <i class="fa fa-angle-down"></i>
                    </larecipe-button>

                    <template slot="list">
                        <form action="/logout" method="POST">
                            {{ csrf_field() }}

                            <button type="submit" class="py-2 px-4 text-white bg-danger inline-flex"><i class="fa fa-power-off mr-2"></i> Logout</button>
                        </form>
                    </template>
                </larecipe-dropdown>
                {{-- /account --}}
            @endauth
        </div>
    </nav>
</div>