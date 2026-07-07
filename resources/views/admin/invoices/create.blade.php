@extends('admin.layout')
@section('title', 'ახალი ინვოისი • ICETECH')

@section('content')
<div class="container-fluid px-0 px-md-2">

<form action="{{ route('admin.invoices.store') }}" method="POST" id="invoiceForm">
@csrf

<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 pb-3 border-bottom gap-3">
    <h4 class="fw-bold text-dark mb-0">ახალი ინვოისი</h4>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.invoices.index') }}" class="btn btn-light border rounded-1 fw-bold px-3 small">გაუქმება</a>
        <button type="submit" class="btn btn-primary rounded-1 fw-bold px-4 small shadow-sm">
            <i class="bi bi-check-lg me-1"></i> შენახვა
        </button>
    </div>
</div>

<div class="row g-4">

    {{-- მარცხენა — გამყიდველი + მყიდველი --}}
    <div class="col-12 col-lg-5">

        {{-- თარიღი --}}
        <div class="card border rounded-1 shadow-none bg-white mb-4">
            <div class="card-header bg-light py-2 border-bottom">
                <h6 class="mb-0 fw-bold text-uppercase x-small-text text-secondary">ინვოისის დეტალები</h6>
            </div>
            <div class="card-body p-3">
                <label class="form-label small fw-bold">თარიღი</label>
                <input type="date" name="issue_date" value="{{ date('Y-m-d') }}" class="form-control border-2 shadow-none small mb-0" required>
            </div>
        </div>

        {{-- გამყიდველი --}}
        <div class="card border rounded-1 shadow-none bg-white mb-4">
            <div class="card-header bg-light py-2 border-bottom d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-bold text-uppercase x-small-text text-secondary">გამყიდველი</h6>
            </div>
            <div class="card-body p-3">
                <div class="row g-2">
                    <div class="col-12">
                        <label class="form-label x-small fw-bold text-muted">სახელი / კომპანია *</label>
                        <input type="text" name="seller_name" value="{{ $seller['name'] }}" class="form-control border-2 shadow-none small" required>
                    </div>
                    <div class="col-6">
                        <label class="form-label x-small fw-bold text-muted">საიდ / ს.ნ.</label>
                        <input type="text" name="seller_id_number" value="{{ $seller['id_number'] }}" class="form-control border-2 shadow-none small">
                    </div>
                    <div class="col-6">
                        <label class="form-label x-small fw-bold text-muted">ტელეფონი</label>
                        <input type="text" name="seller_phone" value="{{ $seller['phone'] }}" class="form-control border-2 shadow-none small">
                    </div>
                    <div class="col-12">
                        <label class="form-label x-small fw-bold text-muted">მისამართი</label>
                        <input type="text" name="seller_address" value="{{ $seller['address'] }}" class="form-control border-2 shadow-none small">
                    </div>
                    <div class="col-12">
                        <label class="form-label x-small fw-bold text-muted">ელ.ფოსტა</label>
                        <input type="email" name="seller_email" value="{{ $seller['email'] }}" class="form-control border-2 shadow-none small">
                    </div>
                    <div class="col-6">
                        <label class="form-label x-small fw-bold text-muted">ბანკი 1</label>
                        <input type="text" name="seller_bank" value="{{ $seller['bank'] }}" class="form-control border-2 shadow-none small">
                    </div>
                    <div class="col-6">
                        <label class="form-label x-small fw-bold text-muted">ანგარიში 1</label>
                        <input type="text" name="seller_account" value="{{ $seller['account'] }}" class="form-control border-2 shadow-none small">
                    </div>
                    <div class="col-6">
                        <label class="form-label x-small fw-bold text-muted">ბანკი 2</label>
                        <input type="text" name="seller_bank2" value="{{ $seller['bank2'] }}" class="form-control border-2 shadow-none small">
                    </div>
                    <div class="col-6">
                        <label class="form-label x-small fw-bold text-muted">ანგარიში 2</label>
                        <input type="text" name="seller_account2" value="{{ $seller['account2'] }}" class="form-control border-2 shadow-none small">
                    </div>
                </div>
            </div>
        </div>

        {{-- მყიდველი --}}
        <div class="card border rounded-1 shadow-none bg-white mb-4">
            <div class="card-header bg-light py-2 border-bottom d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-bold text-uppercase x-small-text text-secondary">მყიდველი <span class="text-muted fw-normal">(სავალდებულო არ არის)</span></h6>
                @if($buyers->isNotEmpty())
                    <select id="buyerSelect" class="form-select form-select-sm border-1 shadow-none w-auto" style="font-size:0.75rem;">
                        <option value="">— შენახული —</option>
                        @foreach($buyers as $b)
                            <option value="{{ $b->id }}"
                                data-name="{{ $b->name }}"
                                data-id="{{ $b->id_number }}"
                                data-address="{{ $b->address }}"
                                data-phone="{{ $b->phone }}"
                                data-email="{{ $b->email }}"
                                data-bank="{{ $b->bank }}"
                                data-account="{{ $b->account }}">
                                {{ $b->name }}
                            </option>
                        @endforeach
                    </select>
                @endif
            </div>
            <div class="card-body p-3">
                <div class="row g-2">
                    <div class="col-12">
                        <label class="form-label x-small fw-bold text-muted">სახელი / კომპანია</label>
                        <input type="text" name="buyer_name" id="buyer_name" class="form-control border-2 shadow-none small">
                    </div>
                    <div class="col-6">
                        <label class="form-label x-small fw-bold text-muted">საიდ / ს.ნ.</label>
                        <input type="text" name="buyer_id_number" id="buyer_id_number" class="form-control border-2 shadow-none small">
                    </div>
                    <div class="col-6">
                        <label class="form-label x-small fw-bold text-muted">ტელეფონი</label>
                        <input type="text" name="buyer_phone" id="buyer_phone" class="form-control border-2 shadow-none small">
                    </div>
                    <div class="col-12">
                        <label class="form-label x-small fw-bold text-muted">მისამართი</label>
                        <input type="text" name="buyer_address" id="buyer_address" class="form-control border-2 shadow-none small">
                    </div>
                    <div class="col-12">
                        <label class="form-label x-small fw-bold text-muted">ელ.ფოსტა</label>
                        <input type="email" name="buyer_email" id="buyer_email" class="form-control border-2 shadow-none small">
                    </div>
                    <div class="col-6">
                        <label class="form-label x-small fw-bold text-muted">ბანკი</label>
                        <input type="text" name="buyer_bank" id="buyer_bank" class="form-control border-2 shadow-none small">
                    </div>
                    <div class="col-6">
                        <label class="form-label x-small fw-bold text-muted">ანგარიში</label>
                        <input type="text" name="buyer_account" id="buyer_account" class="form-control border-2 shadow-none small">
                    </div>
                    <div class="col-12 pt-1">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="save_buyer" id="save_buyer" value="1">
                            <label class="form-check-label x-small" for="save_buyer">ამ კომპანიის შენახვა სიაში</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- შენიშვნა --}}
        <div class="card border rounded-1 shadow-none bg-white">
            <div class="card-header bg-light py-2 border-bottom">
                <h6 class="mb-0 fw-bold text-uppercase x-small-text text-secondary">შენიშვნა</h6>
            </div>
            <div class="card-body p-3">
                <textarea name="notes" rows="3" class="form-control border-2 shadow-none small" placeholder="სურვილისამებრ..."></textarea>
            </div>
        </div>

    </div>

    {{-- მარჯვენა — პროდუქტები --}}
    <div class="col-12 col-lg-7">
        <div class="card border rounded-1 shadow-none bg-white">
            <div class="card-header bg-light py-2 border-bottom d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-bold text-uppercase x-small-text text-secondary">პროდუქტები</h6>
            </div>
            <div class="card-body p-3">

                {{-- პროდუქტის ძიება --}}
                <div class="mb-3 position-relative">
                    <input type="text" id="productSearch" class="form-control border-2 shadow-none small"
                           placeholder="კატალოგიდან პროდუქტის ძიება...">
                    <div id="productDropdown" class="position-absolute w-100 bg-white border rounded-1 shadow-sm mt-1 d-none" style="z-index:999; max-height:250px; overflow-y:auto;"></div>
                </div>

                {{-- ხელით დამატება --}}
                <button type="button" id="addCustomRow" class="btn btn-light border btn-sm rounded-1 mb-3 small">
                    <i class="bi bi-plus me-1"></i> ხელით დამატება
                </button>

                {{-- ნივთების სია --}}
                <div id="itemsContainer"></div>

                {{-- ჯამი --}}
                <div class="border-top pt-3 text-end">
                    <span class="fw-bold">სულ: ₾ <span id="grandTotal">0.00</span></span>
                </div>

            </div>
        </div>
    </div>

