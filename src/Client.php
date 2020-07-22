<?php

declare(strict_types=1);
/**
 * PayFast API HTTP Client.
 *
 * @author    Jacques Marneweck <jacques@siberia.co.za>
 * @copyright 2018-2020 Jacques Marneweck.  All rights strictly reserved.
 * @license   MPLv2
 */

namespace Jacques\Payment\PayFast;

use Exception;

class Client extends \GuzzleHttp\Client
{
    /**
     * @const string Version number
     */
    const VERSION = '0.0.2';

    /**
     * @var array[]
     */
    protected $options = [
        'scheme'      => 'https',
        'hostname'    => 'api.payfast.co.za',
        'port'        => '443',
        'merchant-id' => null,
        'passphrase'  => null,
    ];

    /**
     * @param array $options
     */
    public function __construct($options = [])
    {
        /*
         * Allow on instantiation to overwrite the defaults
         */
        $this->options = array_merge(
            $this->options,
            $options
        );

        $config = [
            'base_uri' => sprintf(
                '%s://%s:%s/',
                $this->options['scheme'],
                $this->options['hostname'],
                $this->options['port']
            ),
            'verify'  => false,
            'headers' => [
                'User-Agent'  => 'PayFastAPIClient-PHP/'.self::VERSION.' '.\GuzzleHttp\default_user_agent(),
                'merchant-id' => $this->options['merchant-id'],
                'version'     => 'v1',
            ],
        ];

        parent::__construct($config);
    }

    /**
     * Returns default parameters PayFast expects to be in our params array for
     * use when creating the hash for the signature of the request.
     *
     * @return array
     */
    public function getDefaultParams(): array
    {
        $params = [
            'merchant-id' => $this->options['merchant-id'],
            'passphrase'  => $this->options['passphrase'],
            'version'     => 'v1',
        ];

        if (isset($this->options['testing'])
            && (bool) $this->options['testing'] === true
        ) {
            $params['testing'] = 'true';
        }

        return $params;
    }

    /**
     * Check if the API is responding to requests for a given token.
     *
     * @param string $token
     *
     * @throws Exception
     * @throws \GuzzleHttp\Exception\ClientException
     *
     * @return string
     */
    public function transactionHistoryDaily(string $date): array
    {
        try {
            $params = array_merge(
                [
                    'timestamp'   => \Carbon\Carbon::now()->toIso8601String(),
                    'date'        => $date,
                ],
                $this->getDefaultParams()
            );

            ksort($params);
            $sigstring = http_build_query($params);

            $response = $this->get(
                sprintf(
                    '/transactions/history/daily?%s%s',
                    isset($params['date']) ? 'date='.$params['date'] : '',
                    isset($params['testing']) ? '&testing=true' : ''
                ),
                [
                    'headers' => [
                        'timestamp' => $params['timestamp'],
                        'signature' => md5($sigstring),
                    ],
                ]
            );

            return [
                'status'    => 'ok',
                'http_code' => $response->getStatusCode(),
                'body'      => (string) $response->getBody(),
            ];
        } catch (\GuzzleHttp\Exception\ServerException $e) {
            return [
                'status'    => 'error',
                'http_code' => $response->getStatusCode(),
                'body'      => $e->getMessage(),
            ];
        }
    }

    /**
     * Check if the API is responding to requests for a given token.
     *
     * @param string $date
     *
     * @throws Exception
     *
     * @return array
     */
    public function transactionHistoryMonthly($date = null): array
    {
        try {
            $params = array_merge(
                [
                    'timestamp'   => \Carbon\Carbon::now()->toIso8601String(),
                ],
                $this->getDefaultParams()
            );
            if (!is_null($date)) {
                $params['date'] = $date;
            }

            ksort($params);
            $sigstring = http_build_query($params);

            $response = $this->get(
                sprintf(
                    '/transactions/history/daily?date=%s%s',
                    $params['date'],
                    isset($params['testing']) ? '&testing=true' : ''
                ),
                [
                    'headers' => [
                        'timestamp' => $params['timestamp'],
                        'signature' => md5($sigstring),
                    ],
                ]
            );

            return [
                'status'    => 'ok',
                'http_code' => $response->getStatusCode(),
                'body'      => (string) $response->getBody(),
            ];
        } catch (\GuzzleHttp\Exception\ServerException $e) {
            return [
                'status'    => 'error',
                'http_code' => $response->getStatusCode(),
                'body'      => $e->getMessage(),
            ];
        }
    }

