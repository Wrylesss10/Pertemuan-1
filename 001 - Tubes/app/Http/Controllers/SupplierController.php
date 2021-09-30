<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;
use App\Models\user;
use App\Models\transaksi;
use App\Models\barang;
use App\Models\produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

Auth::routes();

class SupplierController extends Controller
{

    protected $hal = 3;

    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function Profile()
    {
        $title = 'Your Profile';
        return view('supplier.ProfileSupp', ['title' => $title]);
    }
    
    public function History()
    {
        $title = 'Daftar Transaksi Penjualan';
        $history = transaksi::where('user_id', Auth::user()->id)->orderBy('tanggal_transaksi', 'desc')->paginate($this->hal);
        return view('supplier.History',['title' => $title, 'history' => $history]);
    }
    
    public function SearchHistory(Request $keyword)
    {
        $title = 'Daftar Transaksi';
        $transaksi = transaksi::when($keyword->keyword, function ($query) use ($keyword) { 
            $query ->where('nama_barang','like',"%". $keyword->keyword ."%")
            ->orwhere('kode_barang','like',"%". $keyword->keyword ."%")
            ->orwhere('kuanttias','like',"%". $keyword->keyword ."%")
            ->orwhere('tanggal_transaksi','like',"%". $keyword->keyword ."%"); 
        } )
        ->where('user_id', Auth::user()->id)->orderBy('tanggal_transaksi', 'desc')
        ->paginate($this->hal);
        return view('supplier.Transaksi', ['title' => $title, 'transaksi' => $transaksi]);
    }

    public function EditProfile(user $user)
    {
        $title = 'Edit Profile';
        $user = Auth::user();
        return view('supplier.EditProfile', ['title' => $title, 'user' => $user]);
    }


    public function Edit(Request $user)
    {
        $user->validate([
            'name' => 'required',
            'email' => 'required|email:rfc,dns',
            'password' => 'min:3|required',
            'konfirmasi_password' => 'required|same:password'
        ]);

        user::where('id', $user->id)
        ->update ([
            'name' => $user->name,
            'email' => $user->email,
            'password' => Hash::make($user->password),
            'nama_toko' => $user->nama_toko,
            'nama_pemilik' => $user->nama_pemilik,
            'no_telp' => $user->no_telp,
            'alamat' => $user->alamat,
            'is_active' => $user->is_active,
            'role' => $user->role,
            'created_at' => $user->created_at,
            'updated_at' => $user->updated_at
        ]);

        return redirect()->back()->with('status', 'Akun berhasil diubah');
    }

    public function TambahProduk()
    {
        $title = 'Form Tambah Produk';
        return view('supplier.TambahProduk', ['title' => $title]);
    }

    public function AddProduk(Request $transaksi)
    {
        $title = 'Form Tambah Barang';

        $transaksi->validate([
            'kode_barang' => 'required',
            'nama_barang' => 'required',
            'kuantitas' => 'required | integer',
        ]);

        transaksi::create([
            'user_id' => $transaksi->user_id,
            'tipe_transaksi' => 1,
            'kode_barang' => $transaksi->kode_barang,
            'nama_barang' => $transaksi->nama_barang,
            'kuantitas' => $transaksi->kuantitas,
            'alamat' => $transaksi->alamat,
            'is_confirmed' => 0,
            'created_at' => $transaksi->created_at,
            'updated_at' => $transaksi->updated_at,
        ]);

        $produk = produk::where('kode_barang', $transaksi->kode_barang)->first();
        if(!$produk){
            produk::create([
                'user_id' => $transaksi->user_id,
                'kode_barang' => $transaksi->kode_barang,
                'nama_barang' => $transaksi->nama_barang,
            ]);
        }
        return redirect()->back()->with('status', 'Barang anda telah berhasil ditambahkan!!');
    }

    public function Produk()
    {
        $title = 'Daftar Produk';
        $produks = produk::paginate($this->hal);
        return view('supplier.Produk', ['title' => $title, 'produks' => $produks ]);
    }
    
    public function SearchProduk(Request $keyword)
    {
        $title = 'Daftar Produk';
        $produks = produk::when($keyword->keyword, function ($query) use ($keyword) { 
            $query ->where('nama_barang','like',"%". $keyword->keyword ."%")
            ->orwhere('kode_barang','like',"%". $keyword->keyword ."%"); } )->paginate($this->hal);
        return view('supplier.Produk', ['title' => $title, 'produks' => $produks] );
    }

    public function Transaksi()
    {
        $title = 'Daftar Transaksi';
        $transaksi = transaksi::where('user_id', Auth::user()->id)->where('is_confirmed' , 1)->orderBy('tanggal_transaksi', 'desc')->paginate($this->hal);
        return view('supplier.Transaksi', ['title' => $title, 'transaksi' => $transaksi]);
    }

    public function SearchTransaksi(Request $keyword)
    {
        $title = 'Daftar Transaksi';
        $transaksi = transaksi::when($keyword->keyword, function ($query) use ($keyword) { 
            $query ->where('nama_barang','like',"%". $keyword->keyword ."%")
            ->orwhere('kode_barang','like',"%". $keyword->keyword ."%")
            ->orwhere('kuanttias','like',"%". $keyword->keyword ."%")
            ->orwhere('tanggal_transaksi','like',"%". $keyword->keyword ."%"); 
        } )
        ->where('user_id', Auth::user()->id)->where('is_confirmed' , 1)->orderBy('tanggal_transaksi', 'desc')
        ->paginate($this->hal);
        return view('supplier.Transaksi', ['title' => $title, 'transaksi' => $transaksi]);
    }

}