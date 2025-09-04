<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 *
 *
 * @property int $id_jurusan
 * @property string $nama_jurusan
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Jurusan newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Jurusan newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Jurusan onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Jurusan query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Jurusan whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Jurusan whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Jurusan whereIdJurusan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Jurusan whereNamaJurusan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Jurusan whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Jurusan withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Jurusan withoutTrashed()
 * @mixin \Eloquent
 */
class Jurusan extends Model
{
    use HasFactory;
    /**
     * fillable
     *
     * @var array
     */
    protected $table = 'jurusan';
    protected $primaryKey = 'id_jurusan';
    protected $fillable = [
        'nama_jurusan'
    ];
}
