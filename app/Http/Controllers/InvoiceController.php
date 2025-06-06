<?php

namespace App\Http\Controllers;

use App\Models\Transactions;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InvoiceController extends Controller
{
    //
    public function print($id)
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

        $pdf = Pdf::loadView('admin.transactions.transactions-invoices', compact('transaction', 'StudentParent'));
        return $pdf->download("hoa-don-{$id}.pdf");
    }


    public function show($id)
    {
        // Lấy thông tin học sinh và phụ huynh
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

        // Lấy thông tin giao dịch
        $transaction = Transactions::select(
            'transactions.*',
            'course_packages.package_name',
            'course_packages.price',
            'course_packages.number_of_sessions'
        )
            ->join('course_packages', 'transactions.course_packages_id', '=', 'course_packages.id')
            ->where('transactions.id', $id)
            ->withSum('transactionDetails as amount_paid', 'amount_paid')
            ->firstOrFail();

        // Tính toán công nợ
         $transaction->debt = $transaction->price - $transaction->amount_paid - $transaction->scholarship_amount;

        // Trả về JSON
        return response()->json([
            'transaction' => $transaction,
            'studentParent' => $StudentParent[0] ?? null
        ]);
    }



    public function showDetail($id, $detailId)
    {

        // Lấy thông tin học sinh và phụ huynh
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

        // Lấy thông tin giao dịch
        $transaction = Transactions::select(
            'transactions.*',
            'course_packages.package_name',
            'course_packages.price',
            'course_packages.number_of_sessions',
            'transaction_details.amount_paid as amount_paid_detail'

        )
            ->join('course_packages', 'transactions.course_packages_id', '=', 'course_packages.id')
            ->join('transaction_details', 'transaction_details.transaction_id', '=', 'transactions.id')
            ->where('transactions.id', $id)
            ->where('transaction_details.id', $detailId)
            ->withSum('transactionDetails as amount_paid', 'amount_paid')
            ->firstOrFail();

        // Tính toán công nợ
         $transaction->debt = $transaction->price - $transaction->amount_paid - $transaction->scholarship_amount;

        // Trả về JSON
        return response()->json([
            'transaction' => $transaction,
            'studentParent' => $StudentParent[0] ?? null
        ]);
    }

    public function printDetail($id, $detailId)
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

        $transaction = Transactions::select(
            'transactions.*',
            'course_packages.package_name',
            'course_packages.price',
            'course_packages.number_of_sessions',
            'transaction_details.amount_paid as amount_paid_detail'

        )
            ->join('course_packages', 'transactions.course_packages_id', '=', 'course_packages.id')
            ->join('transaction_details', 'transaction_details.transaction_id', '=', 'transactions.id')
            ->where('transactions.id', $id)
            ->where('transaction_details.id', $detailId)
            ->withSum('transactionDetails as amount_paid', 'amount_paid')
            ->firstOrFail();


        $pdf = Pdf::loadView('admin.transactions.transactions-invoices', compact('transaction', 'StudentParent'));
        return $pdf->download("hoa-don-{$id}.pdf");
    }
}
