@if(config('wave.demo'))
<!--

@if(strlen(strstr($_SERVER['HTTP_USER_AGENT'], 'Chrome') > 0))
┏┓┓        ┏  ┓┓        ┓      ┓        ╻
┣┫┣┓┏┓┓┏   ╋┏┓┃┃┏┓┓┏┏  ┏┫┏┓┓┏┏┓┃┏┓┏┓┏┓┏┓┃
┛┗┛┗┗┛┗┫╻  ┛┗ ┗┗┗┛┗┻┛  ┗┻┗ ┗┛┗ ┗┗┛┣┛┗ ┛ •
       ┛                          ┛
@else
Ahoy, Fellow Developer! 
@endif

@include('wave::premium-theme-messages.' . rand(1, 9))

-->
@endif