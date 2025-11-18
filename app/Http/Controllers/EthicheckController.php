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

        // 1. AMBIL DAN BERSIHKAN API KEY (TRIM PENTING!)
        $apiKey = trim(config('gemini.api_key'));

        if (empty($apiKey)) {
            Log::error('Gemini API Key kosong atau tidak ditemukan.');
            return response()->json(['error' => 'Konfigurasi API Key bermasalah.'], 500);
        }

        // Ubah $url di EthicheckController.php menjadi:
        $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash-lite:generateContent";

        $prompt = "
            Anda adalah ahli editor jurnalisme. Analisis teks ini berdasarkan UU Pers, Pedoman Media Siber, dan KEJ:
            \"" . $userText . "\"
            
            Output JSON:
            - highlighted_text (string dengan tag <mark> jika ada pelanggaran)
            - explanations (array of objects: id, rule, reasoning)
            - status_message (string or null)
            - score (int 0-100)
            - recommended_text (string perbaikan)
            
            Hanya JSON valid. Tanpa markdown ```json.
        ";

        $payload = [
            'contents' => [
                ['parts' => [['text' => $prompt]]]
            ],
            'generationConfig' => [
                'temperature' => 0.5,
            ]
        ];

        try {
            // 3. KIRIM REQUEST DENGAN HEADER 'x-goog-api-key'
            // Menggunakan header lebih aman daripada menempelkan key di URL
            $response = Http::retry(3, 2000) // Coba 3x, jeda 2 detik
                ->timeout(60)
                ->withHeaders([
                    'Content-Type' => 'application/json',
                    'x-goog-api-key' => $apiKey
                ])
                ->post($url, $payload);

            if (!$response->successful()) {
                // Log error detail dari Google untuk debugging
                Log::error('Gemini API Error Detail:', $response->json() ?? []);
                throw new \Exception('API Error: ' . $response->status() . ' - ' . $response->body());
            }

            $rawJsonText = $response->json('candidates.0.content.parts.0.text');

            // ... (Sisa logika decoding JSON ke bawah tetap sama seperti kode Anda sebelumnya)
            $cleanedJsonText = $this->cleanAiResponse($rawJsonText);
            $decoded = json_decode($cleanedJsonText, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new \Exception('Format JSON dari AI rusak.');
            }

            // Fallback logic (tetap gunakan yang lama)
            if (!isset($decoded['score'])) $decoded['score'] = $this->computeScore($decoded);
            if (!isset($decoded['recommended_text'])) $decoded['recommended_text'] = $this->generateRecommendedText($decoded, $userText);

            return response()->json($decoded);
        } catch (\Exception $e) {
            Log::error('Analyze Exception: ' . $e->getMessage());
            return response()->json(['error' => 'Gagal memproses: ' . $e->getMessage()], 500);
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

    public function checkConnection()
    {
        $apiKey = trim(config('gemini.api_key'));

        // 1. Cek apakah Key terbaca oleh Laravel
        if (empty($apiKey)) {
            return response()->json(['error' => 'API Key kosong. Cek .env dan config/gemini.php'], 500);
        }

        // 2. Minta daftar model yang tersedia untuk Key ini
        // Endpoint ini tidak melakukan generate, hanya melist model.
        $url = "https://generativelanguage.googleapis.com/v1beta/models?key=" . $apiKey;

        try {
            $response = Http::get($url);

            return response()->json([
                'status' => $response->status(),
                'key_preview' => substr($apiKey, 0, 5) . '...', // Cek apakah key yang terkirim benar
                'response_body' => $response->json(),
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
