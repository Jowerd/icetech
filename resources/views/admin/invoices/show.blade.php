@extends('admin.layout')
@section('title', $invoice->invoice_number . ' • ICETECH')

@section('styles')
<style>
@font-face {
    font-family: 'BPG Mrgvlovani';
    src: url('/fonts/BPGMrgvlovaniCaps2010.woff2') format('woff2');
    font-weight: normal; font-style: normal; font-display: swap;
}

/* ამ გვერდზე content padding ვაშოროთ — ჰედერი კიდემდე მივიდეს */
.content { padding: 0 !important; }

@media print {
    @page { margin: 0; }
    .no-print { display: none !important; }
    body, html { background: #fff !important; margin: 0 !important; padding: 0 !important; }
    .content { margin: 0 !important; padding: 0 !important; }
    .invoice-wrap { box-shadow: none !important; border: none !important; max-width: 100% !important; }
    .inv-header, .inv-table th, .subtotal-row td {
        -webkit-print-color-adjust: exact; print-color-adjust: exact;
    }
}

.invoice-wrap {
    max-width: 100%;
    margin: 0;
    padding-top: 18px;
    background: #fff;
    border: none;
    font-family: 'BPG Mrgvlovani', Tahoma, Arial, sans-serif;
}

/* HEADER */
.inv-header {
    background-color: #1a365d;
    padding: 22px 32px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-bottom: 3px solid #00a4bd;
}
.inv-logo {
    font-size: 1.75rem;
    font-weight: 700;
    color: #fff;
    letter-spacing: 0.8px;
}
.inv-logo span { color: #fff; }
.inv-tag {
    display: inline-block;
    background: rgba(0,164,189,0.2);
    border: 1px solid rgba(0,164,189,0.45);
    color: #fff;
    font-size: 0.62rem;
    font-weight: 600;
    letter-spacing: 0.8px;
    text-transform: uppercase;
    padding: 2px 10px;
    margin-bottom: 4px;
}
.inv-number { font-size: 0.88rem; font-weight: 700; color: #fff; }

/* BODY */
.inv-body { padding: 26px 32px 30px; }

/* თარიღი — მყიდველის გასწვრივ */
.inv-date-line {
    text-align: right;
    font-size: 0.8rem;
    color: #6c757d;
    margin-bottom: 6px;
}
.inv-date-line strong { color: #1a365d; font-size: 0.86rem; }
.inv-date-input { font-family: inherit; font-size: 0.82rem; padding: 1px 6px; }

/* PARTY — ღია, ჩარჩოს გარეშე */
.party-box { padding: 4px 0; height: 100%; }
.party-box h6 {
    font-size: 0.65rem;
    text-transform: uppercase;
    letter-spacing: .7px;
    color: #00a4bd;
    font-weight: 700;
    margin-bottom: 6px;
    border-bottom: 1.5px solid #00a4bd;
    padding-bottom: 4px;
    display: inline-block;
}
.party-box.buyer h6 { color: #1a365d; border-bottom-color: #1a365d; }
.party-box .p-name { font-size: 0.9rem; font-weight: 700; color: #1a365d; margin-bottom: 4px; }
.party-box .p-detail { font-size: 0.76rem; color: #6c757d; margin: 2px 0; line-height: 1.6; }

/* TABLE — ბრტყელი, ჩარჩოს გარეშე */
.inv-table { width: 100%; border-collapse: collapse; margin-bottom: 12px; }
.inv-table th {
    background: #1a365d;
    color: #fff;
    font-size: 0.67rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: .5px;
    padding: 10px 14px;
}
.inv-table td {
    padding: 9px 14px;
    font-size: 0.82rem;
    color: #2b3445;
    vertical-align: middle;
    border-bottom: 1px solid #f0f2f5;
}
.inv-table tbody tr:nth-child(even) { background: #f8f9fb; }
.subtotal-row td {
    background: rgba(0,164,189,0.08) !important;
    font-weight: 700;
    color: #1a365d;
    font-size: 0.88rem;
    border-top: 2px solid rgba(0,164,189,0.25);
    border-bottom: none;
}
.prod-thumb {
    width: 46px; height: 46px;
    object-fit: contain;
    border: 1px solid #eee;
    vertical-align: middle;
    margin-right: 10px;
}

/* STAMP */
.stamp-row {
    display: flex;
    gap: 32px;
    margin: 8px 0 24px;
    padding-top: 16px;
    border-top: 1.5px dashed #dee2e6;
}
.stamp-box { flex: 1; text-align: center; font-size: 0.73rem; color: #adb5bd; }
.stamp-line { border-top: 1px solid #ced4da; padding-top: 5px; margin-top: 52px; }

/* PAYMENT — ღია, ბრტყელი */
.payment-box { padding: 4px 0; }
.payment-box h6 {
    font-size: 0.65rem;
    text-transform: uppercase;
    letter-spacing: .7px;
    color: #1a365d;
    font-weight: 700;
    margin-bottom: 8px;
    border-bottom: 1.5px solid #1a365d;
    padding-bottom: 4px;
    display: inline-block;
}
.payment-line {
    font-size: 0.82rem;
    color: #333;
    margin: 6px 0;
    display: flex;
    align-items: center;
    gap: 8px;
}
.payment-line .bank-logo { height: 26px; width: auto; border-radius: 5px; }
.payment-line .bank-name {
    font-weight: 700;
    color: #1a365d;
    min-width: 36px;
    font-size: 0.8rem;
}
.payment-line .bank-account { font-family: monospace; font-size: 0.98rem; font-weight: 700; letter-spacing: .3px; color: #1a365d; }

.note-label { font-size:0.68rem;font-weight:800;text-transform:uppercase;letter-spacing:.6px;color:#1a365d;margin-bottom:5px; }
.note-text { font-size:0.82rem;color:#555;border-bottom:1px solid #ddd;padding-bottom:6px; }

/* ============ EDIT MODE ============ */
.edit-only { display: none; }
body.editing .edit-only { display: inline-block; }
body.editing tr.edit-only { display: table-row; }
body.editing .view-only { display: none; }

.edit-block { display: none; }
body.editing .edit-block { display: block; }

/* პროდუქტის ძებნის dropdown */
.prod-search-wrap { position: relative; margin-bottom: 12px; }
.prod-search-wrap input {
    width: 100%; font-family: inherit; font-size: 0.85rem;
    padding: 8px 12px; border: 2px solid #cfd8e3; border-radius: 6px; outline: none;
}
.prod-search-wrap input:focus { border-color: #00a4bd; }
.prod-dropdown {
    position: absolute; width: 100%; background: #fff; border: 1px solid #dee2e6;
    border-radius: 6px; box-shadow: 0 6px 18px rgba(0,0,0,.1); margin-top: 3px;
    z-index: 999; max-height: 260px; overflow-y: auto;
}
.prod-dropdown .pd-item { display: flex; align-items: center; gap: 10px; padding: 8px 12px; cursor: pointer; }
.prod-dropdown .pd-item:hover { background: #f1f7f9; }
.prod-dropdown .pd-item img { width: 34px; height: 34px; object-fit: contain; border-radius: 4px; border: 1px solid #eee; }
.prod-dropdown .pd-name { font-size: 0.82rem; font-weight: 600; color: #1a365d; }
.prod-dropdown .pd-price { font-size: 0.76rem; color: #00a4bd; font-weight: 700; }

body.editing [contenteditable="true"] {
    outline: 1px dashed #00a4bd;
    background: rgba(0,164,189,0.07);
    border-radius: 3px;
    padding: 0 4px;
    min-width: 22px;
    display: inline-block;
    cursor: text;
}
body.editing .ev:empty::before { content: attr(data-ph); color: #c2c2c2; font-weight: 400; }

/* view მოდში ცარიელი არასავალდებულო ველები არ ჩანს */
body:not(.editing) .opt-row:has(.ev:empty) { display: none; }
body:not(.editing) .opt-pay:has(.bank-key:empty) { display: none; }
body:not(.editing) .payment-box:not(:has(.bank-key:not(:empty))) { display: none; }
body:not(.editing) .opt-notes:has(.notes-ev:empty) { display: none; }

.row-del {
    display: none;
    border: none; background: #fde8e8; color: #e03131;
    width: 20px; height: 20px; line-height: 18px; border-radius: 4px;
    font-weight: 700; margin-right: 6px; cursor: pointer; vertical-align: middle;
}
body.editing .row-del { display: inline-block; }

.img-btn {
    border: none; background: #eef4f7; color: #1a365d;
    border-radius: 4px; padding: 1px 7px; margin-left: 8px;
    cursor: pointer; font-size: 0.82rem; vertical-align: middle;
}
.img-btn:hover { background: #d9ebf1; }
.item-file { display: none; }
</style>
@endsection

@php
$bankLogo = function ($name) {
    $n = mb_strtolower(trim((string) $name));
    if ($n === '') return null;
    if (str_contains($n, 'bog') || str_contains($n, 'ბოგ') || str_contains($n, 'საქართველოს ბანკი') || str_contains($n, 'bank of georgia')) return 'images/banks/bog.png';
    if (str_contains($n, 'tbc') || str_contains($n, 'ტბც') || str_contains($n, 'თიბისი')) return 'images/banks/tbc.webp';
    return null;
};
$logo1 = $bankLogo($invoice->seller_bank);
$logo2 = $bankLogo($invoice->seller_bank2);
@endphp

@section('content')
<div class="container-fluid px-0">

    @if(session('success'))
        <div class="alert alert-success rounded-1 mx-3 mt-3 mb-0 no-print py-2 small fw-bold">{{ session('success') }}</div>
    @endif

    <div class="d-flex gap-2 mb-3 no-print px-3 pt-3" id="toolbar">
        <a href="{{ route('admin.invoices.index') }}" class="tb-view btn btn-light border rounded-1 small fw-bold">
            <i class="bi bi-arrow-left me-1"></i> უკან
        </a>
        <button onclick="toggleEdit(true)" class="tb-view btn btn-primary rounded-1 small fw-bold">
            <i class="bi bi-pencil-square me-1"></i> რედაქტირება
        </button>
        <button onclick="downloadPdf(this)" class="tb-view btn btn-light border rounded-1 small fw-bold">
            <i class="bi bi-download me-1"></i> PDF ჩამოტვირთვა
        </button>
        <button onclick="window.print()" class="tb-view btn btn-light border rounded-1 small fw-bold">
            <i class="bi bi-printer me-1"></i> ბეჭდვა
        </button>
        <a href="{{ route('admin.invoices.excel', $invoice) }}" class="tb-view btn btn-light border rounded-1 small fw-bold text-success">
            <i class="bi bi-file-earmark-excel me-1"></i> Excel
        </a>

        <button onclick="saveInvoice(this)" class="tb-edit d-none btn btn-success rounded-1 small fw-bold">
            <i class="bi bi-check-lg me-1"></i> შენახვა
        </button>
        <button onclick="location.reload()" class="tb-edit d-none btn btn-light border rounded-1 small fw-bold">
            <i class="bi bi-x-lg me-1"></i> გაუქმება
        </button>
        <button onclick="addItemRow()" class="tb-edit d-none btn btn-outline-primary rounded-1 small fw-bold">
            <i class="bi bi-plus-lg me-1"></i> სტრიქონის დამატება
        </button>

        <form action="{{ route('admin.invoices.destroy', $invoice) }}" method="POST" class="tb-view ms-auto"
              onsubmit="return confirm('წავშალო?')">
            @csrf @method('DELETE')
            <button class="btn btn-light border rounded-1 small text-danger fw-bold">
                <i class="bi bi-trash me-1"></i> წაშლა
            </button>
        </form>
    </div>

    {{-- ფარული ფორმა — შენახვისთვის --}}
    <form id="editForm" method="POST" action="{{ route('admin.invoices.update', $invoice) }}" class="d-none">
        @csrf @method('PUT')
    </form>

    <div class="invoice-wrap">

        {{-- 1. HEADER --}}
        <div class="inv-header">
            <div>
                <div class="inv-logo">ICE<span>TECH</span></div>
                <div style="font-size:0.72rem;color:rgba(255,255,255,0.55);margin-top:3px;">კომერციული სამზარეულოს აღჭურვილობა</div>
            </div>
            <div class="text-end">
                <div class="inv-tag">ინვოისი</div>
                <div class="inv-number">{{ $invoice->invoice_number }}</div>
            </div>
        </div>

        <div class="inv-body">

            {{-- თარიღი --}}
            <div class="inv-date-line">
                თარიღი:
                <strong class="view-only">{{ $invoice->issue_date->format('d.m.Y') }}</strong>
                <input type="date" class="edit-only inv-date-input" value="{{ $invoice->issue_date->format('Y-m-d') }}">
            </div>

            {{-- 2. გამყიდველი / მყიდველი --}}
            <div class="row g-3 mb-3">
                <div class="col-6">
                    <div class="party-box">
                        <h6>გამყიდველი:</h6>
                        <div class="p-name"><span class="ev" data-field="seller_name" data-ph="კომპანიის სახელი">{{ $invoice->seller_name }}</span></div>
                        <p class="p-detail opt-row">ს.ნ.: <span class="ev" data-field="seller_id_number" data-ph="საიდენტიფიკაციო">{{ $invoice->seller_id_number }}</span></p>
                        <p class="p-detail opt-row"><span class="ev" data-field="seller_address" data-ph="მისამართი">{{ $invoice->seller_address }}</span></p>
                        <p class="p-detail opt-row"><span class="ev" data-field="seller_phone" data-ph="ტელეფონი">{{ $invoice->seller_phone }}</span></p>
                        <p class="p-detail opt-row"><span class="ev" data-field="seller_email" data-ph="ელ.ფოსტა">{{ $invoice->seller_email }}</span></p>
                    </div>
                </div>
                <div class="col-6">
                    <div class="party-box buyer">
                        <h6>მყიდველი:</h6>
                        <div class="p-name"><span class="ev" data-field="buyer_name" data-ph="კომპანიის სახელი">{{ $invoice->buyer_name }}</span></div>
                        <p class="p-detail opt-row">ს.ნ.: <span class="ev" data-field="buyer_id_number" data-ph="საიდენტიფიკაციო">{{ $invoice->buyer_id_number }}</span></p>
                        <p class="p-detail opt-row"><span class="ev" data-field="buyer_address" data-ph="მისამართი">{{ $invoice->buyer_address }}</span></p>
                        <p class="p-detail opt-row"><span class="ev" data-field="buyer_phone" data-ph="ტელეფონი">{{ $invoice->buyer_phone }}</span></p>
                        <p class="p-detail opt-row"><span class="ev" data-field="buyer_email" data-ph="ელ.ფოსტა">{{ $invoice->buyer_email }}</span></p>
                    </div>
                </div>
            </div>

            {{-- პროდუქტის ძებნა კატალოგიდან (მხოლოდ რედაქტირებისას) --}}
            <div class="edit-block prod-search-wrap" id="prodSearchWrap">
                <input type="text" id="productSearch" placeholder="🔍 კატალოგიდან პროდუქტის ძებნა და დამატება...">
                <div id="productDropdown" class="prod-dropdown d-none"></div>
            </div>

            {{-- 3. PRODUCTS TABLE --}}
            <table class="inv-table">
                <thead>
                    <tr>
                        <th style="width:60px;">#</th>
                        <th>დასახელება</th>
                        <th class="text-center" style="width:65px;">რ-ბა</th>
                        <th class="text-end" style="width:110px;">ფასი</th>
                        <th class="text-end" style="width:110px;">ჯამი</th>
                    </tr>
                </thead>
                <tbody id="itemsBody">
                    @foreach($invoice->items as $i => $item)
                    <tr class="item-row" data-product-id="{{ $item->product_id }}" data-image="{{ $item->image }}">
                        <td class="text-muted">
                            <button type="button" class="row-del" title="წაშლა" onclick="delRow(this)">×</button>
                            <span class="rownum">{{ $i + 1 }}</span>
                        </td>
                        <td class="name-cell">
                            @if($item->image)
                                <img src="{{ asset('storage/' . $item->image) }}" class="prod-thumb" alt="">
                            @endif
                            <span class="ev name" data-ph="დასახელება">{{ $item->name }}</span>
                            <button type="button" class="img-btn edit-only" title="ფოტოს ატვირთვა" onclick="pickImg(this)"><i class="bi bi-camera"></i></button>
                            <input type="file" class="item-file" accept="image/*" onchange="uploadRowImage(this)">
                        </td>
                        <td class="text-center"><span class="ev qty">{{ $item->quantity }}</span></td>
                        <td class="text-end"><span class="ev price">{{ number_format($item->unit_price, 2) }}</span> ₾</td>
                        <td class="text-end fw-bold"><span class="rowtotal">{{ number_format($item->total, 2) }}</span> ₾</td>
                    </tr>
                    @endforeach
                    <tr class="subtotal-row">
                        <td colspan="4" class="text-end">ჯამი</td>
                        <td class="text-end"><span id="grandTotal">{{ number_format($invoice->total, 2) }}</span> ₾</td>
                    </tr>
                </tbody>
            </table>

            {{-- 4. შენიშვნა --}}
            <div class="mb-3 opt-notes">
                <div class="note-label">შენიშვნა:</div>
                <div class="note-text notes-ev" data-ph="შენიშვნა...">{{ $invoice->notes }}</div>
            </div>

            {{-- 5. ხელმოწერა + ბეჭედი (მაღლა) --}}
            <div class="stamp-row">
                <div class="stamp-box">
                    <div class="stamp-line">ხელმოწერა</div>
                </div>
                <div class="stamp-box">
                    <div class="stamp-line">ბეჭედი</div>
                </div>
            </div>

            {{-- 6. გადახდის ინფო (დაბლა) --}}
            <div class="payment-box">
                <h6>გადახდის ინფო:</h6>
                <div class="payment-line opt-pay">
                    @if($logo1)
                        <img src="{{ asset($logo1) }}" class="bank-logo view-only" alt="{{ $invoice->seller_bank }}">
                    @else
                        <span class="bank-name view-only">{{ $invoice->seller_bank }}{{ $invoice->seller_bank ? ':' : '' }}</span>
                    @endif
                    <span class="bank-name ev edit-only bank-key" data-field="seller_bank" data-ph="ბანკი (BOG/TBC)">{{ $invoice->seller_bank }}</span>
                    <span class="bank-account ev" data-field="seller_account" data-ph="ანგარიშის ნომერი">{{ $invoice->seller_account }}</span>
                </div>
                <div class="payment-line opt-pay">
                    @if($logo2)
                        <img src="{{ asset($logo2) }}" class="bank-logo view-only" alt="{{ $invoice->seller_bank2 }}">
                    @else
                        <span class="bank-name view-only">{{ $invoice->seller_bank2 }}{{ $invoice->seller_bank2 ? ':' : '' }}</span>
                    @endif
                    <span class="bank-name ev edit-only bank-key" data-field="seller_bank2" data-ph="ბანკი (BOG/TBC)">{{ $invoice->seller_bank2 }}</span>
                    <span class="bank-account ev" data-field="seller_account2" data-ph="ანგარიშის ნომერი">{{ $invoice->seller_account2 }}</span>
                </div>
            </div>

        </div>
    </div>

</div>
@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script>
/* ---------- რიცხვი ტექსტიდან ---------- */
function num(s) { return parseFloat((s || '').toString().replace(/[^0-9.]/g, '')) || 0; }

/* ---------- ფოტოს ატვირთვა (ხელით დამატებული პროდუქტისთვის) ---------- */
const CSRF_TOKEN = '{{ csrf_token() }}';
const UPLOAD_URL = '{{ route('admin.invoices.upload-image') }}';

function pickImg(btn) {
    const inp = btn.parentElement.querySelector('.item-file');
    if (inp) inp.click();
}

function uploadRowImage(input) {
    const file = input.files && input.files[0];
    if (!file) return;
    const tr = input.closest('tr');
    const btn = tr.querySelector('.img-btn');
    const oldBtn = btn ? btn.innerHTML : '';
    if (btn) { btn.disabled = true; btn.innerHTML = '<i class="bi bi-hourglass-split"></i>'; }

    const fd = new FormData();
    fd.append('image', file);
    fd.append('_token', CSRF_TOKEN);

    fetch(UPLOAD_URL, { method: 'POST', body: fd, headers: { 'X-Requested-With': 'XMLHttpRequest' } })
        .then(r => r.ok ? r.json() : Promise.reject())
        .then(d => {
            if (!d.path) return Promise.reject();
            tr.dataset.image = d.path;
            let img = tr.querySelector('.prod-thumb');
            if (!img) {
                img = document.createElement('img');
                img.className = 'prod-thumb'; img.alt = '';
                const cell = tr.querySelector('.name-cell');
                cell.insertBefore(img, cell.firstChild);
            }
            img.src = d.url;
        })
        .catch(() => alert('ფოტოს ატვირთვა ვერ მოხერხდა.'))
        .finally(() => {
            input.value = '';
            if (btn) { btn.disabled = false; btn.innerHTML = oldBtn; }
        });
}

/* ---------- რედაქტირების რეჟიმი ---------- */
function toggleEdit(on) {
    document.body.classList.toggle('editing', on);
    document.querySelectorAll('.tb-view').forEach(e => e.classList.toggle('d-none', on));
    document.querySelectorAll('.tb-edit').forEach(e => e.classList.toggle('d-none', !on));
    document.querySelectorAll('.ev, .notes-ev').forEach(el => el.contentEditable = on ? 'true' : 'false');
    if (on) recalc();
}

/* ---------- ჯამების გადათვლა ---------- */
function recalc() {
    let grand = 0;
    document.querySelectorAll('#itemsBody tr.item-row').forEach(function (tr) {
        const q = num(tr.querySelector('.qty').textContent);
        const p = num(tr.querySelector('.price').textContent);
        const t = q * p;
        tr.querySelector('.rowtotal').textContent = t.toFixed(2);
        grand += t;
    });
    document.getElementById('grandTotal').textContent = grand.toFixed(2);
}

document.addEventListener('input', function (e) {
    if (e.target.classList && (e.target.classList.contains('qty') || e.target.classList.contains('price'))) {
        recalc();
    }
});

/* ---------- სტრიქონები ---------- */
function renumber() {
    document.querySelectorAll('#itemsBody .item-row').forEach(function (tr, i) {
        const n = tr.querySelector('.rownum');
        if (n) n.textContent = i + 1;
    });
}
function delRow(btn) {
    btn.closest('tr').remove();
    renumber(); recalc();
}
function addItemRow(data) {
    data = data || {};
    const tb = document.getElementById('itemsBody');
    const subtotal = tb.querySelector('.subtotal-row');
    const tr = document.createElement('tr');
    tr.className = 'item-row';
    tr.dataset.productId = data.product_id || '';
    tr.dataset.image = data.image || '';
    tr.innerHTML =
        '<td class="text-muted">' +
            '<button type="button" class="row-del" title="წაშლა" onclick="delRow(this)">×</button>' +
            '<span class="rownum"></span>' +
        '</td>' +
        '<td class="name-cell"><span class="ev name" data-ph="დასახელება" contenteditable="true"></span>' +
            '<button type="button" class="img-btn edit-only" title="ფოტოს ატვირთვა" onclick="pickImg(this)"><i class="bi bi-camera"></i></button>' +
            '<input type="file" class="item-file" accept="image/*" onchange="uploadRowImage(this)"></td>' +
        '<td class="text-center"><span class="ev qty" contenteditable="true">1</span></td>' +
        '<td class="text-end"><span class="ev price" contenteditable="true">0.00</span> ₾</td>' +
        '<td class="text-end fw-bold"><span class="rowtotal">0.00</span> ₾</td>';
    tb.insertBefore(tr, subtotal);

    if (data.imageUrl) {
        const img = document.createElement('img');
        img.src = data.imageUrl; img.className = 'prod-thumb'; img.alt = '';
        const cell = tr.querySelector('.name-cell');
        cell.insertBefore(img, cell.firstChild);
    }
    if (data.name) tr.querySelector('.name').textContent = data.name;
    if (data.price != null && data.price !== '') tr.querySelector('.price').textContent = parseFloat(data.price).toFixed(2);

    renumber(); recalc();
    tr.querySelector('.name').focus();
    return tr;
}

/* ---------- პროდუქტის ძებნა კატალოგიდან ---------- */
const CATALOG   = @json($products);
const STORAGE   = '{{ asset('storage') }}';
const searchInput = document.getElementById('productSearch');
const dropdown    = document.getElementById('productDropdown');

if (searchInput) {
    searchInput.addEventListener('input', function () {
        const q = this.value.trim().toLowerCase();
        if (!q) { dropdown.classList.add('d-none'); return; }
        const matches = CATALOG.filter(p => (p.name || '').toLowerCase().includes(q)).slice(0, 12);
        if (!matches.length) { dropdown.classList.add('d-none'); return; }
        dropdown.innerHTML = matches.map(p =>
            '<div class="pd-item" data-id="' + p.id + '" data-name="' + (p.name || '').replace(/"/g, '&quot;') +
                '" data-price="' + p.price + '" data-image="' + (p.image || '') + '">' +
                (p.image ? '<img src="' + STORAGE + '/' + p.image + '" alt="">' : '<div style="width:34px;height:34px;background:#f0f0f0;border-radius:4px;"></div>') +
                '<div><div class="pd-name">' + (p.name || '') + '</div>' +
                '<div class="pd-price">₾ ' + parseFloat(p.price).toFixed(2) + '</div></div>' +
            '</div>'
        ).join('');
        dropdown.classList.remove('d-none');
    });

    dropdown.addEventListener('click', function (e) {
        const item = e.target.closest('.pd-item');
        if (!item) return;
        addItemRow({
            product_id: item.dataset.id,
            name:       item.dataset.name,
            price:      item.dataset.price,
            image:      item.dataset.image,
            imageUrl:   item.dataset.image ? STORAGE + '/' + item.dataset.image : ''
        });
        searchInput.value = '';
        dropdown.classList.add('d-none');
    });

    document.addEventListener('click', function (e) {
        if (!searchInput.contains(e.target) && !dropdown.contains(e.target)) {
            dropdown.classList.add('d-none');
        }
    });
}

/* ---------- ავტომატური რედაქტირება (?edit=1) ---------- */
if (new URLSearchParams(location.search).get('edit') === '1') {
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', () => toggleEdit(true));
    } else {
        toggleEdit(true);
    }
}

/* ---------- შენახვა ---------- */
function saveInvoice(btn) {
    const form = document.getElementById('editForm');
    form.querySelectorAll('.dyn').forEach(e => e.remove());

    const add = function (name, val) {
        const i = document.createElement('input');
        i.type = 'hidden'; i.name = name; i.value = (val == null ? '' : val); i.className = 'dyn';
        form.appendChild(i);
    };

    add('issue_date', document.querySelector('.inv-date-input').value);

    document.querySelectorAll('.ev[data-field]').forEach(function (el) {
        add(el.dataset.field, el.textContent.trim());
    });

    add('notes', document.querySelector('.notes-ev').textContent.trim());

    const sellerName = document.querySelector('.ev[data-field="seller_name"]').textContent.trim();
    if (!sellerName) { alert('გამყიდველის სახელი სავალდებულოა.'); return; }

    let idx = 0, hasItem = false;
    document.querySelectorAll('#itemsBody tr.item-row').forEach(function (tr) {
        const name = tr.querySelector('.name').textContent.trim();
        if (!name) return;
        const qty = Math.max(1, Math.round(num(tr.querySelector('.qty').textContent)));
        const price = num(tr.querySelector('.price').textContent);
        add('items[' + idx + '][name]', name);
        add('items[' + idx + '][quantity]', qty);
        add('items[' + idx + '][unit_price]', price);
        add('items[' + idx + '][product_id]', tr.dataset.productId || '');
        add('items[' + idx + '][image]', tr.dataset.image || '');
        idx++; hasItem = true;
    });

    if (!hasItem) { alert('მინიმუმ ერთი პროდუქტი მაინც უნდა იყოს.'); return; }

    if (btn) { btn.disabled = true; btn.innerHTML = '<i class="bi bi-hourglass-split me-1"></i> ინახება...'; }
    form.submit();
}

/* ---------- PDF ჩამოტვირთვა ---------- */
function downloadPdf(btn) {
    const el = document.querySelector('.invoice-wrap');
    if (!el) return;

    const original = btn ? btn.innerHTML : null;
    if (btn) { btn.disabled = true; btn.innerHTML = '<i class="bi bi-hourglass-split me-1"></i> მუშავდება...'; }

    const reset = function () {
        if (btn) { btn.disabled = false; btn.innerHTML = original; }
    };

    html2canvas(el, {
        scale: 2,
        useCORS: true,
        backgroundColor: '#ffffff',
        windowWidth: el.scrollWidth
    }).then(function (canvas) {
        const { jsPDF } = window.jspdf;
        const pdf = new jsPDF('p', 'mm', 'a4');
        const pageW = pdf.internal.pageSize.getWidth();
        const pageH = pdf.internal.pageSize.getHeight();

        const imgW = pageW;                              // სრული სიგანე — კიდემდე
        const imgH = canvas.height * imgW / canvas.width;
        const imgData = canvas.toDataURL('image/jpeg', 0.98);

        let heightLeft = imgH;
        let position = 0;

        pdf.addImage(imgData, 'JPEG', 0, position, imgW, imgH);
        heightLeft -= pageH;

        while (heightLeft > 0) {
            position -= pageH;
            pdf.addPage();
            pdf.addImage(imgData, 'JPEG', 0, position, imgW, imgH);
            heightLeft -= pageH;
        }

        pdf.save('{{ $invoice->invoice_number }}.pdf');
        reset();
    }).catch(function () {
        reset();
        alert('PDF-ის შექმნა ვერ მოხერხდა. სცადეთ „ბეჭდვა“ ღილაკით.');
    });
}
</script>
@endsection
