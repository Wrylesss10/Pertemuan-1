<?php

namespace App\Http\Controllers;
use App\Models\Admin;
use App\Models\user;
use App\Models\barang;
use App\Models\transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    protected $hal = 3;

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function IsiDataDiri()
    {
        return view('admin.IsiData');
    }

    public function UpdateDataDiri(Request $user)
    {
        user::where('id', $user->id)
        ->update ([
            'name' => $user->name,
            'email' => $user->email,
            'password' => $user->password,
            'nama_toko' => $user->nama_toko,
            'nama_pemilik' => $user->nama_pemilik,
            'no_telp' => $user->no_telp,
            'alamat' => $user->alamat,
            'is_active' => $user->is_active,
            'role' => $user->role,
        ]);

        if($user->role == 'customer'){
            return redirect('/admin')->with('status', 'Data toko berhasil ditambahkan');
        }else{
            return redirect('/supplier')->with('status', 'Data toko berhasil ditambahkan');
        }

    }

    public function UserManage()
    {
        $title = "Daftar User";
        $user = user::paginate($this->hal);
        $notif_pembelian = transaksi::join('users', 'users.id', '=', 'transaksis.user_id')
        ->select('users.nama_toko', 'transaksis.*')
        ->orderBy('tanggal_transaksi', 'desc')
        ->where('tipe_transaksi', 1)->where('is_confirmed', 0)->paginate(3);
        $notif_penjualan = transaksi::join('users', 'users.id', '=', 'transaksis.user_id')
        ->select('users.nama_toko', 'transaksis.*')
        ->orderBy('tanggal_transaksi', 'desc')
        ->where('tipe_transaksi', 2)->where('is_confirmed', 0)->paginate(3);
        return view('/admin/UserManage', ['title' => $title ,'users' => $user, 'notif_pembelian' => $notif_pembelian, 'notif_penjualan' => $notif_penjualan]);
    }

    public function SearchUser(Request $keyword)
    {
        $title = "Daftar User";
        $users = user::when($keyword->keyword, function ($query) use ($keyword) { 
            $query ->where('name','like',"%". $keyword->keyword ."%")
            ->orwhere('email','like',"%". $keyword->keyword ."%")
            ->orwhere('nama_pemilik','like',"%". $keyword->keyword ."%")
            ->orwhere('nama_toko','like',"%". $keyword->keyword ."%");
        })->paginate($this->hal);
        $notif_pembelian = transaksi::join('users', 'users.id', '=', 'transaksis.user_id')
        ->select('users.nama_toko', 'transaksis.*')
        ->orderBy('tanggal_transaksi', 'desc')
        ->where('tipe_transaksi', 1)->where('is_confirmed', 0)->paginate(3);
        $notif_penjualan = transaksi::join('users', 'users.id', '=', 'transaksis.user_id')
        ->select('users.nama_toko', 'transaksis.*')
        ->orderBy('tanggal_transaksi', 'desc')
        ->where('tipe_transaksi', 2)->where('is_confirmed', 0)->paginate(3);
        return view('/admin/UserManage', ['title' => $title ,'users' => $users , 'notif_pembelian' => $notif_pembelian, 'notif_penjualan' => $notif_penjualan]);
    }

    public function Detail(user $user)
    {
        $title = 'Profile User';
        $notif_pembelian = transaksi::join('users', 'users.id', '=', 'transaksis.user_id')
        ->select('users.nama_toko', 'transaksis.*')
        ->orderBy('tanggal_transaksi', 'desc')
        ->where('tipe_transaksi', 1)->where('is_confirmed', 0)->paginate(3);
        $notif_penjualan = transaksi::join('users', 'users.id', '=', 'transaksis.user_id')
        ->select('users.nama_toko', 'transaksis.*')
        ->orderBy('tanggal_transaksi', 'desc')
        ->where('tipe_transaksi', 2)->where('is_confirmed', 0)->paginate(3);
        return view('/admin/DetailUser', ['title' => $title, 'user' => $user , 'notif_pembelian' => $notif_pembelian, 'notif_penjualan' => $notif_penjualan]);
    }

    public function DisableUser(user $user)
    {
        user::where('id', $user->id)
        ->update ([
            'name' => $user->name,
            'email' => $user->email,
            'password' => $user->password,
            'nama_toko' => $user->nama_toko,
            'nama_pemilik' => $user->nama_pemilik,
            'no_telp' => $user->no_telp,
            'alamat' => $user->alamat,
            'is_active' => 0,
            'role' => $user->role,
            'created_at' => $user->created_at,
            'updated_at' => $user->updated_at
        ]);

        return redirect()->back()->with('status', 'Akun telah berhasil di Disable');
    }

    public function UndisableUser(user $user)
    {
        user::where('id', $user->id)
        ->update ([
            'name' => $user->name,
            'email' => $user->email,
            'password' => $user->password,
            'nama_toko' => $user->nama_toko,
            'nama_pemilik' => $user->nama_pemilik,
            'no_telp' => $user->no_telp,
            'alamat' => $user->alamat,
            'is_active' => 1,
            'role' => $user->role,
            'created_at' => $user->created_at,
            'updated_at' => $user->updated_at
        ]);

        return redirect()->back()->with('status', 'Akun telah berhasil di Aktifkan');
    }

    public function EditProfile(user $user)
    {
        $title = 'Edit Profile';
        $notif_pembelian = transaksi::join('users', 'users.id', '=', 'transaksis.user_id')
        ->select('users.nama_toko', 'transaksis.*')
        ->orderBy('tanggal_transaksi', 'desc')
        ->where('tipe_transaksi', 1)->where('is_confirmed', 0)->paginate(3);
        $notif_penjualan = transaksi::join('users', 'users.id', '=', 'transaksis.user_id')
        ->select('users.nama_toko', 'transaksis.*')
        ->orderBy('tanggal_transaksi', 'desc')
        ->where('tipe_transaksi', 2)->where('is_confirmed', 0)->paginate(3);
        return view('admin/EditProfile', ['title' => $title, 'user' => $user, 'notif_pembelian' => $notif_pembelian, 'notif_penjualan' => $notif_penjualan ]);
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
            'password' => $user->password,
            'nama_toko' => $user->nama_toko,
            'nama_pemilik' => $user->nama_pemilik,
            'no_telp' => $user->no_telp,
            'alamat' => $user->alamat,
            'is_active' => $user->is_active,
            'role_id' => $user->role_id,
            'created_at' => $user->created_at,
            'updated_at' => $user->updated_at
        ]);

        return redirect()->back()->with('status', 'Akun berhasil diubah');
    }

    public function StockBarang()
    {
        $title = "Stock Barang";
        $barang = barang::paginate($this->hal);
        $notif_pembelian = transaksi::join('users', 'users.id', '=', 'transaksis.user_id')
        ->select('users.nama_toko', 'transaksis.*')
        ->orderBy('tanggal_transaksi', 'desc')
        ->where('tipe_transaksi', 1)->where('is_confirmed', 0)->paginate(3);
        $notif_penjualan = transaksi::join('users', 'users.id', '=', 'transaksis.user_id')
        ->select('users.nama_toko', 'transaksis.*')
        ->orderBy('tanggal_transaksi', 'desc')
        ->where('tipe_transaksi', 2)->where('is_confirmed', 0)->paginate(3);
        return view('admin/StockBarang', ['title' => $title, 'barang' => $barang , 'notif_pembelian' => $notif_pembelian, 'notif_penjualan' => $notif_penjualan]);
    }

    public function SearchStockBarang(Request $keyword)
    {
        $title = "Stock Barang";
        $barang = barang::when($keyword->keyword, function ($query) use ($keyword) { 
            $query ->where('kode_barang','like',"%". $keyword->keyword ."%")
            ->orwhere('nama_barang','like',"%". $keyword->keyword ."%");
        })
        ->paginate($this->hal);
        $notif_pembelian = transaksi::join('users', 'users.id', '=', 'transaksis.user_id')
        ->select('users.nama_toko', 'transaksis.*')
        ->orderBy('tanggal_transaksi', 'desc')
        ->where('tipe_transaksi', 1)->where('is_confirmed', 0)->paginate(3);
        $notif_penjualan = transaksi::join('users', 'users.id', '=', 'transaksis.user_id')
        ->select('users.nama_toko', 'transaksis.*')
        ->orderBy('tanggal_transaksi', 'desc')
        ->where('tipe_transaksi', 2)->where('is_confirmed', 0)->paginate(3);
        return view('admin/StockBarang', ['title' => $title, 'barang' => $barang , 'notif_pembelian' => $notif_pembelian, 'notif_penjualan' => $notif_penjualan]);
    }

    public function Supplier()
    {
        $title = "Daftar Supplier";
        $supplier = user::where('role', 'supplier')->paginate($this->hal);
        $notif_pembelian = transaksi::join('users', 'users.id', '=', 'transaksis.user_id')
        ->select('users.nama_toko', 'transaksis.*')
        ->orderBy('tanggal_transaksi', 'desc')
        ->where('tipe_transaksi', 1)->where('is_confirmed', 0)->paginate(3);
        $notif_penjualan = transaksi::join('users', 'users.id', '=', 'transaksis.user_id')
        ->select('users.nama_toko', 'transaksis.*')
        ->orderBy('tanggal_transaksi', 'desc')
        ->where('tipe_transaksi', 2)->where('is_confirmed', 0)->paginate(3);
        return view('admin/Supplier', ['title' => $title, 'supplier' => $supplier , 'notif_pembelian' => $notif_pembelian, 'notif_penjualan' => $notif_penjualan]);
    }

    public function SearchSupplier(Request $keyword)
    {
        $title = "Daftar User";
        $supplier = user::when($keyword->keyword, function ($query) use ($keyword) { 
            $query ->where('name','like',"%". $keyword->keyword ."%")
            ->orwhere('email','like',"%". $keyword->keyword ."%")
            ->orwhere('nama_pemilik','like',"%". $keyword->keyword ."%")
            ->orwhere('nama_toko','like',"%". $keyword->keyword ."%");
        })->paginate($this->hal);
        $notif_pembelian = transaksi::join('users', 'users.id', '=', 'transaksis.user_id')
        ->select('users.nama_toko', 'transaksis.*')
        ->orderBy('tanggal_transaksi', 'desc')
        ->where('tipe_transaksi', 1)->where('is_confirmed', 1)->paginate(3);
        $notif_penjualan = transaksi::join('users', 'users.id', '=', 'transaksis.user_id')
        ->select('users.nama_toko', 'transaksis.*')
        ->orderBy('tanggal_transaksi', 'desc')
        ->where('tipe_transaksi', 2)->where('is_confirmed', 1)->paginate(3);
        return view('admin/Supplier', ['title' => $title, 'supplier' => $supplier , 'notif_pembelian' => $notif_pembelian, 'notif_penjualan' => $notif_penjualan]);
    }
    
    public function Pelanggan()
    {
        $title = "Daftar Pelanggan";
        $pelanggan = user::where('role', 'customer')->paginate($this->hal);
        $notif_pembelian = transaksi::join('users', 'users.id', '=', 'transaksis.user_id')
        ->select('users.nama_toko', 'transaksis.*')
        ->orderBy('tanggal_transaksi', 'desc')
        ->where('tipe_transaksi', 1)->where('is_confirmed', 0)->paginate(3);
        $notif_penjualan = transaksi::join('users', 'users.id', '=', 'transaksis.user_id')
        ->select('users.nama_toko', 'transaksis.*')
        ->orderBy('tanggal_transaksi', 'desc')
        ->where('tipe_transaksi', 2)->where('is_confirmed', 0)->paginate(3);
        return view('admin/Pelanggan', ['title' => $title, 'pelanggan' => $pelanggan , 'notif_pembelian' => $notif_pembelian, 'notif_penjualan' => $notif_penjualan]);
    }
    
    public function SearchPelanggan(Request $keyword)
    {
        $title = "Daftar Pelanggan";
        $pelanggan = user::when($keyword->keyword, function ($query) use ($keyword) { 
            $query ->where('name','like',"%". $keyword->keyword ."%")
            ->orwhere('nama_pemilik','like',"%". $keyword->keyword ."%");
        })->paginate($this->hal);
        $notif_pembelian = transaksi::join('users', 'users.id', '=', 'transaksis.user_id')
        ->select('users.nama_toko', 'transaksis.*')
        ->orderBy('tanggal_transaksi', 'desc')
        ->where('tipe_transaksi', 1)->where('is_confirmed', 0)->paginate(3);
        $notif_penjualan = transaksi::join('users', 'users.id', '=', 'transaksis.user_id')
        ->select('users.nama_toko', 'transaksis.*')
        ->orderBy('tanggal_transaksi', 'desc')
        ->where('tipe_transaksi', 2)->where('is_confirmed', 0)->paginate(3);
        return view('admin/Pelanggan', ['title' => $title, 'pelanggan' => $pelanggan , 'notif_pembelian' => $notif_pembelian, 'notif_penjualan' => $notif_penjualan]);
    }

    public function Chat()
    {
        $users = user::all();
        return view('chat', ['users'=> $users]);
    }

}
