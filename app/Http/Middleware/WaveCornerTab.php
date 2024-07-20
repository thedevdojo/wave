<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class WaveCornerTab
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->path() == 'wave') {
            return $next($request);
        } else {
            $cornerTabHTML = <<<HTML
                <a href="/wave" 
                    x-data="{ 
                        visible: false, 
                        iniFrame() {
                            console.log('they see me running')
                            if (window.self !== window.top) {
                                this.visible=false;
                                console.log('set false'); 
                            } else {
                                this.visible=true;
                            }
                        } 
                    }" 
                    wire:ignore
                    x-init="
                            iniFrame();
                    " 
                    :class="{ 'opacity-100' : visible, 'opacity-0' : !visible }" 
                    class="absolute shadow-[0_1px_2px_0_rgb(0,0,0,0.05)] hover:shadow-none top-0 right-0 z-50 flex items-end text-gray-700 hover:text-gray-900 justify-center border-b border-gray-200/20 group w-32 h-32 rotate-45 translate-x-20 -translate-y-20 ease-out duration-300 cursor-pointer hover:translate-x-[4.9rem] hover:-translate-y-[4.9rem] bg-white hover:bg-gradient-to-b hover:from-gray-50 hover:to-white" 
                    x-cloak
                >
                    <svg class="w-6 h-6 opacity-90 translate-x-0.5 -translate-y-1.5 cursor-pointer fill-current group-hover:opacity-100" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path fill-rule="evenodd" d="M12 3.75a.75.75 0 01.75.75v13.19l5.47-5.47a.75.75 0 111.06 1.06l-6.75 6.75a.75.75 0 01-1.06 0l-6.75-6.75a.75.75 0 111.06-1.06l5.47 5.47V4.5a.75.75 0 01.75-.75z" clip-rule="evenodd"></path></svg>
                </a>
            HTML;
            
            $response = $next($request);
            $content = $response->getContent();
            
            // Inject $cornerTabHTML inside the <body> tag
            $content = preg_replace('/(<body[^>]*>)(.*?)(<\/body>)/s', '$1$2' . $cornerTabHTML . '$3', $content);
            // dd($content);
            return $response->setContent($content);
        }
        
        //return $next($request);
    }
}