</div>
</form>
</div>

{{-- Item template (hidden) --}}
<template id="itemRowTemplate">
    <div class="item-row border rounded-1 p-2 mb-2 bg-light d-flex gap-2 align-items-start">
        <input type="hidden" name="items[__IDX__][product_id]" class="item-product-id">
        <input type="hidden" name="items[__IDX__][image]"      class="item-image">
        <div class="item-img-wrap flex-shrink-0" title="ფოტოს ატვირთვა" style="width:48px;height:48px;background:#fff;border:1px solid #eee;border-radius:6px;overflow:hidden;display:flex;align-items:center;justify-content:center;cursor:pointer;position:relative;">
            <img class="item-thumb" src="" alt="" style="width:100%;height:100%;object-fit:contain;display:none;">
            <i class="bi bi-camera text-muted item-noimg"></i>
        </div>
        <input type="file" class="item-file" accept="image/*" style="display:none;">
        <div class="flex-grow-1">
            <input type="text" name="items[__IDX__][name]" placeholder="დასახელება *"
                   class="form-control form-control-sm border-1 shadow-none mb-1 small item-name" required>
            <div class="d-flex gap-1">
                <input type="number" name="items[__IDX__][quantity]" value="1" min="1"
                       class="form-control form-control-sm border-1 shadow-none small item-qty" style="width:70px;" required>
                <span class="align-self-center text-muted small">×</span>
                <input type="number" name="items[__IDX__][unit_price]" value="0" min="0" step="0.01"
                       class="form-control form-control-sm border-1 shadow-none small item-price" style="width:100px;" required>
                <span class="align-self-center text-muted small">= ₾</span>
                <span class="align-self-center fw-bold small item-total" style="min-width:70px;">0.00</span>
            </div>
        </div>
        <button type="button" class="btn btn-sm text-danger remove-row flex-shrink-0 p-1">
            <i class="bi bi-x-lg"></i>
        </button>
    </div>
