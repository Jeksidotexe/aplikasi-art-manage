<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
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
	class Agenda_kegiatan extends \Eloquent {}
}

namespace App\Models{
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
	class Alat extends \Eloquent {}
}

namespace App\Models{
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
	class Bidang extends \Eloquent {}
}

namespace App\Models{
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
	class Jurusan extends \Eloquent {}
}

namespace App\Models{
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
	class Kehadiran_kegiatan extends \Eloquent {}
}

namespace App\Models{
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
 * @property string|null $keterangan_admin
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Peminjaman_alat whereKeteranganAdmin($value)
 */
	class Peminjaman_alat extends \Eloquent {}
}

namespace App\Models{
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
	class Prodi extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id_users
 * @property string|null $nim
 * @property string $nama
 * @property int|null $id_jurusan
 * @property int|null $id_prodi
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $two_factor_secret
 * @property string|null $two_factor_recovery_codes
 * @property string|null $two_factor_confirmed_at
 * @property string $no_telepon
 * @property int|null $id_bidang
 * @property string $tanggal_daftar
 * @property string|null $foto
 * @property string $role
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\Bidang|null $bidang
 * @property-read \App\Models\Jurusan|null $jurusan
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \App\Models\Prodi|null $prodi
 * @property-read string $profile_photo_url
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Sanctum\PersonalAccessToken> $tokens
 * @property-read int|null $tokens_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereFoto($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereIdBidang($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereIdJurusan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereIdProdi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereIdUsers($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereNama($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereNim($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereNoTelepon($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereTanggalDaftar($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereTwoFactorConfirmedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereTwoFactorRecoveryCodes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereTwoFactorSecret($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User withoutTrashed()
 * @mixin \Eloquent
 * @property string $status
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereStatus($value)
 */
	class User extends \Eloquent implements \Illuminate\Contracts\Auth\MustVerifyEmail {}
}

