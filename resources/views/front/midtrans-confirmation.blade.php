@extends('front.layouts.app')

@section('content')
<script type="text/javascript"
    src="{{ config('midtrans.snap_url') }}"
    data-client-key="{{ config('midtrans.client_key') }}">
</script>

    <section class="container">
        <div class="col-md-12 text-center py-5">
            @if (Session::has('success'))
            <div class="alert alert-success">
                {{ Session::get('success') }}
            </div>
            @endif
            
            <h1>Silahkan Lakukan Pembayaran</h1>

            {{-- TODO:silahkan tambahkan info pembelian dan pembeli --}}
            
            <p>Order Id anda: {{ $id }}</p>
            <button class="btn btn-primary submit-btn" id="pay-button">Bayar Sekarang</button>    
        </div>
    </section>


<script type="text/javascript">
    const queryString = window.location.search;

    // Membuat instance dari URLSearchParams
    const urlParams = new URLSearchParams(queryString);

    // Mendapatkan nilai parameter 'name'
    const snapToken = urlParams.get('snapToken');

  // For example trigger on button clicked, or any time you need
  var payButton = document.getElementById('pay-button');
  payButton.addEventListener('click', function () {
    // Trigger snap popup. @TODO: Replace TRANSACTION_TOKEN_HERE with your transaction token


    window.snap.pay(snapToken, {
      onSuccess: function(result){
        /* You may add your own implementation here */
        alert("Pembayaran Sukses!"); 
        const url = '<?= URL::to('/thanks/' . $id); ?>';
        window.open(url,  '_self');        
      },
      onPending: function(result){
        /* You may add your own implementation here */
        alert("Sedang menunggu pembayaran anda!"); console.log(result);
      },
      onError: function(result){
        /* You may add your own implementation here */
        alert("Pembayaran Gagal!"); console.log(result);
      },
      onClose: function(){
        /* You may add your own implementation here */
        alert('Anda menutup pop up tanpa melakukan pembayaran');
      }
    })
  });
</script>
@endsection