@extends('layout.index')
@section('title',$title)
@section('path',$path)
@section('content')
<div class="frame-empty">
    <div class="icn fa fa-lg fa-thermometer-empty btn-main-color"></div>
    <div class="ttl padding-15px">
        We can not find this box.
    </div>
    <div class="desc ctn-main-font ctn-bold ctn-14px ctn-sek-color padding-bottom-20px">
        This box has been deleted by user or has been determined.
    </div>
    <a href="{{ url('/compose') }}">
        <button class="create btn btn-sekunder-color btn-radius width-all">
            <span class="fas fa-lg fa-plus"></span>
            <span>Create Other Box</span>
        </button>
    </a>
</div>
@endsection