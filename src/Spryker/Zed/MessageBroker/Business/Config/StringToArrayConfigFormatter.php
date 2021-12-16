<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MessageBroker\Business\Config;

class StringToArrayConfigFormatter implements ConfigFormatterInterface
{
    /**
     * @param string $config
     *
     * @return array
     */
    public function format(string $config): array
    {
        $formattedConfiguration = [];

        $configOptions = explode('&', rtrim($config, '&'));

        foreach ($configOptions as $configOption) {
            [$key, $value] = explode('=', $configOption);
            $formattedConfiguration[$key] = $value;
        }

        return $formattedConfiguration;
    }
}
