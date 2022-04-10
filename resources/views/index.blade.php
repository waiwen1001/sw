@extends('app')
@section('content')

  <div style="width: 100%; text-align: center; padding-top: 20px;">
    <form method="POST" action="{{ route('startCampaign') }}">
      @csrf
      @if($voucher)
        <button id="start">Restart the campaign - Activated at {{ date('d M Y h:i A', strtotime($voucher->created_at)) }}</button>
      @else
        <button id="start">Start the campaign</button>
      @endif
    </form>
  </div>

@endsection