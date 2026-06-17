<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class FonnteService
{
    protected $token;
    protected $url = 'https://api.fonnte.com/send';

    public function __construct()
    {
        $this->token = env('FONNTE_TOKEN');
    }

    public function kirim($noHp, $pesan)
    {
        $noHp = $this->formatNomor($noHp);

        $response = Http::withHeaders([
            'Authorization' => $this->token,
        ])->post($this->url, [
            'target'  => $noHp,
            'message' => $pesan,
        ]);

        return $response->json();
    }

    private function formatNomor($noHp)
    {
        $noHp = preg_replace('/[^0-9]/', '', $noHp);

        if (substr($noHp, 0, 1) == '0') {
            $noHp = '62' . substr($noHp, 1);
        }

        return $noHp;
    }
}
