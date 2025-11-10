<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http; // Pastikan ini ada
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
// JANGAN 'use' Gemini\Laravel\Facades\Gemini; lagi

class EthicheckController extends Controller
{
    /**
     * Menampilkan halaman Ethicheck.
     */
    public function show()
    {
        return view('ethicheck');
    }

    /**
     * Menganalisis teks menggunakan AI (via REST API langsung).
     */
    public function analyze(Request $request)
    {
        $request->validate(['text' => 'required|string|min:50']);
        $userText = $request->input('text');

        $apiKey = config('gemini.api_key');

        if (!$apiKey) {
            Log::error('Gemini API Key not found in config/gemini.php or .env');
            return response()->json(['error' => 'Konfigurasi API Key server tidak ditemukan.'], 500);
        }

        // URL v1 yang sudah benar
        // INI URL YANG BENAR (MENGGUNAKAN MODEL STABIL):
        $url = "https://generativelanguage.googleapis.com/v1/models/gemini-2.5-flash:generateContent?key=" . $apiKey;

        // Prompt Anda yang sudah disempurnakan
        $prompt = "
            Anda adalah seorang ahli editor jurnalisme Indonesia yang sangat teliti.
            Tugas Anda adalah menganalisis teks berita berdasarkan TIGA sumber aturan ketat:
            1. UU Nomor 40 Tahun 1999 Tentang Pers
            2. Pedoman Pemberitaan Media Siber
            3. Kode Etik Jurnalistik (KEJ)

            Analisis teks berikut:
            \"" . $userText . "\"

            Lakukan tugas-tugas berikut:
            1. Kembalikan respons HANYA dalam format JSON yang valid.
            2. JSON harus memiliki kunci-kunci: 'highlighted_text', 'explanations', 'status_message'.
            3. Tambahkan kunci 'score' (integer 0-100) yang menunjukkan seberapa sesuai teks dengan pedoman (0 = sangat buruk, 100 = sempurna).
            4. Tambahkan kunci 'recommended_text' yang berisi versi berita yang telah diperbaiki atau direkomendasikan agar sesuai pedoman (ringkas dan konkret jika memungkinkan).

            SKENARIO 1: JIKA ADA PELANGGARAN
            - 'highlighted_text': Kembalikan teks berita LENGKAP, tetapi bungkus frasa yang melanggar dengan tag <mark data-violation-id=\"N\">...</mark>.
            - 'explanations': Berisi array objek (id, rule, reasoning).
            - 'status_message': null
            - 'score': Berikan angka antara 0-100 sesuai tingkat pelanggaran.
            - 'recommended_text': Berikan contoh perbaikan teks yang menanggulangi pelanggaran.

            SKENARIO 2: JIKA TIDAK ADA PELANGGARAN
            - 'highlighted_text': Kembalikan teks berita LENGKAP apa adanya (tanpa tag <mark>).
            - 'explanations': Kembalikan array kosong [].
            - 'status_message': \"Berita sudah sesuai pedoman\"
            - 'score': 100
            - 'recommended_text': Kembalikan teks asli atau perbaikan gaya jika perlu.

            Pastikan respons Anda HANYA JSON. Jangan sertakan teks, komentar, atau markup di luar JSON.
        ";

        // Payload yang diperbaiki
        $payload = [
            'contents' => [
                [
                    'role' => 'user',
                    'parts' => [['text' => $prompt]]
                ]
            ],
            'generationConfig' => [
                // 'responseMimeType' DIHAPUS KARENA MENYEBABKAN ERROR 400
                'temperature' => 0.5,
            ]
        ];

        try {
            $response = Http::timeout(120)->withHeaders([
                'Content-Type' => 'application/json',
            ])->post($url, $payload);

            if (!$response->successful()) {
                Log::error('Gemini API HTTP Error', $response->json() ?: ['body' => $response->body()]);
                throw new \Exception('API call gagal: ' . $response->status() . ' - ' . $response->body());
            }

            // Ambil teks mentah dari dalam respons
            $rawJsonText = $response->json('candidates.0.content.parts.0.text');

            Log::info('Gemini Raw Response (Mentah): ' . $rawJsonText);

            if (empty($rawJsonText)) {
                Log::warning('AI mengembalikan respons JSON yang kosong.', $response->json());
                throw new \Exception('AI mengembalikan respons JSON yang kosong.');
            }

            // --- KEMBALIKAN FUNGSI CLEANER ---
            // Karena kita tidak memaksa JSON, AI mungkin mengirim markdown
            $cleanedJsonText = $this->cleanAiResponse($rawJsonText);
            Log::info('Gemini Raw Response (Bersih): ' . $cleanedJsonText);

            // Decode teks JSON yang sudah bersih
            $decoded = json_decode($cleanedJsonText, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                Log::warning('Failed to decode JSON from Gemini. Cleaned text was: ' . $cleanedJsonText);
                throw new \Exception('Respons AI tidak valid atau tidak berformat JSON.');
            }

            // --- FALLBACKS: jika AI tidak menyertakan 'score' atau 'recommended_text', buat fallback sederhana ---
            if (!array_key_exists('score', $decoded)) {
                $decoded['score'] = $this->computeScore($decoded);
                Log::info('Computed fallback score: ' . $decoded['score']);
            }

            if (!array_key_exists('recommended_text', $decoded) || empty(trim((string)($decoded['recommended_text'] ?? '')))) {
                $decoded['recommended_text'] = $this->generateRecommendedText($decoded, $userText);
                Log::info('Generated fallback recommended_text.');
            }

            return response()->json($decoded);
        } catch (\Exception $e) {
            Log::error('Gemini API Error: ' . $e->getMessage());
            return response()->json([
                'error' => 'Gagal menganalisis teks. Silakan coba lagi.'
            ], 500);
        }
    }

    /**
     * Membersihkan respons AI dari backtick dan 'json'
     * (Fungsi ini dikembalikan)
     */
    /**
     * Hitung skor fallback sederhana berdasarkan jumlah penjelasan pelanggaran.
     * Jika tidak ada penjelasan, kembalikan 100.
     */
    private function computeScore(array $decoded): int
    {
        if (empty($decoded['explanations']) || !is_array($decoded['explanations'])) {
            return 100;
        }

        $count = count($decoded['explanations']);

        // Deduct 20 points per violation up to a reasonable cap.
        $deduction = min(95, $count * 20);
        $score = max(0, 100 - $deduction);

        return (int)$score;
    }

    /**
     * Buat recommended_text fallback dengan menghapus tag <mark> dari highlighted_text
     * atau dari teks asli jika highlighted_text tidak tersedia.
     */
    private function generateRecommendedText(array $decoded, string $original): string
    {
        $base = $decoded['highlighted_text'] ?? $original;

        // Hapus tag <mark ...> dan kembalikan isi di dalamnya
        $cleaned = preg_replace('/<mark[^>]*>(.*?)<\/mark>/is', '$1', $base);

        // Hapus atribut data-violation-id yang mungkin tersisa (kata hati-hati)
        $cleaned = preg_replace('/data-violation-id=\"?\d+\"?/i', '', $cleaned);

        // Normalize whitespace
        $cleaned = preg_replace('/\s+/', ' ', $cleaned);

        return trim($cleaned);
    }

    private function cleanAiResponse(string $text): string
    {
        $text = preg_replace('/^```json\s*/', '', $text);
        $text = preg_replace('/\s*```$/', '', $text);
        return trim($text);
    }
}
