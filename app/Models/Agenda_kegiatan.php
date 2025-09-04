<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 *
 *
 * @property int $id_agenda
 * @property int $id_bidang
 * @property string $nama_kegiatan
 * @property string $tanggal
 * @property string $lokasi
 * @property string $keterangan
 * @property string $file_sk
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\Bidang $bidang
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Agenda_kegiatan newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Agenda_kegiatan newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Agenda_kegiatan onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Agenda_kegiatan query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Agenda_kegiatan whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Agenda_kegiatan whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Agenda_kegiatan whereFileSk($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Agenda_kegiatan whereIdAgenda($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Agenda_kegiatan whereIdBidang($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Agenda_kegiatan whereKeterangan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Agenda_kegiatan whereLokasi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Agenda_kegiatan whereNamaKegiatan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Agenda_kegiatan whereTanggal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Agenda_kegiatan whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Agenda_kegiatan withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Agenda_kegiatan withoutTrashed()
 * @mixin \Eloquent
 */
class Agenda_kegiatan extends Model
{
    use HasFactory;
    /**
     * fillable
     *
     * @var array
     */
    protected $table = 'agenda_kegiatan';
    protected $primaryKey = 'id_agenda';
    protected $fillable = [
        'id_bidang',
        'nama_kegiatan',
        'tanggal',
        'lokasi',
        'keterangan',
        'file_sk'
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
