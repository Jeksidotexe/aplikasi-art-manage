<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 *
 *
 * @property int $id_bidang
 * @property string $nama_bidang
 * @property string $deskripsi
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Alat> $alat
 * @property-read int|null $alat_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Bidang newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Bidang newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Bidang onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Bidang query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Bidang whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Bidang whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Bidang whereDeskripsi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Bidang whereIdBidang($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Bidang whereNamaBidang($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Bidang whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Bidang withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Bidang withoutTrashed()
 * @mixin \Eloquent
 */
class Bidang extends Model
{
    use HasFactory;
    /**
     * fillable
     *
     * @var array
     */
    protected $table = 'bidang';
    protected $primaryKey = 'id_bidang';
    protected $fillable = [
        'nama_bidang',
        'deskripsi'
    ];

    public function alat()
    {
        return $this->hasMany(Alat::class, 'id_bidang', 'id_bidang');
    }
}
