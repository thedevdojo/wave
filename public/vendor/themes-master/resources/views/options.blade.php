@extends('voyager::master')

@section('css')

	<style type="text/css">

		#theme_options .page-title small{
			margin-left:10px;
			color:rgba(0, 0, 0, 0.55);
		}

		#theme_options label, #theme_options label:not(#theme_options .toggle-group label){
			font-weight: normal;
		    font-size: 16px;
		    width: 100%;
		    margin-bottom: 10px;
		    position: relative;
		    left: 1px;
		}

		#theme_options span.how_to {
		    font-size: 12px;
		    margin-left: 10px;
		    background: #fff;
		    padding: 5px 14px;
		    border-radius: 15px;
		    display: inline-block;
		    margin-top: 10px;
		    border: 1px solid #f1f1f1;
		    position: relative;
		    top: -1px;
		}

		#theme_options span.how_to code{
			background: none;
    		border: 0px;
		}

        .voyager .nav-tabs>li.active>a:hover{
            background-color:#62a8ea;
        }

	</style>

@endsection

@section('content')

<div id="theme_options">

	<div class="container-fluid">

		<h1 class="page-title">
        	<i class="voyager-params"></i> {{ $theme->name }} Theme Options
        	<small>Options and settings for the {{ $theme->name }} theme.</small>
        </h1>

        <div class="panel">
        	<div class="panel-body">

	        		@if(file_exists(config('themes.themes_folder', resource_path('views/themes')) . '/' . $theme->folder . '/options.blade.php'))
	        			<?php if (!defined('ACTIVE_THEME_FOLDER')) { define("ACTIVE_THEME_FOLDER", $theme->folder); } ?>
	        			<form action="{{ route('voyager.theme.options', $theme->folder) }}" method="POST" enctype="multipart/form-data">

	        				@include('themes_folder::' . $theme->folder . '.options')
	        				{{ csrf_field() }}
	        				<button class="btn btn-success">Save Theme Settings</button>
	        			</form>
	        		@else
	        			<p>No options file for {{ $theme->name }} theme.</p>
	        		@endif

        	</div>
        </div>

    </div>

</div>

@endsection

@section('javascript')
	<script>
		$(function () {
			$('.toggleswitch').bootstrapToggle();
		});
	</script>
@endsection
