@extends('layouts.app')
@section('title', 'Procurement & Inventory')
@section('header', 'College Accounts, Procurement, Vendors & Stock Inventory')

@section('content')
<div class="grid lg:grid-cols-2 gap-6 mb-6">
    <div class="bg-white rounded-xl border border-slate-200 p-5">
        <h3 class="text-sm font-semibold text-slate-900 mb-3">Create Purchase Requisition</h3>
        <form method="POST" action="{{ route('procurement.requisitions.store') }}" class="grid grid-cols-2 gap-2.5 text-xs">
            @csrf
            <select name="department_id" class="rounded border border-slate-300 px-3 py-2">@foreach($departments as $d)<option value="{{ $d->id }}">{{ $d->code }}</option>@endforeach</select>
            <input type="number" name="quantity" value="1" min="1" placeholder="Qty" class="rounded border border-slate-300 px-3 py-2">
            <input type="text" name="item_description" placeholder="Item Description *" required class="rounded border border-slate-300 px-3 py-2 col-span-2">
            <input type="number" step="0.01" name="estimated_cost" placeholder="Estimated Cost (₹) *" required class="rounded border border-slate-300 px-3 py-2">
            <button class="rounded-lg bg-indigo-600 text-white font-semibold py-2">Submit Requisition</button>
        </form>
    </div>

    <div class="bg-white rounded-xl border border-slate-200 p-5">
        <h3 class="text-sm font-semibold text-slate-900 mb-3">Issue Purchase Order (PO)</h3>
        <form method="POST" action="{{ route('procurement.orders.store') }}" class="grid grid-cols-2 gap-2.5 text-xs">
            @csrf
            <select name="vendor_id" class="rounded border border-slate-300 px-3 py-2">@foreach($vendors as $v)<option value="{{ $v->id }}">{{ $v->name }}</option>@endforeach</select>
            <select name="purchase_requisition_id" class="rounded border border-slate-300 px-3 py-2"><option value="">Direct PO</option>@foreach($requisitions as $r)<option value="{{ $r->id }}">{{ $r->req_no }}</option>@endforeach</select>
            <input type="date" name="order_date" value="{{ now()->toDateString() }}" class="rounded border border-slate-300 px-3 py-2">
            <input type="number" step="0.01" name="total_amount" placeholder="PO Total (₹) *" required class="rounded border border-slate-300 px-3 py-2">
            <button class="col-span-2 rounded-lg bg-emerald-600 text-white font-semibold py-2">Generate Purchase Order</button>
        </form>
    </div>
</div>

<div class="grid lg:grid-cols-2 gap-6">
    <div class="bg-white rounded-xl border border-slate-200 overflow-x-auto">
        <div class="p-4 border-b font-semibold text-sm">Purchase Requisitions &amp; Orders</div>
        <table class="w-full text-left text-xs">
            <thead class="bg-slate-50 border-b"><tr><th class="p-3">Ref</th><th class="p-3">Item / Vendor</th><th class="p-3">Amount</th><th class="p-3">Status</th></tr></thead>
            <tbody class="divide-y divide-slate-100">
                @foreach($orders as $po)
                    <tr><td class="p-3 font-semibold">{{ $po->po_no }}</td><td class="p-3">{{ $po->vendor->name ?? '' }}</td><td class="p-3">₹{{ number_format($po->total_amount) }}</td><td class="p-3"><span class="px-2 py-0.5 rounded bg-indigo-50 text-indigo-700">{{ $po->status }}</span></td></tr>
                @endforeach
                @foreach($requisitions as $pr)
                    <tr><td class="p-3 font-semibold">{{ $pr->req_no }}</td><td class="p-3">{{ $pr->item_description }} (x{{ $pr->quantity }})</td><td class="p-3">₹{{ number_format($pr->estimated_cost) }}</td><td class="p-3"><span class="px-2 py-0.5 rounded bg-slate-100">{{ $pr->status }}</span></td></tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="bg-white rounded-xl border border-slate-200 overflow-x-auto">
        <div class="p-4 border-b font-semibold text-sm">Department Stock &amp; Lab Inventory</div>
        <table class="w-full text-left text-xs">
            <thead class="bg-slate-50 border-b"><tr><th class="p-3">Code</th><th class="p-3">Item</th><th class="p-3">Category</th><th class="p-3">Qty</th></tr></thead>
            <tbody class="divide-y divide-slate-100">
                @foreach($inventory as $inv)
                    <tr><td class="p-3 font-semibold">{{ $inv->item_code }}</td><td class="p-3">{{ $inv->name }}</td><td class="p-3">{{ $inv->category }}</td><td class="p-3 font-bold">{{ $inv->quantity }} {{ $inv->unit }}</td></tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

