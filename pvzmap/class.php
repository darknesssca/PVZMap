<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

use Bitrix\Main\Loader;
use Qsoft\Helpers\ComponentHelper;
use Qsoft\Pvzmap\PVZ\CDEK;
use Qsoft\Pvzmap\PVZFactory;

class PVZMap extends ComponentHelper
{
    private $cacheKey = 'PVZ';
    protected $relativePath = '/qsoft/pvzmap';

    public function onPrepareComponentParams($arParams)
    {
        if (empty($arParams['CACHE_TIME'])) {
            $arParams['CACHE_TIME'] = 86400;
        }

        if (empty($arParams['CACHE_TYPE'])) {
            $arParams['CACHE_TYPE'] = "A";
        }

        parent::onPrepareComponentParams($arParams);

        return $arParams;
    }

    public function executeComponent()
    {
        if (Loader::includeModule('qsoft.pvzmap')) {
            $this->arResult['PVZ'] = $this->getPVZCollectionByCity();
            $this->IncludeComponentTemplate();
        }
    }

    private function getPVZCollection()
    {
        global $LOCATION;

        //Возвращать json всех доступных для данного заказа ПВЗ.
        $arReturn = [];
        if ($this->initCache($this->cacheKey)) {
            $arReturn = $this->getCachedVars($this->cacheKey);
        } elseif ($this->startCache()) {
            $this->startTagCache();
            $this->registerTag($this->cacheKey);
            $arReturn = PVZFactory::getPVZCollection();
            if (!empty($arReturn)) {
                $this->endTagCache();
                $this->saveToCache($this->cacheKey, $arReturn);
            } else {
                $this->abortTagCache();
                $this->abortCache();
            }
        }

        $arReturn['CENTER'] = $LOCATION->getLocationCoords();

        return $arReturn;
    }

    private function getPVZCollectionByCity()
    {
        global  $LOCATION;
        $city = $LOCATION->getName();
        $arPVZ = $this->getPVZCollection();
        $arPVZ['PVZ'] = PVZFactory::getPVZCollectionByCity($city, $arPVZ['PVZ']);

        return $arPVZ;
    }


    //Нужна логика привязки к службам доставки
}
