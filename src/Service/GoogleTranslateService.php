<?php

namespace App\Service;

use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Contracts\HttpClient\HttpClientInterface;

readonly class GoogleTranslateService
{
    public function __construct(
        private HttpClientInterface $httpClient,
        #[Autowire('%google_translate_api_key%')] private string $googleApiKey,
    ) {}

    public function translate(string $text, string $targetLocale, ?string $sourceLocale = null): string {
        $options = [
            'json' => [
                'q' => $text,
                'target' => $targetLocale,
                'format' => 'text',
            ],
            'query' => [
                'key' => $this->googleApiKey,
            ],
        ];

        if ($sourceLocale) {
            $options['json']['source'] = $sourceLocale;
        }

        try {
            $response = $this->httpClient->request(
                'POST',
                'https://translation.googleapis.com/language/translate/v2',
                $options
            );

            if (200 === $response->getStatusCode()) {
                $content = $response->toArray();

                $translation = $content['data']['translations'][0]['translatedText'] ?? null;

                if (!$translation) {
                    throw new \RuntimeException('No translation found.');
                }
            } else {
                throw new \RuntimeException('Response error:'.$response->getStatusCode());
            }
        } catch (\Exception $e) {
            throw new \RuntimeException($e->getMessage(), $e->getCode(), $e);
        }

        return $translation;
    }
}
