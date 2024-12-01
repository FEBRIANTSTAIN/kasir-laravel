@extends('admin.template.master')

@section('css')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('') }}plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="{{ asset('') }}plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="{{ asset('') }}plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- Modal -->
    <div class="modal fade" id="ModalTambahStok" data-backdrop="static" data-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Tambah Stok</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="form-tambah-stok" method="post">
                    <div class="modal-body">
                        <input type="hidden" name="id_produk" id="id_produk">
                        <label for="">Jumlah Stok</label>
                        <input type="number" name="Stok" id="nilaitambahStok" class="form-control" required>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">{{ $title }}</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">{{ $title }}</a></li>
                            <li class="breadcrumb-item active">{{ $subtitle }}</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">{{ $title }}</h3>
                        <a href="{{ route('penjualan.index') }}" class="btn btn-sm btn-warning float-right mt-5">Kembali<i
                                class="fa-duotone fa-solid fa-arrow-up-from-bracket"></i></a>
                        @if (session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif
                        @if (session('error'))
                            <div class="alert alert-danger">
                                {{ session('error') }}
                            </div>
                        @endif
                    </div>
                    <form action="{{ route('penjualan.store') }}" method="post">

                    <div class="card-body">
                        <table id="example1" class="table table-bordered table-striped;">

                                @csrf
                                <thead>
                                    <tr>
                                        <th>Produk</th>
                                        <th>Harga</th>
                                        <th>Jumlah</th>
                                        <th>Total</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="penjualan">
                                    <td>
                                        <select name="ProdukId[]" class="form-control kode-produk" onchange="pilihProduk(this)">
                                            <option value="">Pilih Produk</option>
                                            @foreach ($produks as $produk)
                                                <option value="{{ $produk->id }}" data-harga="{{ $produk->Harga }}">
                                                    {{ $produk->NamaProduk }}</option>
                                            @endforeach

                                        </select>
                                    </td>
                                    <td>
                                        <input type="text" name="harga[]"  class="form-control harga"
                                         readonly>
                                    </td>
                                    <td>
                                        <input type="number" name="JumlahProduk[]"  class="form-control JumlahProduk" oninput="hitungTotal(this)">
                                    </td>
                                    <td>
                                        <input type="text" name="TotalHarga[]"  class="form-control TotalHarga"
                                            readonly>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-danger" onclick="hapusProduk(this)">Hapus</button>
                                    </td>
                                </tbody>
                                <tfooter>
                                    <tr>
                                        <td colspan="3">
                                            Total Harga
                                        </td>
                                        <td colspan="2">
                                            <input type="text" id="total" class="form-control" name="Total" readonly required>
                                        </td>
                                </tfooter>

                        </table>
                        <button type="button" class="btn btn-primary" onclick="tambahProduk()">TambahProduk</button>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Simpan</button>

                    </div>
                </form>
                </div>


            </div><!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>
@endsection

@section('js')
    <!-- DataTables  & Plugins -->
    <script src="{{ asset('') }}plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="{{ asset('') }}plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
    <script src="{{ asset('') }}plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
    <script src="{{ asset('') }}plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
    <script src="{{ asset('') }}plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
    <script src="{{ asset('') }}plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
    <script src="{{ asset('') }}plugins/jszip/jszip.min.js"></script>
    <script src="{{ asset('') }}plugins/pdfmake/pdfmake.min.js"></script>
    <script src="{{ asset('') }}plugins/pdfmake/vfs_fonts.js"></script>
    <script src="{{ asset('') }}plugins/datatables-buttons/js/buttons.html5.min.js"></script>
    <script src="{{ asset('') }}plugins/datatables-buttons/js/buttons.print.min.js"></script>
    <script src="{{ asset('') }}plugins/datatables-buttons/js/buttons.colVis.min.js"></script>
    <script>
        function tambahProduk() {
            const newArrow = `
        <tr>
                                        <td>
                                            <select name="ProdukId[]"  class="form-control" kode-produk onchange="pilihProduk(this)">
                                                <option value="">Pilih Produk</option>
                                                @foreach ($produks as $produk)
                                                    <option value="{{ $produk->id }}" data-harga="{{ $produk->Harga }}">
                                                        {{ $produk->NamaProduk }}</option>
                                                @endforeach

                                            </select>
                                        </td>
                                        <td>
                                            <input type="text" name="harga[]"  class="form-control harga"
                                                readonly>
                                        </td>
                                        <td>
                                            <input type="number" name="JumlahProduk[]"  class="form-control JumlahProduk" oninput="hitungTotal(this)"
                                                >
                                        </td>
                                        <td>
                                            <input type="text" name="TotalHarga[]"  class="form-control TotalHarga"
                                                readonly>
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-danger" onclick="hapusProduk(this)">Hapus</button>
                                        </td>
                                   </tr>
        `;
            $('#penjualan').append(newArrow);
        }

        function hapusProduk(buttonElement) {
            $(buttonElement).closest('tr').remove();
        }

        function pilihProduk(produk){
            const selectOption = produk.options[produk.selectedIndex];
            const row = $(produk).closest('tr');

            const harga = $(selectOption).data('harga');
            const selectedKode = produk.value;
            if ($(".kode-produk").not(produk).filter((_, el) => el.value === selectedKode).length > 0) {
                alert('produk sudah ada');
                row.find('.kode-produk').val('');
                return;
            }

            row.find('.harga').val(harga);

        }

        function hitungTotal(inputElement) {
            const row = $(inputElement).closest('tr');
            const harga = parseFloat(row.find('.harga').val());
            const jumlah = parseFloat(inputElement.value);
            const total = harga * jumlah;
            row.find('.TotalHarga').val(total);

            hitungTotalAkhir();
        }

        function hitungTotalAkhir()
        {
            let total = 0;

            $('.TotalHarga').each(function() {
                total += parseFloat($(this).val()) || 0;
            });

            $('#total').val(total);
        }
    </script>
@endsection
