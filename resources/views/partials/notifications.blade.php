<div>
    @foreach($messages as $message)
        {!! $message !!}
    @endforeach
    <a href="#" class="btn btn-default" data-toggle="clear-notifications">Clear</a>
</div>