<?php 
namespace App\Services;

use App\Models\FinanceTransaction;
use Illuminate\Support\Facades\DB;

class ReportService
{
    public function report($user_id, $request)
    {
        $report = ($request->query('type') == 'daily') ? $this->dailyTransaction($user_id, $request) : $this->monthlyTransaction($user_id, $request);

        return $report;
    }

    public function dailyTransaction($user_id, $request)
    {
        $date       = $request->query('date');
        $start_date = $request->query('start_date');
        $end_date   = $request->query('end_date');

        $transactions = FinanceTransaction::select(
            DB::raw("DATE(finance_transactions.created_at) as dateTransaction"),
            DB::raw("CAST(SUM(finance_transactions.amount) as UNSIGNED) as total_transaction")
            )
            ->join('finance_accounts', 'finance_transactions.account_id', 'finance_accounts.id')
            ->where('finance_accounts.user_id', $user_id)
            ->when($date, function($query) use($date){
                return $query->whereDate('finance_transactions.created_at', $date);
            })
            ->when($start_date || $end_date, function($query) use($start_date, $end_date){
                return $query->whereDate('finance_transactions.created_at','>=',$start_date)->whereDate('finance_transactions.created_at','<=',$end_date);
            })
            ->groupBy('dateTransaction')
            ->get();

            return $transactions;
    }

    public function monthlyTransaction($user_id, $request)
    {
        $month       = $request->query('month');
        $year        = $request->query('year');
        $month_start = $request->query('month_start');
        $month_end   = $request->query('month_end');
        $year_start  = $request->query('year_start');
        $year_end    = $request->query('year_end');

        $transactions = FinanceTransaction::select(
            DB::raw("MONTH(finance_transactions.created_at) as monthNumber"),
            DB::raw("CASE 
                WHEN MONTH(finance_transactions.created_at) = 1 then 'January'
                WHEN MONTH(finance_transactions.created_at) = 2 then 'February'
                WHEN MONTH(finance_transactions.created_at) = 3 then 'March'
                WHEN MONTH(finance_transactions.created_at) = 4 then 'April'
                WHEN MONTH(finance_transactions.created_at) = 5 then 'May'
                WHEN MONTH(finance_transactions.created_at) = 6 then 'June'
                WHEN MONTH(finance_transactions.created_at) = 7 then 'July'
                WHEN MONTH(finance_transactions.created_at) = 8 then 'August'
                WHEN MONTH(finance_transactions.created_at) = 9 then 'September'
                WHEN MONTH(finance_transactions.created_at) = 10 then 'October'
                WHEN MONTH(finance_transactions.created_at) = 11 then 'November'
                WHEN MONTH(finance_transactions.created_at) = 12 then 'Desember'
            END as monthName"),
            DB::raw("CAST(SUM(finance_transactions.amount) as UNSIGNED) as total_transaction")
            )
            ->join('finance_accounts', 'finance_transactions.account_id', 'finance_accounts.id')
            ->where('finance_accounts.user_id', $user_id)
            ->when($month, function($query) use($month){
                return $query->whereMonth('finance_transactions.created_at', $month);
            })
            ->when($year, function($query) use($year){
                return $query->whereYear('finance_transactions.created_at', $year);
            })
            ->when($month_start || $month_end, function($query) use($month_start, $month_end){
                return $query->whereMonth('finance_transactions.created_at','>=' ,$month_start)->whereMonth('finance_transactions.created_at','<=' ,$month_end);
            })
            ->when($year_start || $year_end, function($query) use($year_start, $year_end){
                return $query->whereYear('finance_transactions.created_at','>=' ,$year_start)->whereYear('finance_transactions.created_at','<=' ,$year_end);
            })
            ->groupBy('monthNumber')
            ->groupBy('monthName')
            ->orderBy('monthNumber','asc')
            ->get();

        return $transactions;
    }
}