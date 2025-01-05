<?php

namespace App\Livewire\Web\Profil;

use App\Actions\Spotlight\Actions\Web\GetPageCouponAction;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Lazy;
use Livewire\Attributes\Title;
use Livewire\Component;
use Mary\Traits\Toast;

#[Layout('components.layouts.client')]
#[Title('Kuponlarım')]
#[Lazy()]
class CouponPage extends Component
{
    use Toast;

    public function placeholder(): string
    {
        return <<<'HTML'
            <div class="relative text-base-content p-4 min-h-[200px]">
                <div class="absolute inset-0 bg-base-100/95 backdrop-blur-md rounded-2xl border border-base-200 shadow-xl z-50 overflow-hidden">
                    <!-- Yükleniyor Animasyonu -->
                    <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-full">
                        <div class="flex flex-col items-center gap-6">
                            <!-- Sabit Emoji Animasyonu -->
                            <div class="relative">
                                <!-- Arka Plan Efekti -->
                                <div class="absolute inset-0 w-24 h-24 rounded-full bg-primary/5"></div>
                                
                                <!-- Emoji Container -->
                                <div class="relative w-24 h-24 flex items-center justify-center">
                                    <!-- Rastgele Seçilmiş Sabit Emoji -->
                                    <div class="text-5xl animate-[massage_1s_ease-in-out_infinite]">
                                        {{ ['💆‍♀️', '💅', '💇‍♀️', '✨'][array_rand(['💆‍♀️', '💅', '💇‍♀️', '✨'])] }}
                                    </div>
                                    
                                    <!-- Parıltı Efekti -->
                                    <div class="absolute inset-0 flex items-center justify-center">
                                        <div class="w-full h-full rounded-full bg-primary/5 animate-[pulse_2s_ease-in-out_infinite]"></div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Yükleniyor Yazısı -->
                            <div class="space-y-2 text-center">
                                <h3 class="text-base-content/80 font-medium tracking-wide">Yükleniyor</h3>
                                <div class="flex items-center justify-center gap-1">
                                    <div class="w-1.5 h-1.5 rounded-full bg-primary/60 animate-[ping_1.5s_infinite]"></div>
                                    <div class="w-1.5 h-1.5 rounded-full bg-primary/60 animate-[ping_1.5s_0.3s_infinite]"></div>
                                    <div class="w-1.5 h-1.5 rounded-full bg-primary/60 animate-[ping_1.5s_0.6s_infinite]"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <style>
                @keyframes massage {
                    0%, 100% {
                        transform: translateX(-3px) rotate(-5deg);
                    }
                    50% {
                        transform: translateX(3px) rotate(5deg);
                    }
                }
            </style>
            </div>

           
        HTML;
    }

    public function render()
    {
        return view('livewire.client.profil.coupon-page', [
            'data' => GetPageCouponAction::run(),
        ]);
    }
}
