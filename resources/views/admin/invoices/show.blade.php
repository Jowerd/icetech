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
.inv-date-val { font-size: 0.75rem; color: rgba(255,255,255,0.6); margin-top: 2px; }

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
.inv-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
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
@endphp

@section('content')
<div class="container-fluid px-0">

    <div class="d-flex gap-2 mb-3 no-print px-3 pt-3">
        <a href="{{ route('admin.invoices.index') }}" class="btn btn-light border rounded-1 small fw-bold">
            <i class="bi bi-arrow-left me-1"></i> უკან
        </a>
        <button onclick="downloadPdf(this)" class="btn btn-primary rounded-1 small fw-bold">
            <i class="bi bi-download me-1"></i> PDF ჩამოტვირთვა
        </button>
        <button onclick="window.print()" class="btn btn-light border rounded-1 small fw-bold">
            <i class="bi bi-printer me-1"></i> ბეჭდვა
        </button>
        <form action="{{ route('admin.invoices.destroy', $invoice) }}" method="POST" class="ms-auto"
              onsubmit="return confirm('წავშალო?')">
            @csrf @method('DELETE')
            <button class="btn btn-light border rounded-1 small text-danger fw-bold">
                <i class="bi bi-trash me-1"></i> წაშლა
            </button>
        </form>
    </div>

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
            <div class="inv-date-line">თარიღი: <strong>{{ $invoice->issue_date->format('d.m.Y') }}</strong></div>

            {{-- 2. გამყიდველი / მყიდველი --}}
            <div class="row g-3 mb-3">
                <div class="col-6">
                    <div class="party-box">
                        <h6>გამყიდველი:</h6>
                        <div class="p-name">{{ $invoice->seller_name }}</div>
                        @if($invoice->seller_id_number) <p class="p-detail">ს.ნ.: {{ $invoice->seller_id_number }}</p> @endif
                        @if($invoice->seller_address)   <p class="p-detail">{{ $invoice->seller_address }}</p> @endif
                        @if($invoice->seller_phone)     <p class="p-detail">{{ $invoice->seller_phone }}</p> @endif
                        @if($invoice->seller_email)     <p class="p-detail">{{ $invoice->seller_email }}</p> @endif
                    </div>
                </div>
                <div class="col-6">
                    <div class="party-box buyer">
                        <h6>მყიდველი:</h6>
                        @if($invoice->buyer_name)
                            <div class="p-name">{{ $invoice->buyer_name }}</div>
                            @if($invoice->buyer_id_number) <p class="p-detail">ს.ნ.: {{ $invoice->buyer_id_number }}</p> @endif
                            @if($invoice->buyer_address)   <p class="p-detail">{{ $invoice->buyer_address }}</p> @endif
                            @if($invoice->buyer_phone)     <p class="p-detail">{{ $invoice->buyer_phone }}</p> @endif
                            @if($invoice->buyer_email)     <p class="p-detail">{{ $invoice->buyer_email }}</p> @endif
                        @else
                            <p class="p-detail" style="color:#aaa;font-style:italic;">—</p>
                        @endif
                    </div>
                </div>
            </div>

            {{-- 3. PRODUCTS TABLE --}}
            <table class="inv-table">
                <thead>
                    <tr>
                        <th style="width:32px;">#</th>
                        <th>დასახელება</th>
                        <th class="text-center" style="width:65px;">რ-ბა</th>
                        <th class="text-end" style="width:110px;">ფასი</th>
                        <th class="text-end" style="width:110px;">ჯამი</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($invoice->items as $i => $item)
                    <tr>
                        <td class="text-muted">{{ $i + 1 }}</td>
                        <td>
                            @if($item->image)
                                <img src="{{ asset('storage/' . $item->image) }}" class="prod-thumb" alt="">
                            @endif
                            <span class="fw-semibold">{{ $item->name }}</span>
                        </td>
                        <td class="text-center">{{ $item->quantity }}</td>
                        <td class="text-end">{{ number_format($item->unit_price, 2) }} ₾</td>
                        <td class="text-end fw-bold">{{ number_format($item->total, 2) }} ₾</td>
                    </tr>
                    @endforeach
                    <tr class="subtotal-row">
                        <td colspan="4" class="text-end">ჯამი</td>
                        <td class="text-end">{{ number_format($invoice->total, 2) }} ₾</td>
                    </tr>
                </tbody>
            </table>

            {{-- 4. შენიშვნა --}}
            @if($invoice->notes)
                <div class="mb-3">
                    <div style="font-size:0.68rem;font-weight:800;text-transform:uppercase;letter-spacing:.6px;color:#1a365d;margin-bottom:5px;">შენიშვნა:</div>
                    <div style="font-size:0.82rem;color:#555;border-bottom:1px solid #ddd;padding-bottom:6px;">{{ $invoice->notes }}</div>
                </div>
            @endif

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
            @if($invoice->seller_bank || $invoice->seller_bank2)
            <div class="payment-box">
                <h6>გადახდის ინფო:</h6>
                @if($invoice->seller_bank)
                    @php $logo1 = $bankLogo($invoice->seller_bank); @endphp
                    <div class="payment-line">
                        @if($logo1 && file_exists(public_path($logo1)))
                            <img src="{{ asset($logo1) }}" class="bank-logo" alt="{{ $invoice->seller_bank }}">
                        @else
                            <span class="bank-name">{{ $invoice->seller_bank }}:</span>
                        @endif
                        <span class="bank-account">{{ $invoice->seller_account }}</span>
                    </div>
                @endif
                @if($invoice->seller_bank2)
                    @php $logo2 = $bankLogo($invoice->seller_bank2); @endphp
                    <div class="payment-line">
                        @if($logo2 && file_exists(public_path($logo2)))
                            <img src="{{ asset($logo2) }}" class="bank-logo" alt="{{ $invoice->seller_bank2 }}">
                        @else
                            <span class="bank-name">{{ $invoice->seller_bank2 }}:</span>
                        @endif
                        <span class="bank-account">{{ $invoice->seller_account2 }}</span>
                    </div>
                @endif
            </div>
            @endif

        </div>
    </div>

</div>
@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script>
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
