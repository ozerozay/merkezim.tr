<?php

namespace App\Actions\SPotlight\Actions\Kasa;

use App\Models\Kasa;
use Carbon\Carbon;
use Lorisleiva\Actions\Concerns\AsAction;

class GetKasaTransactions
{
    use AsAction;

    public function handle($info)
    {
        /*$first_date = null;
        $last_date = null;
        $split_date = preg_split('/\s-\s/', $info['date']);
        if (count($split_date) > 1) {
            $first_date = Carbon::createFromFormat('Y-m-d H:i', $split_date[0])->format('Y-m-d');
            $last_date = Carbon::createFromFormat('Y-m-d H:i', $split_date[1])->format('Y-m-d');
        } else {
            $first_date = $last_date = Carbon::createFromFormat('Y-m-d H:i', $split_date[0])->format('Y-m-d');
        }
*/
        $first_date = $info['first_date'];
        $last_date = $info['last_date'];

        $transactions_sql = Kasa::query()
            ->whereIn('kasas.branch_id', $info['branches'])
            ->where('kasas.active', true)
            ->leftJoin('transactions', 'kasas.id', '=', 'transactions.kasa_id')
            ->leftJoin('branches', 'branches.id', '=', 'kasas.branch_id')
            ->selectRaw('branches.name as branch_name,branches.id as branch_id,kasas.name as kasa_adi, kasas.id as id,
    SUM(CASE WHEN transactions.date < ? THEN transactions.price ELSE 0 END) as devir,
    SUM(CASE WHEN (transactions.date <= DATE(?) and transactions.date >= DATE(?)) AND transactions.price < 0 THEN transactions.price ELSE 0 END) as odenen,
    SUM(CASE WHEN (transactions.date <= ? and transactions.date >= ?) AND transactions.price > 0 THEN transactions.price ELSE 0 END) as tahsilat
', [$first_date, $last_date, $first_date, $last_date, $first_date])
            ->groupBy('kasas.id', 'kasas.name')
            ->get();

        return collect($transactions_sql)->groupBy('branch_id');
    }
}
