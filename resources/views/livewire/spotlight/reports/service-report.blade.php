<div>
    <x-report-page title="Hizmet Raporu" :filters="$filters"
        name="{{ \App\Enum\ReportType::report_service }}" namee="service-report" :favori="$favori">
        @if (count($filters) > 0)
            <x-table :headers="$headers" striped :rows="$report_data" :sort-by="$sortBy" with-pagination>

            </x-table>
        @else
            Filtreleyerek raporu çalıştırabilirsiniz.
        @endif
    </x-report-page>
</div>
