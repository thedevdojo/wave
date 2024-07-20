<?php
    use function Laravel\Folio\{name};
    use Livewire\Volt\Component;
    name('wave.wave');

    new class extends Component
    {
        public $content = '';

        public function mount(){

        }

        public function load_content($var){
            //$this->content = 'wave.deploy-to-do';
            $this->content = 'admin-iframe';
            $this->dispatch('hide-content-loader');
        }
    }
?>

<x-layouts.empty>
    @volt('wave.wave')
    <div x-data="{ 
            open: $persist(true).as('wave_menu_open'), 
            display: 'iframe',
            adminContentLoading: true,
            content_loading: true,
            iframe_src: '/',
            secondaryMenu: false,
            sidebar_active: 'pages',
            iframe_src_editor: $persist(false).as('wave_src_editor'),
            iframe_src_params: 'iframe=true',
            get iframeSrc() {
                let params = '?iframe=false';
                if(this.inEditMode){
                    params = '?editor=true&amp;iframe=false';
                }
                if(!this.selfIsTop()){
                    params = '?iframe=false';
                }
                return this.iframe_src + params;
            },
            get inEditMode(){
                return false;
                // If the parent window is the top window
                if(this.parentWindowIsTop()){
                    // we can allow the browser persisted settings for the editor
                    return this.iframe_src_editor;
                } else {
                    // we always want this to be false because it could be an iframe inside an iframe
                    return false;
                }
            },
            get menuArrowVisible(){
                if(!this.parentWindowIsTop()){
                    // if we are in an iframe that the parent window is not the top, we automatically want to hide the arrow
                    return false;
                } else {
                    // if we are in edit mode, we also do not want to show the arrow
                    if(this.inEditMode){
                        return false;
                    }
                    return !this.open;
                }
            },
            get pageDisplay(){
                url = this.iframe_src; //new URL(this.iframe_src);
                /*pageDisplay = 'Home';
                if(url.pathname != '/'){
                    pageDisplay = url.pathname.replace('/', '');
                }
                return this.capitalizeFirstLetter(pageDisplay); */
                return url;
            },
            foundationLogout(){
                this.open = false;
                window.location.href = '/foundation/logout';
            },
            menuItemClicked(slug){
                 this.adminContentLoading = true; 
                 this.sidebar_active = slug;
                 if(slug == 'pages'){
                    this.display = 'iframe';
                    this.iframe_src = '/';
                 } else if(slug == 'admin'){   
                    this.display = 'iframe';
                    this.iframe_src = '/admin';
                 } else {
                    this.display=slug;
                    $wire.load_content('test'); 
                 }
            },
            clearContent(){
                @this.set('content', '');
            },
            parentWindowIsTop () {
                try {
                    return parent.window.self == window.top;
                } catch (e) {
                    return false;
                }
            },
            selfIsTop(){
                return window.self == window.top;
            },
            capitalizeFirstLetter(string) {
                return string.charAt(0).toUpperCase() + string.slice(1);
            }
        }"
        x-init="
            if(selfIsTop()){
                content_loading = false;
            }
            url = new URL(window.location.href);
            if(!selfIsTop() && !url.searchParams.has('iframe')){
                window.top.dispatchEvent(new CustomEvent('set-iframe-src', { detail: window.location.href }));
                window.location.href = window.location.href + '?iframe=false';
            } else {
                //content_loading = false;
            }
            setTimeout(function(){
                $refs.container.classList.add('duration-300', 'ease-in-out');
            }, 1000);

            let that = this;

            @if (session()->has('foundation_open'))
                content_loading = true;
                document.addEventListener('DOMContentLoaded', function() {
                    setTimeout(function(){
                        open=true;
                        content_loading=false;
                        console.log('and here...');
                    }, 1000);
                });
            @endif

            
            $watch('iframe_src', function(value){
                console.log('iframe_src changed');
                console.log(value);
                if(selfIsTop()){
                    window.top.history.pushState({}, '', value);
                }
            });

        "
        @hide-content-loader.window="adminContentLoading=false"
        @set-iframe-src.window="iframe_src = event.detail;"
        class="flex overflow-hidden relative w-screen bg-gray-50">

        <div x-show="content_loading" id="content_loader" class="fixed top-0 left-0 z-[99] flex items-center justify-center w-screen h-screen bg-gray-50">
                                <svg class="w-5 h-5 text-gray-700 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                            </div>

        <div class="fixed inset-0 w-screen h-screen"></div>
        
        <div x-show="open" class="flex overflow-hidden fixed flex-col justify-between w-56 h-full border-r border-gray-200" x-cloak>
            <div class="flex relative flex-col">
                {{--------------------------------------------------------------------------
                | Logo and Main menu
                ----------------------------------------------------------------------------}}
                <div :class="{ '-translate-x-full' : secondaryMenu, 'translate-x-0' : !secondaryMenu }" class="flex relative flex-col px-7 py-8 duration-500 ease-out">
                    <a href="{{ config('foundation.base_domain') }}" class="flex justify-start items-start w-7 h-7 cursor-pointer group">
                        <svg class="flex-shrink-0 mx-0.5 w-7 h-7 text-black duration-300 ease-out fill-current group-hover:-rotate-45" viewBox="0 0 686 686" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M347 685.2H338C323.9 681.5 319.9 670.9 319.5 657.9C319.4 655.2 319.2 652.5 319.8 649.9C322.3 638.4 324.7 626.8 327.9 615.5C330.2 607.3 329.3 603 322.9 597.4C321.1 595.8 319.6 592.8 319.4 590.4C318.9 584.6 319.1 578.7 319.3 572.9C319.4 570.1 318.3 569.3 315.6 569C283.7 565.3 253.9 555.3 226.4 538.8C216.9 533.1 207.9 526.5 198 519.9C193.5 524.6 189.2 529.6 184.2 534.1C182.3 535.8 179 537 176.4 536.9C168 536.4 164.3 538.7 160.1 546.2C154.3 556.5 148.2 566.6 141.4 576.2C136.7 582.9 130.3 588.1 122.2 590.9C113.5 593.9 106.5 590.8 100.5 584.8C94.4 578.7 91.1 571.4 94.8 563.1C97.4 557.5 101.1 552.1 105.4 547.6C115.3 537.4 127.5 530.6 140.3 524.7C146.8 521.7 149.7 516.7 148.4 509.9C147.7 506.1 148.7 503.5 151.4 501C156.3 496.5 161.1 491.9 166.1 487.2C137.4 451.4 120.9 411.5 115.9 366.3C108.8 366.3 102 366.6 95.3 366.1C92.7 365.9 89.6 364.4 87.9 362.5C82.5 356.4 77.9 354.9 70.2 357.7C56.9 362.7 43.1 366.1 28.8 366C15.4 366 4.00001 362.2 0.200012 347.3V338.3C6.70001 323.3 15.1 317.9 32.4 320C45.2 321.6 57.8 324.9 70.3 328.3C77.9 330.3 82.8 329.8 87.5 323.5C89.7 320.5 92.3 319.3 95.9 319.4C102.5 319.6 109.1 319.5 116 319.5C118.5 297.1 123.8 276.1 132.1 255.8C140.5 235.4 151.9 216.7 165.9 199C160.8 194 155.7 189.4 151.2 184.3C149.6 182.5 148.5 179.3 148.7 176.9C149.4 168.7 147 164.8 140 160.8C129.6 154.9 119.3 148.7 109.6 141.9C103.1 137.3 97.9 131.1 95 123.3C91.9 114.7 94.4 107.6 100.5 101.4C106.6 95.2 113.7 91.9 122.6 95C130.6 97.8 137 103 141.7 109.7C148.7 119.8 154.9 130.4 161.1 141C164.5 146.9 169.2 150.1 175.7 148.8C179.9 148 182.7 149.3 185.3 152.2C189.6 157 194.1 161.6 198.6 166.3C216.7 151.9 235.3 140.7 255.6 132.3C276 123.8 297.2 118.7 319.4 116.1C319.4 108.9 319.1 102 319.6 95.3C319.8 92.9 321.2 89.9 323.1 88.3C329.5 82.7 331 78.2 328 70.2C323 57 319.8 43.4 319.7 29.3C319.6 12.6 324.2 6.09999 339.2 0.399994H347.2C359.9 3.69999 364.9 12.8 365.8 25C366 27.3 366 29.7 365.9 32C365.4 45.4 362.2 58.3 357.5 70.8C354.8 78 355.9 83.1 362.1 87.5C365.4 89.9 366.4 92.6 366.3 96.4C366.1 103 366.2 109.6 366.2 116.2C411.6 121.3 451.4 137.9 486.2 165.7C486.9 165.4 487.1 165.4 487.2 165.3C491.9 160.6 496.5 155.7 501.5 151.4C503.4 149.8 506.5 148.8 509 149C517 149.7 521.4 147.4 525.1 140.2C529 132.7 532.6 124.8 537.7 118.2C543.5 110.7 550.3 103.5 557.9 97.8C566.9 91.1 576.5 92.9 584.6 100.7C592.9 108.7 594.4 118 588.7 127.8C587.1 130.5 585.2 133.1 583.1 135.4C572.7 146.8 559.7 154.5 545.9 161C538.8 164.3 536.3 168.2 537.3 176C537.8 180.1 536.6 183 533.7 185.7C529 190 524.4 194.6 520 199C552.4 244.7 558.3 258.7 569.4 316.6C569.9 319.3 571.2 319.7 573.5 319.7C579.3 319.6 585.2 319.4 591 319.8C593.3 320 596.1 321.3 597.6 323.1C603.6 330.1 607.4 330.6 616.5 328.2C628.8 324.9 641.3 321.8 653.9 320.3C670.7 318.4 678.9 323.6 685.4 337.6V348.6C681.2 362.6 670.2 366.1 657.5 366.3C643.2 366.5 629.5 363 616.1 358.2C607.6 355.1 603.5 356.4 597.7 363.2C596.2 364.9 593.4 366.3 591.1 366.5C585.5 367 579.8 366.8 574.1 366.6C570.6 366.4 569.6 367.8 569.3 371.1C566.4 396.6 559.5 421.1 547.5 443.8C539.6 458.8 530.2 472.9 520.8 488.5C524.6 492.1 529.4 496.8 534.3 501.2C536.9 503.5 537.8 506.1 537.4 509.6C536.4 518.4 538.8 521.4 546.6 525.8C556.6 531.4 566.4 537.3 575.8 543.9C582.6 548.7 588.2 555.1 591 563.3C594 571.8 591.3 579 585.2 585.1C579.2 591.1 572.2 594.3 563.5 591.3C555.5 588.6 549 583.3 544.3 576.6C537.4 566.8 531.2 556.6 525.4 546.2C521.4 539.1 517.5 536.7 509.4 537.2C506.8 537.4 503.5 536.3 501.5 534.5C496.4 530.1 491.9 524.9 487.5 520.5C486.5 520.7 486.3 520.7 486.2 520.8C451.8 547.9 413 564.5 369.4 569.7C368.3 569.8 366.7 571.9 366.6 573.2C366.3 579.2 366.8 585.2 366.3 591.2C366.1 593.5 364.8 596.3 363 597.8C356.2 603.7 354.9 607.8 358 616.2C363 629.9 366.4 643.9 366.1 658.6C365.4 671.1 361.2 681.7 347 685.2ZM511 355.8C509.3 355.6 508.3 355.5 507.3 355.5C502.5 355.6 497.8 356.1 494.2 351.5C493.2 350.3 489.7 350 487.8 350.8C471.8 356.9 456 356.9 440 350.7C438.2 350 434.6 350.4 433.6 351.6C430.3 356 425.9 355.4 421.5 355.6C420.1 355.7 418.2 357 417.7 358.2C413.9 367.9 410.3 377.6 406.4 388.2C406.1 387.9 406.7 388.7 407.4 389.3C410.7 392.4 413.9 395.3 413.1 400.7C412.9 402.3 415.1 405.2 416.8 406C432.7 413 443.9 424.3 451.1 440.1C452 442.1 455.5 444.1 457.8 444.1C461.5 444.1 463.8 445.4 465.9 447.9C467.3 449.6 468.5 451.4 470.1 453.6C494.5 424.5 507.9 392.6 511 355.8ZM511 330C507.8 292.9 494.5 261 470.9 233C469.5 234.3 468.4 235.3 467.3 236.3C464.4 239.1 462 242.5 456.8 241.6C455.1 241.3 451.8 243.9 450.9 245.9C443.9 261.3 432.9 272.4 417.4 279.4C415.3 280.3 413 283.7 413.1 285.8C413.2 289.9 411.6 292.4 408.9 294.8C407.6 296 406.5 297.3 405.2 298.6C405.7 298.6 406.2 298.5 406.7 298.5C410.2 307.9 414 317.3 417.2 326.8C418.1 329.5 419.4 330.1 421.9 330C426.2 329.8 430.3 329.7 433.6 333.8C434.7 335.2 438.7 335.3 440.9 334.5C456.4 328.8 471.8 328.8 487.3 334.5C489.6 335.3 493.6 334.6 495.3 333C497.9 330.5 500.5 329.8 503.7 330C505.9 330.1 508 330 511 330ZM297.2 406.5C297.4 406.3 296.6 406.9 296 407.6C292.9 410.9 290 414 284.6 413.4C282.9 413.2 280 415.7 279.1 417.6C272.2 433.1 261 444.1 245.7 451.1C243.6 452.1 241.1 455.5 241.3 457.5C241.7 461.7 239.8 464.1 237.1 466.4C235.6 467.7 233.9 468.8 231.9 470.2C260.9 494.6 292.9 508 329.5 511C329.7 509.4 329.8 508.6 329.8 507.8C329.8 502.9 329.2 498 333.9 494.2C335.1 493.2 335.3 489.6 334.6 487.8C328.5 472 328.6 456.3 334.5 440.4C335.2 438.4 334.9 434.6 333.6 433.6C329.4 430.4 329.6 426.2 329.8 421.9C329.9 419.4 329.3 418.1 326.6 417.2C317.1 414.1 307.7 410.5 297.2 406.5ZM174.2 355.5C177.4 392.7 190.7 424.6 214.3 452.6C215.6 451.4 216.7 450.5 217.6 449.6C220.6 446.4 223.3 443.5 228.5 444C230.2 444.2 233.2 441.9 234.1 440C241.2 424.4 252.3 413.2 267.9 406.2C269.9 405.3 271.9 401.9 272 399.6C272.1 396 273.1 393.5 275.8 391.5C277.4 390.3 279.1 389.2 279.5 388.9C275.4 378.8 271.4 370.4 268.6 361.6C267.2 357.2 266.1 354.8 261.1 355.6C257.7 356.2 254.9 355.2 252.2 352.4C250.8 350.9 247 350.1 245.1 350.8C229.1 357 213.3 356.9 197.3 350.9C195.3 350.1 191.5 350.8 190.2 352.3C187.4 355.3 184.5 355.9 180.9 355.6C178.9 355.4 177 355.5 174.2 355.5ZM174.2 329.7C176.1 329.9 177.2 330.1 178.3 330.1C182.9 330 187.4 329.7 190.9 334.1C191.9 335.4 195.8 335.5 197.8 334.8C213.5 328.8 229 328.9 244.7 334.8C246.7 335.6 250.5 335.4 251.5 334.1C254.9 329.9 259.1 330.2 263.6 330.1C265 330.1 267.1 328.8 267.6 327.6C271.4 317.9 274.9 308.1 278.5 298.4C279.3 298.1 278.9 297.4 278.3 296.9C274.9 293.5 271.1 290.7 271.9 284.8C272.1 283.2 269.5 280.4 267.5 279.5C252.2 272.6 241.3 261.6 234.3 246.4C233.3 244.2 229.8 241.8 227.5 241.9C223.7 242 221.4 240.6 219.5 238.1C218.1 236.3 217 234.2 215.6 231.8C190.7 261.1 177.4 292.9 174.2 329.7ZM232.8 214.5C234 215.8 234.8 216.8 235.8 217.7C238.8 220.7 242.2 223.2 241.4 228.6C241.1 230.4 244 233.7 246.2 234.7C261.3 241.6 272.1 252.5 279 267.5C280 269.6 283.2 272.2 285.2 272.1C289.8 271.8 292.3 274 295 276.8C296.1 278 297.3 279 298.5 280.1C298.5 279.6 298.4 279.1 298.4 278.7C307.5 275.2 316.5 271.4 325.8 268.4C329 267.3 330.1 266.2 329.9 263C329.7 259 329.6 255.3 333.4 252.2C334.9 251 335.2 246.8 334.4 244.5C328.8 228.9 328.7 213.6 334.5 198.1C335.3 195.9 334.8 191.8 333.3 190.4C330.1 187.5 329.7 184.3 329.9 180.6C330 178.7 329.9 176.8 329.9 174.4C292.7 177.6 260.8 191 232.8 214.5ZM387.8 404.9C387.5 405.6 387.2 406.3 386.9 407C377.5 410.5 368.1 414.2 358.5 417.4C355.7 418.4 355.5 419.9 355.6 422.2C355.8 426.4 355.7 430.2 351.9 433.4C350.4 434.7 350.1 438.8 350.9 441.1C356.6 456.6 356.5 472 350.9 487.5C350.1 489.8 350.8 493.9 352.4 495.5C355.1 498.2 355.9 500.9 355.7 504.4C355.6 506.5 355.7 508.6 355.7 511.2C392.7 507.9 424.6 494.6 452.5 471.1C451.5 470 450.9 469.2 450.2 468.6C446.7 465.3 443.2 462.3 443.8 456.5C444 454.9 441.4 452 439.5 451.1C424 444.1 413 433 406 417.6C405 415.5 401.5 414.4 399 413.2C396.7 412 393.9 411.5 391.8 410C390.1 408.8 389.1 406.6 387.8 404.9ZM453.5 215.4C424.3 190.9 392.5 177.6 356 174.4C355.8 176 355.6 177.1 355.6 178.3C355.6 182.9 356.1 187.4 351.5 190.9C350.2 191.9 349.9 195.7 350.6 197.7C356.5 213.5 356.5 229.2 350.6 245C349.9 247 350.2 250.8 351.5 251.8C356 255.1 355.4 259.4 355.6 263.9C355.7 265.2 356.6 267.3 357.7 267.7C367.5 271.5 377.4 275.1 388 278.9C387.7 279.2 388.3 278.7 388.9 278.1C392.1 274.8 395 271.3 400.6 272C402.2 272.2 405.1 269.7 406 267.8C412.9 252.6 423.8 241.5 439.1 234.6C441.3 233.6 443.8 230.2 443.8 227.9C443.8 223.9 445.2 221.2 448.1 219C449.7 217.8 451.4 216.8 453.5 215.4ZM302.6 342.5C302.5 364.6 320.2 382.6 342.4 382.7C364.4 382.8 382.5 365 382.8 342.9C383.1 321.1 364.8 302.8 342.8 302.7C320.6 302.7 302.8 320.3 302.6 342.5ZM148.8 418.3C148.8 424.5 154 429.7 160.2 429.7C166.3 429.7 171.6 424.4 171.7 418.3C171.7 412.2 166.4 406.9 160.3 406.9C154 406.9 148.8 412.1 148.8 418.3ZM429.4 160.6C429.5 154.3 424.5 149.1 418.3 149C412.1 148.9 406.9 154 406.8 160.2C406.7 166.5 411.8 171.8 418 171.9C424.1 172 429.4 166.8 429.4 160.6ZM278.4 160.6C278.5 154.3 273.5 149.1 267.2 149C261 148.9 255.8 154 255.7 160.2C255.6 166.5 260.7 171.8 266.8 171.9C272.9 172 278.3 166.8 278.4 160.6ZM536.3 418.3C536.3 411.9 531.2 406.8 524.9 406.9C518.6 407 513.6 412.2 513.7 418.5C513.8 424.6 518.8 429.6 524.9 429.7C531.2 429.8 536.3 424.7 536.3 418.3ZM160.2 278.6C166.4 278.6 171.6 273.3 171.6 267.2C171.6 261 166.3 255.9 160.1 255.9C153.9 255.9 148.8 261.1 148.8 267.3C148.8 273.6 154 278.6 160.2 278.6ZM418.1 536.5C424.5 536.5 429.5 531.5 429.4 525.1C429.4 518.9 424.6 513.9 418.4 513.8C412.1 513.6 406.9 518.7 406.8 525C406.7 531.3 411.8 536.5 418.1 536.5ZM278.4 525C278.3 518.7 273 513.6 266.8 513.8C260.5 514 255.6 519.2 255.8 525.6C256 531.7 261 536.6 267.1 536.6C273.4 536.5 278.5 531.3 278.4 525ZM536.3 267.4C536.4 261 531.4 255.9 525.1 255.9C518.8 255.9 513.7 261 513.7 267.4C513.7 273.6 518.6 278.5 524.7 278.7C531.1 278.7 536.2 273.8 536.3 267.4Z" fill="currentColor"></path><path fill-rule="evenodd" clip-rule="evenodd" d="M302.6 342.5C302.7 320.3 320.6 302.7 342.8 302.7C364.8 302.8 383 321.1 382.8 342.9C382.5 365 364.4 382.8 342.4 382.7C320.3 382.7 302.5 364.7 302.6 342.5Z" fill="transparent"></path></svg>
                    </a>
                    <ul class="pt-7 space-y-2 text-base">
                        {{-- @include('foundation_views::partials.foundation_menu_item', ['text' => 'Content']) --}}
                        {{-- @include('wave::partials.wave_menu_item', ['text' => 'My App']) --}}
                        @include('wave::partials.wave_menu_item', ['text' => 'Pages', 'slug' => 'pages'])
                        @include('wave::partials.wave_menu_item', ['text' => 'Designs', 'slug' => 'designs'])
                        @include('wave::partials.wave_menu_item', ['text' => 'Settings', 'slug' => 'settings'])
                        @include('wave::partials.wave_menu_item', ['text' => 'Packages', 'slug' => 'packages'])
                        @include('wave::partials.wave_menu_item', ['text' => 'Admin', 'slug' => 'admin'])
                        {{--  @include('wave::partials.wave_menu_item', ['text' => 'Components']) --}}
                        {{-- @include('wave::partials.wave_menu_item', ['text' => 'Administration']) --}}
                        {{-- @include('wave::partials.wave_menu_item', ['text' => 'Database']) --}}
                        
                        {{-- @include('wave::partials.wave_menu_item', ['text' => 'Plugins']) --}}
                        @include('wave::partials.wave_menu_item', ['text' => 'Learn', 'slug' => 'learn'])
                    </ul>
                </div>

                {{--------------------------------------------------------------------------
                | Back to App button and secondary menu
                ----------------------------------------------------------------------------}}
                <div :class="{ 'translate-x-0' : secondaryMenu, 'translate-x-full opacity-100' : !secondaryMenu }" class="absolute px-7 py-8 h-screen bg-[#f9fafc] w-full duration-500 ease-out">
                    <button @click="secondaryMenu=false; display='iframe'; clearContent()" class="flex relative items-center pl-5 mb-4 font-medium text-gray-700 group lg:w-auto lg:items-center lg:justify-center">
                        <span class="overflow-hidden absolute left-0 w-4 h-4 transition duration-150 ease-out transform translate-x-0 group-hover:-translate-x-0.5 group-hover:w-4">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M7.707 14.707a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l2.293 2.293a1 1 0 010 1.414z" clip-rule="evenodd"></path></svg>
                        </span>
                        <span class="mx-auto ml-1 text-sm font-bold leading-none select-none">Back to App</span>
                    </button>
                    <div class="pt-4">
                        {{-- @if($content ?? false)
                            @include('foundation_views::admin.submenu.' . $content)
                        @endif --}}
                    </div>
                </div>
                <button x-show="display!='iframe'"
                    x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 -translate-x-full scale-90"
        x-transition:enter-end="opacity-100 translate-x-0 scale-100"
        x-transition:leave="transition ease-in duration-300"
        x-transition:leave-start="opacity-100 translate-x-0 scale-100"
        x-transition:leave-end="opacity-0 -translate-x-full scale-90"
                        @click="display='iframe'" class="flex hidden items-center mt-5 w-full text-sm text-neutral-900/60 hover:text-neutral-900 group">
                    <span>Back to App</span>
                    <svg class="mt-0.5 -mr-1 ml-2 w-3 h-3 opacity-40 stroke-current stroke-2 group-hover:opacity-100" fill="none" viewBox="0 0 10 10" aria-hidden="true"><path class="opacity-0 transition group-hover:opacity-100" d="M0 5h7"></path><path class="transition group-hover:translate-x-[3px]" d="M1 1l4 4-4 4"></path></svg>
                </button>
            </div>
            <div x-show="display == 'iframe'" class="flex flex-col justify-center items-start w-full">
                
            </div>
        </div>
        <div x-ref="container" :class="{ 'pl-56' : open }" class="w-full duration-300 ease-out" x-cloak>
            <div class="flex relative flex-col h-screen">
                <div x-show="display=='iframe'"
                    x-transition:enter="transition ease-out duration-500"
                    x-transition:enter-start="translate-x-full"
                    x-transition:enter-end="translate-x-0"
                    x-transition:leave="transition ease-out duration-500"
                    x-transition:leave-start="translate-x-0"
                    x-transition:leave-end="translate-x-full"
                    :class="{ 'p-0' : open && !inEditMode }" class="flex absolute z-50 flex-col w-full h-full duration-300 ease-out" x-cloak>
                    <div :class="{ 'rounded-none border-0 border-gray-200' : open && !inEditMode, 'border-0 border-gray-200' : inEditMode }" class="overflow-hidden h-full">
                        <div x-show="open" class="flex justify-between items-center w-full h-10 text-center text-white bg-gray-50 border-b border-gray-200/50" x-cloak>
                            <button x-show="!inEditMode" @click="iframe_src_editor=!iframe_src_editor" class="relative flex items-center justify-center w-auto h-full px-3 text-xs cursor-pointer text-gray-500/80 hover:text-gray-700 hover:bg-[#f9fafc]">
                                <svg class="mr-2 w-4 h-4 stroke-current" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M11 2H9C4 2 2 4 2 9v6c0 5 2 7 7 7h6c5 0 7-2 7-7v-2" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><path d="M16.04 3.02 8.16 10.9c-.3.3-.6.89-.66 1.32l-.43 3.01c-.16 1.09.61 1.85 1.7 1.7l3.01-.43c.42-.06 1.01-.36 1.32-.66l7.88-7.88c1.36-1.36 2-2.94 0-4.94-2-2-3.58-1.36-4.94 0Z" stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/><path d="M14.91 4.15a7.144 7.144 0 0 0 4.94 4.94" stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                <span>Edit Page</span>
                            </button>
                            <div x-show="inEditMode" @click="document.getElementById('appIframe').contentWindow.dispatchEvent(new CustomEvent('toggle-editor-sidebar', {}));" class="flex items-center justify-center w-10 h-full text-gray-700 cursor-pointer hover:bg-[#f9fafc]">
                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><g stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M2.749 6.75a3 3 0 0 1 3-3h12.502a3 3 0 0 1 3 3v10.5a3 3 0 0 1-3 3H5.749a3 3 0 0 1-3-3V6.75ZM10.25 3.75v16.5M5.75 7.75h1.5M5.75 11h1.5M5.75 14.25h1.5"></path></g></svg>
                            </div>
                            <div class="absolute text-[0.7rem] text-gray-500 min-w-[180px] -translate-x-1/2 bg-[#f9fafc] rounded-full flex items-center justify-center cursor-pointer hover:bg-gray-100 border border-gray-100 left-1/2">
                                <span class="hidden px-1 py-1 w-full h-full uppercase bg-opacity-5 hover:bg-black/5">Page:</span>
                                <span class="inline-block p-1" x-text="pageDisplay"></span>
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="absolute right-0 mr-2 w-3 h-3"><path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd" /></svg>
                            </div>


                            {{--------------------------------------------------------------------------
                            | Right top menu
                            ----------------------------------------------------------------------------}}
                            <div class="flex relative items-center">
                                <button @click="foundationLogout()" class="flex cursor-pointer h-10 w-10 justify-center right-0 text-[0.7rem] items-center text-gray-500/80  hover:bg-[#f9fafc] hover:text-gray-700 ">
                                    <svg class="w-4 h-4 stroke-current" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><g fill="none" stroke="none"><path d="M3.43262 4.24262L7.42764 7.14648C7.94437 7.52208 8.25 8.12152 8.25 8.7594V19.2505C8.25 20.0661 7.32241 20.5372 6.66149 20.0571L3.57915 17.8185C3.06129 17.4424 2.75472 16.8411 2.75445 16.2011L2.75 5.74553C2.75 4.64343 3.64543 3.75 4.75 3.75H12.25C13.3546 3.75 14.25 4.64343 14.25 5.74553V7.23805M8.25 18.2176H12.2502C13.3548 18.2176 14.2502 17.3242 14.2502 16.2221V14.7299" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path><path d="M21.25 10.9837L11.75 10.9837M21.25 10.9837L17.75 14.2264M21.25 10.9837L17.75 7.74097" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path></g></svg>
                                </button>
                                <div x-show="!inEditMode" @click="open=false" class="relative flex items-center justify-center w-10 h-10 cursor-pointer text-gray-500/80 hover:text-gray-700 hover:bg-[#f9fafc]">
                                    <svg class="w-3.5 h-3.5 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6"><path fill-rule="evenodd" d="M8.25 3.75H19.5a.75.75 0 01.75.75v11.25a.75.75 0 01-1.5 0V6.31L5.03 20.03a.75.75 0 01-1.06-1.06L17.69 5.25H8.25a.75.75 0 010-1.5z" clip-rule="evenodd" /></svg>
                                </div>
                                <div x-show="inEditMode" @click="iframe_src_editor=false; open=true;" class="relative flex items-center justify-center w-10 h-10 text-gray-700 cursor-pointer hover:bg-[#f9fafc]">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                                </div>
                            </div>

                            <div class="hidden fixed top-0 left-0 z-50 text-white bg-black">
                                <span x-show="selfIsTop()">Self is the top</span>
                                <span x-show="!selfIsTop()">Nope, self not top</span>
                            </div>

                        </div>
                        <div :class="{ '' : open }" class="overflow-hidden relative w-full h-full">
                            <div x-show="menuArrowVisible" 
                                x-transition:enter="transition ease-out duration-300"
                                x-transition:enter-start="translate-x-full -translate-y-full"
                                x-transition:enter-end="translate-x-0 translate-y-0"
                                class="fixed top-0 right-0 w-8 h-8 duration-100">
                                <div @click="open=true" class="absolute shadow-[0_1px_2px_0_rgb(0,0,0,0.05)] hover:shadow-none top-0 right-0 z-50 flex items-end text-gray-700 hover:text-gray-900 justify-center border-b border-gray-200/20 group w-32 h-32 rotate-45 translate-x-20 -translate-y-20 ease-out duration-300 cursor-pointer hover:translate-x-[4.9rem] hover:-translate-y-[4.9rem] bg-white hover:bg-gradient-to-b hover:from-gray-50 hover:to-white" x-cloak>
                                    <svg class="w-6 h-6 opacity-90 translate-x-0.5 -translate-y-1.5 fill-current group-hover:opacity-100" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6"><path fill-rule="evenodd" d="M12 3.75a.75.75 0 01.75.75v13.19l5.47-5.47a.75.75 0 111.06 1.06l-6.75 6.75a.75.75 0 01-1.06 0l-6.75-6.75a.75.75 0 111.06-1.06l5.47 5.47V4.5a.75.75 0 01.75-.75z" clip-rule="evenodd" /></svg>
                                </div>
                            </div>
                            
                            {{-- <div x-show="inEditMode" @click="iframe_src_editor=false; open=true;" class="flex absolute top-0 right-0 items-center px-2 py-1 mt-1.5 mr-6 space-x-1 text-xs bg-white rounded-full shadow-sm cursor-pointer hover:bg-gray-200/70">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-3 h-3"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                                <span>Close</span>
                            </div> --}}
                    
                            {{-- <div x-show="inEditMode" class="fixed top-0 flex items-center justify-between w-full h-10 bg-[#f9fafc] border-b border-gray-200" x-cloak>
                                <div @click="document.getElementById('appIframe').contentWindow.dispatchEvent(new CustomEvent('toggle-editor-sidebar', {}));" class="flex justify-center items-center w-10 h-full text-gray-700 border-r border-gray-200 cursor-pointer hover:bg-gray-100">
                                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><g stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M2.749 6.75a3 3 0 0 1 3-3h12.502a3 3 0 0 1 3 3v10.5a3 3 0 0 1-3 3H5.749a3 3 0 0 1-3-3V6.75ZM10.25 3.75v16.5M5.75 7.75h1.5M5.75 11h1.5M5.75 14.25h1.5"></path></g></svg>
                                </div>
                                <div @click="iframe_src_editor=false; open=true;" class="flex relative justify-center items-center w-10 h-10 text-gray-700 cursor-pointer hover:bg-gray-100">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                                </div>
                            </div> --}}
                            
                            <iframe id="appIframe" x-bind:src="iframeSrc" class="w-full h-full select-none" border="0"></iframe>
                        </div>
                    </div>
                </div>

                <div x-show="display!='iframe'"
                    x-transition:enter="transition ease-out duration-500"
                    x-transition:enter-start="translate-x-full"
                    x-transition:enter-end="translate-x-0"
                    x-transition:leave="transition ease-out duration-500"
                    x-transition:leave-start="translate-x-0"
                    x-transition:leave-end="translate-x-full"
                    :class="{ '' : open }" class="flex absolute z-40 flex-col w-full h-full text-gray-900 bg-gray-50" x-cloak>
                    
                    {{--------------------------------------------------------------------------
                    | Dynamic Admin Section
                    ----------------------------------------------------------------------------}}
                    <div class="w-full h-full">
                        <div x-show="!adminContentLoading" class="flex flex-col items-stretch w-full h-full">
                            @if($content ?? false)
                                <div class="relative w-full h-full">
                                    <livewire:dynamic-component :component="$content" :wire:key="'wave-' . $content" />
                                </div>
                            @endif
                        </div>
                        <div x-show="adminContentLoading" class="flex justify-center items-center w-full h-full">
                            {{-- Loader SVG --}}
                            <svg class="mr-3 w-5 h-5 animate-spin text-neutral-900" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
    @endvolt
</x-layouts.empty>