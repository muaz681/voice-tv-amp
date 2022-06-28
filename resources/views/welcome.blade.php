@extends('layouts.main')

@section('seo')
    @include('layouts.seo', ['seoData' => null])
@endsection


@section('content')
    <div class="mt4 py4 justify-center md-flex">
        <h3 class="mt4 py4 justify-center md-flex">Go to main website &nbsp;<a href="https://bn.voicetv.tv" title="Voice TV">bn.voicetv.tv</a></h3>
    </div>

    @if($latest)
        <hr class="mt4">
        <section class="ampstart-related-section mb4 px3 d-print-none">
            <h2 class="h3 mb1">সর্বশেষ সংবাদ</h2>
            @foreach($latest as $l)
                <div class="ampstart-byline clearfix mb4 px3 h5">
                    <amp-img class="ampstart-byline-photo mr3 left" src="{{$l->image}}" layout="fixed" height="60" width="60"></amp-img>
                    <a class="text-decoration-none bold" href="/news/{{$l->id}}">{{$l->title}}</a>
                    <p class="my1">{{substr($post->excerpt, 0, 200)}}</p>
                </div>
            @endforeach
        </section>
    @endif
@endsection