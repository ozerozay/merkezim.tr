<?php

namespace App\Traits;

use Mary\Traits\Toast;

trait ReportFilterHandler
{
    use Toast;

    public function mount($filters)
    {
        $this->fill($filters);
    }

    public function save()
    {
        $this->dispatch('report-filter', $this->all());
        $this->close();
    }
}
