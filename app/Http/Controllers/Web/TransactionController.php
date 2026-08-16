<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\Product;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class TransactionController extends Controller
{
    public function create(Request $request)
    {
        $type = $request->query('type', 'IN');
        $products = Product::all();
        return view('transactions_create', compact('type', 'products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|in:IN,OUT',
            'product_id' => 'required|array|min:1',
            'product_id.*' => 'required|exists:products,id',
            'qty' => 'required|array|min:1',
            'qty.*' => 'required|numeric|min:1',
            'notes' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:5120'
        ]);

        DB::beginTransaction();
        try {
            $photoUrl = null;
            if ($request->hasFile('photo')) {
                $manager = new ImageManager(new Driver());
                $image = $manager->read($request->file('photo'));
                $image->scale(height: 800);
                $encoded = $image->toWebp(70);
                $filename = 'transactions/' . uniqid() . '.webp';
                Storage::disk('public')->put($filename, (string) $encoded);
                $photoUrl = '/storage/' . $filename;
            }

            $transaction = Transaction::create([
                'type' => $request->type,
                'notes' => $request->notes,
                'photo_url' => $photoUrl,
                'created_by' => auth()->id()
            ]);

            foreach ($request->product_id as $index => $pid) {
                TransactionItem::create([
                    'transaction_id' => $transaction->id,
                    'product_id' => $pid,
                    'qty' => $request->qty[$index]
                ]);
            }

            ActivityLog::log('CREATE_TRANSACTION', 'Mencatat transaksi ' . ($request->type == 'IN' ? 'Masuk' : 'Keluar'));

            DB::commit();
            return redirect('/dashboard')->with('success', 'Transaksi berhasil disimpan!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Gagal menyimpan: ' . $e->getMessage()]);
        }
    }
    public function edit(Transaction $transaction)
    {
        if (auth()->user()->role !== 'owner') {
            abort(403, 'Hanya Owner yang dapat mengedit transaksi.');
        }
        $transaction->load('items.product');
        $products = Product::all();
        return view('transactions_edit', compact('transaction', 'products'));
    }

    public function update(Request $request, Transaction $transaction)
    {
        if (auth()->user()->role !== 'owner') {
            abort(403, 'Hanya Owner yang dapat mengedit transaksi.');
        }

        $request->validate([
            'type' => 'required|in:IN,OUT',
            'product_id' => 'required|array|min:1',
            'product_id.*' => 'required|exists:products,id',
            'qty' => 'required|array|min:1',
            'qty.*' => 'required|numeric|min:1',
            'notes' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:5120'
        ]);

        DB::beginTransaction();
        try {
            $photoUrl = $transaction->photo_url;
            if ($request->hasFile('photo')) {
                $manager = new ImageManager(new Driver());
                $image = $manager->read($request->file('photo'));
                $image->scale(height: 800);
                $encoded = $image->toWebp(70);
                $filename = 'transactions/' . uniqid() . '.webp';
                Storage::disk('public')->put($filename, (string) $encoded);
                $photoUrl = '/storage/' . $filename;
            }

            $transaction->update([
                'type' => $request->type,
                'notes' => $request->notes,
                'photo_url' => $photoUrl,
            ]);

            $transaction->items()->delete();

            foreach ($request->product_id as $index => $pid) {
                TransactionItem::create([
                    'transaction_id' => $transaction->id,
                    'product_id' => $pid,
                    'qty' => $request->qty[$index]
                ]);
            }

            ActivityLog::log('UPDATE_TRANSACTION', 'Mengubah transaksi ' . ($request->type == 'IN' ? 'Masuk' : 'Keluar'));

            DB::commit();
            return redirect('/reports')->with('success', 'Transaksi berhasil diperbarui!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Gagal mengubah: ' . $e->getMessage()]);
        }
    }

    public function destroy(Transaction $transaction)
    {
        if (auth()->user()->role !== 'owner') {
            abort(403, 'Hanya Owner yang dapat menghapus transaksi.');
        }
        
        $transaction->items()->delete();
        $transaction->delete();
        
        ActivityLog::log('DELETE_TRANSACTION', 'Menghapus transaksi');
        
        return redirect('/reports')->with('success', 'Transaksi berhasil dihapus!');
    }
}
