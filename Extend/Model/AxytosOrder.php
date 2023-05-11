<?php

namespace Axytos\KaufAufRechnung_OXID6\Extend\Model;

class AxytosOrder extends AxytosOrder_parent
{
    /**
     * @return void
     */
    public function initializeOrderNumber()
    {
        // _setNumber will be renamed to setNumber in future releases of oxid6
        if (method_exists($this, 'setNumber')) {
            $this->setNumber();
        } else {
            $this->_setNumber();
        }
    }
}
