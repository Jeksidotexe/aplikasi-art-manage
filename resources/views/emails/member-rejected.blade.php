@component('mail::message')
# Pemberitahuan Status Pendaftaran Anggota

Yth. **{{ $namaUser }}**,

Terima kasih atas minat Anda untuk bergabung dengan Unit Kegiatan Mahasiswa (UKM) Seni & Budaya.

Setelah melakukan peninjauan terhadap data pendaftaran yang Anda kirimkan, dengan berat hati kami memberitahukan bahwa
pendaftaran Anda **belum dapat kami terima** saat ini.

**Alasan penolakan:**
@component('mail::panel')
{{ $alasan }}
@endcomponent

Kami sangat menghargai waktu dan antusiasme yang telah Anda tunjukkan. Keputusan ini tidak mengurangi penghargaan kami
terhadap Anda. Jika Anda memiliki pertanyaan lebih lanjut, jangan ragu untuk menghubungi kami.

Terima kasih atas pengertiannya.

Hormat kami,
<br>
**Pengurus UKM Seni & Budaya**
@endcomponent