<?php

namespace App\Http\Controllers;

use App\Models\TransactionDetails;
use App\Models\Transactions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class transactionDetailsController extends Controller
{
    //
    public function detail($id)
    {
        $StudentParent = DB::select('
                    SELECT
                t.id AS transaction_id,

                u.id AS student_user_id,
                u.full_name AS student_name,
                u.email AS student_email,
                u.mobile AS student_phone,
                s.school AS student_school,
                s.grade AS student_grade,

                pu.id AS parent_user_id,
                pu.full_name AS parent_name,
                pu.email AS parent_email,
                pu.mobile AS parent_phone

            FROM transactions t
            JOIN students s ON t.student_id = s.user_id
            JOIN users u ON s.user_id = u.id
            LEFT JOIN parents p ON s.parent_id = p.user_id
            LEFT JOIN users pu ON p.user_id = pu.id

            WHERE t.id = ?

        ', [$id]);

        $transaction = Transactions::select('transactions.*', 'course_packages.package_name', 'course_packages.price', 'course_packages.number_of_sessions')
            ->join('course_packages', 'transactions.course_packages_id', '=', 'course_packages.id')
            ->where('transactions.id', $id)
            ->withSum('transactionDetails as amount_paid', 'amount_paid')
            ->firstOrFail();

        // dd($transaction);
        return view('admin.transactions.transactions-detail', compact('transaction', 'StudentParent'));
    }

    public function store(Request $request, $id)
    {
        $validator =  Validator::make($request->all(),[
            'transaction_id' => 'required|exists:transactions,id',
            'amount_paid' => [
                'required',
                'integer',
                'min:0',
                function ($attribute, $value, $fail) use ($request) {
                    //Lấy số nợ của giao dịch
                    $transaction = Transactions::findOrFail($request->transaction_id);
                    $totalPayable = $transaction->debt;
                    if ($value > $totalPayable) {
                        $fail('Số tiền đã thanh toán không được lớn hơn số tiền phải nộp (' . number_format($totalPayable, 0, ',', '.') . ' đồng).');
                    }
                },
            ],
            'note' => 'nullable|string|max:255',
        ], [
            'transaction_id.required' => 'Mã giao dịch không được để trống',
            'transaction_id.exists' => 'Mã giao dịch không tồn tại',
            'amount_paid.required' => 'Số tiền đã thanh toán không được để trống',
            'amount_paid.numeric' => 'Số tiền đã thanh toán phải là số',
            'amount_paid.min' => 'Số tiền đã thanh toán phải lớn hơn hoặc bằng 0',
            'note.string' => 'Ghi chú phải là chuỗi ký tự',
            'note.max' => 'Ghi chú không được vượt quá 255 ký tự',
        ]);


        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }


        // Tạo mới bản ghi trong bảng transaction_details
        // dd($request->all());
        TransactionDetails::create([
            'transaction_id' => $request->transaction_id,
            'amount_paid' => $request->amount_paid,
            'note' => $request->note,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Cập nhật số tiền đã thanh toán trong bảng transactions
        $transaction = Transactions::findOrFail($id);
        $transaction->debt -= $request->amount_paid;
        $transaction->save();


        return redirect()->back()->with('success', 'Thêm thành công');
    }
}
