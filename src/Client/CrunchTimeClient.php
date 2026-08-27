<?php

namespace Kudu\CTKudu\Client;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Kudu\CTKudu\Support\CTKuduLogger;
use RuntimeException;

class CrunchTimeClient
{
    protected string $baseUrl;
    protected string $token;
    protected string $sitename;
    protected string $userId;
    protected string $password;
    protected string $traceId;
    protected int $timeout;
    protected int $connectTimeout;
    protected int $retryTimes;
    protected int $retrySleep;

    private CTKuduLogger $logger;

    public function __construct($goal)
    {
        $this->baseUrl = rtrim((string)config('ctkudu.base_url'), '/');
        $this->token = (string)config('ctkudu.tokens.' . $goal);
        $this->sitename = (string)config('ctkudu.sitename');
        $this->userId = (string)config('ctkudu.userid');
        $this->password = (string)config('ctkudu.password');
        $this->traceId = (string)config('ctkudu.X-B3-TraceId');

        $this->timeout = (int)config('ctkudu.timeout', 60);
        $this->connectTimeout = (int)config('ctkudu.connect_timeout', 60);

        $this->retryTimes = (int)config('ctkudu.retry.times', 3);
        $this->retrySleep = (int)config('ctkudu.retry.sleep', 500);

        $this->logger = new CTKuduLogger();
    }

    protected function request(): PendingRequest
    {
        if ($this->baseUrl === '') {
            throw new RuntimeException('CTKUDU base URL is not configured.');
        }

        $request = Http::baseUrl($this->baseUrl)
            ->acceptJson()
            ->withHeaders($this->additionalHeaders())
            ->withOptions(['verify' => false])
            ->timeout($this->timeout)
            ->connectTimeout($this->connectTimeout)
            ->retry(
                $this->retryTimes,
                $this->retrySleep
            );

        if ($this->token !== '') {
            $request->withToken($this->token);
        }

        return $request;
    }

    public function get(string $endpoint, array $query = []): array
    {
        $this->logger->info('Sending request to CrunchTime', [
            'endpoint' => $endpoint,
            'query' => $query,
        ]);

        $response = $this->request()->get($endpoint, $query);

        return $this->handleResponse($response);
    }

    public function post(string $endpoint, array $data = []): array
    {
        $response = $this->request()->post($endpoint, $data);

        return $this->handleResponse($response);
    }

    protected function handleResponse(Response $response): array
    {
        $response->throw();

        $data = $response->json();

        if (!is_array($data)) {
            throw new RuntimeException('CrunchTime API returned an invalid JSON response.');
        }

        return $data;
    }

    private function additionalHeaders(): array
    {
        return [
            'authenticationtoken' => $this->token,
            'sitename' => $this->sitename,
            'userid' => $this->userId,
            'password' => $this->password,
            'X-B3-TraceId' => $this->traceId,
        ];
    }
}
