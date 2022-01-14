@extends('layouts.last')

@section('title'){{ 'Telefono numeriai pagal kodą' }}@endsection
@section('description'){{ 'Kas skambino? kieno numeris? telefono numeriai pagal kodą' }}@endsection
@section('keywords'){{ 'Telefono numeriai pagal kodą, kas skambino? kieno numeris?' }}@endsection

@section('content')
<h1>Paskutiniai komentarai</h1>

<table class="table table-striped table-hover">
                <tbody class="table_start">
                    @foreach($resultArray as $code)
                        <tr>
                            <td>
                                <a href="{{ route('number.comment.view', ['number' => $code[0]]) }}" style="color:#2A8ACA">{{ $code[0] }}</a>
                            </td>
                            <td>
                            {{ $code[2] }}
                            </td>
                            <td>
                                 <a href="{{ route('number.comment.view', ['number' => $code[0]]) }}" style="color:black; float:left;"><i class="fa fa-comments-o"></i></a>
                            </td>
                            <td>
                                <a href="{{ route('number.comment.view', ['number' => $code[0]]) }}" style="color:grey; float:left; margin-left:10px;">{{ $code[1] }}</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

<style type="text/css">
    i{
        font-size: 18px !important;
        float:right;
    }
</style>             
<!-- @foreach($resultArray as $code)
<div class="col-md-12 showrow" style="background-color:#2889CA; color:white;padding:10px 20px 10px 20px;">{{ $code[0] }}
    <a href="{{ route('number.code.read', ['code' => $code[3]]) }}" style="color:white; float:right; margin-left:10px;">{{ $code[1] }}</a>
    <a href="{{ route('number.code.read', ['code' => $code[3]]) }}" style="color:white; float:right;"><i class="fa fa-comments-dollar"></i></a>
    
</div>
<?php 
    // dd($code->content);
?>
<div class="col-md-12 showrow">{{ $code[2] }}</div>
@endforeach
     -->
@endsection