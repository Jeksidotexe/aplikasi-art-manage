@component('mail::message')
# Selamat Bergabung di UKM Seni & Budaya!

Halo, **{{ $user->nama }}**,

Dengan senang hati kami memberitahukan bahwa pendaftaran Anda sebagai anggota Unit Kegiatan Mahasiswa (UKM) Seni &
Budaya telah kami **terima**.

Selamat datang! Anda sekarang adalah bagian dari keluarga besar kami. Anda dapat mulai mengakses semua fitur anggota,
termasuk informasi kegiatan dan peminjaman alat, dengan login melalui tombol di bawah ini.

@component('mail::button', ['url' => route('login')])
Login Sekarang
@endcomponent

Berikut adalah detail akun Anda yang terdaftar:
@component('mail::panel')
**NIM:** {{ $user->nim }} <br>
**Nama:** {{ $user->nama }} <br>
**Email:** {{ $user->email }}
@endcomponent

Kami tidak sabar untuk melihat partisipasi dan kontribusi Anda dalam kegiatan-kegiatan kami ke depan.

Terima kasih telah bergabung,
<br>
**Pengurus UKM Seni & Budaya**
@endcomponent