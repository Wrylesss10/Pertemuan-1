<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;
use App\Models\user;
use App\Models\transaksi;
use App\Models\barang;
use App\Models\produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function Profile()
    {
        $title = 'Your Profile';
        return view('supplier.ProfileSupp', ['title' => $title]);
    }

    public function Produk()
    {
        $title = 'Daftar Produk yang Tersedia';
        $produk  = barang::all();
        return view('customer.Produk', ['title' => $title, 'produk' => $produk]);
    }

    public function Beli(barang $barang)
    {
        $title = 'Form Beli Barang';
        //return $barang;
        return view('customer.Beli', ['title' => $title, 'barang' => $barang ]);
    }

    public function ProsesBeli(Request $beli)
    {
        $produk = barang::where('id', $beli->id)->first();

        if( $produk->kuantitas < $beli->kuantitas)
        {
            return redirect()->back()->with('status_tidak_cukup', 'Kuantitas yang dimasukkan tidak cukup');
        }else{
            transaksi::create([
            'tipe_transaksi' => 2,
            'kode_barang' => $beli->kode_barang,
            'nama_barang' => $beli->nama_barang,
            'alamat' => Auth::user()->alamat,
            'kuantitas' => $beli->kuantitas,
            'is_confirmed' => 0,
            'user_id' => Auth::user()->id
            ]);

            return redirect()->back()->with('status', 'Transaksi telah dikonfirmasi');
        }
        
    }

    public function History()
    {
        $title = 'History Transaksi';
        $history = transaksi::where('user_id', Auth::user()->id)->orderBy('tanggal_transaksi', 'desc')->get();
        return view('customer.History', ['title'=> $title, 'history' => $history]);
    }

    public function Transaksi()
    {
        $title = 'Your Transaksi';
        $transaksi = transaksi::where('user_id', Auth::user()->id)->where('is_confirmed', '1')->orderBy('tanggal_transaksi', 'desc')->get();
        return view('customer.Transaksi', ['title'=> $title, 'transaksi' => $transaksi]);
    }

    public function EditProfile()
    {
        $title = 'Edit Profile';
        $user = Auth::user();
        return view('supplier.EditProfile', ['title' => $title, 'user' => $user]);
    }

}