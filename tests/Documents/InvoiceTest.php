<?php
/**
 * Created by enea dhack - 17/08/2020 20:50.
 */

namespace Enea\Tests\Documents;

use Enea\Cashier\Contracts\DocumentContract;
use Enea\Cashier\Documents\Invoice;
use Enea\Cashier\Taxes;

class InvoiceTest extends DocumentTestCase
{
    public function getDocument(): DocumentContract
    {
        return Invoice::create()->using([
            Taxes::IVA
        ]);
    }

    protected function getExpectedTaxes(): array
    {
        return [Taxes::IVA];
    }

    protected function getDocumentName(): string
    {
        return 'invoice';
    }
}
