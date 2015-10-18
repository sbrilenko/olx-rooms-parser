<?php

class HttpRequest extends CHttpRequest
{
    public $noValidationRoutes=array();

    protected function normalizeRequest()
    {
        parent::normalizeRequest();
        if($this->enableCsrfValidation)
        {
            $url=Yii::app()->getUrlManager()->parseUrl($this);
            if (in_array($url, $this->noValidationRoutes))
            {
                Yii::app()->detachEventHandler('onBeginRequest',array($this,'validateCsrfToken'));
            }
        }
    }
}