    /**
     * Check if the API is responding to requests for a given token.
     *
     * @param string $from
     * @param string $to
     *
     * @throws \GuzzleHttp\Exception\ClientException
     *
     * @return array
     */
    public function transactionHistoryPeriod(string $from, string $to): array
    {
        try {
            $params = array_merge(
                [
                    'timestamp'   => \Carbon\Carbon::now()->toIso8601String(),
                    'from'        => $from,
                    'to'          => $to,
                ],
                $this->getDefaultParams()
            );

            ksort($params);
            $sigstring = http_build_query($params);

            $response = $this->get(
                sprintf(
                    '/transactions/history?from=%s&to=%s%s',
                    $params['from'],
                    $params['to'],
                    isset($params['testing']) ? '&testing=true' : ''
                ),
                [
                    'headers' => [
                        'timestamp' => $params['timestamp'],
                        'signature' => md5($sigstring),
                    ],
                ]
            );

            return [
                'status'    => 'ok',
                'http_code' => $response->getStatusCode(),
                'body'      => (string) $response->getBody(),
            ];
        } catch (\GuzzleHttp\Exception\ServerException $e) {
            return [
                'status'    => 'error',
                'http_code' => $response->getStatusCode(),
                'body'      => $e->getMessage(),
            ];
        }
    }

    /**
     * Check if the API is responding to requests for a given token.
     *
     * @param string $date
     *
     * @throws Exception
     *
     * @return array
     */
    public function transactionHistoryWeekly(string $date): array
    {
        try {
            $params = array_merge(
                [
                    'timestamp'   => \Carbon\Carbon::now()->toIso8601String(),
                ],
                $this->getDefaultParams()
            );
            if (!is_null($date)) {
                $params['date'] = $date;
            }

            ksort($params);
            $sigstring = http_build_query($params);

            $response = $this->get(
                sprintf(
                    '/transactions/history/daily?date=%s%s',
                    $params['date'],
                    isset($params['testing']) ? '&testing=true' : ''
                ),
                [
                    'headers' => [
                        'timestamp' => $params['timestamp'],
                        'signature' => md5($sigstring),
                    ],
                ]
            );

            return [
                'status'    => 'ok',
                'http_code' => $response->getStatusCode(),
                'body'      => (string) $response->getBody(),
            ];
        } catch (\GuzzleHttp\Exception\ServerException $e) {
            return [
                'status'    => 'error',
                'http_code' => $response->getStatusCode(),
                'body'      => $e->getMessage(),
            ];
        }
    }

    /**
     * Check if the API is responding to requests for a given token.
     *
     * @param string $arn Acquirer (PayFast's Reference Number)
     *
     * @throws Exception
     *
     * @return array
     */
    public function transactionQuery(string $arn): array
    {
        try {
            $params = array_merge(
                [
                    'timestamp'   => \Carbon\Carbon::now()->toIso8601String(),
                ],
                $this->getDefaultParams()
            );

            ksort($params);
            $sigstring = http_build_query($params);

            $response = $this->get(
                sprintf(
                    '/process/query/%s%s',
                    $arn,
                    isset($params['testing']) ? '?testing=true' : ''
                ),
                [
                    'headers' => [
                        'timestamp' => $params['timestamp'],
                        'signature' => md5($sigstring),
                    ],
                ]
            );

            return [
                'status'    => 'ok',
                'http_code' => $response->getStatusCode(),
                'body'      => (string) $response->getBody(),
            ];
        } catch (\GuzzleHttp\Exception\ServerException $e) {
            return [
                'status'    => 'error',
                'http_code' => $response->getStatusCode(),
                'body'      => $e->getMessage(),
            ];
        }
    }
}
