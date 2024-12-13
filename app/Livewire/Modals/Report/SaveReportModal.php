<?php

namespace App\Livewire\Modals\Report;

use App\Actions\Spotlight\Actions\Create\CreateUniqueID;
use App\Models\UserReport;
use App\Traits\StrHelper;
use Mary\Traits\Toast;
use WireElements\Pro\Components\SlideOver\SlideOver;

class SaveReportModal extends SlideOver
{
    use StrHelper, Toast;

    public $filters = [];

    public $name = null;

    public $type = null;

    public function mount($filters, $type)
    {
        $this->filters = $filters;
        $this->type = $type;
    }

    public function save()
    {

        $validator = \Validator::make([
            'user_id' => auth()->user()->id,
            'data' => $this->filters,
            'type' => $this->type,
            'unique_id' => CreateUniqueID::run('report'),
            'name' => $this->strUpper($this->name),
        ], [
            'user_id' => 'required',
            'data' => 'required',
            'unique_id' => 'required',
            'type' => 'required',
            'name' => 'required',
        ]);

        if ($validator->fails()) {
            $this->error($validator->messages()->first());

            return;
        }

        if (UserReport::where('type', $this->type)->where('data', $this->filters)->where('user_id', auth()->user()->id)->exists()) {
            $this->error('Bu kriterlerde raporunuz bulunuyor.');

            return;
        }

        UserReport::create($validator->validated());
        $this->success('Kaydedildi.');
        $this->close();
    }

    public function render()
    {
        return view('livewire.spotlight.modals.report.save-report-modal');
    }
}
