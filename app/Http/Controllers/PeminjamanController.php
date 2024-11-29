<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PeminjamanController extends Controller
{
    // Menampilkan form peminjaman barang
    public function create()
    {
        $items = Item::where('quantity', '>', 0)
            ->where('status', 'tersedia')
            ->get();

        return view('peminjaman.create', compact('items'));
    }

    // Menyimpan data peminjaman
    public function store(Request $request)
    {
        // Validasi data input
        $request->validate([
            'tanggal_pinjam' => 'required|date',
            'tanggal_kembali' => 'nullable|date|after:tanggal_pinjam',
            'items' => 'required|array|min:1',
            'items.*.id' => 'required|exists:item_inventory_table_v4,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        DB::beginTransaction();

        try {
            // Buat data peminjaman
            $peminjaman = new Peminjaman();
            $peminjaman->user_id = Auth::id();
            $peminjaman->tanggal_pinjam = $request->tanggal_pinjam;

            // Jika ada barang bertipe "jangka panjang", tanggal_kembali wajib
            $containsLongTermItem = false;
            foreach ($request->items as $item) {
                $itemModel = Item::find($item['id']);
                if (strtolower($itemModel->category_name) === 'barang jangka panjang') {
                    $containsLongTermItem = true;
                }
            }

            if ($containsLongTermItem && empty($request->tanggal_kembali)) {
                return redirect()->back()->withErrors(['tanggal_kembali' => 'Tanggal pengembalian wajib diisi untuk barang jangka panjang.']);
            }

            $peminjaman->tanggal_kembali = $request->tanggal_kembali;
            $peminjaman->status = 'menunggu';
            $peminjaman->save();

            // Attach items dengan quantity
            foreach ($request->items as $item) {
                $peminjaman->items()->attach($item['id'], ['quantity' => $item['quantity']]);
                // Kurangi stok barang
                $itemModel = Item::find($item['id']);
                $itemModel->decrement('quantity', $item['quantity']);
            }

            DB::commit();
            return redirect()->route('PeminjamanInventaris')->with('success', 'Peminjaman berhasil diajukan.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage());
        }
    }

    // Menampilkan data barang yang tersedia
    public function index()
    {
        $items = Item::where('quantity', '>', 0)
            ->orderBy('item_name')
            ->get();

        return view('layouts.PeminjamanInventaris', compact('items'));
    }

    // Menampilkan riwayat peminjaman
    public function history()
    {
        $peminjaman = Peminjaman::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('peminjaman.history', compact('peminjaman'));
    }

    // Pencarian barang
    public function searchItems(Request $request)
    {
        $search = $request->search;

        $items = Item::where('quantity', '>', 0)
            ->where(function ($query) use ($search) {
                $query->where('item_code', 'LIKE', "%{$search}%")
                    ->orWhere('item_name', 'LIKE', "%{$search}%")
                    ->orWhere('category_name', 'LIKE', "%{$search}%");
            })
            ->get();

        return response()->json($items->map(function ($item) {
            return [
                'id' => $item->id,
                'item_name' => $item->item_name,
                'quantity' => $item->quantity,
                'type_name' => $item->type_name,
                'category_name' => $item->category_name,
            ];
        }));
    }

    // Method reject untuk menolak peminjaman
    public function reject(Request $request, $id)
    {
        $request->validate([
            'keterangan' => 'required|string|max:255'
        ], [
            'keterangan.required' => 'Keterangan penolakan wajib diisi'
        ]);

        DB::beginTransaction();
        try {
            $peminjaman = Peminjaman::findOrFail($id);
            
            // Update status dan keterangan
            $peminjaman->status = 'ditolak';
            $peminjaman->keterangan = $request->keterangan;
            $peminjaman->rejected_by = Auth::id();
            $peminjaman->rejected_at = now();
            $peminjaman->save();

            // Kembalikan stok barang
            foreach ($peminjaman->items as $item) {
                $item->increment('quantity', $item->pivot->quantity);
            }

            DB::commit();
            return redirect()->back()->with('success', 'Peminjaman berhasil ditolak.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menolak peminjaman: ' . $e->getMessage());
        }
    }

    // Tambahkan method approve untuk menyetujui peminjaman
    public function approve(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $peminjaman = Peminjaman::findOrFail($id);
            
            // Cek apakah user memiliki peminjaman yang belum dikembalikan
            $hasUnreturnedItems = Peminjaman::where('user_id', $peminjaman->user_id)
                ->whereIn('status', ['dipinjam', 'menunggu pengembalian', 'terlambat'])
                ->exists();

            if ($hasUnreturnedItems) {
                return redirect()->back()->with('error', 'Tidak dapat menyetujui peminjaman. User masih memiliki peminjaman yang belum dikembalikan.');
            }

            // Cek apakah ada barang jangka panjang
            $hasLongTermItem = $peminjaman->items->contains(function($item) {
                return strtolower($item->category_name) === 'barang jangka panjang';
            });

            // Set status berdasarkan jenis barang
            if ($hasLongTermItem) {
                // Jika ada barang jangka panjang, set status menunggu pengembalian
                $peminjaman->status = 'menunggu pengembalian';
                
                // Pastikan tanggal kembali ada untuk barang jangka panjang
                if (empty($peminjaman->tanggal_kembali)) {
                    return redirect()->back()->with('error', 'Tanggal pengembalian wajib diisi untuk barang jangka panjang.');
                }

                // Cek apakah sudah melewati tanggal kembali
                if (now() > \Carbon\Carbon::parse($peminjaman->tanggal_kembali)) {
                    $peminjaman->status = 'terlambat';
                }
            } else {
                // Jika semua barang habis pakai, set status selesai
                $peminjaman->status = 'selesai';
            }

            // Catat admin yang menyetujui
            $peminjaman->approved_by = Auth::id();
            $peminjaman->approved_at = now();
            
            $peminjaman->save();

            DB::commit();
            return redirect()->back()->with('success', 'Peminjaman berhasil disetujui.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menyetujui peminjaman: ' . $e->getMessage());
        }
    }

    // Tambahkan method untuk mengembalikan barang
    public function return(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $peminjaman = Peminjaman::findOrFail($id);
            
            // Update status menjadi selesai
            $peminjaman->status = 'selesai';
            $peminjaman->returned_by = Auth::id();
            $peminjaman->returned_at = now();
            $peminjaman->save();

            // Untuk barang jangka panjang, kembalikan stok
            foreach ($peminjaman->items as $item) {
                if (strtolower($item->category_name) === 'barang jangka panjang') {
                    $item->increment('quantity', $item->pivot->quantity);
                }
            }

            DB::commit();
            return redirect()->back()->with('success', 'Peminjaman telah selesai.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
