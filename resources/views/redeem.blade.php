@extends('app')
@section('content')

  <style>
    .box { margin: 20px; padding: 20px; }
  </style>
  <h4 style="text-align: center;">Redeem Form</h4>
  <div class="box">

    <form method="POST" action="{{ route('submitRedeem') }}" enctype="multipart/form-data">
      @csrf
      <label style="display: block; font-weight: bold;">Your voucher will be expired at {{ date('d M Y h:i A', strtotime($voucher->expired_at)) }}</label>

      <label style="display: block; font-weight: bold;">Upload your image</label>
      <input type="file" accept="image/png, image/gif, image/jpeg" name="img" />
      <input type="hidden" value="{{ $voucher->voucher_code }}" name="voucher_code" />

      <div style="display: block; margin-top: 20px;">
        <button type="submit" class="btn btn-success">Submit</button>
      </div>

      <a href="{{ route('getCustomerList') }}" style="display: block; text-align: center;">Back to customer list</a>
    </form>
  </div>

@endsection