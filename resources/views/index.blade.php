@extends('app')
@section('content')

  <div style="width: 100%; text-align: center; padding-top: 20px;">
    <form method="POST" action="{{ route('startCampaign') }}">
      @csrf
      <button id="start">Start the campaign</button>
    </form>
  </div>

@endsection