@extends('layouts.main')
@section('seo')
	<?php
	$seoData = $post->seo;
	$seoData += ['id' => $post->id];
	?>
    @include('layouts.seo', ['seoData' => $seoData])
@endsection

@section('content')
    <div class="pt1">
        <ul class="breadcrumbs">
            <li><a href="https://bn.voicetv.tv" class="text-decoration-none">প্রচ্ছদ</a></li>
            <li><a href="#" class="text-decoration-none">{{$post->main_category}}</a></li>
        </ul>
    </div>
    <article class="article">
        <header>
            <h1 class="mb1 article-title ampstart-title-md">{{$post->title}}</h1>
			<?php
			$currentDate = date("l, F j, Y", strtotime($post->post_date));

			$engDATE = array(1,2,3,4,5,6,7,8,9,0, 'January', 'February', 'March','April', 'May', 'June', 'July', 'August','September', 'October', 'November', 'December', 'Saturday', 'Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday');

			$bangDATE = array('১','২','৩','৪','৫','৬','৭','৮','৯','০','জানুয়ারী','ফেব্রুয়ারী','মার্চ','এপ্রিল','মে','জুন','জুলাই','আগস্ট','সেপ্টেম্বর','অক্টোবর','নভেম্বর','ডিসেম্বর','শনিবার','রবিবার','সোমবার','মঙ্গলবার',' বুধবার','বৃহস্পতিবার','শুক্রবার' );

			$convertedDATE = str_replace($engDATE, $bangDATE, $currentDate);
			$content = preg_replace('/<iframe.*?\/iframe>/i','', $post->content); // remove all iframes
            $content = preg_replace('/<img .*? src="([^"]*)" .*?>/', '<amp-img src="$1" width="800" height="600" layout="intrinsic" alt="AMP"></amp-img>', $content); // replace img tags with amp-img tags
			$contentArray = explode("\n", $content);
			?>
            <div class="article-meta">
                <time class="ampstart-byline-pubdate bold my1" datetime="{{$post->post_date}}">{{$convertedDATE}}</time>

                <div class="right d-print-none">
                    <amp-social-share
                            class="circle mr1"
                            type="facebook"
                            width="18"
                            height="18"
                            data-param-app_id="563400841004160"
                            data-param-text="{{$post->title}}"
                    >
                    </amp-social-share>
                    <amp-social-share
                            class="circle mr1"
                            type="twitter"
                            width="18"
                            height="18"
                            data-param-text="{{$post->title}}"
                    >
                    </amp-social-share>
                    <amp-social-share
                            class="circle mr1"
                            type="whatsapp"
                            width="18"
                            height="18"
                            data-param-text="{{$post->title}} \n https://bn.voicetv.tv/news/{{$post->id}}"
                    >
                    </amp-social-share>
                    <amp-social-share
                            class="circle mr1"
                            type="linkedin"
                            width="18"
                            height="18"
                    >
                    </amp-social-share>
                </div>
                <ul class="tags block">
                    @foreach($post->categories as $cat)
                        <li class="tag">{{$cat->name}}</li>
                    @endforeach
                </ul>
            </div>

            <!-- End byline -->
            @if(!empty($post->image))
                <amp-img src="{{$post->image}}" width="1280" height="853" layout="responsive" alt="featured image" class="mb4"></amp-img>
            @endif
        </header>
        @foreach($contentArray as $key => $p)
            <p class="mb4">
            {!! $p !!}
            @if(in_array($key, [1,8]))
                <div class="justify-center md-flex my2 d-print-none">
                    <!-- <amp-ad
                            width="300"
                            height="250"
                            type="industrybrains"
                            data-width="300"
                            data-height="250"
                            data-cid="19626-3798936394"
                    >
                        <div placeholder>Loading ...</div>
                    </amp-ad> -->
                </div>
                @endif
                </p>
                @endforeach
                @if(!empty($youtube_video_id))
        @section('extraTags')
            <script async custom-element="amp-youtube" src="https://cdn.ampproject.org/v0/amp-youtube-0.1.js"></script>
        @endsection
        <div class="d-print-none">
            <amp-youtube
                    data-videoid="{{$youtube_video_id}}"
                    layout="responsive"
                    width="480"
                    height="270"
            ></amp-youtube>
        </div>
        @endif
    </article>
    <hr class="mt4">
    @if($related)
        <section class="ampstart-related-section mb4 px3 d-print-none">
            <h2 class="h3 mb1">আরো খবর</h2>
            <ul class="ampstart-related-section-items list-reset flex flex-wrap m0">
                @foreach($related as $r)
                    <li class="col-12 sm-col-3 md-col-3 lg-col-3 pr2 py2">
                        <a href="/news/{{$r->id}}" class="text-decoration-none">
                            <figure class="ampstart-image-with-caption m0 relative mb4">
                                <amp-img src="{{$r['image']}}" alt="Post Image" width="233" height="202" layout="responsive" class=""></amp-img>
                                <figcaption class="h5 mt1 px3">
                                    {{$r->title}}
                                </figcaption>
                            </figure>
                        </a>
                    </li>
                @endforeach
            </ul>
        </section>
    @endif

    @if($latest)
        <hr class="mt4">
        <section class="ampstart-related-section mb4 px3 d-print-none">
            <h2 class="h3 mb1">সর্বশেষ সংবাদ</h2>
            @foreach($latest as $l)
                <div class="ampstart-byline clearfix mb4 px3 h5">
                    <amp-img class="ampstart-byline-photo mr3 left" src="{{$l->image}}" layout="fixed" height="60" width="60"></amp-img>
                    <a class="text-decoration-none bold" href="/news/{{$l->id}}">{{$l->title}}</a>
                    <p class="my1">{{substr($l->excerpt, 0, 200)}}</p>
                </div>
            @endforeach
        </section>
    @endif
    <div class="d-print-none">
        <hr class="mt4">
<!--         <amp-embed
        type="taboola"
        width="400"
        height="300"
        layout="responsive"
        data-publisher="amp-demo"
        data-mode="thumbnails-a"
        data-placement="Ads Example"
        data-article="auto"
>
</amp-embed> -->
    </div>
@endsection