<?php

namespace App\Http\Controllers;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http;

class EnderecoController extends Controller
{
    /**
     * @throws ConnectionException
     */
    public function search($ceps): JsonResponse
    {
        $cepArray = explode(',', $ceps);
        $resultados = [];

        foreach ($cepArray as $cep) {
            $response = Http::withOptions(['verify' => false])->get("https://viacep.com.br/ws/{$cep}/json/");
            $endereco = $response->json();

            if (!isset($endereco['erro'])) {
                $resultados[] = [
                    'cep' => str_replace(['-', '.'], '', $endereco['cep']),
                    'label' => $this->getLabel($endereco),
                    'logradouro' => $endereco['logradouro'] ?? '',
                    'complemento' => $endereco['complemento'] ?? '',
                    'bairro' => $endereco['bairro'] ?? '',
                    'localidade' => $endereco['localidade'] ?? '',
                    'uf' => $endereco['uf'] ?? '',
                    'ibge' => $endereco['ibge'] ?? '',
                    'gia' => $endereco['gia'] ?? '',
                    'ddd' => $endereco['ddd'] ?? '',
                    'siafi' => $endereco['siafi'] ?? ''
                ];
            } else {
                $resultados[] = [
                    'cep' => str_replace(['-', '.'], '', $cep),
                    'erro' => 'CEP não encontrado'
                ];
            }
        }

        return response()->json($resultados);
    }

    private function getLabel($endereco): string
    {
        $partes = [];

        if (!empty($endereco['logradouro'])) {
            $partes[] = $endereco['logradouro'];
        }

        if (!empty($endereco['bairro'])) {
            $partes[] = $endereco['bairro'];
        }

        if (!empty($endereco['localidade'])) {
            $partes[] = $endereco['localidade'];
        }

        return implode(', ', $partes);
    }
}

