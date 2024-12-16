<?php

namespace App\Actions\Spotlight\Queries;

use App\Models\UserReport;
use Lorisleiva\Actions\Concerns\AsAction;
use WireElements\Pro\Components\Spotlight\SpotlightQuery;
use WireElements\Pro\Components\Spotlight\SpotlightResult;
use WireElements\Pro\Components\Spotlight\SpotlightScopeToken;

class ReportQuery
{
    use AsAction;

    public function handle()
    {
        return SpotlightQuery::forToken('reports', function (SpotlightScopeToken $reportsToken, $query) {

            $results = collect();

            $reports = (! auth()->user()->hasRole('admin'))
            ? UserReport::query()
                ->where('user_id', auth()->user()->id)
                ->get()
                : UserReport::query()
                    ->get();

            foreach ($reports as $user_report) {
                $results->push(SpotlightResult::make()
                    ->setTitle($user_report->name)
                    ->setGroup('reports_custom')
                    ->setIcon('heart')
                    ->setAction('jump_to', ['path' => route('admin.reports.client', ['report' => $user_report->unique_id])])
                );
            }

            $results->push(SpotlightResult::make()
                ->setTitle('Danışan')
                ->setGroup('reports')
                ->setIcon('chart-bar')
                ->setAction('jump_to', ['path' => route('admin.reports.client')])
            );

            $results->push(SpotlightResult::make()
                ->setTitle('Satış')
                ->setGroup('reports')
                ->setIcon('chart-bar')
                ->setAction('jump_to', ['path' => route('admin.reports.sale')])
            );

            $results->push(SpotlightResult::make()
                ->setTitle('Taksit')
                ->setGroup('reports')
                ->setIcon('chart-bar')
                ->setAction('jump_to', ['path' => route('admin.reports.taksit')])
            );

            $results->push(SpotlightResult::make()
                ->setTitle('Hizmet')
                ->setGroup('reports')
                ->setIcon('chart-bar')
                ->setAction('jump_to', ['path' => route('admin.reports.service')])
            );

            $results->push(SpotlightResult::make()
                ->setTitle('Personel Muhasebe')
                ->setGroup('reports')
                ->setIcon('chart-bar')
            );

            $results->push(SpotlightResult::make()
                ->setTitle('Randevu')
                ->setGroup('reports')
                ->setIcon('chart-bar')
                ->setAction('jump_to', ['path' => route('admin.reports.appointment')])
            );

            $results->push(SpotlightResult::make()
                ->setTitle('Kasa')
                ->setGroup('reports')
                ->setIcon('chart-bar')
                ->setAction('jump_to', ['path' => route('admin.reports.kasa')])
            );

            $results->push(SpotlightResult::make()
                ->setTitle('Ürün Satış')
                ->setGroup('reports')
                ->setIcon('chart-bar')
            );

            $results->push(SpotlightResult::make()
                ->setTitle('Talep')
                ->setGroup('reports')
                ->setIcon('chart-bar')
            );

            $results->push(SpotlightResult::make()
                ->setTitle('Zaman Tüneli')
                ->setGroup('reports')
                ->setIcon('chart-bar')
            );

            $results->push(SpotlightResult::make()
                ->setTitle('SMS')
                ->setGroup('reports')
                ->setIcon('chart-bar')
            );

            $results->push(SpotlightResult::make()
                ->setTitle('Notlar')
                ->setGroup('reports')
                ->setIcon('chart-bar')
            );

            $results->push(SpotlightResult::make()
                ->setTitle('Adisyon')
                ->setGroup('reports')
                ->setIcon('chart-bar')
            );

            $results->push(SpotlightResult::make()
                ->setTitle('Teklif')
                ->setGroup('reports')
                ->setIcon('chart-bar')
            );

            $results->push(SpotlightResult::make()
                ->setTitle('Kupon')
                ->setGroup('reports')
                ->setIcon('chart-bar')
            );

            $results->push(SpotlightResult::make()
                ->setTitle('Onay')
                ->setGroup('reports')
                ->setIcon('chart-bar')
            );

            $results = $results->when(! blank($query), function ($collection) use ($query) {
                return $collection->where(fn (SpotlightResult $result) => str($result->title())->lower()->contains(str($query)->lower()));
            });

            return $results;
        });
    }
}
