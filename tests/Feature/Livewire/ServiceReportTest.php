<?php

use App\Actions\Spotlight\Actions\Report\GetServiceReportAction;
use App\Enum\ReportType;
use App\Livewire\Reports\ServiceReport;
use App\Models\ClientService;
use App\Models\Sale;
use App\Models\Service;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ServiceReportTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function the_component_can_render()
    {
        Livewire::test(ServiceReport::class)
            ->assertStatus(200);
    }

    /** @test */
    public function can_get_report_data()
    {
        // Create some test data
        $user = User::factory()->create();
        $this->actingAs($user);
        $sale = Sale::factory()->create();
        $service = Service::factory()->create();
        $clientService = ClientService::factory()->create([
            'sale_id' => $sale->id,
            'service_id' => $service->id,
        ]);

        // Test with empty filters (adjust as needed for your default behavior)
        $filters = [];
        $sortBy = ['column' => 'created_at', 'direction' => 'desc'];
        $reportData = GetServiceReportAction::run($filters, $sortBy);

        // Assertions about the returned data
        $this->assertNotEmpty($reportData->items());
        $this->assertGreaterThanOrEqual(1, $reportData->total()); // Expect at least one record

        // Assert the Livewire component displays the data (example)
        Livewire::test(ServiceReport::class)
            ->set('filters', $filters)
            ->assertSee($clientService->client->name);  // Or another relevant piece of data
    }

    /** @test  */
    public function can_filter_report_data()
    {
        // Create test data with specific values for filtering
        $user = User::factory()->create();
        $this->actingAs($user);
        $sale = Sale::factory()->create();
        $service = Service::factory()->create(['name' => 'Test Service']); // Example filterable value
        $clientService = ClientService::factory()->create([
            'sale_id' => $sale->id,
            'service_id' => $service->id,
            'created_at' => now()->subDay(), // Example filterable value
        ]);

        // Example filter: Service Name
        $filters = ['service_name' => 'Test Service']; // Assuming you add a service_name filter
        $reportData = GetServiceReportAction::run($filters, ['column' => 'created_at', 'direction' => 'desc']);
        $this->assertEquals(1, $reportData->total());

        // Example filter: Created at date range. Use a range that includes the created_at date
        $filters = ['date_range' => now()->subDays(2).' - '.now()];
        $reportData = GetServiceReportAction::run($filters, ['column' => 'created_at', 'direction' => 'desc']);
        $this->assertEquals(1, $reportData->total());

        // Test a filter that should return no results
        $filters = ['service_name' => 'Nonexistent Service'];
        $reportData = GetServiceReportAction::run($filters, ['column' => 'created_at', 'direction' => 'desc']);
        $this->assertEquals(0, $reportData->total()); // Should be empty
    }

    /** @test */
    public function can_sort_report_data()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        // ... create multiple ClientServices with different created_at times

        // Test sorting ascending
        $sortBy = ['column' => 'created_at', 'direction' => 'asc'];
        Livewire::test(ServiceReport::class)
            ->set('sortBy', $sortBy)
            ->assertSet('sortBy.column', 'created_at')
            ->assertSet('sortBy.direction', 'asc');

        // Test sorting descending (you'll need to add assertions related to the actual order of data in $reportData)
        $sortBy = ['column' => 'created_at', 'direction' => 'desc'];
        Livewire::test(ServiceReport::class)
            ->set('sortBy', $sortBy)
            ->assertSet('sortBy.column', 'created_at')
            ->assertSet('sortBy.direction', 'desc');
    }

    /** @test */
    public function headers_are_correct()
    {
        Livewire::test(ServiceReport::class)
            ->assertSet('reportType', ReportType::report_service)
            ->assertSet('reportName', 'service-report')
            ->assertMethodExists('getHeaders')
            ->call('getHeaders') //  Call the method to get the headers array
            ->assertSee('Tarih')  // Assertions for each header element
            ->assertSee('Satış')
            ->assertSee('Hizmet')
            ->assertSee('Danışan')
            ->assertSee('Durum')
            ->assertSee('Kalan')
            ->assertSee('Toplam')
            ->assertSee('Hediye');
    }

    // ... add more tests for other functionality like report saving, deleting, etc.
}
