<?php

namespace App\Actions;

use TCG\Voyager\Actions\AbstractAction;

class ExportToExcelAction extends AbstractAction
{
    public function getTitle()
    {
        return 'Export to Excel';
    }

    public function getIcon()
    {
        return 'voyager-download';
    }

    public function getAttributes()
    {
        return [
            'class' => 'btn btn-sm btn-primary pull-right',
        ];
    }

    public function getDefaultRoute()
    {
        return route('voyager.export', ['slug' => $this->dataType->slug]);
        // return route('voyager.' . $this->dataType->slug . '.export', $this->data->{$this->data->getKeyName()});
    }

    public function shouldActionDisplayOnDataType()
    {
        return true;
    }
}
