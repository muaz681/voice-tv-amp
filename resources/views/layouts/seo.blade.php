
    {{-- Meta Fields --}}
    @if(empty($seoData))
    <title>Welcome to Voice TV</title>
    <link rel="canonical" href="https://bn.voicetv.tv/">
    @else
    <title>{{ $seoData['title']}}</title>
    <meta name="robots" content="index, follow" />
    <link rel="canonical" href="https://bn.voicetv.tv/news/{{$seoData['id']}}">
    <meta name="description" content="{{ empty($seoData['_yoast_wpseo_opengraph-description']) ? $seoData['description'] : $seoData['_yoast_wpseo_opengraph-description'] }}"/>
    <meta property="og:locale" content="bn_BD" />
    <meta property="og:type" content="article" />
    <meta property="og:title" content="{{ empty($seoData['_yoast_wpseo_opengraph-title']) ? $seoData['title'] : $seoData['_yoast_wpseo_opengraph-title'] }}" />
    <meta property="og:description" content="{{ empty($seoData['_yoast_wpseo_opengraph-description']) ? $seoData['description'] : $seoData['_yoast_wpseo_opengraph-description'] }}"/>

    <meta property="article:published_time" content="{{$seoData['post_date_gmt']}}" />
    <meta property="article:modified_time" content="{{$seoData['post_modified_gmt']}}" />

    @if(!empty($seoData['image']))
        <meta name="og:image" content="{{ empty($seoData['_yoast_wpseo_opengraph-image']) ? $seoData['image'] : $seoData['_yoast_wpseo_opengraph-image'] }}" />
        <meta property="og:image:width" content="{{$seoData['image_width']}}" />
        <meta property="og:image:height" content="{{$seoData['image_height']}}" />
    @endif

    <meta property='article:author' content='https://bn.voicetv.tv/' />
    <meta property='article:publisher' content='https://bn.voicetv.tv/' />
    <meta property='og:site_name' content='Voice TV 24 | ভয়েস টিভি' />

    <meta name="googlebot" content="index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1" />
    <meta name="bingbot" content="index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1" />


    <meta name="twitter:card" content="summary" />
    <meta name="twitter:description" content="{{ $seoData['_yoast_wpseo_twitter-description'] }}" />
    <meta name="twitter:title" content="{{ $seoData['_yoast_wpseo_twitter-title'] }}" />
    @endif
    {{-- end: Meta Fields --}}