<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class AppBrand extends Component
{
    public $site_name;

    public function __construct()
    {
        /* try {
             $general_settings = \App\Actions\Spotlight\Actions\Settings\GetGeneralSettings::run();
             $this->site_name = $general_settings->get(\App\Enum\SettingsType::site_name->name);
         } catch (\Throwable $e) {

         }*/
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return <<<'HTML'
                <a href="{{ route('admin.index') }}" wire:navigate>
                    <!-- Hidden when collapsed -->
                    <div {{ $attributes->class(["hidden-when-collapsed"]) }}>
                       <div class="flex gap-2">
                            <span class="text-3xl font-bold text-gray-900 dark:text-gray-100 rounded-lg transition-all duration-300">
                                Merkezim
                            </span>
                       </div>
                    </div>
                    <!-- Display when collapsed -->
                    <div class="display-when-collapsed hidden mx-5 mt-4 lg:mb-6 h-[28px]">
                        <x-icon name="o-home" />
                    </div>
                </a>
            HTML;
    }
}
