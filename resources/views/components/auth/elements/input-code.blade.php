@props([
    // total number of boxes to display
    'digits' => 4,

    'eventCallback' => null
])

<div x-data="
    {
        total_digits: @js($digits),
        eventCallback: @js($eventCallback),
        callbackSubmitted: false,
        moveCursorNext (index, digits, evt) {
        
            if (!isNaN(parseInt(evt.key)) && parseInt(evt.key) >= 0 && parseInt(evt.key) <= 9 && index != digits) {
                evt.preventDefault();
                evt.stopPropagation();
                this.$refs['input' + index].value = evt.key;
                this.$refs['input' + (index+1)].focus();
                
            } else {

                if (evt.key === 'Backspace') {
                    evt.preventDefault();
                    evt.stopPropagation();
                    if (index > 1) {
                        if (this.$refs['input' + index].value !== '') {
                            this.$refs['input' + index].value = '';
                        } else {
                            if (index > 1) {
                                this.$refs['input' + (index-1)].value='';
                                this.$refs['input' + (index-1)].focus();
                            }
                        }
                    } else {
                        this.$refs['input' + index].value = '';
                    }
                } else {
                    
                }

            }

            let that = this;
            setTimeout(function(){
                that.$refs.pin.value = that.generateCode();
                if (index === digits && [...Array(digits).keys()].every(i => that.$refs['input' + (i + 1)].value !== '')) {
                    that.submitCallback();
                }
            }, 100);

            {{-- console.log(this.generateCode()); --}}



        },
        submitCallback(){
            if(this.eventCallback && !this.callbackSubmitted){
                this.callbackSubmitted = true;
                window.dispatchEvent(new CustomEvent(this.eventCallback, { detail: { code: this.generateCode() }}));
            }
        },
        pasteValue(event){
            event.preventDefault();
            {{-- let paste = (event.clipboardData || window.clipboardData).getData('text'); --}}
            let paste = (event.clipboardData || window.clipboardData).getData('text');
            for (let i = 0; i < paste.length; i++) {
                if (i < this.total_digits) {
                    this.$refs['input' + (i + 1)].value = paste[i];
                }
                let focusLastInput = (paste.length <= this.total_digits) ? paste.length : this.total_digits;
                this.$refs['input' + focusLastInput].focus();
                if(paste.length >= this.total_digits){
                    let that = this;
                    setTimeout(function(){
                        that.$refs.pin.value = that.generateCode();
                        that.submitCallback();
                    }, 100);
                    
                }
            }
        },
        generateCode() {
            let code = '';
            for (let i = 1; i <= this.total_digits; i++) {
                code += this.$refs['input' + i].value;
            }
            return code;
        },
    }" 
    x-init="
        setTimeout(function(){
            $refs.input1.focus();
        }, 100);
    "
    @focus-auth-2fa-auth-code.window="$refs.input1.focus()"
    class="relative"
>
    <div class="flex">
        <div class="flex mx-auto space-x-2">
            @for ($x = 1; $x <= $digits; $x++)
                <input
                    x-ref="input{{ $x }}"
                    numeric="true"
                    type="number"
                    x-on:paste="pasteValue"
                    x-on:keydown="moveCursorNext({{ $x }}, {{ $digits }}, event)"
                    x-on:focus="$el.select()"
                    class="w-12 h-12 font-light text-center text-black rounded-md border shadow-xs appearance-none auth-component-code-input dark:text-dark-400 border-zinc-200 focus:border-2"
                    maxlength="1"
                />
            @endfor
        </div>
    </div>
    <input {{ $attributes->whereStartsWith('id') }} type="hidden" x-ref="pin" name="pin" />
</div>
