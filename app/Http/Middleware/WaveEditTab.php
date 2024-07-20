<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class WaveEditTab
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
                <div class="fixed top-0 left-0 px-3 py-2 z-[99999999] group bg-white rounded-r-md border border-gray-200 shadow-sm translate-y-7 cursor-pointer">
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
                        class="flex justify-center items-center w-full h-full" 
                        x-cloak
                    >
                        <svg class="w-5 h-5 opacity-60 fill-current group-hover:opacity-100"xmlns="http://www.w3.org/2000/svg" viewBox="0 0 256 256"><rect width="256" height="256" fill="none"/><polyline points="216 216 96 216 40.51 160.51" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16"/><line x1="164" y1="92" x2="68" y2="188" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16"/><path d="M96,216H48a8,8,0,0,1-8-8V163.31a8,8,0,0,1,2.34-5.65L165.66,34.34a8,8,0,0,1,11.31,0L221.66,79a8,8,0,0,1,0,11.31Z" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16"/><line x1="136" y1="64" x2="192" y2="120" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16"/></svg>
                        
                    </a>
                    <div class="absolute top-0 right-0 w-2 h-full bg-gray-200"></div>
                </div>
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
