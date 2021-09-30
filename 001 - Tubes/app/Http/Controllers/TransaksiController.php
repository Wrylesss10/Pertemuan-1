<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\barang;
use App\Models\user;
use App\Models\transaksi;

class TransaksiController extends Controller
{
    protected $hal = 2;
    
    public function Pembelian()
    {
        $title = "Transaksi Barang Masuk";
        $pembelian = transaksi::join('users', 'users.id', '=', 'transaksis.user_id')
        ->select('users.nama_toko', 'transaksis.*')
        ->orderBy('tanggal_transaksi', 'desc')
        ->where('tipe_transaksi', 1)->paginate($this->hal);
        $notif_pembelian = transaksi::join('users', 'users.id', '=', 'transaksis.user_id')
        ->select('users.nama_toko', 'transaksis.*')
        ->orderBy('tanggal_transaksi', 'desc')
        ->where('tipe_transaksi', 1)->where('is_confirmed', 0)->paginate($this->hal);
        $notif_penjualan = transaksi::join('users', 'users.id', '=', 'transaksis.user_id')
        ->select('users.nama_toko', 'transaksis.*')
        ->orderBy('tanggal_transaksi', 'desc')
        ->where('tipe_transaksi', 2)->where('is_confirmed', 0)->paginate($this->hal);
        return view('admin/Pembelian', ['title' => $title, 'pembelian' => $pembelian ,'notif_pembelian' => $notif_pembelian, 'notif_penjualan' => $notif_penjualan]);
    }

    public function SearchPembelian(Request $keyword)
    {
        $title = "Transaksi Barang Masuk";
        $pembelian = transaksi::when($keyword->keyword, function ($query) use ($keyword) { 
            $query ->where('kode_barang','like',"%". $keyword->keyword ."%")
            ->orwhere('nama_barang','like',"%". $keyword->keyword ."%")
            ->orwhere('nama_toko','like',"%". $keyword->keyword ."%");
        })
        ->join('users', 'users.id', '=', 'transaksis.user_id')
        ->select('users.nama_toko', 'transaksis.*')
        ->orderBy('tanggal_transaksi', 'desc')
        ->where('tipe_transaksi', 1)
        ->paginate($this->hal);
        $notif_pembelian = transaksi::join('users', 'users.id', '=', 'transaksis.user_id')
        ->select('users.nama_toko', 'transaksis.*')
        ->orderBy('tanggal_transaksi', 'desc')
        ->where('tipe_transaksi', 1)->where('is_confirmed', 0)->paginate($this->hal);
        $notif_penjualan = transaksi::join('users', 'users.id', '=', 'transaksis.user_id')
        ->select('users.nama_toko', 'transaksis.*')
        ->orderBy('tanggal_transaksi', 'desc')
        ->where('tipe_transaksi', 2)->where('is_confirmed', 0)->paginate($this->hal);
        return view('admin/Pembelian', ['title' => $title, 'pembelian' => $pembelian ,'notif_pembelian' => $notif_pembelian, 'notif_penjualan' => $notif_penjualan]);
    }

    public function Penjualan()
    {
        $title = "Transaksi Barang Keluar";
        $penjualan = transaksi::join('users', 'users.id', '=', 'transaksis.user_id')
        ->select('users.nama_toko', 'transaksis.*')
        ->orderBy('tanggal_transaksi', 'desc')
        ->where('tipe_transaksi', 2)->paginate($this->hal);
        $notif_pembelian = transaksi::join('users', 'users.id', '=', 'transaksis.user_id')
        ->select('users.nama_toko', 'transaksis.*')
        ->orderBy('tanggal_transaksi', 'desc')
        ->where('tipe_transaksi', 1)->where('is_confirmed', 0)->paginate($this->hal);
        $notif_penjualan = transaksi::join('users', 'users.id', '=', 'transaksis.user_id')
        ->select('users.nama_toko', 'transaksis.*')
        ->orderBy('tanggal_transaksi', 'desc')
        ->where('tipe_transaksi', 2)->where('is_confirmed', 0)->paginate($this->hal);
        return view('admin/Penjualan', ['title' => $title, 'penjualan' => $penjualan ,'notif_pembelian' => $notif_pembelian, 'notif_penjualan' => $notif_penjualan]);
    }
    
