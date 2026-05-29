<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\InvoiceBuyer;
use App\Models\Product;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function index()
    {
        $invoices = Invoice::latest()->paginate(20);
        return view('admin.invoices.index', compact('invoices'));
    }

    public function create()
    {
        $buyers   = InvoiceBuyer::orderBy('name')->get();
        $products = Product::whereNotNull('slug')->orderBy('name')->get(['id', 'name', 'price', 'image']);

        // გამყიდველის defaults — ბოლო ინვოისიდან
        $lastInvoice = Invoice::latest()->first();
        $seller = $lastInvoice ? [
            'name'      => $lastInvoice->seller_name,
            'id_number' => $lastInvoice->seller_id_number,
            'address'   => $lastInvoice->seller_address,
            'phone'     => $lastInvoice->seller_phone,
            'email'     => $lastInvoice->seller_email,
            'bank'      => $lastInvoice->seller_bank,
            'account'   => $lastInvoice->seller_account,
            'bank2'     => $lastInvoice->seller_bank2,
            'account2'  => $lastInvoice->seller_account2,
        ] : [
            'name'      => 'ICETECH',
            'id_number' => '',
            'address'   => 'ხაშური, ბორჯომის ქუჩა #2',
            'phone'     => '+995 511 555 888',
            'email'     => 'info@icetech.ge',
            'bank'      => '',
            'account'   => '',
            'bank2'     => '',
            'account2'  => '',
        ];

        return view('admin.invoices.create', compact('buyers', 'products', 'seller'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'issue_date'  => 'required|date',
            'seller_name' => 'required|string|max:255',
            'items'       => 'required|array|min:1',
            'items.*.name'       => 'required|string',
            'items.*.quantity'   => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
        ]);

        // ჯამის დათვლა
        $total = collect($request->items)->sum(fn($i) => $i['quantity'] * $i['unit_price']);

        $invoice = Invoice::create([
            'invoice_number' => Invoice::generateNumber(),
            'issue_date'     => $request->issue_date,
            'status'         => 'issued',
            'seller_name'    => $request->seller_name,
            'seller_id_number' => $request->seller_id_number,
            'seller_address' => $request->seller_address,
            'seller_phone'   => $request->seller_phone,
            'seller_email'   => $request->seller_email,
            'seller_bank'     => $request->seller_bank,
            'seller_account'  => $request->seller_account,
            'seller_bank2'    => $request->seller_bank2,
            'seller_account2' => $request->seller_account2,
            'buyer_name'     => $request->buyer_name,
            'buyer_id_number' => $request->buyer_id_number,
            'buyer_address'  => $request->buyer_address,
            'buyer_phone'    => $request->buyer_phone,
            'buyer_email'    => $request->buyer_email,
            'buyer_bank'     => $request->buyer_bank,
            'buyer_account'  => $request->buyer_account,
            'notes'          => $request->notes,
            'total'          => $total,
        ]);

        foreach ($request->items as $item) {
            $invoice->items()->create([
                'product_id' => $item['product_id'] ?? null,
                'name'       => $item['name'],
                'image'      => $item['image'] ?? null,
                'quantity'   => $item['quantity'],
                'unit_price' => $item['unit_price'],
                'total'      => $item['quantity'] * $item['unit_price'],
            ]);
        }

        // მყიდველის შენახვა სიაში (თუ სახელი მითითებულია)
        if ($request->buyer_name && $request->save_buyer) {
            InvoiceBuyer::updateOrCreate(
                ['name' => $request->buyer_name],
                [
                    'id_number' => $request->buyer_id_number,
                    'address'   => $request->buyer_address,
                    'phone'     => $request->buyer_phone,
                    'email'     => $request->buyer_email,
                    'bank'      => $request->buyer_bank,
                    'account'   => $request->buyer_account,
                ]
            );
        }

        return redirect()->route('admin.invoices.show', $invoice)->with('success', 'ინვოისი შეიქმნა!');
    }

    public function show(Invoice $invoice)
    {
        $invoice->load('items');
        return view('admin.invoices.show', compact('invoice'));
    }

    public function destroy(Invoice $invoice)
    {
        $invoice->delete();
        return redirect()->route('admin.invoices.index')->with('success', 'ინვოისი წაიშალა.');
    }

    public function buyers()
    {
        return response()->json(InvoiceBuyer::orderBy('name')->get());
    }
}
