<?php

/**
 * Tailwind Theme
 * Created by Tony Lea and the DevDojo
 *
 * Use the theme_field() function to display fields in
 * your theme. Take a look at the function DEFINITION
 * EXAMPLE, EXPLANATION, and TYPES OF FIELDS below:
 *
 * 	DEFINITION:
 *
 * 		theme_field(
 * 			$type,
 * 		 	$key,
 * 		 	$title = '',
 * 		  	$content = '',
 * 		   	$details = '',
 * 		    $placeholder = '',
 * 		    $required = 1)
 *
 * 	EXAMPLE of a textbox asking for headline:
 *
 * 		{!! theme_field(
 * 				'text',
 * 			 	'headline',
 * 			  	'My Aweseome Headline',
 * 			   	'{}',
 * 			    'Add your Headline here',
 * 			    0)
 * 	    !!}
 *
 * 		Only the first 2 are arguments are required
 *
 * 		{!! theme_field('test', 'headline') !!}
 *
 * 	EXPLANATION:
 * 		$type
 * 			This is the type of field you want to display, you can
 * 		 	take a look at all the fields from the TYPES OF FIELDS
 * 		  	section below.
 * 	    $key
 * 		   	This is the key you want to create to reference the
 * 		    field in your theme.
 * 		 $title
 * 		 	This is the title or the label above the field
 * 	     $content
 * 		    The current contents or value of the field, if the field
 * 		    has already been created in the db, the value in the
 * 		    database will be used instead
 * 	     $details
 * 		    The details of the field in JSON. You can find more
 * 		    info about the details from the following URL:
 * 		    https://voyager.readme.io/docs/additional-field-options
 * 	     $placeholder
 * 		    The placeholder value of the field
 * 	     $required
 * 		    Whether or not this field is required
 *
 * 	TYPES OF FIELDS
 * 		checkbox, color, date, file, image, multiple_images,
 * 		number, password, radio_btn, rich_text_box, code_editor,
 * 		markdown_editor, select_dropdown, select_multiple, text,
 * 		text_area, timestamp, hidden, coordinates
 */

?>
<style>.tab-pane{ display:none; }, .tab-pane.active{ display:block; }</style>

<div class="theme-settings">

	<ul class="nav nav-tabs">
		<li class="active"><a data-toggle="tab" href="#branding">Branding</a></li>
		<li><a data-toggle="tab" href="#homepage">Homepage</a></li>
		<li><a data-toggle="tab" href="#themeinfo">Theme Info</a></li>
	</ul>

	<div class="tab-content">

		<div id="branding" class="tab-pane fade in active">

			{!! theme_field('image', 'logo', 'Site Logo') !!}

			{!! theme_field('image', 'footer_logo', 'Footer Logo') !!}

		</div>

		<div id="homepage" class="tab-pane">

			{!! theme_field('text', 'home_headline', 'Homepage Headline') !!}
			{!! theme_field('text', 'home_subheadline', 'Homepage Subheadline') !!}
			{!! theme_field('text_area', 'home_description', 'Homepage Copy Below Subheadline') !!}
			{!! theme_field('text', 'home_cta', 'Homepage CTA Text') !!}
			{!! theme_field('text', 'home_cta_url', 'Homepage CTA URL') !!}
			{!! theme_field('image', 'home_promo_image', 'Homepage Promo Image') !!}

		</div>

		<div id="themeinfo" class="tab-pane">

			<h3>TailwindCSS</h3>
			<p>This theme was built using TailwindCSS. You can learn more about Tailwind by visiting the links below.</p>
			<a href="https://tailwindcss.com" target="_blank" class="btn btn-small btn-primary">TailwindCSS Homepage</a>
			<a href="https://tailwindcss.com/docs" target="_blank" class="btn btn-small btn-primary">Tailwind Documentation</a>
			<hr>
			<h3>Theme Options</h3>
			<p>You can edit this file located at: <code>{{ resource_path('views/' . theme_folder()) . '/options.blade.php' }}</code>
			<p>It's quite easy to add your own options, instructions are commented at the top of the file.</p>
			<hr>

		</div>

	</div>


</div>
