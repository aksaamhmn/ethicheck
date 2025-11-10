<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Topic;
use App\Models\CaseStudy;
use App\Models\CaseSentence;
use App\Models\SentenceViolation;

class SikataStageOneSeeder extends Seeder
{
    public function run(): void
    {
        // Topics
        $topics = [
            ['slug' => 'pelecehan-seksual', 'name' => 'Isu Pelecehan Seksual', 'description' => null],
            ['slug' => 'sara', 'name' => 'Isu SARA', 'description' => null],
            ['slug' => 'ekonomi', 'name' => 'Isu Ekonomi', 'description' => null],
        ];

        foreach ($topics as $t) {
            Topic::updateOrCreate(['slug' => $t['slug']], $t);
        }

        // 1) Isu Pelecehan Seksual
        $topic = Topic::where('slug', 'pelecehan-seksual')->first();
        $case = CaseStudy::updateOrCreate([
            'topic_id' => $topic->id,
            'title' => 'Mahasiswi Cantik Unpad Dilecehkan Dosen Ternama, Korban Akui Trauma Berat!',
        ], [
            'summary' => null,
            'article_body' => 'Bandung – Dunia kampus dihebohkan dengan kabar mengejutkan. Seorang mahasiswi cantik berinisial S (21) mengaku menjadi korban pelecehan seksual oleh dosennya sendiri, Dr. R, yang dikenal sebagai figur akademik populer dan sering tampil di televisi. Menurut pengakuan S kepada wartawan kami, insiden tersebut terjadi di ruang kerja dosen saat bimbingan skripsi. “Beliau memegang tangan saya, lalu mencoba mendekat. Saya ketakutan,” ujar S dengan suara gemetar sambil menunduk. Berdasarkan penelusuran redaksi, Dr. R diketahui telah berkeluarga dan memiliki dua anak. Dalam akun media sosialnya, ia kerap mengunggah foto bersama mahasiswi dan memberi emoji hati. Banyak warganet kemudian menyebut Dr. R sebagai “predator kampus”. Namun, sejumlah mahasiswa mengaku tidak heran dan mengatakan Dr. R “memang genit dari dulu”. Berita ini langsung viral di media sosial, memicu kecaman luas dari publik. Hingga kini, kasus ini masih dalam proses penyelidikan internal kampus.',
            'final_title' => 'Mahasiswi Laporkan Dugaan Pelecehan Seksual oleh Dosen ke Pihak Kampus',
            'final_article' => 'Bandung – Seorang mahasiswi Universitas Padjadjaran melaporkan dugaan pelecehan seksual oleh dosennya kepada pihak kampus. Kasus ini kini dalam proses pemeriksaan oleh tim etik universitas.\nBerdasarkan laporan yang diterima pihak kampus, dugaan pelecehan terjadi di lingkungan akademik. Identitas korban dirahasiakan untuk menjaga privasi dan keamanan.\nDr. R merupakan dosen tetap di kampus yang sama, namun hingga kini belum memberikan tanggapan terkait laporan tersebut.\nBeberapa mahasiswa memilih tidak berkomentar hingga proses penyelidikan selesai. Pihak kampus menyatakan komitmennya untuk menindaklanjuti kasus ini sesuai aturan dan menjamin perlindungan bagi semua pihak yang terlibat.',
            'is_active' => true,
        ]);

        $sentences = [
            1 => 'Bandung – Dunia kampus dihebohkan dengan kabar mengejutkan.',
            2 => 'Seorang mahasiswi cantik berinisial S (21) mengaku menjadi korban pelecehan seksual oleh dosennya sendiri, Dr. R, yang dikenal sebagai figur akademik populer dan sering tampil di televisi.',
            3 => 'Menurut pengakuan S kepada wartawan kami, insiden tersebut terjadi di ruang kerja dosen saat bimbingan skripsi.',
            4 => '“Beliau memegang tangan saya, lalu mencoba mendekat.',
            5 => 'Saya ketakutan,” ujar S dengan suara gemetar sambil menunduk.',
            6 => 'Berdasarkan penelusuran redaksi, Dr. R diketahui telah berkeluarga dan memiliki dua anak.',
            7 => 'Dalam akun media sosialnya, ia kerap mengunggah foto bersama mahasiswi dan memberi emoji hati.',
            8 => 'Banyak warganet kemudian menyebut Dr. R sebagai “predator kampus”.',
            9 => 'Namun, sejumlah mahasiswa mengaku tidak heran dan mengatakan Dr. R “memang genit dari dulu”.',
            10 => 'Berita ini langsung viral di media sosial, memicu kecaman luas dari publik.',
            11 => 'Hingga kini, kasus ini masih dalam proses penyelidikan internal kampus.',
        ];

        $map = [];
        foreach ($sentences as $idx => $txt) {
            $s = CaseSentence::updateOrCreate(['case_id' => $case->id, 'sentence_index' => $idx], ['text' => $txt]);
            $map[$idx] = $s->id;
        }

        // Violations (from user table)
        $violations = [
            2 => [
                ['violation_code' => 'KEJ-8', 'violation_title' => 'Diskriminatif dan tidak relevan', 'snippet' => 'Mahasiswi cantik...', 'description' => 'Menyebut fisik korban memperkuat stereotip gender dan tidak relevan dengan substansi kasus.', 'legal_basis' => 'KEJ Pasal 8 (tidak menulis diskriminatif), Pedoman Pemberitaan Isu Kekerasan Seksual (Dewan Pers 2022)', 'severity' => 'minor'],
                ['violation_code' => 'KEJ-5', 'violation_title' => 'Identifikasi korban', 'snippet' => 'S (21)', 'description' => 'Menyebut inisial, usia, dan identitas yang bisa menyingkap korban pelaku kekerasan seksual.', 'legal_basis' => 'KEJ Pasal 5, UU Pers No. 40/1999 Pasal 5 ayat (1)', 'severity' => 'major'],
            ],
            3 => [
                ['violation_code' => null, 'violation_title' => 'Wawancara korban tanpa perlindungan psikologis', 'snippet' => 'Menurut pengakuan S kepada wartawan kami...', 'description' => 'Wartawan tidak boleh menekan atau mengekspos pernyataan korban tanpa pendampingan ahli atau izin sadar.', 'legal_basis' => 'Pedoman Pemberitaan Isu Kekerasan Seksual, KEJ Pasal 3', 'severity' => 'minor'],
            ],
            6 => [
                ['violation_code' => 'KEJ-1', 'violation_title' => 'Tidak relevan dan berpotensi menggiring opini', 'snippet' => 'Dr. R diketahui telah berkeluarga dan memiliki dua anak.', 'description' => 'Informasi pribadi tidak relevan dengan inti kasus dan memperkuat framing moral.', 'legal_basis' => 'KEJ Pasal 1 (berimbang), Pasal 3 (tidak beritikad buruk)', 'severity' => 'minor'],
            ],
            8 => [
                ['violation_code' => null, 'violation_title' => 'Penyebaran tuduhan tanpa verifikasi', 'snippet' => 'Banyak warganet menyebut Dr. R sebagai ‘predator kampus’.', 'description' => 'Mengutip opini publik tanpa verifikasi bisa dianggap pencemaran nama baik.', 'legal_basis' => 'KEJ Pasal 3, UU Pers Pasal 5 ayat (2), Pedoman Siber Pasal 4', 'severity' => 'major'],
            ],
            9 => [
                ['violation_code' => null, 'violation_title' => 'Tidak berimbang dan menuduh tanpa bukti', 'snippet' => 'Mahasiswa lain mengatakan Dr. R ‘memang genit dari dulu’.', 'description' => 'Tidak ada konfirmasi dari pihak terlapor.', 'legal_basis' => 'KEJ Pasal 1 (berimbang), Pasal 3 (verifikasi fakta)', 'severity' => 'minor'],
            ],
        ];

        foreach ($violations as $idx => $rows) {
            foreach ($rows as $v) {
                SentenceViolation::updateOrCreate([
                    'case_sentence_id' => $map[$idx],
                    'violation_title' => $v['violation_title'],
                ], $v + ['case_sentence_id' => $map[$idx]]);
            }
        }

        // 2) Isu SARA (lengkap sesuai materi pengguna)
        $topic = Topic::where('slug', 'sara')->first();
        $case = CaseStudy::updateOrCreate([
            'topic_id' => $topic->id,
            'title' => 'Pejabat X Mengecam Kelompok Y Secara Terbuka, Warga Heboh',
        ], [
            'summary' => null,
            'article_body' => 'Jakarta – Pernyataan pejabat X dalam forum publik yang digelar di Balai Kota beberapa hari lalu menjadi perhatian publik. Dalam pidatonya, pejabat X menyinggung kelompok Y sebagai penyebab berbagai masalah sosial di wilayahnya. Pernyataan tersebut disampaikan dengan nada keras dan dianggap sebagian warga sebagai bentuk kebencian terhadap kelompok tertentu. Beragam tanggapan muncul di media sosial, mencerminkan pandangan masyarakat yang berbeda-beda. Beberapa tokoh masyarakat juga menilai komentar pejabat X “tidak pantas” dan dapat memicu konflik di tengah masyarakat. Pernyataan tersebut menjadi perbincangan luas di berbagai platform daring. Banyak warganet menilai pejabat X tidak layak menjabat dan menuntut pencopotan dari jabatannya. Hingga kini, pejabat X belum memberikan klarifikasi resmi, sementara pemerintah daerah menyatakan akan menindaklanjuti sesuai aturan.',
            'final_title' => 'Pejabat X Diduga Mengeluarkan Pernyataan Menyinggung Kelompok Tertentu',
            'final_article' => 'Jakarta – Pernyataan pejabat X dalam forum publik yang digelar di Balai Kota beberapa hari lalu menjadi perhatian publik. Dalam pidatonya, pejabat X menyoroti masalah sosial yang terjadi di wilayahnya. Pernyataan pejabat X menjadi perhatian warga dengan berbagai komentar yang muncul.\nBeragam tanggapan muncul di media sosial, mencerminkan pandangan masyarakat yang berbeda-beda. Beberapa tokoh masyarakat juga meminta klarifikasi atas ucapan pejabat X.\nPernyataan tersebut menjadi perbincangan luas di berbagai platform daring. Banyak warganet menyampaikan pandangan bahwa pejabat X sebaiknya dicopot dari jabatannya, sementara berbagai opini lain juga muncul di media sosial. Hingga kini, pejabat X belum memberikan klarifikasi resmi, sementara pemerintah daerah menyatakan akan menindaklanjuti sesuai aturan.',
            'is_active' => true,
        ]);
        $sents = [
            1 => 'Jakarta – Pernyataan pejabat X dalam forum publik yang digelar di Balai Kota beberapa hari lalu menjadi perhatian publik.',
            2 => 'Dalam pidatonya, pejabat X menyinggung kelompok Y sebagai penyebab berbagai masalah sosial di wilayahnya.',
            3 => 'Pernyataan tersebut disampaikan dengan nada keras dan dianggap sebagian warga sebagai bentuk kebencian terhadap kelompok tertentu.',
            4 => 'Beragam tanggapan muncul di media sosial, mencerminkan pandangan masyarakat yang berbeda-beda.',
            5 => 'Beberapa tokoh masyarakat juga menilai komentar pejabat X "tidak pantas" dan dapat memicu konflik di tengah masyarakat.',
            6 => 'Pernyataan tersebut menjadi perbincangan luas di berbagai platform daring.',
            7 => 'Banyak warganet menilai pejabat X tidak layak menjabat dan menuntut pencopotan dari jabatannya.',
            8 => 'Hingga kini, pejabat X belum memberikan klarifikasi resmi, sementara pemerintah daerah menyatakan akan menindaklanjuti sesuai aturan.',
        ];
        $map = [];
        foreach ($sents as $i => $t) {
            $s = CaseSentence::updateOrCreate(['case_id' => $case->id, 'sentence_index' => $i], ['text' => $t]);
            $map[$i] = $s->id;
        }
        $vio = [
            2 => [[
                'violation_code' => null,
                'violation_title' => 'Diskriminatif / SARA',
                'snippet' => 'menyinggung kelompok Y sebagai penyebab berbagai masalah sosial di wilayahnya',
                'description' => 'Mengaitkan masalah sosial dengan identitas kelompok, berpotensi memicu konflik.',
                'legal_basis' => 'KEJ Pasal 1, 3; UU Pers Pasal 4 & 5',
                'severity' => 'major'
            ]],
            3 => [[
                'violation_code' => null,
                'violation_title' => 'Tidak terverifikasi / Spekulatif',
                'snippet' => 'bentuk kebencian terhadap kelompok tertentu',
                'description' => 'Menyimpulkan sikap pejabat tanpa bukti kuat dan tanpa konfirmasi.',
                'legal_basis' => 'KEJ Pasal 3',
                'severity' => 'minor'
            ]],
            5 => [[
                'violation_code' => null,
                'violation_title' => 'Tidak berimbang',
                'snippet' => '“tidak pantas”',
                'description' => 'Mengutip opini tokoh tanpa konfirmasi pejabat, bisa bias.',
                'legal_basis' => 'KEJ Pasal 3, Pedoman Siber Pasal 4',
                'severity' => 'minor'
            ]],
            7 => [[
                'violation_code' => null,
                'violation_title' => 'Opini publik tanpa verifikasi',
                'snippet' => 'tidak layak menjabat',
                'description' => 'Mengutip opini subjektif tanpa sumber jelas; bisa menimbulkan prasangka.',
                'legal_basis' => 'KEJ Pasal 3; Pedoman Siber Pasal 4',
                'severity' => 'minor'
            ]],
        ];
        foreach ($vio as $idx => $rows) {
            foreach ($rows as $v) {
                SentenceViolation::updateOrCreate(['case_sentence_id' => $map[$idx], 'violation_title' => $v['violation_title']], $v + ['case_sentence_id' => $map[$idx]]);
            }
        }

        // 3) Isu Ekonomi (lengkap sesuai materi pengguna)
        $topic = Topic::where('slug', 'ekonomi')->first();
        $case = CaseStudy::updateOrCreate([
            'topic_id' => $topic->id,
            'title' => '“CEO Muda Kaya Raya FinGrow Ketahuan Tilep Dana Investor! Uang Ratusan Miliar Raib!”',
        ], [
            'summary' => null,
            'article_body' => 'Jakarta – Dunia startup geger! CEO muda nan tajir FinGrow, Arga Pratama (28), dituding menilep dana investor hingga ratusan miliar rupiah. Kasus ini mencuat setelah sejumlah investor asing melapor ke OJK karena dana mereka hilang tanpa kejelasan. “Kami hanya ingin uang kami kembali. FinGrow ternyata cuma tipu-tipu!” ujar salah satu investor dengan nada marah. Berdasarkan penelusuran redaksi, Arga kerap memamerkan mobil sport dan liburan mewah di media sosial. Banyak netizen menyebutnya “Crazy Rich Bodong”! Kasus ini menjadi sorotan publik dan ramai diperbincangkan di platform X, namun sampai sat ini pihak FinGrow belum memberikan tanggapan resmi atas laporan tersebut.',
            'final_title' => 'OJK Selidiki Dugaan Penyelewengan Dana di Startup FinGrow',
            'final_article' => 'Jakarta – Otoritas Jasa Keuangan (OJK) tengah menyelidiki dugaan penyelewengan dana oleh pimpinan startup keuangan digital FinGrow. Kasus ini mencuat setelah sejumlah investor asing melapor ke OJK karena dana mereka hilang tanpa kejelasan. Salah satu investor menyatakan kekecewaannya dan meminta agar dana yang telah diinvestasikan segera dikembalikan.\nFinGrow merupakan startup keuangan digital yang berdiri sejak 2019 dan saat ini tengah dalam proses pemeriksaan oleh otoritas terkait. Beberapa komentar di media sosial menyinggung gaya hidup mewah CEO FinGrow, meskipun klaim terkait kasus ini masih belum diverifikasi kebenarannya.\nKasus ini menjadi sorotan publik dan ramai diperbincangkan di platform X, namun sampai saat ini pihak FinGrow belum memberikan tanggapan resmi atas laporan tersebut.',
            'is_active' => true,
        ]);
        $sents = [
            1 => 'Jakarta – Dunia startup geger! CEO muda nan tajir FinGrow, Arga Pratama (28), dituding menilep dana investor hingga ratusan miliar rupiah.',
            2 => 'Kasus ini mencuat setelah sejumlah investor asing melapor ke OJK karena dana mereka hilang tanpa kejelasan.',
            3 => '“Kami hanya ingin uang kami kembali. FinGrow ternyata cuma tipu-tipu!” ujar salah satu investor dengan nada marah.',
            4 => 'Berdasarkan penelusuran redaksi, Arga kerap memamerkan mobil sport dan liburan mewah di media sosial.',
            5 => 'Banyak netizen menyebutnya “Crazy Rich Bodong”!',
            6 => 'Kasus ini menjadi sorotan publik dan ramai diperbincangkan di platform X, namun sampai sat ini pihak FinGrow belum memberikan tanggapan resmi atas laporan tersebut.',
        ];
        $map = [];
        foreach ($sents as $i => $t) {
            $s = CaseSentence::updateOrCreate(['case_id' => $case->id, 'sentence_index' => $i], ['text' => $t]);
            $map[$i] = $s->id;
        }
        $vio = [
            1 => [[
                'violation_code' => null,
                'violation_title' => 'Stereotip & sensasional',
                'snippet' => 'CEO muda nan tajir',
                'description' => 'Mengaitkan kekayaan pribadi dengan tuduhan tanpa relevansi.',
                'legal_basis' => 'KEJ Pasal 3',
                'severity' => 'minor'
            ]],
            3 => [[
                'violation_code' => null,
                'violation_title' => 'Kutipan emosional tanpa verifikasi',
                'snippet' => 'cuma tipu-tipu!',
                'description' => 'Wawancara sepihak tanpa bukti konkret.',
                'legal_basis' => 'KEJ Pasal 1 & 3',
                'severity' => 'minor'
            ]],
            4 => [[
                'violation_code' => null,
                'violation_title' => 'Tidak relevan',
                'snippet' => 'memamerkan mobil sport dan liburan mewah',
                'description' => 'Informasi pribadi tak terkait langsung dengan substansi kasus.',
                'legal_basis' => 'KEJ Pasal 8',
                'severity' => 'minor'
            ]],
            5 => [[
                'violation_code' => null,
                'violation_title' => 'Tuduhan & penghinaan',
                'snippet' => '“Crazy Rich Bodong”',
                'description' => 'Frasa merendahkan tanpa dasar hukum.',
                'legal_basis' => 'Pedoman Pemberitaan Media Siber (Dewan Pers) Pasal 3 & 4; KEJ Pasal 3',
                'severity' => 'major'
            ]],
        ];
        foreach ($vio as $idx => $rows) {
            foreach ($rows as $v) {
                SentenceViolation::updateOrCreate(['case_sentence_id' => $map[$idx], 'violation_title' => $v['violation_title']], $v + ['case_sentence_id' => $map[$idx]]);
            }
        }
    }
}
