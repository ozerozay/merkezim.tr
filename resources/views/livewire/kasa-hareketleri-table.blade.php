<div>
    <div class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
        Günlük Kasa Hareketleri
    </div>
    <div class="mt-2 space-y-6">
        @php
            $genelToplam = [
                'devir' => 0,
                'tahsilat' => 0,
                'odenen' => 0,
                'bakiye' => 0
            ];
        @endphp

        @foreach($this->getKasaTransactions() as $branch)
            @php
                $genelToplam['devir'] += $branch['totals']['devir'];
                $genelToplam['tahsilat'] += $branch['totals']['tahsilat'];
                $genelToplam['odenen'] += $branch['totals']['odenen'];
                $genelToplam['bakiye'] += $branch['totals']['bakiye'];
            @endphp

            <!-- Mevcut şube tabloları -->
            <div class="overflow-x-auto">
                <!-- Şube Başlığı -->
                <div class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    {{ $branch['branch_name'] }}
                </div>

                <!-- Mobil Görünüm -->
                <div class="block sm:hidden space-y-3">
                    @foreach($branch['transactions'] as $transaction)
                        <div class="py-3 space-y-2 hover:bg-gray-50 dark:hover:bg-gray-800/50 cursor-pointer"
                             wire:click="showKasaDetail('{{ str_replace('kasa-', '', $transaction['id']) }}')">
                            <div class="flex justify-between items-center">
                                <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                    {{ $transaction['label'] }}
                                </div>
                                <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                    ₺{{ number_format($transaction['bakiye'], 2) }}
                                </div>
                            </div>
                            <div class="flex justify-between text-xs">
                                <div class="text-gray-600 dark:text-gray-400">
                                    Devir: ₺{{ number_format($transaction['devir'], 2) }}
                                </div>
                                <div class="text-green-600 dark:text-green-400">
                                    +₺{{ number_format($transaction['tahsilat'], 2) }}
                                </div>
                                <div class="text-red-600 dark:text-red-400">
                                    -₺{{ number_format($transaction['odenen'], 2) }}
                                </div>
                            </div>
                        </div>
                    @endforeach

                    <!-- Şube Toplamları (Mobil) -->
                    <div class="border-t border-gray-200 dark:border-gray-700 pt-2 mt-2">
                        <div class="flex justify-between text-xs font-medium">
                            <div class="text-gray-600 dark:text-gray-400">
                                Toplam Devir: ₺{{ number_format($branch['totals']['devir'], 2) }}
                            </div>
                            <div class="text-green-600 dark:text-green-400">
                                +₺{{ number_format($branch['totals']['tahsilat'], 2) }}
                            </div>
                            <div class="text-red-600 dark:text-red-400">
                                -₺{{ number_format($branch['totals']['odenen'], 2) }}
                            </div>
                            <div class="text-gray-900 dark:text-gray-100">
                                ₺{{ number_format($branch['totals']['bakiye'], 2) }}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Masaüstü Görünüm -->
                <table class="hidden sm:table min-w-full">
                    <thead class="bg-gray-50 dark:bg-gray-800">
                        <tr>
                            <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Kasa</th>
                            <th class="px-3 py-2 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Devir</th>
                            <th class="px-3 py-2 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Tahsilat</th>
                            <th class="px-3 py-2 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Ödenen</th>
                            <th class="px-3 py-2 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Bakiye</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-800">
                        @foreach($branch['transactions'] as $transaction)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 cursor-pointer"
                                wire:click="showKasaDetail('{{ str_replace('kasa-', '', $transaction['id']) }}')">
                                <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                    {{ $transaction['label'] }}
                                </td>
                                <td class="px-3 py-2 whitespace-nowrap text-sm text-right text-gray-600 dark:text-gray-400">
                                    ₺{{ number_format($transaction['devir'], 2) }}
                                </td>
                                <td class="px-3 py-2 whitespace-nowrap text-sm text-right text-green-600 dark:text-green-400">
                                    +₺{{ number_format($transaction['tahsilat'], 2) }}
                                </td>
                                <td class="px-3 py-2 whitespace-nowrap text-sm text-right text-red-600 dark:text-red-400">
                                    -₺{{ number_format($transaction['odenen'], 2) }}
                                </td>
                                <td class="px-3 py-2 whitespace-nowrap text-sm text-right font-medium text-gray-900 dark:text-gray-100">
                                    ₺{{ number_format($transaction['bakiye'], 2) }}
                                </td>
                            </tr>
                        @endforeach

                        <!-- Şube Toplamları (Masaüstü) -->
                        <tr class="bg-gray-50 dark:bg-gray-800/50 font-medium">
                            <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                Şube Toplamı
                            </td>
                            <td class="px-3 py-2 whitespace-nowrap text-sm text-right text-gray-600 dark:text-gray-400">
                                ₺{{ number_format($branch['totals']['devir'], 2) }}
                            </td>
                            <td class="px-3 py-2 whitespace-nowrap text-sm text-right text-green-600 dark:text-green-400">
                                +₺{{ number_format($branch['totals']['tahsilat'], 2) }}
                            </td>
                            <td class="px-3 py-2 whitespace-nowrap text-sm text-right text-red-600 dark:text-red-400">
                                -₺{{ number_format($branch['totals']['odenen'], 2) }}
                            </td>
                            <td class="px-3 py-2 whitespace-nowrap text-sm text-right font-medium text-gray-900 dark:text-gray-100">
                                ₺{{ number_format($branch['totals']['bakiye'], 2) }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        @endforeach

        <!-- Genel Toplamlar -->
        <div class="mt-8 border-t-2 border-gray-200 dark:border-gray-700 pt-4">
            <!-- Mobil Görünüm -->
            <div class="block sm:hidden">
                <div class="text-sm font-medium text-gray-900 dark:text-gray-100 mb-2">
                    Genel Toplam
                </div>
                <div class="flex justify-between text-xs font-medium">
                    <div class="text-gray-600 dark:text-gray-400">
                        Toplam Devir: ₺{{ number_format($genelToplam['devir'], 2) }}
                    </div>
                    <div class="text-green-600 dark:text-green-400">
                        +₺{{ number_format($genelToplam['tahsilat'], 2) }}
                    </div>
                    <div class="text-red-600 dark:text-red-400">
                        -₺{{ number_format($genelToplam['odenen'], 2) }}
                    </div>
                    <div class="text-gray-900 dark:text-gray-100">
                        ₺{{ number_format($genelToplam['bakiye'], 2) }}
                    </div>
                </div>
            </div>

            <!-- Masaüstü Görünüm -->
            <table class="hidden sm:table min-w-full">
                <tbody>
                    <tr class="bg-gray-100 dark:bg-gray-800 font-medium text-sm">
                        <td class="px-3 py-3 text-gray-900 dark:text-gray-100">
                            Genel Toplam
                        </td>
                        <td class="px-3 py-3 text-right text-gray-600 dark:text-gray-400">
                            ₺{{ number_format($genelToplam['devir'], 2) }}
                        </td>
                        <td class="px-3 py-3 text-right text-green-600 dark:text-green-400">
                            +₺{{ number_format($genelToplam['tahsilat'], 2) }}
                        </td>
                        <td class="px-3 py-3 text-right text-red-600 dark:text-red-400">
                            -₺{{ number_format($genelToplam['odenen'], 2) }}
                        </td>
                        <td class="px-3 py-3 text-right font-medium text-gray-900 dark:text-gray-100">
                            ₺{{ number_format($genelToplam['bakiye'], 2) }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div> 