<?php

namespace App\Http\Controllers;

use App\Models\CoursePackage;
use App\Models\Student;
use App\Models\TransactionDetails;
use App\Models\Transactions as ModelsTransactions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class transactionsController extends Controller
{
    //
    public function index(Request $request)
    {
        // $transactions = ModelsTransactions::latest()->with('student', 'coursePackage')->paginate(10);
        $transactions = ModelsTransactions::latest()
            ->with(['student', 'coursePackage'])
            ->withSum('transactionDetails as amount_paid', 'amount_paid')
            ->withCount('transactionDetails as transactionDetails')
            ->paginate(10);
        // dd($transactions->all());
        $coursePackages = CoursePackage::all();
        if ($request->ajax()) {
            return response()->json([
                'transactions' => $transactions,
                'pagination' => $transactions->links('pagination::bootstrap-5')->render()
            ]);
        }

        return view('admin.transactions.transactions-list', compact('transactions', 'coursePackages'));
    }

    public function accountsPayable(Request $request)
    {
        $transactions = ModelsTransactions::latest()
            ->where('debt', '>', 0)->with(['student', 'coursePackage'])
            ->withSum('transactionDetails as amount_paid', 'amount_paid')
            ->paginate(10);
        $coursePackages = CoursePackage::all();
        if ($request->ajax()) {
            return response()->json([
                'transactions' => $transactions,
                'pagination' => $transactions->links('pagination::bootstrap-5')->render()
            ]);
        }

        return view('admin.accountsPayable.accountsPayable-list', compact('transactions', 'coursePackages'));
    }

    public function filter(Request $request)
    {
        $transactions = $this->getFilteredTransactions($request);
        $transactions->appends($request->all());

        return response()->json([
            'transactions' => $transactions,
            'pagination' => $transactions->links('pagination::bootstrap-5')->render()
        ]);
    }

    private function getFilteredTransactions(Request $request)
    {
        $query = ModelsTransactions::query()
            ->withSum('transactionDetails as amount_paid', 'amount_paid')
            ->withCount('transactionDetails as transactionDetails')
        ;

        // Lọc theo gói học (nếu có quan hệ hoặc cột)
        if ($request->filled('course_package_id')) {
            $query->where('course_packages_id', $request->course_package_id);
        }

        // Lọc công nợ dựa trên debt_amount
        if ($request->filled('debt')) {
            if ($request->debt == '1') { // Còn nợ
                $query->where('debt', '>', 0);
            } elseif ($request->debt == '0') { // Đã thanh toán đủ
                $query->where('debt', '<=', 0);
            }
        }

        // Lọc ngày giao dịch
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '=', $request->date_from);
        }

        if ($request->filled('keyword')) {
            $keyword = $request->keyword;

            $query->where(function ($q) use ($keyword) {
                $q->where('id', 'like', "%$keyword%")
                    ->orWhereHas('student', function ($q2) use ($keyword) {
                        $q2->where('full_name', 'like', "%$keyword%");
                    });
            });
        }

        return $query->with(['student', 'coursePackage'])->orderByDesc('created_at')->paginate($request->limit ?? 10);
    }




    public function create()
    {
        $course_packages = CoursePackage::all();
        $students = Student::all();
        return view('admin.transactions.transactions-add', compact('course_packages', 'students'));
    }


    public function store(Request $request)
    {
        // dd($request->all());


        $validator = Validator::make($request->all(), [
            'student_id' => 'required|exists:users,id',
            'course_packages_id' => 'required|exists:course_packages,id',
            'promo_sessions' => 'nullable|integer|min:0',
            'scholarship_amount' => 'nullable|integer|min:0',
            'amount_paid' => [
                'required',
                'integer',
                'min:0',
                function ($attribute, $value, $fail) use ($request) {
                    // Lấy giá gói khóa học
                    $coursePackage = \App\Models\CoursePackage::find($request->course_packages_id);
                    $price = $coursePackage ? $coursePackage->price : 0;
                    $scholarshipAmount = $request->scholarship_amount ?? 0;
                    $totalPayable = $price - $scholarshipAmount;

                    if ($value > $totalPayable) {
                        $fail('Số tiền đã thanh toán không được lớn hơn số tiền phải nộp (' . number_format($totalPayable, 0, ',', '.') . ' đồng).');
                    }
                },
            ],
            'debt' => 'required|integer|min:0',
        ], [
            'student_id.required' => 'Vui lòng chọn học viên.',
            'student_id.exists' => 'Học viên không tồn tại.',
            'course_packages_id.required' => 'Vui lòng chọn gói học.',
            'course_packages_id.exists' => 'Gói học không tồn tại.',
            'promo_sessions.integer' => 'Số buổi khuyến mãi phải là số nguyên.',
            'promo_sessions.min' => 'Số buổi khuyến mãi không được âm.',
            'scholarship_amount.integer' => 'Số tiền học bổng phải là số nguyên.',
            'scholarship_amount.min' => 'Số tiền học bổng không được âm.',
            'amount_paid.required' => 'Vui lòng nhập số tiền đã thanh toán.',
            'amount_paid.integer' => 'Số tiền thanh toán phải là số nguyên.',
            'amount_paid.min' => 'Số tiền thanh toán không được âm.',
            'debt.required' => 'Vui lòng nhập số tiền còn nợ.',
            'debt.integer' => 'Số tiền còn nợ phải là số nguyên.',
            'debt.min' => 'Số tiền còn nợ không được âm.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        //Thêm giao dịch
        $transaction = ModelsTransactions::create([
            'student_id' => $request->student_id,
            'course_packages_id' => $request->course_packages_id,
            'promo_sessions' => $request->promo_sessions ?? 0,
            'scholarship_amount' => $request->scholarship_amount ?? 0,
            'debt' => $request->debt,
            'note' => $request->note,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        //Thêm giao dịch chi tiết
        TransactionDetails::create([
            'transaction_id' => $transaction->id,
            'amount_paid' => $request->amount_paid,
            'note' => $request->note,
            'created_at' => now(),
            'updated_at' => now()
        ]);


        //Thêm số buổi đã đăng ký cho học sinh
        $student = Student::find($request->student_id);
        if ($student) {
            $student->registered_sessions = $request->total_sessions ?? 0;
            $student->save();
        }

        return redirect()->route('admin.transactions')->with('success', 'Tạo giao dịch thành công.');
    }



    public function edit($id)
    {
        $course_packages = CoursePackage::all();
        $students = Student::with('parent.user')->get();
        $transaction = ModelsTransactions::select('transactions.*', 'course_packages.package_name', 'course_packages.price', 'course_packages.number_of_sessions')
            ->join('course_packages', 'transactions.course_packages_id', '=', 'course_packages.id')
            ->where('transactions.id', $id)
            ->withSum('transactionDetails as amount_paid', 'amount_paid')
            ->firstOrFail();

        return view('admin.transactions.transactions-edit', compact('transaction', 'course_packages', 'students'));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'student_id' => 'required|exists:users,id',
            'course_packages_id' => 'required|exists:course_packages,id',
            'promo_sessions' => 'nullable|integer|min:0',
            'scholarship_amount' => 'nullable|integer|min:0',
            'amount_paid' => [
                'required',
                'integer',
                'min:0',
                function ($attribute, $value, $fail) use ($request) {
                    // Lấy giá gói khóa học
                    $coursePackage = \App\Models\CoursePackage::find($request->course_packages_id);
                    $price = $coursePackage ? $coursePackage->price : 0;
                    $scholarshipAmount = $request->scholarship_amount ?? 0;
                    $totalPayable = $price - $scholarshipAmount;

                    if ($value > $totalPayable) {
                        $fail('Số tiền đã thanh toán không được lớn hơn số tiền phải nộp (' . number_format($totalPayable, 0, ',', '.') . ' đồng).');
                    }
                },
            ],
            'debt' => 'required|integer|min:0',
        ], [
            'student_id.required' => 'Vui lòng chọn học viên.',
            'student_id.exists' => 'Học viên không tồn tại.',
            'course_packages_id.required' => 'Vui lòng chọn gói học.',
            'course_packages_id.exists' => 'Gói học không tồn tại.',
            'promo_sessions.integer' => 'Số buổi khuyến mãi phải là số nguyên.',
            'promo_sessions.min' => 'Số buổi khuyến mãi không được âm.',
            'scholarship_amount.integer' => 'Số tiền học bổng phải là số nguyên.',
            'scholarship_amount.min' => 'Số tiền học bổng không được âm.',
            'amount_paid.required' => 'Vui lòng nhập số tiền đã thanh toán.',
            'amount_paid.integer' => 'Số tiền thanh toán phải là số nguyên.',
            'amount_paid.min' => 'Số tiền thanh toán không được âm.',
            'debt.required' => 'Vui lòng nhập số tiền còn nợ.',
            'debt.integer' => 'Số tiền còn nợ phải là số nguyên.',
            'debt.min' => 'Số tiền còn nợ không được âm.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $transaction = ModelsTransactions::findOrFail($id);
        $transaction->update([
            'student_id' => $request->student_id,
            'course_packages_id' => $request->course_packages_id,
            'promo_sessions' => $request->promo_sessions ?? 0,
            'scholarship_amount' => $request->scholarship_amount ?? 0,
            'debt' => $request->debt,
            'note' => $request->note,
            'updated_at' => now()
        ]);

        //Cập nhật giao dịch chi tiết
        $transactionDetails = $transaction->transactionDetails()->oldest()->first();
        if ($transactionDetails) {
            $transactionDetails->update([
                'amount_paid' => $request->amount_paid,
                'note' => $request->note,
                'updated_at' => now()
            ]);
        }

        //cập nhật số buổi đã đăng ký cho học sinh
        $student = Student::find($request->student_id);
        if ($student) {
            $student->registered_sessions = $request->total_sessions ?? 0;
            $student->save();
        }

        return redirect()->route('admin.transactions')->with('success', 'Cập nhật giao dịch thành công.');
    }


    public function delete($id)
    {
        $transactionDetails = TransactionDetails::where('transaction_id', $id)->get();
        foreach ($transactionDetails as $transactionDetail) {
            $transactionDetail->delete();
        }
        // Xóa giao dịch
        $transaction = ModelsTransactions::findOrFail($id);
        $transaction->delete();

        return redirect()->back()->with('success', 'Giao dịch đã được xóa thành công.');
    }



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

        $transaction = ModelsTransactions::select('transactions.*', 'course_packages.package_name', 'course_packages.price', 'course_packages.number_of_sessions')
            ->join('course_packages', 'transactions.course_packages_id', '=', 'course_packages.id')
            ->where('transactions.id', $id)
            ->withSum('transactionDetails as amount_paid', 'amount_paid')
            ->firstOrFail();

        // dd($transaction);
        return view('admin.transactions.transactions-detail', compact('transaction', 'StudentParent'));
    }
}
