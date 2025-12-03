<?php

namespace App\Http\Controllers;

use App\Filament\Resources\Orders\OrderResource;
use Illuminate\Http\Request;
use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;

class OrderController extends Controller
{
    public function pdf($id)
    {
        $record = Order::with('shipping','user')->findOrFail($id);

        $pdf = Pdf::loadView('admin.orders.pdf', [
            'record' => $record,
            'for_pdf' => true,
        ])->setPaper('a4', 'portrait');
        return $pdf->stream('order-' . ($record->order_number ?? $record->id) . '.pdf');

    }
    public function destroy(Order $order)
    {
        $order->delete();
        // return response()->json(['message' => 'Deleted'], 200);
      return redirect(OrderResource::getUrl('index'));
    }

}
    