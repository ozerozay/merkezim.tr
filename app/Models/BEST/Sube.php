<?php

namespace App\Models\BEST;

use Illuminate\Database\Eloquent\Model;
use \Staudenmeir\EloquentJsonRelations\HasJsonRelationships;


class Sube extends Model
{
    use HasJsonRelationships;
    public $table = 'sube';

    protected $connection = 'best';

    /*
     * Gösterilecek Alert
     * Kota ve Ödül
     * Talepler
     * Santral => Randevu Talebi, Muhasebe Talebi
     * Statik IP
     *
     */

    // Bütün kullanıcılarda gösterilecek alert
    // Kota ve Ödül
    // Talepler kimlere düşecek
    // Santral
    //

    protected $fillable = [
        'ad', 'ayarlar'
    ];

    protected $casts = [
        'ayarlar' => 'array'
    ];

    protected $hidden = ['deleted_at'];

    public $timestamps = false;

    public function Kullanicilari()
    {
        return $this->hasManyJson('App\User', 'sube')
            ->where('aktif', 1)->select('id', 'name', 'sube');
    }
    public function Kasalari()
    {
        return $this->hasMany('App\Model\Kasa', 'sube');
    }

    public function Odalari()
    {
        return $this->hasMany('App\Model\HizmetOda', 'sube');
    }

    public function Tedarikcileri()
    {
        return $this->hasMany('App\Model\Tedarikci', 'sube');
    }

    public function Urunleri()
    {
        return $this->hasMany('App\Model\Urun', 'urun');
    }

    public function SubeBilgi($bilgi, $baslangic, $bitis)
    {
        switch ($bilgi) {
        }
    }
}
