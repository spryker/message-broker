<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MessageBroker\Business\Sender\Client\Formatter;

use Generated\Shared\Transfer\MessageAttributesTransfer;
use Spryker\Zed\MessageBroker\MessageBrokerConfig;

class HttpHeaderFormatter implements HttpHeaderFormatterInterface
{
    /**
     * @param array<string, mixed> $headers
     *
     * @return array<string, mixed>
     */
    public function formatHeaders(array $headers): array
    {
        $formattedHeaders = [];
        $allowedHeaders = $this->getAllowedHeaders();

        foreach ($headers as $header => $value) {
            $headerName = $this->prepareHeader($allowedHeaders, $header);
            if (!$headerName) {
                continue;
            }

            if (is_array($value)) {
                $value = json_encode($value);
            }

            $formattedHeaders[$headerName] = $value;
        }

        $formattedHeaders['Name'] = $formattedHeaders['Transfer-Name'] ?? null;

        return $formattedHeaders;
    }

    /**
     * @return array<int, string>
     */
    protected function getAllowedHeaders(): array
    {
        $allowedHeaders = (new MessageAttributesTransfer())->toArrayNotRecursiveCamelCased();
        $allowedHeaders['publisher'] = null;

        return array_keys($allowedHeaders);
    }

    /**
     * @param array<int, string> $standardHttpHeaders
     * @param array<int, string> $allowedHeaders
     * @param string $header
     *
     * @return string|null
     */
    protected function prepareHeader(array $allowedHeaders, string $header): ?string
    {
        if (!in_array($header, $allowedHeaders, true)) {
            return null;
        }

        $header = preg_replace('/(?<=[a-z])(?=[A-Z])/', '-', ucfirst($header));
        if (!$header) {
            return null;
        }

        return $header;
    }
}
