<?php

namespace App\Traits;

use App\Models\Branch;

trait AdminWidgetTrait
{
    public $dateRangeOptions = [
        'today' => 'Bugün',
        'yesterday' => 'Dün',
        'this_week' => 'Bu Hafta',
        'last_week' => 'Geçen Hafta',
        'this_month' => 'Bu Ay',
        'last_month' => 'Geçen Ay',
        'this_year' => 'Bu Yıl',
        'last_year' => 'Geçen Yıl'
    ];

    public $branches = [];
    public $selectedBranches = [];

    public function mount(): void
    {
        $this->loadUserBranches();
        $this->selectedBranches = collect($this->branches)
            ->pluck('id')
            ->map(fn($id) => (string) $id)
            ->toArray();
        $this->loadData();
    }

    public function updatedSelectedBranches($value): void
    {
        if (empty($this->selectedBranches)) {
            $this->selectedBranches = collect($this->branches)
                ->pluck('id')
                ->map(fn($id) => (string) $id)
                ->toArray();
        }

        $this->loadData();
    }

    public function updatedSelectedDateRange($value)
    {
        $this->loadData();
    }

    public function loadUserBranches(): void
    {
        $this->branches = Branch::whereIn('id', auth()->user()->staff_branches)
            ->where('active', true)
            ->get()
            ->map(function ($branch) {
                return [
                    'id' => $branch->id,
                    'name' => $branch->name,
                ];
            })
            ->toArray();
    }

    protected function getDateRange(): array
    {
        $now = now();

        return match ($this->selectedDateRange) {
            'today' => [
                'start' => $now->format('Y-m-d'),
                'end' => $now->format('Y-m-d')
            ],
            'yesterday' => [
                'start' => $now->copy()->subDay()->format('Y-m-d'),
                'end' => $now->copy()->subDay()->format('Y-m-d')
            ],
            'this_week' => [
                'start' => $now->copy()->startOfWeek()->format('Y-m-d'),
                'end' => $now->copy()->endOfWeek()->format('Y-m-d')
            ],
            'last_week' => [
                'start' => $now->copy()->subWeek()->startOfWeek()->format('Y-m-d'),
                'end' => $now->copy()->subWeek()->endOfWeek()->format('Y-m-d')
            ],
            'this_month' => [
                'start' => $now->copy()->startOfMonth()->format('Y-m-d'),
                'end' => $now->copy()->endOfMonth()->format('Y-m-d')
            ],
            'last_month' => [
                'start' => $now->copy()->subMonth()->startOfMonth()->format('Y-m-d'),
                'end' => $now->copy()->subMonth()->endOfMonth()->format('Y-m-d')
            ],
            'last_3_months' => [
                'start' => $now->copy()->subMonths(2)->startOfMonth()->format('Y-m-d'),
                'end' => $now->copy()->endOfMonth()->format('Y-m-d')
            ],
            'this_year' => [
                'start' => $now->copy()->startOfYear()->format('Y-m-d'),
                'end' => $now->copy()->endOfYear()->format('Y-m-d')
            ],
            'last_year' => [
                'start' => $now->copy()->subYear()->startOfYear()->format('Y-m-d'),
                'end' => $now->copy()->subYear()->endOfYear()->format('Y-m-d')
            ],
            default => [
                'start' => $now->format('Y-m-d'),
                'end' => $now->format('Y-m-d')
            ]
        };
    }

    public function placeholder(): string
    {
        return <<<'HTML'
            <div class="relative text-base-content p-2 min-h-[200px]">
                <div class="absolute inset-0 bg-base-200/50 backdrop-blur-sm rounded-lg z-50">
                    <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2">
                        <div class="flex flex-col items-center gap-2">
                            <span class="loading loading-spinner loading-md text-primary"></span>
                            <span class="text-sm text-base-content/70">Yükleniyor...</span>
                        </div>
                    </div>
                </div>
            </div>
            HTML;
    }

    abstract public function loadData(): void;
}
