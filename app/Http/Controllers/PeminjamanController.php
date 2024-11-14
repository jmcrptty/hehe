<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class PeminjamanController extends Controller
{
    public function approve($id)
    {
        DB::beginTransaction();
        try {
            $peminjaman = Peminjaman::findOrFail($id);
            
            // Debug log
            Log::info('Approving peminjaman', [
                'peminjaman_id' => $id,
                'current_status' => $peminjaman->status,
                'user_id' => Auth::id()
            ]);

            $peminjaman->status = 'disetujui';
            $peminjaman->approved_by = Auth::id();
            $peminjaman->approved_at = Carbon::now();
            $peminjaman->save();

            // Debug log setelah update
            Log::info('Peminjaman approved', [
                'peminjaman_id' => $id,
                'new_status' => $peminjaman->status
            ]);

            DB::commit();
            return redirect()->back()->with('success', 'Peminjaman berhasil disetujui');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error approving peminjaman', [
                'peminjaman_id' => $id,
                'error' => $e->getMessage()
            ]);
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menyetujui peminjaman');
        }
    }

    public function confirmPickup($id)
    {
        DB::beginTransaction();
        try {
            $peminjaman = Peminjaman::findOrFail($id);
            $peminjaman->status = 'dipinjam';
            $peminjaman->save();

            foreach ($peminjaman->items as $item) {
                $itemModel = Item::find($item->id);
                $itemModel->quantity -= $item->pivot->quantity;
                $itemModel->save();
                $itemModel->updateStatus();
            }
            
            DB::commit();
            return redirect()->back()->with('success', 'Barang berhasil diambil');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Terjadi kesalahan');
        }
    }

    public function return($id)
    {
        DB::beginTransaction();
        try {
            $peminjaman = Peminjaman::findOrFail($id);
            
            foreach ($peminjaman->items as $item) {
                $itemModel = Item::find($item->id);
                
                if ($itemModel->type === 'jangka_panjang') {
                    $itemModel->quantity += $item->pivot->quantity;
                    $itemModel->save();
                    $itemModel->updateStatus();
                    
                    // Update status di pivot table
                    $peminjaman->items()->updateExistingPivot($item->id, ['status' => 'dikembalikan']);
                }
            }

            $peminjaman->status = 'selesai';
            $peminjaman->tanggal_kembali = now();
            $peminjaman->save();
            
            DB::commit();
            return redirect()->back()->with('success', 'Pengembalian berhasil');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Terjadi kesalahan');
        }
    }

    public function restock(Request $request, $itemId)
    {
        DB::beginTransaction();
        try {
            $item = Item::findOrFail($itemId);
            $item->quantity += $request->quantity;
            $item->save();
            $item->updateStatus();
            
            DB::commit();
            return redirect()->back()->with('success', 'Restok berhasil');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Terjadi kesalahan');
        }
    }

    public function reject(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $request->validate([
                'keterangan' => 'required|string|max:255'
            ]);

            $peminjaman = Peminjaman::findOrFail($id);
            
            // Pastikan status masih menunggu
            if ($peminjaman->status !== 'menunggu') {
                return redirect()->back()->with('error', 'Status peminjaman sudah berubah');
            }

            $peminjaman->status = 'ditolak';
            $peminjaman->keterangan = $request->keterangan;
            $peminjaman->rejected_by = Auth::id();
            $peminjaman->rejected_at = Carbon::now();
            $peminjaman->save();

            DB::commit();
            return redirect()->back()->with('success', 'Peminjaman berhasil ditolak');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error rejecting peminjaman: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menolak peminjaman');
        }
    }

    public function create()
    {
        $items = Item::where('quantity', '>', 0)
                     ->where('status', 'tersedia')
                     ->get();
        return view('peminjaman.create', compact('items'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal_pinjam' => 'required|date',
            'tanggal_kembali' => 'required|date|after:tanggal_pinjam',
            'items' => 'required|array',
            'items.*.id' => 'required|exists:items,id',
            'items.*.quantity' => 'required|integer|min:1'
        ]);

        $peminjaman = new Peminjaman();
        $peminjaman->user_id = Auth::id();
        $peminjaman->tanggal_pinjam = Carbon::now();
        if ($request->tanggal_kembali) {
            $peminjaman->tanggal_kembali = Carbon::parse($request->tanggal_kembali);
        }
        $peminjaman->status = 'menunggu';
        $peminjaman->save();

        // Attach items dengan quantity
        foreach ($request->items as $item) {
            $peminjaman->items()->attach($item['id'], ['quantity' => $item['quantity']]);
        }

        return redirect()->route('user.dashboard')->with('success', 'Peminjaman berhasil diajukan dan menunggu persetujuan');
    }

    public function history()
    {
        $peminjaman = Peminjaman::where('user_id', Auth::id())
                               ->orderBy('created_at', 'desc')
                               ->get();
        return view('peminjaman.history', compact('peminjaman'));
    }

    public function index()
    {
        // Ambil semua data barang yang tersedia
        $items = DB::table('item_inventory_table_v4')
                  ->where('quantity', '>', 0)
                  ->orderBy('item_name')
                  ->get();
                    
        return view('layouts.PeminjamanInventaris', compact('items'));
    }

    public function searchItems(Request $request)
    {
        $search = $request->search;
        
        $items = DB::table('item_inventory_table_v4')
            ->where('quantity', '>', 0)
            ->where(function($query) use ($search) {
                $query->where('item_code', 'LIKE', "%{$search}%")
                      ->orWhere('item_name', 'LIKE', "%{$search}%")
                      ->orWhere('category_name', 'LIKE', "%{$search}%");
            })
            ->get();

        return $items->map(function($item) {
            return [
                'id' => $item->id,
                'item_code' => $item->item_code,
                'item_name' => $item->item_name,
                'quantity' => $item->quantity,
                'unit' => $item->unit,
                'type_name' => $item->type_name,
                'category_name' => $item->category_name,
                'condition_name' => $item->condition_name
            ];
        });
    }
} 