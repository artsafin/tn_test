<?php

namespace TnTest\View;

use Money\Currency;
use Money\Money;
use Slim\Http\Request;
use Slim\Http\Response;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\GreaterThan;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Required;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use TnTest\Domain\Ad;
use TnTest\Domain\AdStatus;
use TnTest\Domain\AutomodService;
use TnTest\Domain\Data\AdRepository;

class PostAction
{
    /**
     * @var ValidatorInterface
     */
    private $validator;
    /**
     * @var AutomodService
     */
    private $automodService;
    /**
     * @var AdRepository
     */
    private $adRepository;

    /**
     * PostAction constructor.
     *
     * @param ValidatorInterface $validator
     * @param AdRepository       $adRepository
     * @param AutomodService     $automodService
     */
    public function __construct(ValidatorInterface $validator,
                                AdRepository $adRepository,
                                AutomodService $automodService)
    {
        $this->validator      = $validator;
        $this->automodService = $automodService;
        $this->adRepository   = $adRepository;
    }

    function __invoke(Request $request, Response $response)
    {
        $data = $request->getParsedBody();

        // Some trivial validation
        $constraint = new Collection([
                                         'address'     => new Required([new NotBlank()]),
                                         'ownerName'   => new Required([new NotBlank()]),
                                         'ownerPhone'  => new Required([new NotBlank()]),
                                         'adType'      => new Required([new Choice(0, 1)]),
                                         'sellerType'  => new Required([new Choice(0, 1)]),
                                         'source'      => new Required([new Choice(0, 1)]),
                                         'description' => new Required([new NotBlank()]),
                                         'price'       => new Required([new NotBlank(), new GreaterThan(0.01)]),
                                         'floor'       => new Required([new GreaterThanOrEqual(1)]),
                                         'totalFloors' => new Required([new GreaterThanOrEqual(1)]),
                                         'numRooms'    => new Required([new GreaterThanOrEqual(1)]),
                                         'totalSquare' => new Required([new GreaterThanOrEqual(1)]),
                                     ]);
        $violations = $this->validator->validate($data, $constraint);

        if ($violations->count()) {
            $response->withStatus(422)->write("Invalid data: {$violations}");

            return;
        }

        $ad = new Ad(
            null,
            $data['address'],
            $data['ownerName'],
            $data['ownerPhone'],
            AdStatus::automoderationPending(),
            (int)$data['adType'],
            new Money((int)(100 * $data['price']), new Currency("EUR")),
            (int)$data['sellerType'],
            (int)$data['floor'],
            (int)$data['totalFloors'],
            (int)$data['numRooms'],
            (float)$data['totalSquare'],
            $data['description'],
            (int)$data['source']
        );

        $ad     = $this->adRepository->add($ad);
        $result = $this->automodService->moderate($ad);

        $response->withStatus(200)->write($result->toString());
    }
}