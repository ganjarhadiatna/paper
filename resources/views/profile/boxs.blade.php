@extends('layout.index')
@section('title',$title)
@section('path', $path)
@section('content')
@include('profile.index')
<div>
    @if (count($userBoxs) == 0)
        <div class="frame-empty">
            <div class="icn fa fa-lg fa-thermometer-empty btn-main-color"></div>
            <div class="ttl padding-15px">Box empty, try to create one.</div>
            <a href="{{ url('/compose') }}">
                <button class="create btn btn-main3-color width-all" onclick="opCompose('open');">
                    <span class="fas fa-lg fa-plus"></span>
                    <span>Create Your First Box</span>
                </button>
            </a>
        </div>
    @else
        <div class="post-flex">
            @foreach ($userBoxs as $bx)
                @include('main.box')
            @endforeach
        </div>
        {{ $userBoxs->links() }}
    @endif
</div>
@endsection