</template>

@section('scripts')
<script>
const products = @json($products);
let rowIndex = 0;

const CSRF = '{{ csrf_token() }}';
const UPLOAD_URL = '{{ route('admin.invoices.upload-image') }}';

function uploadCreateImage(input, row) {
    const file = input.files && input.files[0];
    if (!file) return;
    const wrap = row.querySelector('.item-img-wrap');
    const fd = new FormData();
    fd.append('image', file);
    fd.append('_token', CSRF);
    fetch(UPLOAD_URL, { method: 'POST', body: fd, headers: { 'X-Requested-With': 'XMLHttpRequest' } })
        .then(r => r.ok ? r.json() : Promise.reject())
        .then(d => {
            if (!d.path) return Promise.reject();
            row.querySelector('.item-image').value = d.path;
            const thumb = row.querySelector('.item-thumb');
            thumb.src = d.url; thumb.style.display = '';
            row.querySelector('.item-noimg').style.display = 'none';
        })
        .catch(() => alert('ფოტოს ატვირთვა ვერ მოხერხდა.'))
        .finally(() => { input.value = ''; });
}

// პროდუქტების ძიება
const searchInput  = document.getElementById('productSearch');
const dropdown     = document.getElementById('productDropdown');

searchInput.addEventListener('input', function() {
    const q = this.value.trim().toLowerCase();
    if (q.length < 1) { dropdown.classList.add('d-none'); return; }
    const matches = products.filter(p => p.name.toLowerCase().includes(q)).slice(0, 10);
    if (!matches.length) { dropdown.classList.add('d-none'); return; }
    dropdown.innerHTML = matches.map(p => `
        <div class="px-3 py-2 d-flex align-items-center gap-2 product-result" style="cursor:pointer;"
             data-id="${p.id}" data-name="${p.name}" data-price="${p.price}"
             data-image="${p.image ? '{{ asset('storage/') }}/' + p.image : ''}">
            ${p.image
                ? `<img src="{{ asset('storage/') }}/${p.image}" style="width:32px;height:32px;object-fit:contain;border-radius:4px;">`
                : `<div style="width:32px;height:32px;background:#f0f0f0;border-radius:4px;"></div>`}
            <div class="small">
                <div class="fw-bold">${p.name}</div>
                <div class="text-muted">₾ ${parseFloat(p.price).toFixed(2)}</div>
            </div>
        </div>
    `).join('');
    dropdown.classList.remove('d-none');
});

