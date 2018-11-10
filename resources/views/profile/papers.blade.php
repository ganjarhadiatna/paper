@extends('layout.index')
@section('title',$title)
@section('path', $path)
@section('content')
@include('profile.index')
<div>
    @if (count($userPapers) == 0)
        <div class="frame-empty">
            <div class="icn fa fa-lg fa-thermometer-empty btn-main-color"></div>
            <div class="ttl padding-15px">Paper empty</div>
        </div>
    @else
        <div class="post-flex">
            @foreach ($userPapers as $bx)
                @include('main.paper')
            @endforeach
        </div>
        {{ $userPapers->links() }}
    @endif
</div>
@endsection