<?php
namespace App\View\Cell;

use Cake\View\Cell;

/**
 * Meddra cell
 */
class MeddraCell extends Cell
{

    /**
     * List of valid options that can be passed into this
     * cell's constructor.
     *
     * @var array
     */
    protected $_validCellOptions = [];

    /**
     * Initialization logic run at the end of object construction.
     *
     * @return void
     */
    public function initialize()
    {
    }

    /**
     * Default display method.
     *
     * @return void
     */
    public function display($descriptor=null, $meddraFieldId=null)
    {
        $this->loadModel('Ccode');
        $contryList = $this->Ccode->find();
        $this->set(compact('meddraFieldId', 'descriptor','contryList'));
    }
}
