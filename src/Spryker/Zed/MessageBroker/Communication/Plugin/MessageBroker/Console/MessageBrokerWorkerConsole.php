<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MessageBroker\Communication\Plugin\MessageBroker\Console;

use Generated\Shared\Transfer\MessageBrokerWorkerConfigTransfer;
use Spryker\Zed\Kernel\Communication\Console\Console;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\ConsoleOutputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * @method \Spryker\Zed\MessageBroker\Business\MessageBrokerFacadeInterface getFacade()
 */
class MessageBrokerWorkerConsole extends Console
{
    /**
     * @var string
     */
    protected const COMMAND_NAME = 'message-broker:consume';

    /**
     * @var string
     */
    protected const COMMAND_DESCRIPTION = 'This command consumes messages from the selected channels.';

    /**
     * @var string
     */
    public const ARGUMENT_QUEUES = 'queues';

    /**
     * @var string
     */
    public const OPTION_MESSAGE_LIMIT = 'message-limit';

    /**
     * @var string
     */
    public const OPTION_MESSAGE_LIMIT_SHORT = 'l';

    /**
     * @var string
     */
    public const OPTION_FAILURE_LIMIT = 'failure-limit';

    /**
     * @var string
     */
    public const OPTION_FAILURE_LIMIT_SHORT = 'f';

    /**
     * @var string
     */
    public const OPTION_MEMORY_LIMIT = 'memory-limit';

    /**
     * @var string
     */
    public const OPTION_MEMORY_LIMIT_SHORT = 'm';

    /**
     * @var string
     */
    public const OPTION_TIME_LIMIT = 'time-limit';

    /**
     * @var string
     */
    public const OPTION_TIME_LIMIT_SHORT = 't';

    /**
     * @var string
     */
    public const OPTION_SLEEP = 'sleep';

    /**
     * @var string
     */
    public const OPTION_SLEEP_SHORT = 's';

    /**
     * @return void
     */
    protected function configure(): void
    {
        $this->setName(static::COMMAND_NAME);
        $this->setDescription(static::COMMAND_DESCRIPTION);
        $this->setDefinition([
            new InputArgument(static::ARGUMENT_QUEUES, InputArgument::REQUIRED | InputArgument::IS_ARRAY, 'Limit receivers to only consume from the specified queues.'),
            new InputOption(static::OPTION_MESSAGE_LIMIT, static::OPTION_MESSAGE_LIMIT_SHORT, InputOption::VALUE_REQUIRED, 'Limit the number of received messages.'),
            new InputOption(static::OPTION_FAILURE_LIMIT, static::OPTION_FAILURE_LIMIT_SHORT, InputOption::VALUE_REQUIRED, 'The number of failed messages the worker can consume.'),
            new InputOption(static::OPTION_MEMORY_LIMIT, static::OPTION_MEMORY_LIMIT_SHORT, InputOption::VALUE_REQUIRED, 'The memory limit the worker can consume.'),
            new InputOption(static::OPTION_TIME_LIMIT, static::OPTION_TIME_LIMIT_SHORT, InputOption::VALUE_REQUIRED, 'The time limit in seconds the worker can handle new messages.'),
            new InputOption(static::OPTION_SLEEP, static::OPTION_SLEEP_SHORT, InputOption::VALUE_REQUIRED, 'Seconds to sleep before asking for new messages after no messages were found.', 1),
        ]);
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $messageBrokerWorkerConfigTransfer = new MessageBrokerWorkerConfigTransfer();

        $queues = $input->getArgument(static::ARGUMENT_QUEUES);
        $messageBrokerWorkerConfigTransfer->setQueues($queues);

        $stopsWhen = [];

        $limit = $input->getOption(static::OPTION_MESSAGE_LIMIT);
        if ($limit) {
            $stopsWhen[] = "processed {$limit} messages";
            $messageBrokerWorkerConfigTransfer->setLimit($limit);
        }

        $failureLimit = $input->getOption(static::OPTION_FAILURE_LIMIT);
        if ($failureLimit) {
            $stopsWhen[] = "reached {$failureLimit} failed messages";
            $messageBrokerWorkerConfigTransfer->setFailureLimit($failureLimit);
        }

        $memoryLimit = $input->getOption(static::OPTION_MEMORY_LIMIT);
        if ($memoryLimit) {
            $stopsWhen[] = "exceeded {$memoryLimit} of memory";
            $messageBrokerWorkerConfigTransfer->setMemoryLimit($memoryLimit);
        }

        $timeLimit = $input->getOption(static::OPTION_TIME_LIMIT);
        if ($timeLimit) {
            $stopsWhen[] = "been running for {$timeLimit}s";
            $messageBrokerWorkerConfigTransfer->setTimeLimit($timeLimit);
        }

        $stopsWhen[] = 'received a stop signal via the messenger:stop-workers command';

        $io = new SymfonyStyle($input, $output instanceof ConsoleOutputInterface ? $output->getErrorOutput() : $output);
        $io->success(sprintf('Consuming messages from queue%s "%s".', count($queues) > 0 ? static::OPTION_SLEEP_SHORT : '', implode(', ', $queues)));

        $last = array_pop($stopsWhen);
        $stopsWhen = ($stopsWhen ? implode(', ', $stopsWhen) . ' or ' : '') . $last;
        $io->comment("The worker will automatically exit once it has {$stopsWhen}.");

        $io->comment('Quit the worker with CONTROL-C.');

        if (OutputInterface::VERBOSITY_VERBOSE > $output->getVerbosity()) {
            $io->comment('Re-run the command with a -vv option to see logs about consumed messages.');
        }

        $messageBrokerWorkerConfigTransfer->setSleep($input->getOption(static::OPTION_SLEEP) * 1000000);

        $this->getFacade()->startWorker($messageBrokerWorkerConfigTransfer);

        return static::CODE_SUCCESS;
    }
}