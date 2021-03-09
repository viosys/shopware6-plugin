<?php

declare(strict_types=1);

namespace Omikron\FactFinder\Shopware6\Command;

use Omikron\FactFinder\Shopware6\Communication\PushImportService;
use Omikron\FactFinder\Shopware6\Export\FeedFactory;
use Omikron\FactFinder\Shopware6\Export\Field\FieldInterface;
use Omikron\FactFinder\Shopware6\Export\SalesChannelService;
use Omikron\FactFinder\Shopware6\Export\Stream\ConsoleOutput;
use Omikron\FactFinder\Shopware6\Export\Stream\CsvFile;
use Omikron\FactFinder\Shopware6\Upload\UploadService;
use Shopware\Core\Framework\Api\Context\SystemSource;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepositoryInterface;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Traversable;

/**
 * @SuppressWarnings(PHPMD.UnusedPrivateMethod)
 */
class ProductExportCommand extends Command implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    private const UPLOAD_FEED_OPTION = 'upload';
    private const PUSH_IMPORT_OPTION = 'import';
    private const SALESCHANNEL_OPTION = 'saleschannel';

    /** @var SalesChannelService */
    private $channelService;

    /** @var FeedFactory */
    private $feedFactory;

    /** @var UploadService */
    private $uploadService;

    /** @var PushImportService */
    private $pushImportService;

    /** @var FieldInterface[] */
    private $productFields;

    /** @var EntityRepositoryInterface */
    private $channelRepository;

    public function __construct(
        SalesChannelService $channelService,
        FeedFactory $feedFactory,
        PushImportService $pushImportService,
        UploadService $uploadService,
        Traversable $productFields,
        EntityRepositoryInterface $channelRepository
    ) {
        parent::__construct('factfinder:export:products');
        $this->channelService       = $channelService;
        $this->feedFactory          = $feedFactory;
        $this->pushImportService    = $pushImportService;
        $this->uploadService        = $uploadService;
        $this->productFields        = iterator_to_array($productFields);
        $this->channelRepository    = $channelRepository;
    }

    protected function configure()
    {
        $this->setDescription('Export articles feed.');
        $this->addOption(self::UPLOAD_FEED_OPTION, 'u', InputOption::VALUE_NONE, 'Should upload after exporting');
        $this->addOption(self::PUSH_IMPORT_OPTION, 'i', InputOption::VALUE_NONE, 'Should import after uploading');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $salesChannel = null;
        if($input->hasOption(self::SALESCHANNEL_OPTION)) {
            $salesChannel = $this->channelRepository->search(
                new Criteria([$input->getOption(self::SALESCHANNEL_OPTION)]),
                new Context(new SystemSource())
            )->first();
        }
        $feedService = $this->feedFactory->create($this->channelService->getSalesChannelContext($salesChannel));
        $feedColumns = $this->getFeedColumns();

        if (!$input->getOption(self::UPLOAD_FEED_OPTION)) {
            $feedService->generate(new ConsoleOutput($output), $feedColumns);
            return 0;
        }

        $fileHandle = tmpfile();
        $feedService->generate(new CsvFile($fileHandle), $feedColumns);
        $this->uploadService->upload($fileHandle);
        $output->writeln('Feed has been succesfully uploaded');

        if ($input->getOption(self::PUSH_IMPORT_OPTION)) {
            $this->pushImportService->execute();
            $output->writeln('FACT-Finder import has been start');
        }

        return 0;
    }

    private function getFeedColumns(): array
    {
        $base = (array) $this->container->getParameter('factfinder.export.columns.base');
        return array_values(array_unique(array_merge($base, array_map([$this, 'getFieldName'], $this->productFields))));
    }

    private function getFieldName(FieldInterface $field): string
    {
        return $field->getName();
    }
}
