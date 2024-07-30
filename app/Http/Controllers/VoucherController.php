<?php

namespace App\Http\Controllers;

use App\Models\Voucher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class VoucherController extends Controller
{
    public function index()
    {
        $vouchers = Voucher::all();
        return view('vouchers.index', compact('vouchers'));
    }

    public function create()
    {
        return view('vouchers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|string|unique:vouchers',
            'discount' => 'required|numeric',
            'expiry_date' => 'required|date',
            'photo' => 'nullable|image|max:2048'
        ]);

        $voucherData = $request->only(['code', 'discount', 'expiry_date']);

        if ($request->hasFile('photo')) {
            $voucherData['photo'] = $request->file('photo')->store('vouchers', 'public');
        }

        Voucher::create($voucherData);

        return redirect()->route('vouchers.index')->with('success', 'Voucher created successfully.');
    }

    public function show(Voucher $voucher)
    {
        return view('vouchers.show', compact('voucher'));
    }

    public function edit(Voucher $voucher)
    {
        return view('vouchers.edit', compact('voucher'));
    }

    public function update(Request $request, Voucher $voucher)
    {
        $request->validate([
            'code' => 'required|string|unique:vouchers,code,' . $voucher->id,
            'discount' => 'required|numeric',
            'expiry_date' => 'required|date',
            'photo' => 'nullable|image|max:2048'
        ]);

        $voucherData = $request->only(['code', 'discount', 'expiry_date']);

        if ($request->hasFile('photo')) {
            if ($voucher->photo) {
                Storage::delete($voucher->photo);
            }
            $voucherData['photo'] = $request->file('photo')->store('vouchers', 'public');
        }

        $voucher->update($voucherData);

        return redirect()->route('vouchers.index')->with('success', 'Voucher updated successfully.');
    }

    public function destroy(Voucher $voucher)
    {
        if ($voucher->photo) {
            Storage::delete($voucher->photo);
        }
        $voucher->delete();

        return redirect()->route('vouchers.index')->with('success', 'Voucher deleted successfully.');
    }

    public function apply(Request $request)
    {
        $request->validate([
            'code' => 'required|string|exists:vouchers,code'
        ]);

        $voucher = Voucher::where('code', $request->code)->first();

        if ($voucher && $voucher->expiry_date >= now()) {
            return response()->json(['success' => true, 'discount' => $voucher->discount]);
        } else {
            return response()->json(['success' => false, 'message' => 'Invalid or expired voucher code']);
        }
    }


}
