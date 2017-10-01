<?php
/**
 * Created on 01/10/17 by enea dhack.
 */

namespace Enea\Cashier\Documents;

class Free extends Document
{
    /**
     * Free constructor.
     */
    public function __construct()
    {
        parent::__construct(null);
    }

    /**
     * Returns a new instance.
     *
     * @return static
     */
    static public function make()
    {
        return new static();
    }

    /**
     * {@inheritdoc}
     */
    public function getKeyDocument()
    {
        return 'free';
    }
}
