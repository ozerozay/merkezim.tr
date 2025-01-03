<?php

namespace App\Livewire;

use Livewire\Component;

class SetupWizard extends Component
{
    public $steps = [
        [
            'title' => 'Workspace’inizi bağlayın',
            'substeps' => [
                ['title' => 'Alanınızı doğrulayın', 'completed' => true],
                ['title' => 'Ekip üyelerini davet edin', 'completed' => false],
                ['title' => 'Çalışma şeklinizi yapılandırın', 'completed' => true],
                ['title' => '2 Adımlı Doğrulama özelliğini kullanıcılarınıza sunun', 'completed' => true],
            ],
            'expanded' => true, // İlk adım açık olacak
        ],
        [
            'title' => 'Workspace’inizi kurun',
            'substeps' => [],
            'expanded' => false,
        ],
        [
            'title' => 'Workspace’inizi kullanın',
            'substeps' => [],
            'expanded' => false,
        ],
    ];

    public function toggleStep($stepIndex)
    {
        $this->steps[$stepIndex]['expanded'] = ! $this->steps[$stepIndex]['expanded'];
    }

    public function toggleSubstep($stepIndex, $substepIndex)
    {
        $this->steps[$stepIndex]['substeps'][$substepIndex]['completed'] = ! $this->steps[$stepIndex]['substeps'][$substepIndex]['completed'];
    }

    public function render()
    {
        return view('livewire.setup-wizard');
    }
}
