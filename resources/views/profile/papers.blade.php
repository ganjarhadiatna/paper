@extends('layout.index')
@section('title',$title)
@section('path', $path)
@section('content')
@include('profile.index')
<div>
    @if (count($userPapers) == 0)
        <div class="frame-empty">
            <div class="icn fa fa-lg fa-thermometer-empty btn-main-color"></div>
            <div class="ttl padding-15px">Paper empty, try to create one.</div>
            <a href="{{ url('/compose') }}">
                <button class="create btn btn-sekunder-color btn-radius">
                    <span class="fas fa-lg fa-plus"></span>
                    <span>Create Your First Paper</span>
                </button>
            </a>
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