<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\User;

class MerkezimSpotlight extends Component
{
    public $isOpen = false;
    public $search = '';
    public $selectedIndex = 0;
    public $currentPath = [];
    public $items = [];
    public $filteredItems = [];
    public $isLoading = false;
    public $weather = [
        'temp' => '23°',
        'icon' => '⛅',
        'description' => 'Parçalı Bulutlu'
    ];
    public $isModalOpen = false;
    public $modalTitle = '';
    public $modalContent = '';

    public function mount()
    {
        $this->items = [
            [
                'id' => 'home',
                'icon' => '🏠',
                'label' => 'Anasayfa',
                'group' => 'Ana Menü',
                'description' => 'Kontrol paneline git',
                'action' => 'goToDashboard'
            ],
            [
                'id' => 'calendar',
                'icon' => '📅',
                'label' => 'Ajanda',
                'group' => 'Ana Menü',
                'description' => 'Ajanda ve takvim yönetimi',
                'children' => [
                    [
                        'id' => 'calendar-view',
                        'icon' => '📆',
                        'label' => 'Takvim Görünümü',
                        'action' => 'showCalendar'
                    ],
                    [
                        'id' => 'calendar-list',
                        'icon' => '📋',
                        'label' => 'Liste Görünümü',
                        'action' => 'showCalendarList'
                    ],
                    [
                        'id' => 'calendar-add',
                        'icon' => '➕',
                        'label' => 'Etkinlik Ekle',
                        'action' => 'addCalendarEvent'
                    ]
                ]
            ],
            [
                'id' => 'requests',
                'icon' => '📨',
                'label' => 'Talepler',
                'group' => 'İşlemler',
                'description' => 'Talep yönetimi',
                'children' => [
                    [
                        'id' => 'new-request',
                        'icon' => '✏️',
                        'label' => 'Yeni Talep',
                        'action' => 'createRequest'
                    ],
                    [
                        'id' => 'my-requests',
                        'icon' => '📥',
                        'label' => 'Taleplerim',
                        'action' => 'showMyRequests'
                    ],
                    [
                        'id' => 'pending-requests',
                        'icon' => '⏳',
                        'label' => 'Bekleyen Talepler',
                        'action' => 'showPendingRequests'
                    ]
                ]
            ],
            [
                'id' => 'approvals',
                'icon' => '✅',
                'label' => 'Onaylar',
                'group' => 'İşlemler',
                'description' => 'Onay süreçleri',
                'children' => [
                    [
                        'id' => 'pending-approvals',
                        'icon' => '⌛',
                        'label' => 'Bekleyen Onaylar',
                        'action' => 'showPendingApprovals'
                    ],
                    [
                        'id' => 'approved-items',
                        'icon' => '✔️',
                        'label' => 'Onaylananlar',
                        'action' => 'showApprovedItems'
                    ],
                    [
                        'id' => 'rejected-items',
                        'icon' => '❌',
                        'label' => 'Reddedilenler',
                        'action' => 'showRejectedItems'
                    ]
                ]
            ],
            [
                'id' => 'appointments',
                'icon' => '🗓️',
                'label' => 'Randevular',
                'group' => 'Randevu Yönetimi',
                'description' => 'Randevu işlemleri',
                'children' => [
                    [
                        'id' => 'new-appointment',
                        'icon' => '➕',
                        'label' => 'Yeni Randevu',
                        'action' => 'createAppointment'
                    ],
                    [
                        'id' => 'my-appointments',
                        'icon' => '📋',
                        'label' => 'Randevularım',
                        'action' => 'showMyAppointments'
                    ],
                    [
                        'id' => 'appointment-calendar',
                        'icon' => '📅',
                        'label' => 'Randevu Takvimi',
                        'action' => 'showAppointmentCalendar'
                    ]
                ]
            ]
        ];

        $this->filteredItems = $this->items;
    }

    public function updatedSearch()
    {
        $this->isLoading = true;
        $this->selectedIndex = 0;
        $this->filterItems();
        $this->isLoading = false;
    }

    public function filterItems()
    {
        if (!empty($this->search)) {
            $this->isLoading = true;
            $menuResults = $this->searchInTree($this->items, $this->search);
            $userResults = $this->searchUsers($this->search);
            $this->filteredItems = array_merge($menuResults, $userResults);
            $this->isLoading = false;
            return;
        }

        $currentItems = $this->items;

        if (!empty($this->currentPath)) {
            foreach ($this->currentPath as $pathId) {
                $found = false;
                foreach ($currentItems as $item) {
                    if ($item['id'] === $pathId) {
                        if (isset($item['children'])) {
                            $currentItems = $item['children'];
                            $found = true;
                            break;
                        }
                    }
                }
                if (!$found) {
                    $this->currentPath = [];
                    $currentItems = $this->items;
                    break;
                }
            }
        }

        $this->filteredItems = array_values($currentItems);
    }

    public function searchInTree($items, $search)
    {
        $results = [];
        foreach ($items as $item) {
            $matchesSearch = str_contains(strtolower($item['label']), strtolower($search)) ||
                str_contains(strtolower($item['description'] ?? ''), strtolower($search));

            if ($matchesSearch) {
                $results[] = $item;
            }

            if (isset($item['children'])) {
                $childResults = $this->searchInTree($item['children'], $search);
                $results = array_merge($results, $childResults);
            }
        }
        return $results;
    }

