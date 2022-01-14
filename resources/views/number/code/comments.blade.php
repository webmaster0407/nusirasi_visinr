@extends('layouts.app')

@section('title'){{ 'Telefono numeriai pagal kodą' }}@endsection
@section('description'){{ 'Kas skambino? kieno numeris? telefono numeriai pagal kodą' }}@endsection
@section('keywords'){{ 'Telefono numeriai pagal kodą, kas skambino? kieno numeris?' }}@endsection

@section('content')

    <h1>Show Last Comments</h1>
        <table class="table table-striped table-hover">
            <tbody class="table_start">
                <tr style="color:black; font-weight:bold;">
                    <td>
                    author
                    </td>
                    <td>
                    content
                    </td>
                    <td>
                    created_at
                    </td>
                    <td>
                    updated_at
                    </td>
                </tr>
                @foreach($code1 as $code)
                    <tr>
                        <td>
                        {{ $code->author }}
                        </td>
                        <td>
                        {{ $code->content }}
                        </td>
                        <td>
                        {{ $code->created_at }}
                        </td>
                        <td>
                        {{ $code->updated_at }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        {{-- <style type="text/css">
            i{
                font-size: 20px !important;
                float:right;
            }
        </style>
        <div class="container">
            <div class="row">                
                @foreach($code1 as $code)
                <div class="col-md-12 showrow" style="background-color:#2889CA; color:white;">{{ $code->author }}
                    <a href="{{ route('number.code.read', ['code' => $code->number_id]) }}" style="color:white; float:right;">{{ $code->number_id }}</a>
                    
                        <a href="{{ route('number.code.read', ['code' => $code->number_id]) }}" style="color:white; float:right;"><i class="fa fa-comments-dollar"></i></a>
                    
                </div>
                <div class="col-md-12 showrow">{{ $code->content }}</div>
                <div class="col-md-12 showrow">{{ $code->created_at }}</div>
                <div class="col-md-12 showrow">{{ $code->updated_at }}</div>
                @endforeach
            </div>
        </div> --}}

@endsection