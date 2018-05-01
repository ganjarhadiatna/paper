@extends('layout.index')
@section('title',$title)
@section('path',$path)
@section('content')
<div class="frame-empty">
    <div class="icn fa fa-lg fa-thermometer-empty btn-main-color"></div>
    <div class="ttl padding-15px">
        We can not find this design.
    </div>
    <div class="desc ctn-main-font ctn-bold ctn-14px ctn-sek-color padding-bottom-20px">
        This design has been deleted by user or has been determined.
    </div>
</div>
@endsection