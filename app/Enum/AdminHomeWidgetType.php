<?php

namespace App\Enum;

enum AdminHomeWidgetType: string
{
    case sale = 'sale';
    case appointment = 'appointment';
    case last_transactions = 'last_transactions';
    case agenda = 'agenda';

    public function label(): string
    {
        return match ($this) {
            self::sale => 'Satışlar',
            self::appointment => 'Randevular',
            self::last_transactions => 'Kasa',
            self::agenda => 'Ajanda',
        };
    }

    public function component(): string
    {
        return match ($this) {
            self::last_transactions => 'admin.widgets.last-transactions-widget',
            self::sale => 'admin.widgets.sale-widget',
            self::appointment => 'admin.widgets.appointment-widget',
            self::agenda => 'admin.widgets.agenda-widget',
        };
    }

    public function getColSpan(): array
    {
        return match ($this) {
            self::sale => [
                'default' => 'col-span-1',
                'sm' => 'sm:col-span-1',
                '2xl' => '2xl:col-span-2',
            ],
            self::appointment => [
                'default' => 'col-span-1',
                'sm' => 'sm:col-span-1',
                '2xl' => '2xl:col-span-2',
            ],
            self::last_transactions => [
                'default' => 'col-span-1',
                'sm' => 'sm:col-span-2',
                '2xl' => '2xl:col-span-4',
            ],
            self::agenda => [
                'default' => 'col-span-1',
                'sm' => 'sm:col-span-2',
                '2xl' => '2xl:col-span-4',
            ],
            default => [
                'default' => 'col-span-1',
                'sm' => 'sm:col-span-1',
                '2xl' => '2xl:col-span-1',
            ]
        };
    }

    public function getClasses(): string
    {
        $spans = $this->getColSpan();
        return implode(' ', $spans);
    }
}
