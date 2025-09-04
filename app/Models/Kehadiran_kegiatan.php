<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 *
 *
 * @property int $id_kehadiran
 * @property int $id_agenda
 * @property int $id_bidang
 * @property string $file_absensi
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\Agenda_kegiatan $agenda_kegiatan
 * @property-read \App\Models\Bidang $bidang
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kehadiran_kegiatan newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kehadiran_kegiatan newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kehadiran_kegiatan onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kehadiran_kegiatan query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kehadiran_kegiatan whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kehadiran_kegiatan whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kehadiran_kegiatan whereFileAbsensi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kehadiran_kegiatan whereIdAgenda($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kehadiran_kegiatan whereIdBidang($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kehadiran_kegiatan whereIdKehadiran($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kehadiran_kegiatan whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kehadiran_kegiatan withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kehadiran_kegiatan withoutTrashed()
 * @mixin \Eloquent
 */
class Kehadiran_kegiatan extends Model
{
    use HasFactory;
    /**
     * fillable
     *
     * @var array
     */
    protected $table = 'kehadiran_kegiatan';
    protected $primaryKey = 'id_kehadiran';
    protected $fillable = [
        'id_agenda',
        'id_bidang',
        'id_users',
        'foto_absensi'
    ];

    /**
     * Agenda_kegiatan
     *
     * @return void
     */

    public function agenda_kegiatan(): BelongsTo
    {
        return $this->belongsTo(Agenda_kegiatan::class, 'id_agenda', 'id_agenda');
    }

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
