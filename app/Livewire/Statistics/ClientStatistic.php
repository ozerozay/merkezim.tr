<?php

namespace App\Livewire\Statistics;

use Livewire\Component;

class ClientStatistic extends Component
{
    public array $myChart = [
        'type' => 'pie',
        'options' => [
            'plugins' => [
                'title' => [
                    'display' => true,
                    'text' => 'Yaşlara Göre',
                ],
                'colors' => [
                    'forceOverride' => true,
                ],
            ],
        ],
        'data' => [
            'labels' => ['18-24', '24-45', '45-60+'],
            'datasets' => [
                [
                    'label' => 'Mecidiyeköy',
                    'data' => [12, 19, 3],
                ],
                [
                    'label' => 'Bakırköy',
                    'data' => [3, 8, 13],
                ],
            ],

        ],
    ];

    public function render()
    {
        return view('livewire.spotlight.statistics.client-statistic');
    }
}
