<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class TransactionController extends Controller
{
    public function index()
    {
        return Transaction::with(['items.product', 'creator'])->get();
    }

    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|in:IN,OUT',
            'notes' => 'nullable|string',
            'items' => 'required|array',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.qty' => 'required|numeric|min:1',
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

            foreach ($request->items as $item) {
                TransactionItem::create([
                    'transaction_id' => $transaction->id,
                    'product_id' => $item['product_id'],
                    'qty' => $item['qty']
                ]);
            }

            ActivityLog::log('CREATE_TRANSACTION', 'Created ' . $request->type . ' transaction.');

            DB::commit();
            return response()->json($transaction->load('items.product'), 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Failed: ' . $e->getMessage()], 500);
        }
    }

    public function show(Transaction $transaction)
    {
        return $transaction->load(['items.product', 'creator']);
    }

    public function update(Request $request, Transaction $transaction)
    {
        if ($transaction->created_by !== auth()->id()) {
            return response()->json(['message' => 'Unauthorized. Only the creator can edit this transaction.'], 403);
        }

        $request->validate([
            'notes' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:5120'
        ]);

        if ($request->hasFile('photo')) {
            $manager = new ImageManager(new Driver());
            $image = $manager->read($request->file('photo'));
            $image->scale(height: 800);
            $encoded = $image->toWebp(70);
            $filename = 'transactions/' . uniqid() . '.webp';
            Storage::disk('public')->put($filename, (string) $encoded);
            
            if ($transaction->photo_url) {
                Storage::disk('public')->delete(str_replace('/storage/', '', $transaction->photo_url));
            }
            $transaction->photo_url = '/storage/' . $filename;
        }

        $transaction->notes = $request->notes;
        $transaction->save();
        ActivityLog::log('UPDATE_TRANSACTION', 'Updated ' . $transaction->type . ' transaction.');

        return response()->json($transaction->load('items.product'));
    }

    public function destroy(Transaction $transaction)
    {
        if ($transaction->created_by !== auth()->id()) {
            return response()->json(['message' => 'Unauthorized. Only the creator can delete this transaction.'], 403);
        }

        if ($transaction->photo_url) {
            Storage::disk('public')->delete(str_replace('/storage/', '', $transaction->photo_url));
        }

        $type = $transaction->type;
        $transaction->delete();
        ActivityLog::log('DELETE_TRANSACTION', 'Deleted ' . $type . ' transaction.');
        return response()->json(null, 204);
    }
}
