<div class="relative group">
    <a href="{{ route('client.shop.packages') }}" 
       class="flex items-center gap-2 p-2 rounded-xl bg-gradient-to-r from-primary/90 to-primary hover:from-primary hover:to-primary/90 transition-all duration-300 transform hover:scale-[1.02] shadow-md shadow-primary/20 group-hover:shadow-lg group-hover:shadow-primary/30">
        
        <!-- Sol: İkon -->
        <span class="text-lg text-white">✨</span>

        <!-- Orta: Başlık ve Açıklama -->
        <div class="flex flex-col">
            <span class="text-sm font-medium text-white">{{ __('client.menu_shop') }}</span>
            <span class="text-[11px] text-white/90">Özel hizmet paketleri</span>
        </div>

        <!-- Sağ: Ok İşareti -->
        <div class="ml-auto text-white/90">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
            </svg>
        </div>

        <!-- Arka Plan Parıltı Efekti -->
        <div class="absolute inset-0 bg-gradient-to-r from-white/10 to-transparent opacity-0 group-hover:opacity-100 rounded-xl transition-opacity duration-300"></div>
    </a>

    <!-- Alt Gölge Efekti -->
    <div class="absolute -inset-0.5 bg-gradient-to-r from-primary/30 to-primary/20 rounded-xl blur opacity-30 group-hover:opacity-40 transition-opacity duration-300 -z-10"></div>
</div>
