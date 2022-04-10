@extends('app')
@section('content')

  <style>
    .box { margin: 20px; padding: 20px; }
    .box-table { max-height: 700px; overflow-y: auto; margin-bottom: 20px; }

  </style>
  <h4 style="text-align: center;">Customer List</h4>
  <div class="box">
    <div class="box-table">
      <table class="table" id="table">
        <thead>
          <th>Name</th>
          <th>Email</th>
          <th>Redeem Voucher</th>
        </thead>
        <tbody>
          @foreach($customer_list as $customer)
            <tr>
              <td>{{ $customer->first_name }} {{ $customer->last_name }}</td>
              <td>{{ $customer->email }}</td>
              <td>
                @if($customer->expired_at)
                  {{ date('d M Y h:i A', strtotime($customer->expired_at)) }}
                @endif
              </td>
              <td>
                @if($customer->redeem == true)
                  @if($fully_redeem == true)
                    Voucher has been fully redeemed.
                  @else
                    @if($customer->voucher_redeemed == null)
                      <a href="{{ route('redeemLink', ['customer_id' => $customer->id]) }}" target="_blank">Redeem now</a>
                    @else
                      Customer already redeemed.
                    @endif
                  @endif
                @endif
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>

  <script>
    
    $(document).ready(function(){
      
    });

  </script>

@endsection
