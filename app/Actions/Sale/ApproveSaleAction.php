<?php

namespace App\Actions\Sale;

use App\Models\Prim;
use App\Models\Sale;
use App\PrimType;
use App\SaleStatus;
use App\StaffMuhasebeType;
use DB;
use Lorisleiva\Actions\Concerns\AsAction;

class ApproveSaleAction
{
    use AsAction;

    public function handle($sale_id, $user_id)
    {
        try {

            DB::beginTransaction();

            $sale = Sale::where('id', $sale_id)->with('staffs:id,name')->first();

            if ($sale->status == SaleStatus::waiting) {
                foreach ($sale->staffs as $staff) {

                    $prim = Prim::where('user_id', $staff->id)
                        ->where('sale_type_id', $sale->sale_type_id)
                        ->where('active', true)
                        ->first();

                    if ($prim) {
                        $prim_price = $prim->type == PrimType::fixed ? $prim->amount : ((int) ($sale->price * $prim->amount) / 100);

                        $prim->staff_muhasebe()->create([
                            'user_id' => $staff->id,
                            'date' => date('Y-m-d'),
                            'type' => StaffMuhasebeType::prim,
                            'message' => $sale->sale_no.' nolu satıştan alınan prim',
                            'prim_price' => $prim_price,
                        ]);
                    } else {

                        $genel_prim = Prim::where('sale_type_id', $sale->sale_type_id)
                            ->where('active', true)
                            ->first();

                        if ($genel_prim) {
                            $genel_prim_price = $genel_prim->type == PrimType::fixed ? $genel_prim->amount : ((int) ($sale->price * $genel_prim->amount) / 100);

                            $genel_prim->staff_muhasebe()->create([
                                'user_id' => $staff->id,
                                'date' => date('Y-m-d'),
                                'type' => StaffMuhasebeType::prim,
                                'message' => $sale->sale_no.' nolu satıştan alınan prim',
                                'prim_price' => $genel_prim_price,
                            ]);
                        }

                    }

                }
            }

            $sale->clientServices()->update([
                'status' => SaleStatus::success,
            ]);

            $sale->clientTaksits()->update([
                'status' => SaleStatus::success,
            ]);

            $sale->status = SaleStatus::success;
            $sale->save();

            DB::commit();

        } catch (\Throwable $e) {
            DB::rollBack();
        }

        //CheckApproveAction::run($user_id) ? SaleStatus::success : SaleStatus::waiting;
    }
}
