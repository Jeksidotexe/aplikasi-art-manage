<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 *
 *
 * @property int $id_prodi
 * @property int $id_jurusan
 * @property string $nama_prodi
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\Jurusan $jurusan
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Prodi newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Prodi newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Prodi onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Prodi query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Prodi whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Prodi whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Prodi whereIdJurusan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Prodi whereIdProdi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Prodi whereNamaProdi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Prodi whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Prodi withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Prodi withoutTrashed()
 * @mixin \Eloquent
 */
class Prodi extends Model
{
    use HasFactory;
    /**
     * fillable
     *
     * @var array
     */
    protected $table = 'prodi';
    protected $primaryKey = 'id_prodi';
    protected $fillable = [
        'id_jurusan',
        'nama_prodi'
    ];

    public function jurusan(): BelongsTo
    {
        return $this->belongsTo(Jurusan::class, 'id_jurusan', 'id_jurusan');
    }
}
