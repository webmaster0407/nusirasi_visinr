@extends('layouts.app')

@section('title'){{ 'Telefono numeriai pagal kodą' }}@endsection
@section('description'){{ 'Kas skambino? kieno numeris? telefono numeriai pagal kodą' }}@endsection
@section('keywords'){{ 'Telefono numeriai pagal kodą, kas skambino? kieno numeris?' }}@endsection

@section('content')
<style>
    .column1 div,.column2 div,.column3 div,.column4 div{
        font-size:12px;
        color:black;
    }
    .searchDiv {
        padding: 0;
        margin: 0;
    }
    .searchDiv > div {
        padding: 0;
    }

    .searchInputDiv > input {
        width: calc(100% + 5px);
        height: 40px;
        border-top-left-radius: 5px;
        border-bottom-left-radius: 5px;
    }
    .searchBtnDiv > button {
        padding: 6px 0;
        height: 40px;
        border-top-left-radius: 0;
        border-bottom-left-radius: 0;
        border-top-right-radius: 5px;
        border-bottom-right-radius: 5px;
        background-color: #eee;
    }
</style>
<script type="text/javascript">
    $(document).ready(function(){
        var url = "{{URL('/searchkey')}}";           
        $.ajaxSetup({
                headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            }); 
               
            $(".send").click(function () {
                var searchvalue = $("#searchkey").val();
                window.location.href = "/" + searchvalue.trim();
            });
    });

    
</script>   
<script type="text/javascript">
    function enterKeyPressed(event) {
                if (event.keyCode == 13) {
                    console.log("Enter key is pressed");
                    var searchvalue = $("#searchkey").val();
                    window.location.href = "/" + searchvalue.trim();
                    
                    return true;
                } 
                else {
                    return false;
                }
            }  
</script>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            
            <div class="form-group">
                <label for="search">Paieškos raktas:</label><br>
                <div class="row searchDiv">
                    <div class="col-md-10 col-sm-10 col-xs-9 searchInputDiv">
                        <input type="text" class="form-control" id="searchkey" onkeypress="enterKeyPressed(event)">
                    </div>
                    <div class="col-md-2 col-sm-2 col-xs-3 searchBtnDiv">
                        <button type="button" class="btn btn-default send" style="width: 100%;">Paieška</button>
                    </div>

                </div>
            </div>
        </div>
        <div class="col-md-6">
            <h1>Telefono numeriai pagal kodą</h1>
            <table class="table table-striped table-hover">
                <tbody class="table_start">
                    @foreach($code1 as $code)
                        <tr>
                            <td>
                            <a href="{{ route('number.code.read', ['code' => $code->code]) }}">{{ $code->code }}</a>
                            </td>
                            <td>
                            {{ $code->provider }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="col-md-6">
            <h1>Naujausi Komentarai</h1>
            <style type="text/css">
                i{
                    font-size: 18px !important;
                    float:right;
                }
            </style>             
                    <?php  $code2 = json_decode($code2, true); ?>
                    @foreach($code2 as $code)
                    <div class="col-md-12 showrow" style="background-color:#2889CA; color:white;padding:10px 20px 10px 20px;">    
                        <a href="{{ route('number.comment.view', ['number' => $code['number']]) }}" style="color:white; font-size: 15px;">{{ $code['number'] }}</a>
                        <a href="{{ route('number.comment.view', ['number' => $code['number']]) }}" style="color:white; float:right; margin-left:10px;">{{ $code['cnt'] }}</a>
                        <a href="{{ route('number.comment.view', ['number' => $code['number']]) }}" style="color:white; float:right;"><i class="fa fa-comments-o"></i></a>
                        
                    </div>
                    <div>
                        <?php  echo  $code['content']; ?>   
                    </div>

                    @endforeach
               
        </div>
    </div>
</div>



    
	

@endsection