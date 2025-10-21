<section class="relative z-10 w-full -mb-5 sm:-mb-0 sm:pt-12 mb:pb-0">
    <div class="px-5 mx-auto max-w-7xl md:px-8">
        <x-marketing.elements.heading
            heading="Words Speak for Themselves"
            description="Our customers' feedback speaks volumes, highlighting how our platform empowers them to achieve their goals and transform their visions into reality."
        />
        <div class="grid w-full grid-cols-4 gap-6 sm:grid-cols-8 lg:grid-cols-12">
            <div class="col-span-4 space-y-6">
                @include('theme::partials.testimonial', [
                    'image' => 'https://cdn.devdojo.com/images/january2022/01-john-robertson.jpeg',
                    'name' => 'John Robertson',
                    'title' => 'Director of Sales at Workflow',
                    'quote' => 'This platform has been a game-changer for our team. The ease of use and powerful features allow us to focus on what truly matters—building and innovating. It\'s like having an extra developer on our team.'
                ])
                @include('theme::partials.testimonial', [
                    'image' => 'https://cdn.devdojo.com/images/january2022/02-mike-samson.jpeg',
                    'name' => 'Mike Samson',
                    'title' => 'CEO at Blocknet',
                    'quote' => 'I was blown away by how quickly we could get up and running. The intuitive interface and comprehensive toolkit have saved us countless hours, making our workflow more efficient and enjoyable.'
                ])
                @include('theme::partials.testimonial', [
                    'image' => 'https://cdn.devdojo.com/images/january2022/03-jack-bennington.jpeg',
                    'name' => 'Jack Bennington',
                    'title' => 'CTO at TNT Solutions',
                    'quote' => 'I\'ve tried many tools, but this one stands out for its simplicity and effectiveness. It\'s perfect for both beginners and pros, making every project feel less like work and more like a creative adventure.'
                ])
            </div>

            <div class="hidden col-span-4 space-y-5 sm:block">
                @include('theme::partials.testimonial', [
                    'image' => 'https://cdn.devdojo.com/images/january2022/04-steve-mitchell.jpeg',
                    'name' => 'Steve Mitchell',
                    'title' => 'CEO and Partner at Rakstation',
                    'quote' => 'The customization options are a breath of fresh air. It allows us to maintain our brand\'s unique style while leveraging all the robust functionality underneath. Finally, a tool that understands our needs!'
                ])
                @include('theme::partials.testimonial', [
                    'image' => 'https://cdn.devdojo.com/images/january2022/05-ron-garrison.jpeg',
                    'name' => 'Ron Garrison',
                    'title' => 'Lead Developer at Devworks',
                    'quote' => 'It\'s not just about the features; it\'s about the whole experience. This tool turns a complex process into a straightforward journey, making every step feel purposeful and clear.'
                ])
                @include('theme::partials.testimonial', [
                    'image' => 'https://cdn.devdojo.com/images/january2022/06-charlie-madocks.jpeg',
                    'name' => 'Charlie Madocks',
                    'title' => 'Director of Marketing at Goji',
                    'quote' => 'As a small team, efficiency is everything. This platform helps us punch above our weight, delivering professional results without the professional-level headaches.'
                ])
            </div>

            <div class="hidden col-span-4 space-y-5 lg:block">
                @include('theme::partials.testimonial', [
                    'image' => 'https://cdn.devdojo.com/images/january2022/07-nick-thompson.jpeg',
                    'name' => 'Nick Thompson',
                    'title' => 'CTO at Craftyworks',
                    'quote' => 'What I love most is how it grows with you. Whether you\'re just starting out or scaling up, the adaptability of this tool means it\'s always just the right fit.'
                ])
                @include('theme::partials.testimonial', [
                    'image' => 'https://cdn.devdojo.com/images/january2022/08-jake-walters.jpeg',
                    'name' => 'Jake Walters',
                    'title' => 'CFO at Edgeworks',
                    'quote' => 'We used to spend so much time on setup and maintenance, but now everything just works. It feels like we\'ve unlocked a secret level in our development process, and we\'re never going back.'
                ])
                @include('theme::partials.testimonial', [
                    'image' => 'https://cdn.devdojo.com/images/january2022/09-sam-robinson.jpeg',
                    'name' => 'Sam Robinson',
                    'title' => 'Lead Developer at Socnet',
                    'quote' => 'Finally, a solution that merges creativity with practicality. It\'s rare to find something that\'s as flexible as it is powerful, giving us the freedom to dream big and execute flawlessly.'
                ])
            </div>

        </div>
    </div>
    <div class="absolute bottom-0 left-0 z-20 flex items-end justify-center w-full h-32 md:h-64 lg:h-96 bg-gradient-to-t from-white dark:from-black"></div>
</section>