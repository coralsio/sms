@foreach($keys as $key)
    {!! CoralsForm::text("keys[$key]",$key,true,data_get($provider,"keys.$key")) !!}
@endforeach