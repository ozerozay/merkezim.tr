<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Actions\Spotlight\Actions\Kasa\GetKasaTransactions;
use App\Enum\PermissionType;

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
    public $showPaymentForm = false;
    public $formType = null;
    public $notifications = [];

    public function mount()
    {
        $user = auth()->user();

        // Temel menü öğelerini tanımla
        $menuItems = [
            [
                'id' => 'home',
                'icon' => '🏠',
                'label' => 'Anasayfa',
                'group' => 'Ana Menü',
                'description' => 'Kontrol paneline git',
                'action' => 'goToDashboard',
                'permission' => null // Her kullanıcı görebilir
            ],
            [
                'id' => 'calendar',
                'icon' => '📅',
                'label' => 'Ajanda',
                'group' => 'Ana Menü',
                'description' => 'Ajanda ve takvim yönetimi',
                'permission' => PermissionType::page_agenda,
                'children' => [
                    [
                        'id' => 'calendar-view',
                        'icon' => '📆',
                        'label' => 'Takvim Görünümü',
                        'action' => 'showCalendar',
                        'permission' => PermissionType::page_agenda
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
            ],
            // Admin için yazılım bilgileri menüsü
            ...(Auth::user()?->hasRole('admin') ? [[
                'id' => 'system-info',
                'icon' => '⚙️',
                'label' => 'Sistem Bilgileri',
                'group' => 'Yazılım',
                'description' => 'Yazılım kullanım ve lisans bilgileri',
                'children' => [
                    [
                        'id' => 'license-info',
                        'icon' => '📄',
                        'label' => 'Lisans Bilgileri',
                        'description' => 'Lisans durumu ve son kullanım tarihi',
                        'action' => 'showLicenseInfo'
                    ],
                    [
                        'id' => 'usage-stats',
                        'icon' => '📊',
                        'label' => 'Kullanım İstatistikleri',
                        'description' => 'Sistem kullanım metrikleri',
                        'action' => 'showUsageStats'
                    ],
                    [
                        'id' => 'system-health',
                        'icon' => '🏥',
                        'label' => 'Sistem Sağlığı',
                        'description' => 'Sunucu durumu ve performans',
                        'action' => 'showSystemHealth'
                    ],
                    [
                        'id' => 'error-logs',
                        'icon' => '🚨',
                        'label' => 'Hata Kayıtları',
                        'description' => 'Sistem hata logları',
                        'action' => 'showErrorLogs'
                    ]
                ]
            ]] : [])
        ];

        // İzinlere göre menü öğelerini filtrele
        $this->items = $this->filterMenuItemsByPermissions($menuItems, $user);

        $this->items = array_merge($this->items, [
            [
                'id' => 'kasa',
                'icon' => '💵',
                'label' => 'Kasa',
                'group' => 'Finans',
                'description' => 'Kasa işlemleri ve raporları',
                'children' => [
                    [
                        'id' => 'kasa-odeme',
                        'icon' => '↗️',
                        'label' => 'Ödeme Yap',
                        'description' => 'Danışan, müşteri veya masraf gruplarına ödeme yapın.',
                        'action' => 'makePayment',
                        'group' => 'İşlemler'
                    ],
                    [
                        'id' => 'kasa-tahsilat',
                        'icon' => '↙️',
                        'label' => 'Ödeme Al',
                        'description' => 'Tahsilat için danışanın menüsüne girin.',
                        'action' => 'receivePaid',
                        'group' => 'İşlemler'
                    ],
                    [
                        'id' => 'kasa-mahsup',
                        'icon' => '↔️',
                        'label' => 'Mahsup',
                        'description' => 'Kasalar arası para transferi',
                        'action' => 'createMahsup',
                        'group' => 'İşlemler'
                    ]
                ]
            ],

            [
                'id' => 'statistics',
                'icon' => '📈',
                'label' => 'İstatistikler',
                'group' => 'Raporlar',
                'description' => 'Sistem istatistikleri ve raporları',
                'children' => [
                    [
                        'id' => 'daily-stats',
                        'icon' => '📅',
                        'label' => 'Günlük İstatistikler',
                        'action' => 'showDailyStats'
                    ],
                    [
                        'id' => 'monthly-stats',
                        'icon' => '📆',
                        'label' => 'Aylık İstatistikler',
                        'action' => 'showMonthlyStats'
                    ]
                ]
            ],

            [
                'id' => 'create-client',
                'icon' => '➕',
                'label' => 'Danışan Oluştur',
                'group' => 'Hızlı İşlemler',
                'description' => 'Yeni danışan kaydı oluştur',
                'action' => 'createClient'
            ]
        ]);

        $this->filteredItems = $this->items;
    }

    private function filterMenuItemsByPermissions($items, $user)
    {
        return collect($items)->filter(function ($item) use ($user) {
            // İzin kontrolü
            if (isset($item['permission']) && $item['permission'] !== null) {
                if (!$user->can($item['permission']->value)) {
                    return false;
                }
            }

            // Alt menüleri kontrol et
            if (isset($item['children'])) {
                $item['children'] = $this->filterMenuItemsByPermissions($item['children'], $user);
                // Eğer alt menülerin hepsi filtrelendiyse, üst menüyü de kaldır
                if (empty($item['children'])) {
                    return false;
                }
            }

            return true;
        })->values()->all();
    }

    public function updatedSearch($value)
    {
        $this->isLoading = true;
        $this->selectedIndex = 0;

        if (empty($value)) {
            // Arama temizlendiğinde mevcut path'teki öğeleri göster
            $this->filterItems();
        } else {
            // Arama yapılırken global arama yap ve path'i temizle
            $this->currentPath = [];
            $menuResults = $this->searchInTree($this->items, $value);
            $userResults = $this->searchUsers($value);
            $this->filteredItems = array_merge($menuResults, $userResults);
        }

        $this->isLoading = false;
    }

    public function filterItems()
    {
        // Arama yoksa normal path navigasyonu
        $currentItems = $this->items;
        if (!empty($this->currentPath)) {
            foreach ($this->currentPath as $pathId) {
                foreach ($currentItems as $item) {
                    if ($item['id'] === $pathId && isset($item['children'])) {
                        $currentItems = $item['children'];
                        break;
                    }
                }
            }
        }

        $this->filteredItems = array_values($currentItems);
    }

    public function searchInTree($items, $search)
    {
        $results = [];
        foreach ($items as $item) {
            // Ana öğede arama
            $matchesSearch = str_contains(strtolower($item['label']), strtolower($search)) ||
                str_contains(strtolower($item['description'] ?? ''), strtolower($search));

            if ($matchesSearch) {
                // Eğer alt menüsü varsa ve arama sonucunda eşleşme varsa, ana menüyü ekle
                if (isset($item['children'])) {
                    $results[] = array_merge($item, ['children' => []]);
                } else {
                    $results[] = $item;
                }
            }

            // Alt öğelerde arama
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

        // Alt menüsü varsa
        if (isset($item['children'])) {
            $this->currentPath[] = $item['id'];
            $this->filterItems();
            $this->selectedIndex = 0;
            $this->search = '';
            return;
        }

        // Action varsa
        if (isset($item['action'])) {
            if ($item['action'] === 'makePayment') {
                $this->showPaymentForm = true;
                return;
            }

            $this->{$item['action']}($item['user_id'] ?? null);

            // Sadece slide-over açan işlemlerde kapat
            if (in_array($item['action'], [
                'showCalendar',
                'createRequest',
                'showUser',
                'createClient'
            ])) {
                $this->dispatch('close-spotlight');
            }
        }
    }

    public function goBack()
    {
        // Eğer ödeme formu açıksa
        if ($this->showPaymentForm) {
            $this->showPaymentForm = false;
            $this->resetPath();
            $this->dispatch('close-payment-form');
            return;
        }

        // Eğer arama yapılmışsa
        if (!empty($this->search)) {
            $this->search = '';
            $this->filterItems();
            return;
        }

        // Eğer path varsa
        if (!empty($this->currentPath)) {
            array_pop($this->currentPath);
            $this->selectedIndex = 0;
            $this->filterItems();
            return;
        }
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
        $this->resetExcept('items', 'weather');
        $this->filteredItems = $this->items;
    }

    public function close()
    {
        $this->resetExcept('items', 'weather');
        $this->filteredItems = $this->items;
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
            'licenseInfo' => [
                'title' => '📄 Lisans Bilgileri',
                'message' => "Lisans Durumu: Aktif\nPaket: Enterprise\nSon Kullanım: 31.12.2024\nKullanıcı Limiti: 1000\nDestek: 7/24"
            ],
            'usageStats' => [
                'title' => '📊 Kullanım İstatistikleri',
                'message' => "Aktif Kullanıcı: 450\nToplam İşlem: 15.234\nDisk Kullanımı: 45%\nBandwidth: 75%\nOrtalama Yanıt Süresi: 0.8s"
            ],
            'systemHealth' => [
                'title' => '🏥 Sistem Sağlığı',
                'message' => "CPU Kullanımı: 35%\nRAM Kullanımı: 60%\nUptime: 45 gün\nQueue İşlemleri: Normal\nCache Hit Rate: 89%"
            ],
            'errorLogs' => [
                'title' => '🚨 Hata Kayıtları',
                'message' => "Son 24 Saat:\n• Kritik: 0\n• Uyarı: 3\n• Bilgi: 12\n\nTüm logları görmek için log yönetimine gidin."
            ],
            default => [
                'title' => '🤖 Asistan',
                'message' => 'Üzgünüm, bu konuda bilgi bulunamadı.'
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

    public function getBreadcrumbs()
    {
        $breadcrumbs = [];
        $items = $this->items;

        foreach ($this->currentPath as $pathId) {
            foreach ($items as $item) {
                if ($item['id'] === $pathId) {
                    $breadcrumbs[] = [
                        'id' => $item['id'],
                        'label' => $item['label']
                    ];
                    if (isset($item['children'])) {
                        $items = $item['children'];
                    }
                    break;
                }
            }
        }

        return $breadcrumbs;
    }

    public function resetPath()
    {
        $this->currentPath = [];
        $this->selectedIndex = 0;
        $this->search = '';
        $this->showPaymentForm = false;
        $this->filterItems();
    }

    public function goToPath($pathId)
    {
        // Verilen path ID'ye kadar olan yolu bul
        $newPath = [];
        foreach ($this->currentPath as $currentPathId) {
            $newPath[] = $currentPathId;
            if ($currentPathId === $pathId) {
                break;
            }
        }

        $this->currentPath = $newPath;
        $this->selectedIndex = 0;
        $this->filterItems();
    }

    public function showLicenseInfo()
    {
        $this->getSimpleResponse('licenseInfo');
    }

    public function showUsageStats()
    {
        $this->getSimpleResponse('usageStats');
    }

    public function showSystemHealth()
    {
        $this->getSimpleResponse('systemHealth');
    }

    public function showErrorLogs()
    {
        $this->getSimpleResponse('errorLogs');
    }

    // Kasa işlemleri
    public function showKasaOverview()
    {
        $this->dispatch('slide-over.open', component: 'modals.kasa.overview');
    }

    public function addIncome()
    {
        $this->dispatch('slide-over.open', component: 'modals.kasa.add-income');
    }

    public function addExpense()
    {
        $this->dispatch('slide-over.open', component: 'modals.kasa.add-expense');
    }

    // İstatistik işlemleri  
    public function showDailyStats()
    {
        $this->dispatch('slide-over.open', component: 'modals.statistics.daily');
    }

    public function showMonthlyStats()
    {
        $this->dispatch('slide-over.open', component: 'modals.statistics.monthly');
    }

    // Danışan oluşturma
    public function createClient()
    {
        $this->dispatch('slide-over.open', component: 'modals.client.create');
    }

    // Kasa işlemleri için yeni metodlar
    public function makePayment()
    {
        $this->showPaymentForm = true;
        $this->formType = 'payment';
        $this->filteredItems = [];
    }

    public function receivePaid()
    {
        $this->showPaymentForm = true;
        $this->formType = 'paid';
        $this->filteredItems = [];
    }

    public function createMahsup()
    {
        $this->showPaymentForm = true;
        $this->formType = 'mahsup';
        $this->filteredItems = [];
    }

    public function backToList()
    {
        $this->showPaymentForm = false;
        $this->formType = null;
        $this->filterItems();
    }

    public function showKasaView()
    {
        // İşlem butonları
        $kasaActions = [
            [
                'id' => 'kasa-odeme',
                'icon' => '↗️',
                'label' => 'Ödeme Yap',
                'description' => 'Danışan, müşteri veya masraf gruplarına ödeme yapın.',
                'action' => 'makePayment',
                'group' => 'İşlemler'
            ],
            [
                'id' => 'kasa-tahsilat',
                'icon' => '↙️',
                'label' => 'Ödeme Al',
                'description' => 'Tahsilat için danışanın menüsüne girin.',
                'action' => 'receivePaid',
                'group' => 'İşlemler'
            ],
            [
                'id' => 'kasa-mahsup',
                'icon' => '↔️',
                'label' => 'Mahsup',
                'description' => 'Kasalar arası para transferi',
                'action' => 'createMahsup',
                'group' => 'İşlemler'
            ]
        ];

        // Sadece işlem butonlarını göster
        $this->filteredItems = $kasaActions;
        $this->currentPath[] = 'kasa';
    }

    protected function getListeners()
    {
        return [
            'payment-saved' => 'handlePaymentSaved',
            'close-payment-form' => 'backToList',
            'spotlight-notification' => 'handleNotification'
        ];
    }

    public function handlePaymentSaved()
    {
        $this->backToList();
    }

    public function handleNotification($data)
    {
        $type = $data['type'] ?? 'info';
        $duration = $data['duration'] ?? 3000;

        $this->notify($data['message'], $type, $duration);
    }

    // Notification metodları
    public function notify($message, $type = 'info', $duration = 3000)
    {
        $this->notifications[] = [
            'id' => uniqid(),
            'message' => $message,
            'type' => $type,
            'duration' => $duration
        ];
    }

    public function success($message, $duration = 3000)
    {
        $this->notify($message, 'success', $duration);
    }

    public function error($message, $duration = 3000)
    {
        $this->notify($message, 'error', $duration);
    }

    public function warning($message, $duration = 3000)
    {
        $this->notify($message, 'warning', $duration);
    }

    public function info($message, $duration = 3000)
    {
        $this->notify($message, 'info', $duration);
    }

    public function removeNotification($id)
    {
        $this->notifications = array_filter($this->notifications, function ($notification) use ($id) {
            return $notification['id'] !== $id;
        });
    }

    public function resetSearch()
    {
        $this->search = '';
        $this->resetPath();
    }

    public function render()
    {
        return view('livewire.merkezim-spotlight');
    }
}
