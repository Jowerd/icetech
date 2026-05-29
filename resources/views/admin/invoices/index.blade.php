@extends('admin.layout')
@section('title', 'ინვოისები • ICETECH')

@section('content')
<div class="container-fluid px-0 px-md-2">

    <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom">
        <h4 class="fw-bold text-dark mb-0">ინვოისები</h4>
        <a href="{{ route('admin.invoices.create') }}" class="btn btn-primary rounded-1 fw-bold px-4 small shadow-sm">
            <i class="bi bi-plus-lg me-1"></i> ახალი ინვოისი
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success rounded-1 py-2 small">{{ session('success') }}</div>
    @endif

    @if($invoices->isEmpty())
        <div class="text-center py-5 text-muted">
            <i class="bi bi-receipt fs-1 d-block mb-3 opacity-25"></i>
            ინვოისები ჯერ არ არის. <a href="{{ route('admin.invoices.create') }}">შექმენი პირველი</a>.
        </div>
    @else
        <div class="card border rounded-1 shadow-none">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 small">
                    <thead class="bg-light text-uppercase x-small-text text-secondary">
                        <tr>
                            <th class="ps-3 py-2">ნომერი</th>
                            <th class="py-2">თარიღი</th>
                            <th class="py-2">მყიდველი</th>
                            <th class="py-2">პროდუქტები</th>
                            <th class="py-2 text-end">ჯამი</th>
                            <th class="py-2"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($invoices as $invoice)
                        <tr>
                            <td class="ps-3 fw-bold text-primary">{{ $invoice->invoice_number }}</td>
                            <td class="text-muted">{{ $invoice->issue_date->format('d.m.Y') }}</td>
                            <td>{{ $invoice->buyer_name ?: '—' }}</td>
                            <td class="text-muted">{{ $invoice->items_count ?? '—' }}</td>
                            <td class="text-end fw-bold">₾ {{ number_format($invoice->total, 2) }}</td>
                            <td class="text-end pe-3">
                                <a href="{{ route('admin.invoices.show', $invoice) }}" class="btn btn-sm btn-light border rounded-1 me-1">
                                    <i class="bi bi-printer"></i>
                                </a>
                                <form action="{{ route('admin.invoices.destroy', $invoice) }}" method="POST" class="d-inline"
                                      onsubmit="return confirm('წავშალო ინვოისი?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-light border rounded-1 text-danger">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="mt-3">{{ $invoices->links('pagination::bootstrap-5') }}</div>
    @endif

</div>
@endsection
