<?php
/**
 * Created by enea dhack - 17/08/2020 20:42.
 */

namespace Enea\Tests\Documents;

use Enea\Cashier\Contracts\DocumentContract;
use Enea\Tests\TestCase;

abstract class DocumentTestCase extends TestCase
{
    abstract public function getDocument(): DocumentContract;

    abstract protected function getExpectedTaxes(): array;

    abstract protected function getDocumentName(): string;

    public function test_transform_document_to_array(): void
    {
        $document = $this->getDocument();

        $this->assertEquals([
            'key' => $this->getDocumentName(),
            'taxes' => $this->getExpectedTaxes(),
        ], $document->toArray());
    }
}
