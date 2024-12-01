<?php

namespace App\Http\Controllers;

use App\Models\LogStok;
use App\Models\Produk;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Milon\Barcode\Facades\DNS1DFacade;

class ProdukController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $title = 'produk';
        $subtitle = 'index';
        $produks = Produk::all();
        return view('admin.produk.index', compact('title', 'subtitle', 'produks'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $title = 'produk';
        $subtitle = 'create';
        return view('admin.produk.create', compact('title', 'subtitle'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate = $request->validate([
            'NamaProduk' => 'required',
            'Harga' => 'required|numeric',
            'Stok' => 'required|numeric',
        ]);
        $validate['user_id'] = Auth::user()->id;
        $simpan = Produk::create($validate);
        if ($simpan) {
            return response()->json(['status' => 200, 'message' => 'Produk Berhasil']);
        } else {
            return response()->json(['status' => 500, 'message' => 'Produk Gagal!']);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Produk $produk)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $title = 'produk';
        $subtitle = 'Edit';
        $produk = Produk::find($id);

        return view('admin.produk.edit', compact('title', 'subtitle', 'produk'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Produk $produk)
    {
        $validate = $request->validate([
            'NamaProduk' => 'required',
            'Harga' => 'required|numeric',
            'Stok' => 'required|numeric',
        ]);
        $validate['user_id'] = Auth::user()->id;
        $simpan = $produk->update($validate);
        if ($simpan) {
            return response()->json(['status' => 200, 'message' => 'Produk Berhasil Diubah']);
        } else {
            return response()->json(['status' => 500, 'message' => 'Produk Gagal']);
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $produk = Produk::find($id);
        $delete = $produk->delete();
        if ($delete) {
            return redirect(route('produk.index'))->with('success', 'Produk Berhasil Dihapus');
        } else {
            return redirect(route('produk.index'))->with('error', 'Produk Gagal Dihapus');
        }
    }

    public function tambahStok($id)
    {
        $title = 'produk';
        $subtitle = 'Tambah Stok';
        $produk = Produk::find($id);

        return view('admin.produk.tambahStok', compact('title', 'subtitle', 'produk'));
    }

    public function updateStok(Request $request, $id)
    {
        $request->validate([
            'Stok' => 'required|numeric|min:1',
        ]);

        $produk = Produk::find($id);

        if (!$produk) {
            return response()->json(['status' => 404, 'message' => 'Produk tidak ditemukan']);
        }

        $produk->Stok += $request->Stok;

        if ($produk->save()) {
            return response()->json(['status' => 200, 'message' => 'Stok Produk Berhasil Ditambahkan']);
        } else {
            return response()->json(['status' => 500, 'message' => 'Gagal Menambahkan Stok Produk']);
        }
    }

    public function logProduk()
    {
        $title = 'produk';
        $subtitle = 'Log Produk';
        $Produks = LogStok::join('produks', 'log_stoks.ProdukId', '=', 'produks.id')
            ->join('users', 'log_stoks.user_id', '=', 'users.id')
            ->select('log_stoks.JumlahProduk', 'log_stoks.created_at', 'produks.NamaProduk', 'users.name')->get();
        return view('admin.produk.logProduk', compact('title', 'subtitle', 'Produks'));
    }

    public function cetakLabel(Request $request)
    {
        $id_produk = $request->id_produk;
        $barcodes = [];

        if (is_array($id_produk)) {
            foreach ($id_produk as $id) {
                $id = (string)$id;
                $harga = Produk::find($id)->Harga;
                $barcode = DNS1DFacade::getBarcodeHTML($id, 'C128');
                $barcodes[] = ['barcode' => $barcode, 'harga' => $harga];
            }
        } else {
            $id_produk = (string) $id_produk;
            $harga = Produk::find($id_produk)->Harga;
            $barcode = DNS1DFacade::getBarcodeHTML($id_produk, 'C128');
            $barcodes[] = ['barcode' => $barcode, 'harga' => $harga];
        }

        $pdf = Pdf::loadView('admin.produk.cetaklabel', compact('barcodes'));

        // Tentukan path file untuk menyimpan PDF
        $file_path = storage_path('app/public/barcodes.pdf');
        $pdf->save($file_path);

        return response()->json(['url' => asset('storage/barcodes.pdf')]);
    }
}
