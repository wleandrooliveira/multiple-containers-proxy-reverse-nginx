<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ElasticSearch\ElasticSearchClient;

class FileController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/upload",
     *     summary="Envio de arquivo para o Elasticsearch",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="file",
     *                     description="Arquivo a ser enviado",
     *                     type="file"
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Arquivo enviado com sucesso",
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Falha no envio do arquivo",
     *     ),
     * )
     */
    public function upload(Request $request)
    {
        try {
            $file = $request->file('file'); // Obtenha o arquivo enviado

            // Configuração do cliente Elasticsearch
            $client = ElasticSearchClient::getClient();

            // Indexe o arquivo no Elasticsearch
            $params = [
                'index' => 'files', // Nome do índice
                'type' => 'file',
                'body' => [
                    'file_name' => $file->getClientOriginalName(),
                    'file_content' => base64_encode(file_get_contents($file->getRealPath())),
                ],
            ];

            $response = $client->index($params);

        } catch (NoNodesAvailableException $e) {
            printf("NoNodesAvailableException: %s\n", $e->getMessage());
        }

        // Verifique a resposta do Elasticsearch
        if ($response['result'] === 'created') {
            return response()->json(['message' => 'Arquivo enviado com sucesso']);
        } else {
            return response()->json(['message' => 'Falha no envio do arquivo'], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/search",
     *     summary="Pesquisar arquivos no Elasticsearch",
     *     @OA\Parameter(
     *         name="query",
     *         in="query",
     *         description="Termo de pesquisa",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Resultados da pesquisa",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Property(property="file_name", type="string"),
     *                 @OA\Property(property="file_content", type="string")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Requisição inválida",
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erro interno do servidor",
     *     ),
     * )
     */

    public function search(Request $request)
    {
        $query = $request->input('query'); // Obtenha a consulta de pesquisa da solicitação

        // Configuração do cliente Elasticsearch
        $client = ElasticSearchClient::getClient();

        // Construa a consulta para buscar arquivos por nome ou conteúdo
        $params = [
            'index' => 'files', // Nome do índice
            'type' => 'file',
            'body' => [
                'query' => [
                    'bool' => [
                        'should' => [
                            [
                                'wildcard' => ['file_name' => '*' . $query . '*'], // Pesquisa por nome ou parte do nome
                            ],
                            [
                                'match' => ['file_content' => $query], // Pesquisa por conteúdo
                            ],
                        ],
                    ],
                ],
            ],
        ];
    }

}
