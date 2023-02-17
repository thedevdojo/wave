@if(setting('digital-ocean.enabled'))

@endif

<div class="clearfix container-fluid row" style="position:relative; top:10px;">
    <div class="col-xs-12 col-md-6 col-lg-4">
        <div class="bg-green-400 panel widget center bgimage" style="border-radius:6px; position:relative; overflow:hidden; background-image: linear-gradient(to right, #6a11cb 0%, #2575fc 100%);">
            <div style="width:100%; height:40px; display:flex; position:relative; z-index:20; justify-content: start; color:#fff;">
                <img src="{{ Storage::url('/settings/April2021/deploy-to-do.png') }}" class="hidden-md" style="width:40px; height:40px;">
                <div class="relative" style="margin-left:14px; text-align:left;">
                    <p style="color:#ffffff; display:block; margin:0px; font-weight:500; font-size:17px; line-height:17px; margin-top:3px; margin-bottom:4px;">Deploy to DO</p>
                    <p style="display:block; color:#dce9fe; text-align:left; line-height:14px; margin:0px;">Deploy to DigitalOcean</p>
                </div>
                <a href="{{ url('admin/do') }}" style="cursor:pointer; flex-shrink:0; justify-self:end; margin-left:auto; background:#fff; color:#4801FF; font-weight:500; padding:10px 20px; border-radius:4px;">
                    Deploy Now
                </a>
            </div>
            <img src="{{ Storage::url('/settings/April2021/digital-ocean.png') }}" style="width:130px; z-index:10; height:auto; top:10px; right:140px; opacity:30%; position:absolute;">
        </div>
    </div>
    <div class="col-xs-12 col-md-6 col-lg-4">
        <div class="bg-green-400 panel widget center bgimage" style="border-radius:6px; position:relative; overflow:hidden; background-image: linear-gradient(to right, #b8cbb8 0%, #b8cbb8 0%, #b465da 0%, #cf6cc9 33%, #ee609c 66%, #ee609c 100%);">
            <div style="width:100%; height:40px; display:flex; position:relative; z-index:20; justify-content: start; color:#fff;">
                <img src="{{ Storage::url('/settings/April2021/play-icon.png') }}" class="hidden-md" style="width:40px; height:40px;">
                <div class="relative" style="margin-left:14px; text-align:left;">
                    <p style="color:#ffffff; display:block; margin:0px; font-weight:500; font-size:17px; line-height:17px; margin-top:3px; margin-bottom:4px;">Watch & Learn</p>
                    <p style="display:block; text-align:left; line-height:14px; margin:0px;">Screencasts to learn Wave</p>
                </div>
                <a href="https://devdojo.com/course/wave" target="_blank" style="cursor:pointer; flex-shrink:0;  justify-self:end; margin-left:auto; background:#fff; color:#ee445d; font-weight:500; padding:10px 20px; border-radius:4px;">
                    Watch Now
                </a>
            </div>
            <img src="{{ Storage::url('/settings/April2021/popcorn-soda-icon.png') }}" style="width:80px; z-index:10; height:auto; bottom:-30px; right:5px; position:absolute;">
        </div>
    </div>
    <div class="col-xs-12 col-md-6 col-lg-4">
        <div class="bg-green-400 panel widget center bgimage" style="border-radius:6px; position:relative; overflow:hidden; background-image: url('{{ Storage::url('/settings/April2021/tails-bg.png') }}'); background-size:cover;">
            <div style="width:100%; height:40px; display:flex; position:relative; z-index:20; justify-content: start; color:#fff;">
                <img src="{{ Storage::url('/settings/April2021/tails-icon.png') }}" class="hidden-md" style="width:40px; height:40px;">
                <div class="relative" style="margin-left:14px; text-align:left;">
                    <p style="color:#ffffff; display:block; margin:0px; font-weight:500; font-size:17px; line-height:17px; margin-top:3px; margin-bottom:4px;">Create a Landing Page</p>
                    <p style="display:block; text-align:left; color:#dce9fe; line-height:14px; margin:0px;">Tailwind Page Creator</p>
                </div>
                <a href="https://devdojo.com/tails" target="_blank" style="cursor:pointer; flex-shrink:0;  justify-self:end; margin-left:auto; background:#fff; color:#4801FF; font-weight:500; padding:10px 20px; border-radius:4px;">
                    Create Now
                </a>
            </div>
        </div>
    </div>


</div>
