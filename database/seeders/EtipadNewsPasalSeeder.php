<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\EtipadNews;
use Illuminate\Support\Str;

class EtipadNewsPasalSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            [
                'title' => 'Pasal 1',
                'tag' => 'Pasal',
                'summary' => 'Potensi pelanggaran Pasal 1 pada berita investigasi Tempo soal pencabutan 2.078 izin tambang dan dugaan upeti tanpa verifikasi memadai.',
                'content' => <<<'TXT'
Berita investigasi Tempo tentang pencabutan 2.078 izin tambang oleh Menteri Bahlil mengandung potensi pelanggaran terhadap Pasal 1 Kode Etik Jurnalistik. Dalam berita tersebut terdapat tuduhan serius berupa “dugaan upeti Rp 5–25 miliar” yang dikaitkan dengan pencabutan atau pengaktifan kembali izin usaha pertambangan. Meskipun ada bantahan dari Menteri Bahlil, jika tuduhan ini disajikan seolah fakta tanpa verifikasi yang memadai, maka berita bisa menyesatkan pembaca.

Selain itu, keberimbangan pemberitaan perlu diperhatikan. Jika narasumber pihak yang dituduh tidak cukup diberi ruang untuk menjelaskan, berita menjadi berat sebelah dan berisiko menimbulkan misinformasi atau pencemaran nama pejabat. Pasal 1 menegaskan bahwa wartawan harus menjaga independensi, akurasi, dan keberimbangan serta tidak menulis dengan itikad buruk. Oleh karena itu, artikel ini memiliki risiko pelanggaran etika bila aspek verifikasi fakta dan keberimbangan tidak terpenuhi.
TXT,
            ],
            [
                'title' => 'Pasal 2 – Cara Kerja Profesional',
                'tag' => 'Pasal',
                'summary' => 'Headline “Ongkang-Ongkang Kaki Dapat Rp112 Juta” dinilai tidak melalui verifikasi memadai dan mengandung opini menyesatkan.',
                'content' => <<<'TXT'
“Wartawan Indonesia menempuh cara-cara yang profesional dalam melaksanakan tugas jurnalistik”
Media Radar bogor menulis headline “Ongkang-Ongkang Kaki Dapat Rp112 Juta”, terkait dana untuk pensiunan Presiden RI ke-6, Susilo Bambang Yudhoyono. Berita tersebut dinilai tidak melalui verifikasi yang memadai dan mengandung opini yang menyesatkan, sehingga tidak mencerminkan cara kerja profesional.
TXT,
            ],
            [
                'title' => 'Pasal 3',
                'tag' => 'Pasal',
                'summary' => 'Contoh pelanggaran Pasal 3: tuduhan ijazah palsu tanpa bukti dan tanpa konfirmasi, serta percampuran fakta-opini yang menghakimi.',
                'content' => <<<'TXT'
Berita dari MimbarRiau.com yang berjudul “Menuju 100 Hari Kepemimpinan Bupati Rohil, Muhajirin: Memalukan”mengandung beberapa pelanggaran terhadap Pasal 3 Kode Etik Jurnalistik.
Dari sisi verifikasi informasi, wartawan menulis tuduhan serius tentang dugaan penggunaan ijazah palsu oleh Bupati tanpa bukti jelas atau konfirmasi langsung. Tidak ada keterangan bahwa pihak Bupati atau lembaga hukum dimintai penjelasan, sehingga informasi yang disajikan belum teruji kebenarannya.
Berita tersebut bersifat informatif, memenuhi hak masyarakat untuk mengetahui, namun tidak berimbang dan mencampurkan fakta dan opini yang menghakimi sehingga melanggar Kode Etik Jurnalistik Pasal 3 yakni “Wartawan Indonesia selalu menguji informasi, memberitakan secara berimbang, tidak mencampurkan fakta dan opini yang menghakimi, serta menerapkan asas praduga tak bersalah”.
TXT,
            ],
            [
                'title' => 'Pasal 4 – Tidak Sadis dan Cabul',
                'tag' => 'Pasal',
                'summary' => 'Liputan6 diduga melanggar Pasal 4 karena deskripsi bernuansa cabul dalam pemberitaan tentang pose foto tidak pantas.',
                'content' => <<<'TXT'
“Wartawan Indonesia tidak membuat berita bohong, fitnah, sadis, dan cabul”
Berita yang diterbitkan oleh media Liputan6.com menuliskan aksi seorang perempuan yang berfoto dengan pose tidak pantas dan menuai kritik dari banyak pihak. Terlihat jelas dalam isi berita yang mendeskripsikan perempuan mengenakan celana denim pendek dan kaus hitam dengan mengubah posenya yaitu mendekatkan bokongnya ke tubuh patung seolah sedang berhubungan seks. pernyataan tersebut jelas mengandung unsur cabul, karena kalimat yang kurang pantas untuk diutarakan dalam pemberitaan. 
TXT,
            ],
            [
                'title' => 'Pasal 5 – Identitas Korban/Anak',
                'tag' => 'Pasal',
                'summary' => 'Contoh pelanggaran Pasal 5 oleh Jawa Pos Radar Solo: menyebut identitas korban susila dan identitas anak pelaku.',
                'content' => <<<'TXT'
“Wartawan Indonesia tidak menyebutkan dan menyiarkan identitas korban kejahatan susila dan tidak menyebutkan identitas anak yang menjadi pelaku kejahatan”
Berita dari Jawa Pos Radar Solo melanggar pasal 5 KEJ karena secara tegas menyebutkan identitas korban kejahatan susila dan menyebutkan bahwa pelaku pembunuhan masih dibawah umur. Hal ini dapat dilihat pada judul dan isi berita yang menuliskan Ayu Andriani sebagai korban, serta penyebutan “bocil” dalam isi berita berdampak negatif bagi perkembangan dan perlindungan anak tersebut. pengungkapan ini melanggar ketentuan yang bertujuan meminimalkan dampak psikologis dan sosial bagi anak pelaku kejahatan.
TXT,
            ],
            [
                'title' => 'Pasal 6 – Penyalahgunaan Profesi',
                'tag' => 'Pasal',
                'summary' => 'Kasus pengaduan terhadap katta.id: indikasi pemerasan dan menyalahgunakan profesi untuk keuntungan pribadi.',
                'content' => <<<'TXT'
“Wartawan Indonesia tidak menyalahgunakan profesi dan tidak menerima suap”
Pengaduan Effendi ghazali terhadap media siber katta.id terkait dugaan penyalahgunaan profesi wartawan dan indikasi upaya pemerasan. Wartawan teradu mengambil keuntungan pribadi atas informasi yang diperoleh dari Pengadu, dengan meminta saran untuk judul berita dan menyatakan kemampuan untuk menyembunyikan informasi negatif tentang Pengadu. Ini menunjukkan unsur “menyalahgunakan profesi”. 
TXT,
            ],
            [
                'title' => 'Pasal 7 – Hak Tolak & Off the Record',
                'tag' => 'Pasal',
                'summary' => 'Contoh pelanggaran off the record di Yogyakarta: informasi yang disepakati tidak untuk disiarkan tetap dipublikasikan.',
                'content' => <<<'TXT'
“Wartawan Indonesia memiliki hak tolak untuk melindungi narasumber yang tidak bersedia diketahui identitas maupun keberadaannya, menghargai ketentuan embargo, informasi latar belakang, dan off the record sesuai dengan kesepakatan.”
Wartawan satu harian di Yogyakarta. Seorang narasumber dari kantor Telekomunikasi setempat mengungkapkan bahwa ada pungutan tidak resmi oleh Asosiasi Warung Telepon di Yogyakarta antara Rp5 juta - Rp25 juta. Keterangan tersebut dengan jelas dan tegas dinyatakan sebagai off the record. Tetapi, ternyata ole wartawan surat kabar ini keterangan tersebut tetap disiarkan. Ini jelas merupakan pelanggaran Kode Etik Jurnalistik, yakni menyiarkan berita yang sebenarnya off the record.
TXT,
            ],
            [
                'title' => 'Pasal 8 – Anti Diskriminasi',
                'tag' => 'Pasal',
                'summary' => 'Kompas.com: contoh diksi yang merendahkan suku Polahi/Boti dan tidak berimbang—berpotensi melanggar Pasal 8.',
                'content' => <<<'TXT'
Berita dari Kompas.com tentang Suku Polahi dan Suku Boti mengandung pelanggaran terhadap Pasal 8 Kode Etik Jurnalistik. Dalam berita tersebut, wartawan menggunakan kata-kata yang terkesan merendahkan seperti “hidup seperti manusia zaman purba” dan “setengah manusia setengah hewan”. Kalimat seperti ini menunjukkan adanya prasangka dan pandangan diskriminatif terhadap kelompok masyarakat adat.

Berita tersebut tidak menampilkan pandangan atau keterangan langsung dari anggota suku yang diberitakan. Akibatnya, informasi yang muncul hanya dari sudut pandang wartawan, tanpa memberi kesempatan bagi pihak yang diberitakan untuk menjelaskan kondisi sebenarnya. Hal ini membuat pemberitaan menjadi tidak berimbang dan cenderung menilai secara sepihak.

Isi berita tersebut juga bisa menimbulkan stigma di masyarakat bahwa suku Polahi dan Boti adalah kelompok yang tertinggal dan aneh, padahal setiap suku memiliki budaya dan cara hidup yang harus dihormati. Karena itulah, pemberitaan ini dinilai melanggar Pasal 8 Kode Etik Jurnalistik, yang menegaskan bahwa wartawan tidak boleh menulis atau menyiarkan berita berdasarkan prasangka atau diskriminasi terhadap seseorang atas dasar suku, ras, atau budaya, serta tidak boleh merendahkan martabat manusia.
TXT,
            ],
            [
                'title' => 'Pasal 9 – Perlindungan Anak',
                'tag' => 'Pasal',
                'summary' => 'detikcom: penyebutan identitas anak di bawah umur dan pelabelan “pelaku” tanpa perlindungan melanggar Pasal 9.',
                'content' => <<<'TXT'
Berita detikcom tentang AG, pacar Mario Dandy, yang masih di bawah umur mengandung pelanggaran terhadap Pasal 9 Kode Etik Jurnalistik. Dalam berita tersebut, identitas anak di bawah umur disebutkan (usia, inisial, hubungan dengan pelaku) dan ia diberi label “pelaku” tanpa perlindungan khusus.

Akibatnya, informasi yang muncul berpotensi menstigma anak dan mengganggu hak privasi mereka. Pasal 9 menegaskan bahwa wartawan wajib menjaga privasi dan melindungi identitas anak di bawah umur serta tidak mengekspos mereka secara berlebihan dalam pemberitaan. Dengan mengungkap identitas secara langsung, berita ini dinilai melanggar Pasal 9, karena tidak memberikan perlindungan yang seharusnya bagi anak sebagai subjek berita.
TXT,
            ],
            [
                'title' => 'Pasal 10 & 11 – Klarifikasi & Hak Jawab',
                'tag' => 'Pasal',
                'summary' => 'Kasus Pijar Flores: tuduhan tanpa verifikasi dan abai hak jawab—melanggar Pasal 10 dan 11 hingga harus klarifikasi.',
                'content' => <<<'TXT'
Kasus media Pijar Flores (Desember 2024) merupakan pelanggaran Pasal 10 dan 11 Kode Etik Jurnalistik. Dalam beritanya pada 7 Oktober 2024 berjudul “Media Floresa Sering Sebarkan Berita Provokasi, Abaikan Suara Warga Poco Leok yang Pro Pengembangan Geotermal”, Pijar Flores menuduh tanpa verifikasi dan mencampurkan fakta dengan opini yang menghakimi. Media tersebut juga tidak memberi ruang hak jawab kepada pihak Floresa.co hingga Dewan Pers turun tangan. Hal ini melanggar Pasal 10 karena tidak segera memperbaiki berita keliru, serta Pasal 11 karena tidak melayani hak jawab secara proporsional. Setelah putusan Dewan Pers, Pijar Flores akhirnya menerbitkan klarifikasi dan permintaan maaf.
TXT,
            ],
        ];

        $date = now();
        foreach ($items as $i => $it) {
            EtipadNews::updateOrCreate(
                ['slug' => Str::slug($it['title'])],
                [
                    'title' => $it['title'],
                    'tag' => $it['tag'] ?? 'Pasal',
                    'author' => 'Redaksi Etipad',
                    'summary' => $it['summary'],
                    'content' => $it['content'],
                    'published_at' => $date->copy()->subDays($i),
                    'hero_url' => null,
                ]
            );
        }
    }
}