dropdown.addEventListener('click', function(e) {
    const row = e.target.closest('.product-result');
    if (!row) return;
    addRow({
        product_id: row.dataset.id,
        name:       row.dataset.name,
        price:      row.dataset.price,
        image:      row.dataset.image,
    });
    searchInput.value = '';
    dropdown.classList.add('d-none');
});

document.addEventListener('click', e => {
    if (!searchInput.contains(e.target) && !dropdown.contains(e.target))
        dropdown.classList.add('d-none');
});

// ხელით დამატება
document.getElementById('addCustomRow').addEventListener('click', () => addRow());

function addRow(data = {}) {
    const tpl = document.getElementById('itemRowTemplate').innerHTML
        .replace(/__IDX__/g, rowIndex++);
    const tmp = document.createElement('div');
    tmp.innerHTML = tpl;
    const row = tmp.firstElementChild;

    if (data.product_id) row.querySelector('.item-product-id').value = data.product_id;
    if (data.name)       row.querySelector('.item-name').value       = data.name;
    if (data.price)      row.querySelector('.item-price').value      = parseFloat(data.price).toFixed(2);
    if (data.image) {
        row.querySelector('.item-image').value  = data.image.replace('{{ asset('storage/') }}/', '').replace('{{ asset('') }}', '');
        row.querySelector('.item-thumb').src    = data.image;
        row.querySelector('.item-thumb').style.display  = '';
        row.querySelector('.item-noimg').style.display  = 'none';
    }

    row.querySelector('.item-qty').addEventListener('input',   () => updateRow(row));
    row.querySelector('.item-price').addEventListener('input',  () => updateRow(row));
    row.querySelector('.remove-row').addEventListener('click',  () => { row.remove(); updateTotal(); });
    row.querySelector('.item-img-wrap').addEventListener('click', () => row.querySelector('.item-file').click());
    row.querySelector('.item-file').addEventListener('change', function () { uploadCreateImage(this, row); });

    document.getElementById('itemsContainer').appendChild(row);
    updateRow(row);
}

function updateRow(row) {
    const qty   = parseFloat(row.querySelector('.item-qty').value)   || 0;
    const price = parseFloat(row.querySelector('.item-price').value) || 0;
    const total = qty * price;
    row.querySelector('.item-total').textContent = total.toFixed(2);
    updateTotal();
}

function updateTotal() {
    const sum = [...document.querySelectorAll('.item-total')]
        .reduce((s, el) => s + parseFloat(el.textContent), 0);
    document.getElementById('grandTotal').textContent = sum.toFixed(2);
}

// შენახული მყიდველის ჩატვირთვა
const buyerSelect = document.getElementById('buyerSelect');
if (buyerSelect) {
    buyerSelect.addEventListener('change', function() {
        const opt = this.selectedOptions[0];
        if (!opt.value) return;
        document.getElementById('buyer_name').value      = opt.dataset.name    || '';
        document.getElementById('buyer_id_number').value = opt.dataset.id      || '';
        document.getElementById('buyer_address').value   = opt.dataset.address || '';
        document.getElementById('buyer_phone').value     = opt.dataset.phone   || '';
        document.getElementById('buyer_email').value     = opt.dataset.email   || '';
        document.getElementById('buyer_bank').value      = opt.dataset.bank    || '';
        document.getElementById('buyer_account').value   = opt.dataset.account || '';
    });
}

// ვალიდაცია — მინიმუმ 1 პროდუქტი
document.getElementById('invoiceForm').addEventListener('submit', function(e) {
    if (document.querySelectorAll('.item-row').length === 0) {
        e.preventDefault();
        alert('დაამატე მინიმუმ 1 პროდუქტი.');
    }
});
</script>
@endsection
@endsection