    public function SearchPenjualan(Request $keyword)
    {
        $title = "Transaksi Barang Masuk";
        $penjualan = transaksi::when($keyword->keyword, function ($query) use ($keyword) { 
            $query ->where('kode_barang','like',"%". $keyword->keyword ."%")
            ->orwhere('nama_barang','like',"%". $keyword->keyword ."%")
            ->orwhere('nama_toko','like',"%". $keyword->keyword ."%");
        })
        ->join('users', 'users.id', '=', 'transaksis.user_id')
        ->select('users.nama_toko', 'transaksis.*')
        ->orderBy('tanggal_transaksi', 'desc')
        ->where('tipe_transaksi', 2)
        ->paginate($this->hal);
        $notif_pembelian = transaksi::join('users', 'users.id', '=', 'transaksis.user_id')
        ->select('users.nama_toko', 'transaksis.*')
        ->orderBy('tanggal_transaksi', 'desc')
        ->where('tipe_transaksi', 1)->where('is_confirmed', 0)->paginate($this->hal);
        $notif_penjualan = transaksi::join('users', 'users.id', '=', 'transaksis.user_id')
        ->select('users.nama_toko', 'transaksis.*')
        ->orderBy('tanggal_transaksi', 'desc')
        ->where('tipe_transaksi', 2)->where('is_confirmed', 0)->paginate($this->hal);
        return view('admin/Pembelian', ['title' => $title, 'penjualan' => $penjualan ,'notif_pembelian' => $notif_pembelian, 'notif_penjualan' => $notif_penjualan]);
    }


    public function ConfirmedPembelian(transaksi $transaksi)
    {
        DB::table('transaksis')->where('id', $transaksi->id)
        ->update ([
            'tipe_transaksi' => $transaksi->tipe_transaksi,
            'kode_barang' => $transaksi->kode_barang,
            'nama_barang' => $transaksi->nama_barang,
            'kuantitas' => $transaksi->kuantitas,
            'tanggal_transaksi' => $transaksi->tanggal_transaksi,
            'is_confirmed' => 1,
            'user_id' => $transaksi->user_id,
            'alamat' => $transaksi->alamat,
            'created_at' => $transaksi->created_at,
            'updated_at' => $transaksi->updated_at,
        ]);

        $user = user::where('id', $transaksi->user_id)->first();

        $barang = barang::where('kode_barang', $transaksi->kode_barang)->first();

        if( !$barang ){
            barang::create([
                'kode_barang' => $transaksi->kode_barang,
                'nama_barang' => $transaksi->nama_barang,
                'nama_toko' => $user->nama_toko,
                'kuantitas' => $transaksi->kuantitas,
                'user_id' => $transaksi->id,
            ]);
        }else{
            $total_barang = $barang->kuantitas + $transaksi->kuantitas;

            barang::where('kode_barang', $transaksi->kode_barang)
            ->update ([
                'kode_barang' => $transaksi->kode_barang,
                'nama_barang' => $transaksi->nama_barang,
                'nama_toko' => $user->nama_toko,
                'kuantitas' => $total_barang,
                'user_id' => $transaksi->id,
            ]);
        }

        return redirect()->back()->with('status', 'Transaksi telah dikonfirmasi');
    }

    public function RejectPembelian(transaksi $transaksi)
    {
        DB::table('transaksis')->where('id', $transaksi->id)
        ->update ([
            'tipe_transaksi' => $transaksi->tipe_transaksi,
            'kode_barang' => $transaksi->kode_barang,
            'nama_barang' => $transaksi->nama_barang,
            'kuantitas' => $transaksi->kuantitas,
            'tanggal_transaksi' => $transaksi->tanggal_transaksi,
            'is_confirmed' => 2,
            'user_id' => $transaksi->user_id,
            'alamat' => $transaksi->alamat,
            'created_at' => $transaksi->created_at,
            'updated_at' => $transaksi->updated_at,
        ]);

        return redirect()->back()->with('status', 'Transaksi telah dibatalka0');
    }
    
    public function ConfirmedPenjualan (transaksi $transaksi)
    {
        DB::table('transaksis')->where('id', $transaksi->id)
        ->update ([
            'tipe_transaksi' => $transaksi->tipe_transaksi,
            'kode_barang' => $transaksi->kode_barang,
            'nama_barang' => $transaksi->nama_barang,
            'kuantitas' => $transaksi->kuantitas,
            'tanggal_transaksi' => $transaksi->tanggal_transaksi,
            'is_confirmed' => 1,
            'user_id' => $transaksi->user_id,
            'alamat' => $transaksi->alamat,
            'created_at' => $transaksi->created_at,
            'updated_at' => $transaksi->updated_at,
        ]);

        $user = user::where('id', $transaksi->user_id)->first();

        $barang = barang::where('kode_barang', $transaksi->kode_barang)->first();

        $total_barang = $barang->kuantitas - $transaksi->kuantitas;

        if($total_barang == 0){
           $barang->delete();
        }else{
            barang::where('kode_barang', $transaksi->kode_barang)
            ->update ([
                'kode_barang' => $transaksi->kode_barang,
                'nama_barang' => $transaksi->nama_barang,
                'nama_toko' => $user->nama_toko,
                'kuantitas' => $total_barang,
                'user_id' => $transaksi->id,
            ]);
        }
        
        return redirect()->back()->with('status', 'Transaksi telah dikonfirmasi');
    }

