<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CaseStudy;
use App\Models\CaseSentence;
use App\Models\SentenceCorrection;

class SikataStageTwoSeeder extends Seeder
{
    public function run(): void
    {
        // Helper to create options
        $make = function (CaseSentence $sentence, array $options, string $correctKey, array $rationales) {
            foreach ($options as $key => $text) {
                SentenceCorrection::updateOrCreate([
                    'case_sentence_id' => $sentence->id,
                    'text' => $text,
                ], [
                    'is_correct' => $key === $correctKey,
                    'rationale' => $rationales[$key] ?? null,
                ]);
            }
        };

        // Pelecehan Seksual (Paragraph mapping)
        $psCase = CaseStudy::where('title', 'Mahasiswi Cantik Unpad Dilecehkan Dosen Ternama, Korban Akui Trauma Berat!')->first();
        if ($psCase) {
            // Paragraph 1 corresponds to sentences 1 & 2 joined originally; we target sentence 2 as problematic combined with 1 context.
            $s2 = CaseSentence::where('case_id', $psCase->id)->where('sentence_index', 2)->first();
            if ($s2) {
                $make(
                    $s2,
                    [
                        'A' => 'Bandung – Seorang mahasiswi Universitas Padjadjaran melaporkan dugaan pelecehan seksual oleh dosennya kepada pihak kampus.',
                        'B' => 'Bandung – Seorang mahasiswi Unpad berinisial S (21) menjadi korban pelecehan seksual oleh Dr. R.',
                        'C' => 'Bandung – Dunia kampus heboh karena dosen populer Unpad dilaporkan melecehkan mahasiswinya.'
                    ],
                    'A',
                    [
                        'A' => 'Menghapus unsur sensasional dan menjaga privasi korban (KEJ Pasal 8 & 5).',
                        'B' => 'Masih mengungkap inisial dan usia yang dapat memudahkan identifikasi.',
                        'C' => 'Mengandung diksi sensasional dan framing yang tidak netral.'
                    ]
                );
            }
            // Paragraph 2 target sentence 3 (and 4,5 originally contain quote) -> choose sentence 3 for correction context.
            $s3 = CaseSentence::where('case_id', $psCase->id)->where('sentence_index', 3)->first();
            if ($s3) {
                $make(
                    $s3,
                    [
                        'A' => 'Berdasarkan laporan yang diterima pihak kampus, dugaan pelecehan terjadi di lingkungan akademik.',
                        'B' => 'S mengatakan dirinya dilecehkan oleh dosen saat bimbingan, namun masih belum ada bukti.',
                        'C' => 'Menurut korban, tindakan itu terjadi di ruang kerja dosen dan membuatnya trauma berat.'
                    ],
                    'A',
                    [
                        'A' => 'Menghindari kutipan emosional langsung; menekankan sumber resmi dan melindungi detail sensitif.',
                        'B' => 'Menambah frasa spekulatif (belum ada bukti) tanpa dasar verifikasi jelas.',
                        'C' => 'Masih memuat narasi langsung yang dapat mengekspos kondisi psikologis korban.'
                    ]
                );
            }
            // Paragraph 3 target sentence 6 (irrelevant personal info)
            $s6 = CaseSentence::where('case_id', $psCase->id)->where('sentence_index', 6)->first();
            if ($s6) {
                $make(
                    $s6,
                    [
                        'A' => 'Redaksi masih menunggu konfirmasi dari pihak dosen terkait laporan tersebut.',
                        'B' => 'Dr. R merupakan dosen tetap di kampus yang sama, namun hingga kini belum memberi tanggapan.',
                        'C' => 'Dr. R dikenal berkeluarga dan sering mengunggah foto dengan mahasiswinya di media sosial.'
                    ],
                    'B',
                    [
                        'A' => 'Netral tetapi kurang menambah konteks relevan mengenai posisi dosen.',
                        'B' => 'Fokus pada status profesional dan ketiadaan tanggapan; menghindari informasi pribadi yang tidak relevan.',
                        'C' => 'Memuat informasi pribadi yang berpotensi menggiring opini.'
                    ]
                );
            }
            // Paragraph 4 target sentence 9 (opini mahasiswa tidak terverifikasi)
            $s9 = CaseSentence::where('case_id', $psCase->id)->where('sentence_index', 9)->first();
            if ($s9) {
                $make(
                    $s9,
                    [
                        'A' => 'Beberapa mahasiswa memilih tidak berkomentar hingga proses penyelidikan selesai.',
                        'B' => 'Mahasiswa menilai Dr. R sudah sering bersikap tidak sopan.',
                        'C' => 'Banyak mahasiswa menduga Dr. R bersalah.'
                    ],
                    'A',
                    [
                        'A' => 'Menjaga asas praduga tak bersalah dan menghindari opini yang belum diverifikasi.',
                        'B' => 'Mengutip opini negatif tanpa konfirmasi pihak terkait.',
                        'C' => 'Mendorong dugaan tanpa dasar verifikasi.'
                    ]
                );
            }
        }

        // SARA case corrections
        $saraCase = CaseStudy::where('title', 'Pejabat X Mengecam Kelompok Y Secara Terbuka, Warga Heboh')->first();
        if ($saraCase) {
            $s2 = CaseSentence::where('case_id', $saraCase->id)->where('sentence_index', 2)->first();
            if ($s2) {
                $make(
                    $s2,
                    [
                        'A' => 'Dalam pidatonya, pejabat X menyoroti masalah sosial yang terjadi di wilayahnya.',
                        'B' => 'Pejabat X menyebut kelompok Y sebagai penyebab masalah sosial di masyarakat.',
                        'C' => 'Pejabat X berbicara keras mengenai kelompok Y yang dianggap sumber konflik.'
                    ],
                    'A',
                    [
                        'A' => 'Netral dan faktual; menghindari diksi yang menyerang identitas kelompok.',
                        'B' => 'Mengandung atribusi menyalahkan kelompok secara langsung.',
                        'C' => 'Memperkuat framing konflik dan potensi bias.'
                    ]
                );
            }
            $s3 = CaseSentence::where('case_id', $saraCase->id)->where('sentence_index', 3)->first();
            if ($s3) {
                $make(
                    $s3,
                    [
                        'A' => 'Pernyataan pejabat X menjadi perhatian warga dengan berbagai komentar yang muncul.',
                        'B' => 'Pernyataan itu disampaikan dengan nada tinggi dan memicu amarah publik.',
                        'C' => 'Banyak warga menilai pejabat X membenci kelompok tertentu.'
                    ],
                    'A',
                    [
                        'A' => 'Netral dan menggambarkan keberagaman respon tanpa spekulasi.',
                        'B' => 'Menambahkan unsur emosional tidak terverifikasi.',
                        'C' => 'Mengandung kesimpulan spekulatif tentang kebencian.'
                    ]
                );
            }
            $s5 = CaseSentence::where('case_id', $saraCase->id)->where('sentence_index', 5)->first();
            if ($s5) {
                $make(
                    $s5,
                    [
                        'A' => 'Beberapa tokoh masyarakat juga meminta klarifikasi atas ucapan pejabat X.',
                        'B' => 'Warga menilai pernyataan pejabat X sangat menyinggung.',
                        'C' => 'Tokoh masyarakat mengecam keras dan menuntut permintaan maaf kepada publik.'
                    ],
                    'A',
                    [
                        'A' => 'Menjaga keberimbangan dan membuka ruang tanggapan pihak pejabat.',
                        'B' => 'Diksi menyinggung menambah interpretasi tanpa konfirmasi.',
                        'C' => 'Mengandung diksi keras yang meningkatkan tensi.'
                    ]
                );
            }
            $s7 = CaseSentence::where('case_id', $saraCase->id)->where('sentence_index', 7)->first();
            if ($s7) {
                $make(
                    $s7,
                    [
                        'A' => 'Banyak warganet menyampaikan pandangan bahwa pejabat X sebaiknya dicopot dari jabatannya, sementara berbagai opini lain juga muncul di media sosial.',
                        'B' => 'Warganet ramai-ramai menyerukan agar pejabat X dicopot.',
                        'C' => 'Netizen marah besar setelah mendengar pernyataan pejabat X.'
                    ],
                    'A',
                    [
                        'A' => 'Menekankan keberagaman opini; menjaga netralitas.',
                        'B' => 'Menggeneralisasi tanpa menyebut variasi opini.',
                        'C' => 'Diksi marah besar menambah sensasi.'
                    ]
                );
            }
        }

        // Ekonomi case corrections
        $ecoCase = CaseStudy::where('title', '“CEO Muda Kaya Raya FinGrow Ketahuan Tilep Dana Investor! Uang Ratusan Miliar Raib!”')->first();
        if ($ecoCase) {
            $e1 = CaseSentence::where('case_id', $ecoCase->id)->where('sentence_index', 1)->first();
            if ($e1) {
                $make(
                    $e1,
                    [
                        'A' => 'Jakarta – Otoritas Jasa Keuangan (OJK) tengah menyelidiki dugaan penyelewengan dana oleh pimpinan startup keuangan digital FinGrow.',
                        'B' => 'Jakarta – CEO FinGrow dituduh gelapkan dana investor oleh sejumlah pihak.',
                        'C' => 'Jakarta – Dunia startup heboh karena CEO FinGrow kabur bawa uang investor.'
                    ],
                    'A',
                    [
                        'A' => 'Objektif, menyebut otoritas resmi; menghindari sensasional.',
                        'B' => 'Menggunakan tuduh tanpa verifikasi otoritas.',
                        'C' => 'Memakai diksi heboh & kabur bersifat sensasional.'
                    ]
                );
            }
            $e3 = CaseSentence::where('case_id', $ecoCase->id)->where('sentence_index', 3)->first();
            if ($e3) {
                $make(
                    $e3,
                    [
                        'A' => 'Salah satu investor menyatakan kekecewaannya dan meminta agar dana yang telah diinvestasikan segera dikembalikan.',
                        'B' => 'Investor kecewa dan menyebut FinGrow menipu mereka.',
                        'C' => 'Salah satu investor menegaskan bahwa FinGrow melakukan penipuan besar-besaran.'
                    ],
                    'A',
                    [
                        'A' => 'Netral, menegaskan permintaan tanpa vonis penipuan.',
                        'B' => 'Mengandung tuduhan menipu tanpa verifikasi.',
                        'C' => 'Vonis penipuan besar-besaran tanpa dasar hukum.'
                    ]
                );
            }
            $e4 = CaseSentence::where('case_id', $ecoCase->id)->where('sentence_index', 4)->first();
            if ($e4) {
                $make(
                    $e4,
                    [
                        'A' => 'Pihak FinGrow belum menanggapi tuduhan yang beredar di media sosial.',
                        'B' => 'Aktivitas pribadi CEO tidak berkaitan langsung dengan proses hukum yang sedang berjalan.',
                        'C' => 'FinGrow merupakan startup keuangan digital yang berdiri sejak 2019 dan saat ini tengah dalam proses pemeriksaan oleh otoritas terkait.'
                    ],
                    'C',
                    [
                        'A' => 'Netral tapi kurang memberikan konteks perusahaan.',
                        'B' => 'Masih menyinggung aktivitas pribadi meski menolak relevansi.',
                        'C' => 'Fokus pada fakta relevan perusahaan dan proses pemeriksaan.'
                    ]
                );
            }
            $e5 = CaseSentence::where('case_id', $ecoCase->id)->where('sentence_index', 5)->first();
            if ($e5) {
                $make(
                    $e5,
                    [
                        'A' => 'Beberapa komentar di media sosial menyinggung gaya hidup mewah CEO FinGrow, meskipun klaim terkait kasus ini masih belum diverifikasi kebenarannya.',
                        'B' => 'Netizen menyebut CEO FinGrow sebagai penipu.',
                        'C' => 'Masyarakat percaya bahwa Arga benar-benar bersalah.'
                    ],
                    'A',
                    [
                        'A' => 'Mengakui adanya komentar tanpa mengafirmasi kebenaran tuduhan.',
                        'B' => 'Melakukan pencemaran nama baik jika tanpa verifikasi.',
                        'C' => 'Menggeneralisasi opini sebagai keyakinan masyarakat.'
                    ]
                );
            }
        }
    }
}
