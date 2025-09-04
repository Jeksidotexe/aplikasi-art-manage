<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 *
 *
 * @property int $id_alat
 * @property int $id_bidang
 * @property string $nama_alat
 * @property string $merk
 * @property int $jumlah
 * @property string $tanggal_beli
 * @property string $kondisi
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\Bidang $bidang
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Alat newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Alat newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Alat onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Alat query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Alat whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Alat whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Alat whereIdAlat($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Alat whereIdBidang($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Alat whereJumlah($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Alat whereKondisi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Alat whereMerk($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Alat whereNamaAlat($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Alat whereTanggalBeli($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Alat whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Alat withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Alat withoutTrashed()
 * @mixin \Eloquent
 */
class Alat extends Model
{
    use HasFactory;
    /**
     * fillable
     *
     * @var array
     */
    protected $table = 'alat';
    protected $primaryKey = 'id_alat';
    protected $fillable = [
        'id_bidang',
        'nama_alat',
        'merk',
        'jumlah',
        'tanggal_beli',
        'kondisi'
    ];

    /**
     * Bidang
     *
     * @return void
     */

    public function bidang(): BelongsTo
    {
        return $this->belongsTo(Bidang::class, 'id_bidang', 'id_bidang');
    }
}
