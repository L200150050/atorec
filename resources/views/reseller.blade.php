@extends('layouts.app')

@section('menu')
<a class="dropdown-item" data-toggle="modal" data-target=#cart> Shop cart </a>
<a class="dropdown-item" data-toggle="modal" data-target=#pesanan> Barang Pesanan Anda </a>
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    @if(Auth::user()->aktif == 1)

                    @if (count($errors) > 0)
                        <div class="alert alert-danger">
                            <ul>
                                 @foreach ($errors->all() as $error)
                                  <li>{{ $error }}</li>
                                 @endforeach
                            </ul>
                        </div>
                    @endif

                    <h2>Produk</h2>
                    <form method="post" action="{{route('rsearch') }}">
                        @csrf   
                        <input type="text" name="search">
                        <button type="submit">Search</button>
                    </form>
                    <div class="row">
                    @foreach($produk as $pro)

                        <!-- produk -->
                        <div class="col-md-3">
                        <div class="thumbnail">
                          <a href="{{$pro->img_url}}">
                            <img src="{{$pro->img_url}}" style="width:100%">
                            <div class="caption">
                            Nama  : {{$pro->nama_p}}</br>
                            harga : Rp.{{$pro->harga}}</br> 
                            harga reseller : Rp.{{$pro->harga_r}}</br>
                            jumlah produk  : {{$pro->qty_p}}</br>
                            </a>
                            </div>
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#a{{$pro->id}}"> Pesan produk </button>
                        </div>
                      </div>

                        <!-- Modal add produk-->
                        <div class="modal" id="a{{$pro->id}}">
                          <div class="modal-dialog">
                            <div class="modal-content">

                              <!-- Modal Header -->
                              <div class="modal-header">
                                <h4 class="modal-title">Masukkan ke Keranjang</h4>
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                              </div>

                              <!-- Modal body -->
                              <div class="modal-body">
                                <img src="{{$pro->img_url}}" width="200px" height="200px">
                                <p>{{$pro->nama_p}}</p>
                                <p>harga reseller : Rp.{{$pro->harga_r}}</p>
                                <form method="post" action="{{ route('addproduk') }}">
                                    @csrf
                                    <input type="number" name="qty">
                                    <input type="hidden" name="OP" value="{{$pro->id}}">
                                    <button type="submit" class="btn btn-primary">
                                        {{ __('Masukkan') }}
                                    </button>
                                </form>
                              </div>

                              <!-- Modal footer -->
                              <div class="modal-footer">
                                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                              </div>

                            </div>
                          </div>
                        </div>
                    @endforeach
                    <div class="float">{{ $produk->links() }}</div>
                    <div>
                @else
                    </br>
                    <a>mohon maaf akun anda belum aktif,</a>
                    <a>mohon tunggu admin untuk meng aktifkan</a>
                @endif                        
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Modal status barang -->
<div class="modal" id="pesanan">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">

    <!-- Modal Header -->
    <div class="modal-header">
        <h4 class="modal-title">Barang Pesanan Anda</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
    </div>

      <!-- Modal body -->
    <div class="modal-body">
        <table class="table responsive">
            <thead>
              <tr>
                <th>Nama Produk</th>
                <th>Atas Nama</th>
                <th>Alamat</th>
                <th>Jumlah pemesanan</th>
                <th>Status Pesanan</th>
              </tr>
            </thead>
            <tbody>
            @foreach($pesanan as $t)
                <tr>
                <td>{{$t->produk->nama_p}}</td>
                <td>{{$t->atas_nama}}</td>
                <td>{{$t->alamat}}</td>
                <td>{{$t->tr_qty}}</td>
                <td>
                <form action="{{ route('rkonfirm') }}" method="POST" name="konfirmasi{{$t->id}}" id="konfirmasi{{$t->id}}">
                    @csrf
                    <input type="hidden" id="id" name="id" value="{{$t->id}}" form="konfirmasi{{$t->id}}">
                    @if($t->status == "Telah Dikirim")
                    <select name="status" id="status" form="konfirmasi{{$t->id}}">
                        <option value="{{$t->status}}">Telah Dikirim</option>
                        <option value="Selesai">Telah Diterima</option>
                        <td><button class="btn btn-danger" type="submit" value="submit" form="konfirmasi{{$t->id}}">Ubah</button></td>
                    @else
                        <label>{{$t->status}}</label>
                    @endif                                                        
                </form></td>

                </tr>
            @endforeach
            </tbody>
          </table>
    </div>
      <!-- Modal footer -->
    <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
    </div>

    </div>
  </div>
</div>

<!-- Modal shop cart-->
<div class="modal fade" id="cart">
    <div clasS="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
    <div class="modal-header">
        <p class="modal-title">Shop Cart</p>
        <button type="button" class="close" data-dismiss="modal">&times;</button>                        
    </div>
    <div class="modal-body">
        @if($shopcart)
        @foreach($shopcart as $pro)
            <div><img src="{{$pro->img_url}}" width="75px" height="75px"></br>
                {{$pro->nama_p}}</br>
                @.Rp{{$pro->harga_r}}</br>
                jumlah = {{session()->get('$cart')[$pro->id][0]}}</br>
                <button type="button" class="btn btn-primary" data-toggle="modal" data-dismiss="modal"data-target="#{{$pro->nama_p}}"> edit jumlah </button>
                <a href="{{route('reseller.hapus',['hapus' => $pro->id]) }}"><button type="button" class="btn btn-primary"> Hapus </button></a>
            <div>
        @endforeach
        @endif
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>   
        @if(session('$cart'))
        <a href="{{ route('transaksiR')}}"><button type="button" class="btn btn-primary" data-toggle="modal" data-target="#transaksi"> Order </button></a>
        @endif
    </div>
    </div>
</div>
@endsection