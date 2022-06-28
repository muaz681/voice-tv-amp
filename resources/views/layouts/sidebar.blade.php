<?php
    $categoriesJson = '[{"id":16,"title":"জাতীয়"},{"id":5,"title":"রাজনীতি"},{"id":4,"title":"অপরাধ"},{"id":13,"title":"খেলাধুলা"},{"id":9,"title":"শিক্ষাঙ্গন"},{"id":12,"title":"দেশের খবর"},{"id":8,"title":"মুক্তমত"},{"id":41,"title":"পশ্চিমবঙ্গ"},{"id":14,"title":"প্রবাসী"},{"id":3,"title":"বিনোদন"},{"id":7,"title":"বিশ্ব"},{"id":20,"title":"ভিডিও সংবাদ"},{"id":15,"title":"ভ্রমণ"},{"id":6,"title":"অর্থনীতি"},{"id":10,"title":"চিকিৎসা"},{"id":11,"title":"ধর্ম"}]';
    $categories = json_decode($categoriesJson);

?>
<amp-sidebar id="header-sidebar" class="ampstart-sidebar px3  " layout="nodisplay">
    <div class="flex justify-start items-center ampstart-sidebar-header pb1 mb3">
        <div role="button" aria-label="close sidebar" on="tap:header-sidebar.toggle" tabindex="0" class="ampstart-navbar-trigger items-start"><svg width="24" height="24" xmlns="http://www.w3.org/2000/svg" fill-rule="evenodd" clip-rule="evenodd"><path d="M12 11.293l10.293-10.293.707.707-10.293 10.293 10.293 10.293-.707.707-10.293-10.293-10.293 10.293-.707-.707 10.293-10.293-10.293-10.293.707-.707 10.293 10.293z"/></svg></div>

        <a class="flex mx-auto" href="https://bn.voicetv.tv">
            <amp-img src="{{secure_asset('assets/images/logo.svg')}}" width="160" height="55" layout="fixed" class="" alt="Voice TV"></amp-img>
        </a>
    </div>
    <nav class="ampstart-sidebar-nav ampstart-nav">
        <ul class="list-reset m0 p0 ampstart-label">
            @foreach($categories as $c)
            <li class="ampstart-nav-item "><a class="ampstart-nav-link" href="https://bn.voicetv.tv/category/{{$c->id}}">{{$c->title}}</a></li>
            @endforeach
        </ul>
    </nav>

{{--    <ul class="ampstart-sidebar-faq list-reset m0">
        <li class="ampstart-faq-item"><a href="#" class="text-decoration-none">About</a></li>
        <li class="ampstart-faq-item"><a href="#" class="text-decoration-none">Contact</a></li>
    </ul>--}}
</amp-sidebar>