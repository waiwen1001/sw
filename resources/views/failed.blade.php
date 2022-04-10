@extends('app')
@section('content')

  <h4 style="text-align: center; margin-top: 50px; color: red;">Voucher redemption is failed, please try again.</h4>
  <a href="{{ route('getCustomerList') }}" style="display: block; text-align: center;">Back to customer list</a>

@endsection