<?php
/**
 * Created by enea dhack - 17/08/2020 20:52.
 */

namespace Enea\Tests\Documents;

use Enea\Cashier\Contracts\DocumentContract;
use Enea\Cashier\Documents\Free;

class FreeDocumentTest extends DocumentTestCase
{
    public function getDocument(): DocumentContract
    {
        return Free::create();
    }

    protected function getExpectedTaxes(): array
    {
        return [];
    }

    protected function getDocumentName(): string
    {
        return 'free';
    }
}
