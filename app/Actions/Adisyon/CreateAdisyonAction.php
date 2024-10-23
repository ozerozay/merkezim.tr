<?php

namespace App\Actions\Adisyon;

use App\Actions\Helper\CreateAdisyonUniqueID;
use App\Exceptions\AppException;
use App\Models\Adisyon;
use App\Models\Kasa;
use App\Models\Package;
use App\Models\Service;
use App\Models\User;
use App\Peren;
use App\TransactionType;
use DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Mary\Exceptions\ToastException;

class CreateAdisyonAction
{
    use AsAction;

    public function handle($info)
    {
        try {

            DB::beginTransaction();

            $client = User::query()
                ->select(['id', 'name', 'branch_id'])
                ->where('id', $info['client_id'])
                ->first();

            $user = User::query()
                ->select(['id', 'name', 'branch_id'])
                ->where('id', $info['user_id'])
                ->first();

            $info['services'] = collect($info['services']);

            $services = Service::query()
                ->select('id', 'name')
                ->whereIn('id', $info['services']->where('type', 'service')->pluck('id'))
                ->get();

            $packages = Package::query()
                ->select('id', 'name')
                ->whereIn('id', $info['services']->where('type', 'package')->pluck('id'))
                ->with('items:id,package_id,service_id,quantity')
                ->get();

            $branch = null;

            if ($client) {
                $branch = $client->branch_id;
            } else {
                $branch = $user->staff_branch()->first();
            }

            $adisyon = Adisyon::create([
                'unique_id' => CreateAdisyonUniqueID::run(),
                'branch_id' => $branch,
                'user_id' => $info['user_id'],
                'client_id' => $client->id ?? null,
                'date' => $info['adisyon_date'],
                'staff_ids' => $info['staff_ids'],
                'message' => $info['message'],
                'price' => $info['price'],
            ]);

            foreach ($info['services']->where('type', 'service')->all() as $s) {
                $adisyon->adisyonServices()->create([
                    'service_id' => $s['service_id'],
                    'total' => $s['quantity'],
                    'gift' => $s['gift'],
                ]);
            }

            foreach ($info['services']->where('type', 'package')->all() as $p) {
                $package = Package::select('id')->where('id', $p['package_id'])->with('items')->first();
                foreach ($package->items as $pi) {
                    $adisyon->adisyonServices()->create([
                        'service_id' => $pi->service_id,
                        'total' => $p['quantity'],
                        'gift' => $p['gift'],
                    ]);
                }
            }

            foreach ($info['cashes'] as $c) {
                $kasa = Kasa::select('id', 'branch_id')->where('id', $c['kasa'])->first();
                $adisyon->transactions()->create([
                    'kasa_id' => $c['kasa'],
                    'branch_id' => $kasa->branch_id,
                    'user_id' => $user->id,
                    'client_id' => $client->id ?? null,
                    'date' => Peren::parseDateField($c['date']),
                    'price' => $c['price'],
                    'message' => $adisyon->unique_id.' nolu adisyondan alınan peşinat',
                    'type' => TransactionType::adisyon_pesinat,
                ]);
            }

            DB::commit();

        } catch (AppException $e) {
            throw ToastException::error($e);
        } catch (\Throwable $e) {
            DB::rollBack();
            throw ToastException::error('İşlem tamamlanamadı.'.$e->getMessage());
        }

    }
}
