@extends('layouts.main')

@section('seo')
    <title>Not Found</title>
    <link rel="canonical" href="https://beta.voicetv24.com/404}}">
@endsection
@section('customCss')

    <style>
        .full-height {
            height: 100vh;
        }

        .flex-center {
            align-items: center;
            display: flex;
            justify-content: center;
        }

        .position-ref {
            position: relative;
        }

        .code {
            border-right: 2px solid;
            font-size: 26px;
            padding: 0 15px 0 15px;
            text-align: center;
        }

        .message {
            font-size: 18px;
            text-align: center;
        }
    </style>
@endsection
@section('content')

    <div class="flex-center position-ref py4">
        <div class="code">
            404
        </div>

        <div class="message" style="padding: 10px;">
            পাওয়া যায়নি!
        </div>
    </div>
    <p class="my2">আপনি যে বিষয়টি অনুসন্ধান করছেন তা খুজে পাওয়া যায়নি। বিষয়টি সম্ভবত ভয়েস টিভির সাথে সংশ্লিষ্ট নয় অথবা আপনি ভুলভাবে অনুসন্ধান করছেন।</p>
    <p class="my2">অনুগ্রহ করে আপনার অনুসন্ধান বিষয়টি সম্বন্ধে নিশ্চিত হোন।</p>
    <a class="block text-center ampstart-btn ampstart-btn-secondary my4" href="http://beta.voicetv24.com" title="VoiceTV">প্রথম পাতায় ফিরে যান</a>
@endsection

