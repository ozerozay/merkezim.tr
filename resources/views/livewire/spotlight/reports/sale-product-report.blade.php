<div>
    <x-report-page title="Ürün Satış Raporu" :filters="$filters" name="{{ \App\Enum\ReportType::report_sale_product }}"
                   namee="sale-product-report" :favori="$favori">
        @if (count($filters) > 0)
            <x-table :headers="$headers" striped :rows="$report_data" :sort-by="$sortBy" with-pagination>
                @can('sale_product_process')
                    @scope('actions', $service)
                    <x-button icon="tabler.settings" wire:key="settings-{{ rand(100000000, 999999999) }}"
                              wire:click="$dispatch('slide-over.open', {component: 'modals.client.sale-product-modal', arguments : {'product' : {{ $service->id }}}})"
                              class="btn-circle btn-sm btn-primary"/>
                    @endscope
                @endcan
                @scope('cell_client.name', $transaction)
                <a class="link link-primary" target="_blank"
                   href="{{ route('admin.client.profil.index', ['user' => $transaction->client_id]) }}">{{ $transaction->client->name ?? '' }}</a>
                @endscope
                @scope('cell_price', $transaction)
                @price($transaction->price)
                @endscope
            </x-table>
        @else
            Filtreleyerek raporu çalıştırabilirsiniz.
        @endif
    </x-report-page>
</div>
