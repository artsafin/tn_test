<?php


namespace TnTest\Domain;

use TnTest\Domain\Data\AdRepository;
use TnTest\Domain\Data\AutomodHistoryRepository;
use TnTest\Domain\Source\SourceFactory;
use TnTest\Util\TransactionContext;

class AutomodService
{
    /**
     * @var SourceFactory
     */
    private $sourceFactory;

    /**
     * @var TransactionContext
     */
    private $transactionContext;

    /**
     * @var AdRepository
     */
    private $adRepo;

    /**
     * @var AutomodHistoryRepository
     */
    private $automodHistoryRepo;

    /**
     * @param SourceFactory            $sourceFactory
     * @param TransactionContext       $transactionContext
     * @param AdRepository             $adRepo
     * @param AutomodHistoryRepository $automodHistoryRepo
     */
    public function __construct(SourceFactory $sourceFactory,
                                TransactionContext $transactionContext,
                                AdRepository $adRepo,
                                AutomodHistoryRepository $automodHistoryRepo)
    {
        $this->sourceFactory      = $sourceFactory;
        $this->transactionContext = $transactionContext;
        $this->adRepo             = $adRepo;
        $this->automodHistoryRepo = $automodHistoryRepo;
    }

    /**
     * @param Ad $ad
     *
     * @return AutomodResult
     */
    public function moderate(Ad $ad)
    {
        $source = $this->sourceFactory->createBySourceCode($ad->getSourceCode());
        $result = $source->moderate($ad);

        if ($result->isPassed()) {
            $ad->automoderationPassed();
        } else {
            $ad->automoderationFailed();
        }

        $this->transactionContext->begin();

        $this->adRepo->updateStatus($ad);
        $this->automodHistoryRepo->persist($result);

        $this->transactionContext->commit();

        return $result;
    }
}