    public function RejectPenjualan(transaksi $transaksi)
    {
        DB::table('transaksis')->where('id', $transaksi->id)
        ->update ([
            'tipe_transaksi' => $transaksi->tipe_transaksi,
            'kode_barang' => $transaksi->kode_barang,
            'nama_barang' => $transaksi->nama_barang,
            'kuantitas' => $transaksi->kuantitas,
            'tanggal_transaksi' => $transaksi->tanggal_transaksi,
            'is_confirmed' => 2,
            'user_id' => $transaksi->user_id,
            'alamat' => $transaksi->alamat,
            'created_at' => $transaksi->created_at,
            'updated_at' => $transaksi->updated_at,
        ]);

        return redirect()->back()->with('status', 'Transaksi telah dibatalkan');
    }
    
    public function FormUbahPesanan(transaksi $transaksi)
    {
        $title = 'Form Ubah Pesanan';
        $notif_pembelian = transaksi::join('users', 'users.id', '=', 'transaksis.user_id')
        ->select('users.nama_toko', 'transaksis.*')
        ->orderBy('tanggal_transaksi', 'desc')
        ->where('tipe_transaksi', 1)->where('is_confirmed', 1)->paginate($this->hal);
        $notif_penjualan = transaksi::join('users', 'users.id', '=', 'transaksis.user_id')
        ->select('users.nama_toko', 'transaksis.*')
        ->orderBy('tanggal_transaksi', 'desc')
        ->where('tipe_transaksi', 2)->where('is_confirmed', 1)->paginate($this->hal);
        return view('admin.UbahPesanan', ['title' => $title, 'transaksi' => $transaksi ,'notif_pembelian' => $notif_pembelian, 'notif_penjualan' => $notif_penjualan]);
    }

    public function UbahPesanan(Request $transaksi)
    {
        transaksi::where('id', $transaksi->id)
            ->update ([
                'kode_barang' => $transaksi->kode_barang,
                'nama_barang' => $transaksi->nama_barang,
                'alamat' => $transaksi->alamat,
                'kuantitas' => $transaksi->kuantitas,
                'is_confirmed' => 1,
                'user_id' => $transaksi->user_id,
                'tipe_transaksi' => $transaksi->tipe_transaksi,
                'created_at' => $transaksi->created_at,
                'updated_at' => $transaksi->updated_at,
            ]);

            if($transaksi->tipe_transaksi == 1){
                $user = user::where('id', $transaksi->user_id)->first();
                $barang = barang::where('kode_barang', $transaksi->kode_barang)->first();
        
                if( !$barang ){

                    barang::create([
                        'kode_barang' => $transaksi->kode_barang,
                        'nama_barang' => $transaksi->nama_barang,
                        'nama_toko' => $user->nama_toko,
                        'kuantitas' => $transaksi->kuantitas,
                        'user_id' => $transaksi->id,
                    ]);

                }else{
                    $total_barang = $barang->kuantitas + $transaksi->kuantitas;

                    barang::where('kode_barang', $transaksi->kode_barang)
                    ->update ([
                        'kode_barang' => $transaksi->kode_barang,
                        'nama_barang' => $transaksi->nama_barang,
                        'nama_toko' => $user->nama_toko,
                        'kuantitas' => $total_barang,
                        'user_id' => $transaksi->id,
                    ]);

                }

            }else{
                $user = user::where('id', $transaksi->user_id)->first();

                $barang = barang::where('kode_barang', $transaksi->kode_barang)->first();

                $total_barang = $barang->kuantitas - $transaksi->kuantitas;

                if($total_barang == 0){
                $barang->delete();
                }else{
                    barang::where('kode_barang', $transaksi->kode_barang)
                    ->update ([
                        'kode_barang' => $transaksi->kode_barang,
                        'nama_barang' => $transaksi->nama_barang,
                        'nama_toko' => $user->nama_toko,
                        'kuantitas' => $total_barang,
                        'user_id' => $transaksi->id,
                    ]);
                }
            }

            return redirect()->back()->with('status', 'Transaksi telah Berhasil diubah');
    }

    /*
    public function Laporan()
    {
        $title = "Laporan";
        return view('Admin/Laporan', ['title' => $title]);
    }
    */
}