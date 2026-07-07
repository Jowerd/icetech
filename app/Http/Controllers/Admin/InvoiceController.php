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
        $products = Product::whereNotNull('slug')->orderBy('name')->get(['id', 'name', 'price', 'image']);
        return view('admin.invoices.show', compact('invoice', 'products'));
    }

    public function update(Request $request, Invoice $invoice)
    {
        $request->validate([
            'issue_date'  => 'required|date',
            'seller_name' => 'required|string|max:255',
            'items'       => 'required|array|min:1',
            'items.*.name'       => 'required|string',
            'items.*.quantity'   => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
        ]);

        $total = collect($request->items)->sum(fn($i) => $i['quantity'] * $i['unit_price']);

        $invoice->update([
            'issue_date'      => $request->issue_date,
            'seller_name'     => $request->seller_name,
            'seller_id_number'=> $request->seller_id_number,
            'seller_address'  => $request->seller_address,
            'seller_phone'    => $request->seller_phone,
            'seller_email'    => $request->seller_email,
            'seller_bank'     => $request->seller_bank,
            'seller_account'  => $request->seller_account,
            'seller_bank2'    => $request->seller_bank2,
            'seller_account2' => $request->seller_account2,
            'buyer_name'      => $request->buyer_name,
            'buyer_id_number' => $request->buyer_id_number,
            'buyer_address'   => $request->buyer_address,
            'buyer_phone'     => $request->buyer_phone,
            'buyer_email'     => $request->buyer_email,
            'notes'           => $request->notes,
            'total'           => $total,
        ]);

        $invoice->items()->delete();
        foreach ($request->items as $item) {
            $invoice->items()->create([
                'product_id' => !empty($item['product_id']) ? $item['product_id'] : null,
                'name'       => $item['name'],
                'image'      => !empty($item['image']) ? $item['image'] : null,
                'quantity'   => $item['quantity'],
                'unit_price' => $item['unit_price'],
                'total'      => $item['quantity'] * $item['unit_price'],
            ]);
        }

        return redirect()->route('admin.invoices.show', $invoice)->with('success', 'ინვოისი განახლდა!');
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

    public function exportExcel(Invoice $invoice)
    {
        $invoice->load('items');

        // --- მონაცემების აწყობა row-ებად ---
        $rows = [];
        $rows[] = [['ინვოისი ' . $invoice->invoice_number, 's']];
        $rows[] = [['თარიღი: ' . $invoice->issue_date->format('d.m.Y'), 's']];
        if ($invoice->buyer_name) {
            $rows[] = [['მყიდველი: ' . $invoice->buyer_name, 's']];
        }
        $rows[] = [['', 's']];

        $headerRowNum = count($rows) + 1;
        $rows[] = [
            ['#', 's'], ['დასახელება', 's'], ['რაოდენობა', 's'],
            ['ერთეულის ფასი (₾)', 's'], ['ჯამი (₾)', 's'],
        ];

        $i = 0;
        foreach ($invoice->items as $item) {
            $i++;
            $rows[] = [
                [$i, 'n'],
                [$item->name, 's'],
                [(int) $item->quantity, 'n'],
                [(float) $item->unit_price, 'n'],
                [(float) $item->total, 'n'],
            ];
        }

        $totalRowNum = count($rows) + 1;
        $rows[] = [
            ['', 's'], ['', 's'], ['', 's'],
            ['სულ:', 's'], [(float) $invoice->total, 'n'],
        ];

        $boldRows = [1, $headerRowNum, $totalRowNum];
        $filename = $invoice->invoice_number . '.xlsx';

        // ZipArchive არ არსებობს — CSV fallback
        if (!class_exists(\ZipArchive::class)) {
            return $this->invoiceCsv($rows, $invoice->invoice_number . '.csv');
        }

        return $this->invoiceXlsx($rows, $boldRows, $filename);
    }

    private function invoiceXlsx(array $rows, array $boldRows, string $filename)
    {
        $sheet = $this->invoiceSheetXml($rows, $boldRows);

        $contentTypes = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            . '<Types xmlns="http://schemas.openxmlformats.org/package/2006/content-types">'
            . '<Default Extension="rels" ContentType="application/vnd.openxmlformats-package.relationships+xml"/>'
            . '<Default Extension="xml" ContentType="application/xml"/>'
            . '<Override PartName="/xl/workbook.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet.main+xml"/>'
            . '<Override PartName="/xl/worksheets/sheet1.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.worksheet+xml"/>'
            . '<Override PartName="/xl/styles.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.styles+xml"/>'
            . '</Types>';

        $rels = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            . '<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">'
            . '<Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument" Target="xl/workbook.xml"/>'
            . '</Relationships>';

        $workbook = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            . '<workbook xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main" xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships">'
            . '<sheets><sheet name="ინვოისი" sheetId="1" r:id="rId1"/></sheets></workbook>';

        $wbRels = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            . '<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">'
            . '<Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/worksheet" Target="worksheets/sheet1.xml"/>'
            . '<Relationship Id="rId2" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/styles" Target="styles.xml"/>'
            . '</Relationships>';

        $styles = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            . '<styleSheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main">'
            . '<fonts count="2"><font><sz val="11"/><name val="Calibri"/></font><font><b/><sz val="11"/><name val="Calibri"/></font></fonts>'
            . '<fills count="2"><fill><patternFill patternType="none"/></fill><fill><patternFill patternType="gray125"/></fill></fills>'
            . '<borders count="1"><border/></borders>'
            . '<cellStyleXfs count="1"><xf numFmtId="0" fontId="0" fillId="0" borderId="0"/></cellStyleXfs>'
            . '<cellXfs count="2"><xf numFmtId="0" fontId="0" fillId="0" borderId="0" xfId="0"/>'
            . '<xf numFmtId="0" fontId="1" fillId="0" borderId="0" xfId="0" applyFont="1"/></cellXfs>'
            . '</styleSheet>';

        $tmp = tempnam(sys_get_temp_dir(), 'inv') . '.xlsx';
        $zip = new \ZipArchive();
        $zip->open($tmp, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);
        $zip->addFromString('[Content_Types].xml', $contentTypes);
        $zip->addFromString('_rels/.rels', $rels);
        $zip->addFromString('xl/workbook.xml', $workbook);
        $zip->addFromString('xl/_rels/workbook.xml.rels', $wbRels);
        $zip->addFromString('xl/styles.xml', $styles);
        $zip->addFromString('xl/worksheets/sheet1.xml', $sheet);
        $zip->close();

        return response()->download($tmp, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ])->deleteFileAfterSend(true);
    }

    private function invoiceSheetXml(array $rows, array $boldRows): string
    {
        $xml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            . '<worksheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main">'
            . '<cols><col min="1" max="1" width="6"/><col min="2" max="2" width="42"/>'
            . '<col min="3" max="3" width="12"/><col min="4" max="5" width="16"/></cols>'
            . '<sheetData>';

        foreach ($rows as $ri => $cells) {
            $rowNum = $ri + 1;
            $xml .= '<row r="' . $rowNum . '">';
            foreach ($cells as $ci => $cell) {
                [$value, $type] = $cell;
                $ref = $this->colLetter($ci) . $rowNum;
                $s = in_array($rowNum, $boldRows, true) ? ' s="1"' : '';
                if ($type === 'n' && $value !== '' && is_numeric($value)) {
                    $xml .= '<c r="' . $ref . '"' . $s . '><v>' . $value . '</v></c>';
                } else {
                    $xml .= '<c r="' . $ref . '"' . $s . ' t="inlineStr"><is><t xml:space="preserve">'
                        . htmlspecialchars((string) $value, ENT_QUOTES | ENT_XML1, 'UTF-8')
                        . '</t></is></c>';
                }
            }
            $xml .= '</row>';
        }

        $xml .= '</sheetData></worksheet>';
        return $xml;
    }

    private function colLetter(int $index): string
    {
        $letter = '';
        $index++;
        while ($index > 0) {
            $mod = ($index - 1) % 26;
            $letter = chr(65 + $mod) . $letter;
            $index = intdiv($index - 1, 26);
        }
        return $letter;
    }

    private function invoiceCsv(array $rows, string $filename)
    {
        $out = "\xEF\xBB\xBF"; // UTF-8 BOM (Excel-ისთვის)
        foreach ($rows as $cells) {
            $line = [];
            foreach ($cells as $cell) {
                $v = (string) $cell[0];
                $line[] = '"' . str_replace('"', '""', $v) . '"';
            }
            $out .= implode(';', $line) . "\r\n";
        }

        return response($out, 200, [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    public function uploadImage(Request $request)
    {
        $request->validate([
            'image' => 'required|image|max:5120', // 5MB
        ]);

        $path = $request->file('image')->store('invoice_items', 'public');

        return response()->json([
            'path' => $path,
            'url'  => asset('storage/' . $path),
        ]);
    }
}
