@extends('layouts.app')

@section('title'){{ 'Telefono numeris ' . numberAddPlus($number->number) . ', ' . numberChangeCode($number->number) }}@endsection
@section('description'){{ 'Kas skambino? kieno numeris? telefono numeris ' . numberAddPlus($number->number) . ', ' . numberChangeCode($number->number) }}@endsection
@section('keywords'){{ numberAddPlus($number->number) . ', ' . numberChangeCode($number->number) . ', telefono numeris, kas skambino? kieno numeris?' }}@endsection

@section('js')
    <script src='https://www.google.com/recaptcha/api.js'></script>
@endsection
<style type="text/css">
    .phone-content {
        padding: 15px;
    }
</style>
@section('content')
    <div class="phone-content">
        <h1>Telefono numeris <b>{{ numberAddPlus($number->number) }}</b> / <b>{{ numberChangeCode($number->number) }}</b></h1>

        <div>
            <b>Kas skambino? Kieno numeris?</b><br/>
            Sulaukei skambučio iš telefono numerio <b>{{ numberAddPlus($number->number) }}</b> ? Čia rasi informaciją apie
            <b>{{ numberChangeCode($number->number) }}</b> telefono numerį.
            <br /> Šio numeriojo ieško jau <span class="badge">@if($number->view_count) {{ $number->view_count }}@else {{ '1' }}@endif</span> k.,
            šis numeris turi <span class="badge">{{ count($number->comments) }}</span> koment.
            @if($number->updated_at)
                <br />Paskutinį kartą tikrintas: <b>{{ $number->updated_at->format('Y-m-d') }}</b>
            @endif
            <br/>Informuok apie šį numerį! Dalinkitės informacija apie šį numerį komentaruose.
        </div>
    </div>

    @if(count($number->comments) > 0)
        <div class="phone-comment-list">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <b>Komentarai</b>
                </div>
                <div class="panel-body">
                    @foreach($number->comments as $comment)
                        <div class="media">
                            <div class="media-body">
                                <h4 class="media-heading"><b>{{ $comment->author }}</b></h4>
                                {{ nl2br($comment->content) }}
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    <div class="phone-comment-form">
        <div class="panel panel-default">
            <div class="panel-heading">
                <b>Palik komentarą</b>
            </div>
            <div class="panel-body">

                <form method="POST">
                    {!! csrf_field() !!}

                    <div class="form-group @if($errors->has('author')) {{ 'has-error' }}"@endif>
                        <label class="control-label" for="author">Vardas</label>
                        <input type="text" class="form-control" name="author" id="author" value="{{ old('author') }}">
                        @if($errors->has('author'))
                            <span class="help-block">
                        {{ $errors->first('author') }}
                    </span>
                        @endif
                    </div>

                    <div class="form-group @if($errors->has('content')) {{ 'has-error' }}"@endif>
                        <label class="control-label" for="content">Komentaras</label>
                        <textarea rows="6" name="content" id="content" class="form-control"
                                  style="resize:vertical;">{{ old('content') }}</textarea>
                        @if($errors->has('content'))
                            <span class="help-block">
                        {{ $errors->first('content') }}
                    </span>
                        @endif
                    </div>

                    <div class="form-group">
                        <div class="g-recaptcha" data-sitekey="{{ Config('app_config.GOOGLE_RECAPTCHA_KEY') }}"></div>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-default">
                            Įrašyti
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection