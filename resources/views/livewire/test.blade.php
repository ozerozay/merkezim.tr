<?php

namespace App\Livewire;

use Livewire\Attributes\Layout;
use Livewire\Component;

new
    #[Layout('components.layouts.main')]
    class extends Component {};
?>
<div>
    <x-header title="Merkezim" separator progress-indicator>

    </x-header>
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">

        <div class="card bg-base-100 shadow-xl">
            <figure>
                <img
                    src="https://img.daisyui.com/images/stock/photo-1606107557195-0e29a4b5b4aa.webp"
                    alt="Shoes" />
            </figure>
            <div class="card-body">
                <h2 class="card-title">
                    MARGE GÜZELLİK21
                    <div class="badge badge-secondary">Yeni</div>
                </h2>
                <p>If a dog chews shoes whose shoes does he choose?</p>
                <div class="card-actions justify-end">
                    <div class="badge badge-primary">Randevu</div>
                    <div class="badge badge-primary">₺₺₺</div>
                    <div class="badge badge-primary">Şişli</div>
                </div>
            </div>
        </div>
        <div class="card bg-base-100 shadow-xl">

            <div class="card-body">
                <h2 class="card-title">Shoes!</h2>
                <p>If a dog chews shoes whose shoes does he choose?</p>
                <div class="card-actions justify-end">
                    <button class="btn btn-primary">Buy Now</button>
                </div>
            </div>
        </div>
        <x-card title="Tayfun Bakırhan Kadın & Erkek Kuaförü" subtitle="Kuaför" shadow>
            <x-slot:actions>
                <div class="rating  rating-half">
                    <input type="radio" name="rating-10" class="rating-hidden" />
                    <input type="radio" name="rating-10" class="mask mask-heart mask-half-1 bg-green-500" />
                    <input type="radio" name="rating-10" class="mask mask-heart mask-half-2 bg-green-500" />
                    <input type="radio" name="rating-10" class="mask mask-heart mask-half-1 bg-green-500"
                        checked="checked" />
                    <input type="radio" name="rating-10" class="mask mask-heart mask-half-2 bg-green-500" />
                    <input type="radio" name="rating-10" class="mask mask-heart mask-half-1 bg-green-500" />
                    <input type="radio" name="rating-10" class="mask mask-heart mask-half-2 bg-green-500" />
                    <input type="radio" name="rating-10" class="mask mask-heart mask-half-1 bg-green-500" />
                    <input type="radio" name="rating-10" class="mask mask-heart mask-half-2 bg-green-500" />
                    <input type="radio" name="rating-10" class="mask mask-heart mask-half-1 bg-green-500" />
                    <input type="radio" name="rating-10" class="mask mask-heart mask-half-2 bg-green-500" />
                </div>
            </x-slot:actions>
        </x-card>
        <x-card title="Marge Güzellik" subtitle="Güzellik Salonu" shadow separator>

            Lazer Epilasyon, Cilt Bakımı
            <x-slot:actions>
                <div class="rating  rating-half">
                    <input type="radio" name="rating-10" class="rating-hidden" />
                    <input type="radio" name="rating-10" class="mask mask-heart mask-half-1 bg-green-500" />
                    <input type="radio" name="rating-10" class="mask mask-heart mask-half-2 bg-green-500" />
                    <input type="radio" name="rating-10" class="mask mask-heart mask-half-1 bg-green-500"
                        checked="checked" />
                    <input type="radio" name="rating-10" class="mask mask-heart mask-half-2 bg-green-500" />
                    <input type="radio" name="rating-10" class="mask mask-heart mask-half-1 bg-green-500" />
                    <input type="radio" name="rating-10" class="mask mask-heart mask-half-2 bg-green-500" />
                    <input type="radio" name="rating-10" class="mask mask-heart mask-half-1 bg-green-500" />
                    <input type="radio" name="rating-10" class="mask mask-heart mask-half-2 bg-green-500" />
                    <input type="radio" name="rating-10" class="mask mask-heart mask-half-1 bg-green-500" />
                    <input type="radio" name="rating-10" class="mask mask-heart mask-half-2 bg-green-500" />
                </div>
            </x-slot:actions>
        </x-card>
    </div>

</div>