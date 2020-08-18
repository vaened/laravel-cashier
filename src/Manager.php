<?php
/**
 * Created by enea dhack - 30/05/2017 03:19 PM.
 */

namespace Enea\Cashier;

use Enea\Cashier\Contracts\{AttributableContract, BuyerContract, DocumentContract};
use Illuminate\Support\{Collection, Str};

abstract class Manager extends ProductCollection implements AttributableContract
{
    use IsJsonable, HasProperties;

    protected BuyerContract $buyer;

    protected DocumentContract $document;

    private string $token;

    public function __construct(BuyerContract $buyer, DocumentContract $document)
    {
        parent::__construct(new Collection());
        $this->buyer = $buyer;
        $this->document = $document;
    }

    public function getBuyer(): BuyerContract
    {
        return $this->buyer;
    }

    public function getDocument(): DocumentContract
    {
        return $this->document;
    }

    public function getGeneratedToken(): string
    {
        return $this->token ??= Str::random(30);
    }

    /**
     * {@inheritdoc}
     */
    public function toArray()
    {
        return array_merge([
            'token' => $this->getGeneratedToken(),
            'properties' => $this->getProperties(),
            'buyer' => [
                'id' => $this->getBuyer()->getUniqueIdentificationKey(),
                'properties' => $this->getBuyer()->getProperties(),
            ],
        ], parent::toArray());
    }
}
