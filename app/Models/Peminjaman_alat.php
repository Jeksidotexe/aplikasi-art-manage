<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 *
 *
 * @property int $id_peminjaman
 * @property int $id_users
 * @property int $id_alat
 * @property int $jumlah_pinjam
 * @property string $tanggal_pinjam
 * @property string $tanggal_harus_kembali
 * @property string|null $tanggal_kembali
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\Alat $alat
 * @property-read \App\Models\User $users
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Peminjaman_alat newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Peminjaman_alat newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Peminjaman_alat onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Peminjaman_alat query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Peminjaman_alat whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Peminjaman_alat whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Peminjaman_alat whereIdAlat($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Peminjaman_alat whereIdPeminjaman($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Peminjaman_alat whereIdUsers($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Peminjaman_alat whereJumlahPinjam($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Peminjaman_alat whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Peminjaman_alat whereTanggalHarusKembali($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Peminjaman_alat whereTanggalKembali($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Peminjaman_alat whereTanggalPinjam($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Peminjaman_alat whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Peminjaman_alat withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Peminjaman_alat withoutTrashed()
 * @mixin \Eloquent
 */
class Peminjaman_alat extends Model
{
    use HasFactory;
    /**
     * fillable
     *
     * @var array
     */
    protected $table = 'peminjaman_alat';
    protected $primaryKey = 'id_peminjaman';
    protected $fillable = [
        'id_users',
        'id_alat',
        'jumlah_pinjam',
        'tanggal_pinjam',
        'tanggal_harus_kembali',
        'tanggal_kembali',
        'status'
    ];

    /**
     * user
     *
     * @return void
     */

    public function users()
    {
        return $this->belongsTo(User::class, 'id_users', 'id_users');
    }

    /**
     * alat musik
     *
     * @return void
     */

    public function alat()
    {
        return $this->belongsTo(Alat::class, 'id_alat', 'id_alat');
    }
}
