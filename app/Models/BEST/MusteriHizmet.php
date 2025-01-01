<?php

namespace App\Models\BEST;

use App\Casts\Json;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MusteriHizmet extends Model
{
    use SoftDeletes;

    protected $table = 'musteri_hizmet';

    protected $fillable = ['sube', 'musteri', 'hizmet', 'satis', 'cesit', 'kalan_seans', 'toplam_seans', 'durum'];

    public $dates = ['created_at', 'updated_at', 'deleted_at'];

    protected $hidden = ['deleted_at'];

    public function scopeAktif($q)
    {
        return $q->where('durum', 'Aktif');
    }

    public function Satisi()
    {
        return $this->belongsTo(MusteriSatis::class, 'satis');
    }

    public function Musterisi()
    {
        return $this->belongsTo(Musteri::class, 'musteri');
    }

    public function Subesi()
    {
        return $this->belongsTo(Sube::class, 'sube');
    }

    public function Hizmeti()
    {
        return $this->belongsTo(Hizmet::class, 'hizmet');
    }

    protected $connection = 'best';
}