    public function searchUsers($search)
    {
        $users = User::where('name', 'like', "%{$search}%")
            ->orWhere('phone', 'like', "%{$search}%")
            ->take(5)
            ->get();

        return $users->map(function ($user) {
            return [
                'id' => 'user-' . $user->id,
                'icon' => '👤',
                'label' => $user->name,
                'description' => $user->phone,
                'group' => 'Kullanıcılar',
                'action' => 'showUser',
                'user_id' => $user->id
            ];
        })->toArray();
    }

    public function getCurrentItems()
    {
        $items = $this->items;
        foreach ($this->currentPath as $pathId) {
            $found = false;
            foreach ($items as $item) {
                if ($item['id'] === $pathId && isset($item['children'])) {
                    $items = $item['children'];
                    $found = true;
                    break;
                }
            }
            if (!$found) {
                $this->currentPath = [];
                return $this->items;
            }
        }
        return $items;
    }

    public function selectItem($index)
    {
        if (!isset($this->filteredItems[$index])) {
            return;
        }

        $item = $this->filteredItems[$index];

        // Kullanıcı seçildiğinde
        if (isset($item['action']) && $item['action'] === 'showUser') {
            return $this->showUserDetails($item['user_id']);
        }

        // Eğer arama yapılmışsa ve sonuç seçildiyse, aramayı temizle
        if (!empty($this->search)) {
            $this->search = '';
        }

        // Alt menü kontrolü
        if (isset($item['children'])) {
            $this->currentPath[] = $item['id'];
            $this->selectedIndex = 0;
            $this->filterItems();
            return;
        }

        // Action kontrolü
        if (isset($item['action'])) {
            $action = $item['action'];
            switch ($action) {
                case 'askGeneral':
                    return $this->getSimpleResponse('askGeneral');
                case 'askAppointments':
                    return $this->getSimpleResponse('askAppointments');
                case 'askServices':
                    return $this->getSimpleResponse('askServices');
                default:
                    break;
            }
        }
    }

    public function goBack()
    {
        array_pop($this->currentPath);
        $this->search = '';
        $this->filterItems();
    }

    public function incrementSelected()
    {
        if ($this->selectedIndex < count($this->filteredItems) - 1) {
            $this->selectedIndex++;
        }
    }

    public function decrementSelected()
    {
        if ($this->selectedIndex > 0) {
            $this->selectedIndex--;
        }
    }

    #[On('merkezim-spotlight-toggle')]
    public function toggle()
    {
        $this->isOpen = !$this->isOpen;
        if ($this->isOpen) {
            $this->reset(['search', 'currentPath', 'selectedIndex']);
        }
    }

    public function close()
    {
        $this->isOpen = false;
        $this->reset(['search', 'currentPath', 'selectedIndex']);
    }

    public function getSimpleResponse($type)
    {
        $response = match ($type) {
            'askGeneral' => [
                'title' => '🤖 Genel Yardım',
                'message' => 'Merhaba! Ben Merkezim asistanı. Size nasıl yardımcı olabilirim? Randevular, hizmetler veya sistem kullanımı hakkında sorularınızı yanıtlayabilirim.'
            ],
            'askAppointments' => [
                'title' => '📅 Randevu Yardımı',
                'message' => "Randevu sistemi hakkında bilmeniz gerekenler:\n• Online randevu alabilirsiniz\n• Randevularınızı görüntüleyebilirsiniz\n• İptal ve değişiklik yapabilirsiniz\n• Hatırlatma alabilirsiniz"
            ],
            'askServices' => [
                'title' => '✨ Hizmetler',
                'message' => "Sunduğumuz hizmetler:\n• Saç kesimi ve şekillendirme\n• Saç boyama ve bakım\n• Cilt bakımı\n• Makyaj\n• Manikür ve pedikür"
            ],
            default => [
                'title' => '🤖 Asistan',
                'message' => 'Üzgünüm, bu konuda yardımcı olamıyorum.'
            ]
        };

        $this->modalTitle = $response['title'];
        $this->modalContent = $response['message'];
        $this->isModalOpen = true;
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
    }

    public function getCurrentPathLabel()
    {
        $items = $this->items;
        $label = 'Menü';

        foreach ($this->currentPath as $pathId) {
            foreach ($items as $item) {
                if ($item['id'] === $pathId) {
                    $label = $item['label'];
                    if (isset($item['children'])) {
                        $items = $item['children'];
                    }
                    break;
                }
            }
        }

        return $label;
    }

    public function showUserDetails($userId)
    {
        $user = User::find($userId);
        if (!$user) return;

        $response = [
            'title' => '👤 Kullanıcı Detayları',
            'message' => "Ad Soyad: {$user->name}\nTelefon: {$user->phone}"
        ];

        $this->js(<<<JS
            document.getElementById('ai-modal-title').innerText = '{$response['title']}';
            document.getElementById('ai-response').innerHTML = '{$response['message']}';
            document.getElementById('ai-modal').classList.remove('hidden');
        JS);
    }

    public function render()
    {
        return view('livewire.merkezim-spotlight');
    }
}
