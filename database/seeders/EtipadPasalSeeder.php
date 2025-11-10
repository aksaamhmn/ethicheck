<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\EtipadDocument;

class EtipadPasalSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            [
                'slug' => 'pasal-1',
                'title' => 'Pasal 1',
                'content' => <<<'TXT'
Berita investigasi Tempo tentang pencabutan 2.078 izin tambang oleh Menteri Bahlil mengandung potensi pelanggaran terhadap Pasal 1 Kode Etik Jurnalistik. Dalam berita tersebut terdapat tuduhan serius berupa “dugaan upeti Rp 5–25 miliar” yang dikaitkan dengan pencabutan atau pengaktifan kembali izin usaha pertambangan. Meskipun ada bantahan dari Menteri Bahlil, jika tuduhan ini disajikan seolah fakta tanpa verifikasi yang memadai, maka berita bisa menyesatkan pembaca.

Selain itu, keberimbangan pemberitaan perlu diperhatikan. Jika narasumber pihak yang dituduh tidak cukup diberi ruang untuk menjelaskan, berita menjadi berat sebelah dan berisiko menimbulkan misinformasi atau pencemaran nama pejabat. Pasal 1 menegaskan bahwa wartawan harus menjaga independensi, akurasi, dan keberimbangan serta tidak menulis dengan itikad buruk. Oleh karena itu, artikel ini memiliki risiko pelanggaran etika bila aspek verifikasi fakta dan keberimbangan tidak terpenuhi.
TXT,
            ],
            [
                'slug' => 'pasal-2',
                'title' => 'Pasal 2 – “Wartawan Indonesia menempuh cara-cara yang profesional dalam melaksanakan tugas jurnalistik”',
                'content' => <<<'TXT'
Media Radar Bogor menulis headline “Ongkang-Ongkang Kaki Dapat Rp112 Juta”, terkait dana untuk pensiunan Presiden RI ke-6, Susilo Bambang Yudhoyono. Berita tersebut dinilai tidak melalui verifikasi yang memadai dan mengandung opini yang menyesatkan, sehingga tidak mencerminkan cara kerja profesional.
TXT,
            ],
            [
                'slug' => 'pasal-3',
                'title' => 'Pasal 3',
                'content' => <<<'TXT'
Berita dari MimbarRiau.com yang berjudul “Menuju 100 Hari Kepemimpinan Bupati Rohil, Muhajirin: Memalukan” mengandung beberapa pelanggaran terhadap Pasal 3 Kode Etik Jurnalistik.

Dari sisi verifikasi informasi, wartawan menulis tuduhan serius tentang dugaan penggunaan ijazah palsu oleh Bupati tanpa bukti jelas atau konfirmasi langsung. Tidak ada keterangan bahwa pihak Bupati atau lembaga hukum dimintai penjelasan, sehingga informasi yang disajikan belum teruji kebenarannya.

Berita tersebut bersifat informatif, memenuhi hak masyarakat untuk mengetahui, namun tidak berimbang dan mencampurkan fakta dan opini yang menghakimi sehingga melanggar Kode Etik Jurnalistik Pasal 3 yakni “Wartawan Indonesia selalu menguji informasi, memberitakan secara berimbang, tidak mencampurkan fakta dan opini yang menghakimi, serta menerapkan asas praduga tak bersalah”.
TXT,
            ],
            [
                'slug' => 'pasal-4',
                'title' => 'Pasal 4 – “Wartawan Indonesia tidak membuat berita bohong, fitnah, sadis, dan cabul”',
                'content' => <<<'TXT'
Berita yang diterbitkan oleh media Liputan6.com menuliskan aksi seorang perempuan yang berfoto dengan pose tidak pantas dan menuai kritik dari banyak pihak. Terlihat jelas dalam isi berita yang mendeskripsikan perempuan mengenakan celana denim pendek dan kaus hitam dengan mengubah posenya yaitu mendekatkan bokongnya ke tubuh patung seolah sedang berhubungan seks. Pernyataan tersebut jelas mengandung unsur cabul, karena kalimat yang kurang pantas untuk diutarakan dalam pemberitaan.
TXT,
            ],
            [
                'slug' => 'pasal-5',
                'title' => 'Pasal 5 – “Tidak menyebut identitas korban kejahatan susila dan anak pelaku”',
                'content' => <<<'TXT'
Berita dari Jawa Pos Radar Solo melanggar Pasal 5 KEJ karena secara tegas menyebutkan identitas korban kejahatan susila dan menyebutkan bahwa pelaku pembunuhan masih di bawah umur. Hal ini dapat dilihat pada judul dan isi berita yang menuliskan Ayu Andriani sebagai korban, serta penyebutan “bocil” dalam isi berita berdampak negatif bagi perkembangan dan perlindungan anak tersebut. Pengungkapan ini melanggar ketentuan yang bertujuan meminimalkan dampak psikologis dan sosial bagi anak pelaku kejahatan.
TXT,
            ],
            [
                'slug' => 'pasal-6',
                'title' => 'Pasal 6 – “Tidak menyalahgunakan profesi dan tidak menerima suap”',
                'content' => <<<'TXT'
Pengaduan Effendi Ghazali terhadap media siber katta.id terkait dugaan penyalahgunaan profesi wartawan dan indikasi upaya pemerasan. Wartawan teradu mengambil keuntungan pribadi atas informasi yang diperoleh dari Pengadu, dengan meminta saran untuk judul berita dan menyatakan kemampuan untuk menyembunyikan informasi negatif tentang Pengadu. Ini menunjukkan unsur “menyalahgunakan profesi”.
TXT,
            ],
            [
                'slug' => 'pasal-7',
                'title' => 'Pasal 7 – Hak tolak & off the record',
                'content' => <<<'TXT'
Wartawan satu harian di Yogyakarta. Seorang narasumber dari kantor Telekomunikasi setempat mengungkapkan bahwa ada pungutan tidak resmi oleh Asosiasi Warung Telepon di Yogyakarta antara Rp5 juta - Rp25 juta. Keterangan tersebut dengan jelas dan tegas dinyatakan sebagai off the record. Tetapi, ternyata oleh wartawan surat kabar ini keterangan tersebut tetap disiarkan. Ini jelas merupakan pelanggaran Kode Etik Jurnalistik, yakni menyiarkan berita yang sebenarnya off the record.
TXT,
            ],
            [
                'slug' => 'pasal-8',
                'title' => 'Pasal 8',
                'content' => <<<'TXT'
Berita dari Kompas.com tentang Suku Polahi dan Suku Boti mengandung pelanggaran terhadap Pasal 8 Kode Etik Jurnalistik. Dalam berita tersebut, wartawan menggunakan kata-kata yang terkesan merendahkan seperti “hidup seperti manusia zaman purba” dan “setengah manusia setengah hewan”. Kalimat seperti ini menunjukkan adanya prasangka dan pandangan diskriminatif terhadap kelompok masyarakat adat.

Berita tersebut tidak menampilkan pandangan atau keterangan langsung dari anggota suku yang diberitakan. Akibatnya, informasi yang muncul hanya dari sudut pandang wartawan, tanpa memberi kesempatan bagi pihak yang diberitakan untuk menjelaskan kondisi sebenarnya. Hal ini membuat pemberitaan menjadi tidak berimbang dan cenderung menilai secara sepihak.

Isi berita tersebut juga bisa menimbulkan stigma di masyarakat bahwa suku Polahi dan Boti adalah kelompok yang tertinggal dan aneh, padahal setiap suku memiliki budaya dan cara hidup yang harus dihormati. Karena itulah, pemberitaan ini dinilai melanggar Pasal 8 Kode Etik Jurnalistik, yang menegaskan bahwa wartawan tidak boleh menulis atau menyiarkan berita berdasarkan prasangka atau diskriminasi terhadap seseorang atas dasar suku, ras, atau budaya, serta tidak boleh merendahkan martabat manusia.
TXT,
            ],
            [
                'slug' => 'pasal-9',
                'title' => 'Pasal 9',
                'content' => <<<'TXT'
Berita detikcom tentang AG, pacar Mario Dandy, yang masih di bawah umur mengandung pelanggaran terhadap Pasal 9 Kode Etik Jurnalistik. Dalam berita tersebut, identitas anak di bawah umur disebutkan (usia, inisial, hubungan dengan pelaku) dan ia diberi label “pelaku” tanpa perlindungan khusus.

Akibatnya, informasi yang muncul berpotensi menstigma anak dan mengganggu hak privasi mereka. Pasal 9 menegaskan bahwa wartawan wajib menjaga privasi dan melindungi identitas anak di bawah umur serta tidak mengekspos mereka secara berlebihan dalam pemberitaan. Dengan mengungkap identitas secara langsung, berita ini dinilai melanggar Pasal 9, karena tidak memberikan perlindungan yang seharusnya bagi anak sebagai subjek berita.
TXT,
            ],
            [
                'slug' => 'pasal-10-11',
                'title' => 'Pasal 10 & 11',
                'content' => <<<'TXT'
Kasus media Pijar Flores (Desember 2024) merupakan pelanggaran Pasal 10 dan 11 Kode Etik Jurnalistik. Dalam beritanya pada 7 Oktober 2024 berjudul “Media Floresa Sering Sebarkan Berita Provokasi, Abaikan Suara Warga Poco Leok yang Pro Pengembangan Geotermal”, Pijar Flores menuduh tanpa verifikasi dan mencampurkan fakta dengan opini yang menghakimi. Media tersebut juga tidak memberi ruang hak jawab kepada pihak Floresa.co hingga Dewan Pers turun tangan. Hal ini melanggar Pasal 10 karena tidak segera memperbaiki berita keliru, serta Pasal 11 karena tidak melayani hak jawab secara proporsional. Setelah putusan Dewan Pers, Pijar Flores akhirnya menerbitkan klarifikasi dan permintaan maaf.
TXT,
            ],
        ];

        foreach ($items as $it) {
            EtipadDocument::updateOrCreate(
                ['slug' => $it['slug']],
                [
                    'title' => $it['title'],
                    'content' => $it['content'],
                    'file_path' => null,
                    'mime_type' => 'text/plain',
                ]
            );
        }
    }
}
