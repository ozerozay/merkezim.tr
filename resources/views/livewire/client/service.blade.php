<?php

new
#[\Livewire\Attributes\Layout('components.layouts.client')]
class extends \Livewire\Volt\Component {

};

?>
<div>
    @php
        $slides = [
            [
                'image' => 'https://picsum.photos/800/400',
            ],
            [
                'image' => 'https://picsum.photos/800/300',
            ],
            [
                'image' => 'https://picsum.photos/800/300',
            ],
            [
                'image' => 'https://picsum.photos/800/300',
            ],
        ];
    @endphp
    <x-card title="Lazer Epilasyon" separator class="text-justify">
        <x-carousel :slides="$slides" class="mb-5"/>
        Epilasyon İstenmeyen tüylerden kurtulma işlemine verilen genel bir addır. Kadınların daha çok tercih ettiği
        bilinse de son zamanlarda erkeklerde epilasyon için daha sık randevu almaya başlamıştır. Tüylenme ya da kıllanma
        sorunu tek seferde çözüme kavuşturulacak kadar kolay değildir. Profesyonel bir epilasyon aletiyle düzenli
        periyodlarla yapılan epilasyon sayesinde tüylr kısa bir süre sonra seyrelmeye başlayacaktır. Epilasyon deyince
        çoğu kişinin aklına geleneksel yöntemler gelir. Beauty Boss ise teknolojiye ayak uydurarak daha hızlı ve kalıcı
        çözüm olan lazer epilasyon hizmetini son sürat sağlamaktadır. İstenmeyen tüylerden kurtulmak için onlarca çözüm
        var. Peki neden lazer epilasyon daha fazla tercih edilmeye başlandı? Buna net bir cevap verelim.

        Epilasyon Hani Bölgelere Uygulanabilir?
        Lazer Epilasyon yapıldıktan sonra, sizden istenilen epilasyon yapılan bölgeye 15 – 20 dakika kadar buz
        uygulamaktır. Bu kızarıklığı ve şişliği azaltır aynı zamanda da cildin rahatlamasını sağlar. Epilasyon bittikten
        sonra bölgeyi nemlendirme de doktorlar tarafından tavsiye edilir. İşlem sonrası ilk 24 saat sıcak su ile
        temastan kaçınılmalıdır. Yaklaşık 15 gün bronzlaşmak için güneşlenmemeli ve solaryuma girilmemelidir. Epilasyon
        yapılan bölgelerin çok fazla yorulup tere maruz bırakılması da tavsiye edilmez.

        Kimler Epilasyon Yaptırabilir?
        Lazer epilasyonu kıllanma ve tüylenme sorunu yaşayan herkes yaptırabilir. Buna rağmen lazer epilasyonu
        yaptırmaması gereken kişiler de vardır. Kıl yapısı gelişimini henüz tamamlamamış olan kişiler için lazer
        epilasyon uygun değildir. Lazer epilasyonu herhangi bir yerde yaptırmanız da oldukça risklidir. Bunun en önemli
        sebebi ise hijyen! Hamilelerin ve kemoterapi görenlerin ise lazer epilasyona gitmesi sağlık açısından
        zararlıdır. Uzmanlar tavsiye etmez. Cildinde herhangi bir hastalık bulunan kişiler de lazer epilasyon
        yaptırmadan önce muhakkak hekime sormalıdır. Lazer epilasyon yaptırmaması gereken bir diğer kişilerde Hepatit B
        hastalarıdır. Unutmamalıdır ki tüylerden kurtulmanın tek yolu lazer epilasyon değildir.

        Neden Lazer Epilasyon Tercih Edilmelidir?
        Ağda bantları tüylerden kurtulmak için harika bir icar olsa da acı hissinin önüne geçememiştir. Epilasyon ise
        daha az acı duymanızı garanti eder. İstenmeyen tüyler için kökünden çözüm olarak bilinmesi ve halk arasında
        yayılması bu seçeneği daha popüler hale getirmiştir. Cımbız, ip, ağda gibi kolay gibi gözüken alternatiflerin
        tarihe karıştığı artık kabul edilmelidir. Lazer epilasyonun dışında tüylere uygulanan işlemler her zaman
        masumane sonuçlar doğurmayabilir.Beauty Boss’da gerçek epilasyon deneyimini yaşarsınız. Cilt tipinize göre de
        epilasyon seansınız tamamlandığında tüyleriniz ya artık hiç çıkmaz yahut seyrelmeye başlar. Epilasyonda başarı
        işidir. Modern cihazların kullanılmadığı epilasyon müşteriyi pek memnun etmeyecektir. Kullanılan ürün epilasyon
        başarı oranını iyi veya kötü yönde muhakkak etkiler.

        Epilasyon Hangi Bölgelere Yapılabilir?
        Epilasyon yaptırmak isteyenlerin sorduğu en yaygın sorulardan biri de epilasyonun hangi bölgelere uygulanması
        gerektiğidir. Kadınlarda, yüz bölgesi, ense, koltuk altı, göbek, kalça, omuz, göğüs, sırt, bacak ve bikini
        bölgesi epilasyon yapılabilen kısımlardır. Erkeklerde ise, sırt, koltuk altı, göğüs, sakal ve sakal üstü, ense,
        kol, omuzlar, el ve ayakların üst kısmı, kaş ortası epilasyona uygun bölgelerdir. Lazer epilasyon ağda gibi acı
        veren bir yöntem olmadığı için kadınların ve erkeklerin muhtemel tercihleri arasındadır. Epilasyon yaptırmak
        istediğiniz bölgeler hakkında uzmanınızla görüşüp fikir almanız sağlıklı olacaktır. Epilasyon işlemi vücudun
        farklı yerlerinde farklı sürelerde tamamlanmaktadır. Uygulanan bölgenin genişliği sürede belirleyici etkendir.
        Bu epilasyonun uygulanmasının mümkün olmadığı yer hassas ve ince yapısından dolayı göz çukurudur. Lazer
        epilasyon işlemi kesinlikle merdiven altı yerlerde yapılmamalıdır. İşlem esnasında koruyu gözlük takılması
        gereklidir. Bu lazer ışığından gözlerin etkilenmesinin önüne geçer.
    </x-card>

</div>

