<?php

use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new
    #[Layout('components.layouts.main')]
    class extends Component {};
?>
<div>
    <x-header title="Merkezim" separator progress-indicator>

    </x-header>
    <div class="container mx-auto p-4">
        <!-- Resource Header -->
        <div class="grid grid-cols-2 gap-4">
          <div class="text-lg font-bold">Employee 1</div>
          <div class="text-lg font-bold">Employee 2</div>
        </div>
        
        <!-- Calendar -->
        <div class="grid grid-cols-2 gap-4 mt-4">
          <div class="p-4 border rounded-lg shadow">
            <h3 class="text-md font-semibold">October 2024</h3>
            <div class="grid grid-cols-7 gap-2 mt-4">
              <!-- Repeat this block for each day -->
              <div class="p-2 bg-blue-100 rounded-lg">1</div>
              <div class="p-2 bg-gray-100 rounded-lg">2</div>
              <!-- Days... -->
            </div>
          </div>
      
          <div class="p-4 border rounded-lg shadow">
            <h3 class="text-md font-semibold">October 2024</h3>
            <div class="grid grid-cols-7 gap-2 mt-4">
              <div class="p-2 bg-blue-100 rounded-lg">1</div>
              <div class="p-2 bg-gray-100 rounded-lg">2</div>
              <!-- Days... -->
            </div>
          </div>
        </div>
      </div>
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