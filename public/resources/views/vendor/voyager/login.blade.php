<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ Voyager::setting("admin.title") }} Login</title>
    <style>
        /*! tailwindcss v2.0.4 | MIT License | https://tailwindcss.com *//*! modern-normalize v1.0.0 | MIT License | https://github.com/sindresorhus/modern-normalize */*,::after,::before{box-sizing:border-box}:root{-moz-tab-size:4;tab-size:4}html{line-height:1.15;-webkit-text-size-adjust:100%}body{margin:0}body{font-family:system-ui,-apple-system,'Segoe UI',Roboto,Helvetica,Arial,sans-serif,'Apple Color Emoji','Segoe UI Emoji'}button,input{font-family:inherit;font-size:100%;line-height:1.15;margin:0}button{text-transform:none}[type=button],[type=submit],button{-webkit-appearance:button}p{margin:0}button{background-color:transparent;background-image:none}button:focus{outline:1px dotted;outline:5px auto -webkit-focus-ring-color}ul{list-style:none;margin:0;padding:0}html{font-family:ui-sans-serif,system-ui,-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,"Noto Sans",sans-serif,"Apple Color Emoji","Segoe UI Emoji","Segoe UI Symbol","Noto Color Emoji";line-height:1.5}body{font-family:inherit;line-height:inherit}*,::after,::before{box-sizing:border-box;border-width:0;border-style:solid;border-color:#e5e7eb}img{border-style:solid}input::placeholder{opacity:1;color:#9ca3af}button{cursor:pointer}a{color:inherit;text-decoration:inherit}button,input{padding:0;line-height:inherit;color:inherit}img{display:block;vertical-align:middle}img{max-width:100%;height:auto}.container{width:100%}@media (min-width:640px){.container{max-width:640px}}@media (min-width:768px){.container{max-width:768px}}@media (min-width:1024px){.container{max-width:1024px}}@media (min-width:1280px){.container{max-width:1280px}}@media (min-width:1536px){.container{max-width:1536px}}.space-x-5>:not([hidden])~:not([hidden]){--tw-space-x-reverse:0;margin-right:calc(1.25rem * var(--tw-space-x-reverse));margin-left:calc(1.25rem * calc(1 - var(--tw-space-x-reverse)))}.bg-white{--tw-bg-opacity:1;background-color:rgba(255,255,255,var(--tw-bg-opacity))}.bg-gray-50{--tw-bg-opacity:1;background-color:rgba(249,250,251,var(--tw-bg-opacity))}.bg-red-400{--tw-bg-opacity:1;background-color:rgba(248,113,113,var(--tw-bg-opacity))}.bg-blue-500{--tw-bg-opacity:1;background-color:rgba(59,130,246,var(--tw-bg-opacity))}.bg-blue-600{--tw-bg-opacity:1;background-color:rgba(37,99,235,var(--tw-bg-opacity))}.bg-gradient-to-r{background-image:linear-gradient(to right,var(--tw-gradient-stops))}.from-blue-500{--tw-gradient-from:#3b82f6;--tw-gradient-stops:var(--tw-gradient-from),var(--tw-gradient-to, rgba(59, 130, 246, 0))}.to-blue-600{--tw-gradient-to:#2563eb}.border-gray-300{--tw-border-opacity:1;border-color:rgba(209,213,219,var(--tw-border-opacity))}.border-blue-500{--tw-border-opacity:1;border-color:rgba(59,130,246,var(--tw-border-opacity))}.rounded-md{border-radius:.375rem}.rounded-xl{border-radius:.75rem}.border{border-width:1px}.block{display:block}.flex{display:flex}.flex-col{flex-direction:column}.items-center{align-items:center}.justify-start{justify-content:flex-start}.justify-center{justify-content:center}.font-normal{font-weight:400}.font-bold{font-weight:700}.h-24{height:6rem}.h-auto{height:auto}.h-full{height:100%}.text-xs{font-size:.75rem;line-height:1rem}.text-sm{font-size:.875rem;line-height:1.25rem}.my-10{margin-top:2.5rem;margin-bottom:2.5rem}.mx-auto{margin-left:auto;margin-right:auto}.mb-2{margin-bottom:.5rem}.mb-6{margin-bottom:1.5rem}.mt-7{margin-top:1.75rem}.max-w-md{max-width:28rem}.min-h-screen{min-height:100vh}.outline-none{outline:2px solid transparent;outline-offset:2px}.overflow-hidden{overflow:hidden}.p-6{padding:1.5rem}.p-8{padding:2rem}.py-2{padding-top:.5rem;padding-bottom:.5rem}.py-3{padding-top:.75rem;padding-bottom:.75rem}.px-3{padding-left:.75rem;padding-right:.75rem}.px-8{padding-left:2rem;padding-right:2rem}.placeholder-gray-300::placeholder{--tw-placeholder-opacity:1;color:rgba(209,213,219,var(--tw-placeholder-opacity))}*{--tw-shadow:0 0 #0000}.shadow-xl{--tw-shadow:0 20px 25px -5px rgba(0, 0, 0, 0.1),0 10px 10px -5px rgba(0, 0, 0, 0.04);box-shadow:var(--tw-ring-offset-shadow,0 0 #0000),var(--tw-ring-shadow,0 0 #0000),var(--tw-shadow)}*{--tw-ring-inset:var(--tw-empty, );/*!*//*!*/--tw-ring-offset-width:0px;--tw-ring-offset-color:#fff;--tw-ring-color:rgba(59, 130, 246, 0.5);--tw-ring-offset-shadow:0 0 #0000;--tw-ring-shadow:0 0 #0000}.ring{--tw-ring-offset-shadow:var(--tw-ring-inset) 0 0 0 var(--tw-ring-offset-width) var(--tw-ring-offset-color);--tw-ring-shadow:var(--tw-ring-inset) 0 0 0 calc(3px + var(--tw-ring-offset-width)) var(--tw-ring-color);box-shadow:var(--tw-ring-offset-shadow),var(--tw-ring-shadow),var(--tw-shadow,0 0 #0000)}.ring-blue-500{--tw-ring-opacity:1;--tw-ring-color:rgba(59, 130, 246, var(--tw-ring-opacity))}.ring-opacity-50{--tw-ring-opacity:0.5}.text-center{text-align:center}.text-white{--tw-text-opacity:1;color:rgba(255,255,255,var(--tw-text-opacity))}.text-gray-400{--tw-text-opacity:1;color:rgba(156,163,175,var(--tw-text-opacity))}.text-gray-600{--tw-text-opacity:1;color:rgba(75,85,99,var(--tw-text-opacity))}.text-indigo-400{--tw-text-opacity:1;color:rgba(129,140,248,var(--tw-text-opacity))}.text-indigo-500{--tw-text-opacity:1;color:rgba(99,102,241,var(--tw-text-opacity))}.underline{text-decoration:underline}.w-10{width:2.5rem}.w-auto{width:auto}.w-full{width:100%}@keyframes spin{to{transform:rotate(360deg)}}@keyframes ping{100%,75%{transform:scale(2);opacity:0}}@keyframes pulse{50%{opacity:.5}}@keyframes bounce{0%,100%{transform:translateY(-25%);animation-timing-function:cubic-bezier(.8,0,1,1)}50%{transform:none;animation-timing-function:cubic-bezier(0,0,.2,1)}}
    </style>
</head>
<body class="w-full">

    <div class="flex items-center min-h-screen bg-gray-50">
        <div class="container mx-auto">
            <div class="max-w-md mx-auto my-10">

                @if(!$errors->isEmpty())
                    <div class="p-6 px-8 text-white bg-red-400 rounded-xl">
                        <ul class="list-unstyled">
                            @foreach($errors->all() as $err)
                            <li>{{ $err }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="overflow-hidden bg-white shadow-xl mt-7 rounded-xl">
                    <div class="flex items-center justify-start h-24 px-8 space-x-5 font-bold text-white bg-gradient-to-r from-blue-500 to-blue-600">
                        <?php $admin_logo_img = Voyager::setting('admin.icon_image', ''); ?>
                        @if($admin_logo_img == '')
                            <img class="w-10 h-auto" src="{{ voyager_asset('images/logo-icon-light.png') }}" alt="Logo Icon">
                        @else
                            <img class="w-10 h-auto" src="{{ Voyager::image($admin_logo_img) }}" alt="Logo Icon">
                        @endif
                        <div class="flex flex-col justify-center w-auto h-full">
                            <p>Sign in to your account below</p>
                            <p class="text-xs font-normal">Welcome to your administration login</p>
                        </div>
                    </div>
                    <div class="p-8">
                        <form action="{{ route('voyager.login') }}" method="POST">
                            {{ csrf_field() }}
                            <div class="mb-6">
                                <label for="email" class="block mb-2 text-sm text-gray-600">Email Address</label>
                                <input type="email" value="{{ old('email') }}" name="email" id="email" placeholder="johndoe@email.com" class="w-full px-3 py-2 placeholder-gray-300 border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-blue-500 focus:ring-opacity-50 focus:border-blue-500" />
                            </div>
                            <div class="mb-6">
                                <label for="password" class="block mb-2 text-sm text-gray-600">Password</label>
                                <input type="password" name="password" id="password" placeholder="password" class="w-full px-3 py-2 placeholder-gray-300 border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-blue-500 focus:ring-opacity-50 focus:border-blue-500" />
                            </div>
                            <div class="mb-6">
                                <button type="submit" class="w-full px-3 py-3 text-white bg-blue-500 rounded-md focus:bg-blue-600 focus:outline-none">Login</button>
                            </div>
                            <p class="text-sm text-center text-gray-400">Don&#x27;t have an account yet? <a href="{{ url()->route('register') }}" class="text-indigo-400 focus:outline-none focus:underline focus:text-indigo-500">Sign up</a>.</p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>
</html>
