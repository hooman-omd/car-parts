@props(['icon'=>true])
<div class="alert alert-danger my-4 text-right" role="alert" id="danger-alert">
    {{$slot}} 
    {!! $icon ? '<i class="fa fa-exclamation-triangle" aria-hidden="true">' : null !!}
</i>
</div>
