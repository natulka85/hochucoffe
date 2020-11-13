<?php
use \Bitrix\Main;
//use \Bitrix\Main\Text\String as String;
use \Bitrix\Main\Localization\Loc as Loc;
use \Bitrix\Main\SystemException as SystemException;

class CElementList extends \CBitrixComponent
{
    protected $items = array();
    protected $filter = array();
    /** @var \Bitrix\Main\DB\Result */
    protected $rs;
    protected $arElementsLink = array();
    protected $sortOrder;
    protected $sortCode;
    protected $countOnPage = 5;

    /**
     * Prepare component params
     */
    public function onPrepareComponentParams($params)
    {
        $defaultParams = array(
            'FILTER_NAME' => 'arrFilter',
            'SORT_FIELD' => 'ID',
            'SORT_ORDER' => 'DESC',
            'COUNT_ON_PAGE' => 5,
        );

        if (empty($params['FILTER_NAME']) || !preg_match("/^[A-Za-z_][A-Za-z01-9_]*$/", $params["FILTER_NAME"])) {
            $params['FILTER_NAME'] = $defaultParams['FILTER_NAME'];
        }

        foreach ($defaultParams as $paramName => $paramValue) {
            if (!empty($params[$paramName])) {
                continue;
            }

            $params[$paramName] = $paramValue;
        }

        return $params;
    }

    public function executeComponent()
    {
        global $APPLICATION;
        try {
            $this->checkModules();
            $this->processRequest();

            if (!$this->extractDataFromCache()) {
                $this->prepareData();
                $this->formatResult();
                //$this->setResultCacheKeys(array());

                if ($this->isAjax()) {
                    ob_start();
                }

                $this->includeComponentTemplate();

                if ($this->isAjax()) {
                    $contents = ob_get_flush();
                    $APPLICATION->RestartBuffer();

                    print json_encode(array_merge(array(
                        'content' => $contents
                    ), (array) $this->arResult['JSON_RESPONSE']));

                    exit;
                }

                $this->putDataToCache();
            }
        } catch (SystemException $e) {
            $this->abortDataCache();

            if ($this->isAjax()) {
                $APPLICATION->restartBuffer();
                print json_encode(array(
                    'status' => 'error',
                    'error' => $e->getMessage())
                );
                die();
            }

            ShowError($e->getMessage());
        }
    }

    /**
     * Check required modules
     */
    protected function checkModules()
    {
    }

    /**
     * Extract data from cache. No action by default.
     * @return bool
     */
    protected function extractDataFromCache()
    {
        //if($this->arParams['CACHE_TYPE'] == 'N')
        //    return false;
        global ${$this->arParams["FILTER_NAME"]};
        $arrFilter = ${$this->arParams["FILTER_NAME"]};
        if(!is_array($arrFilter))
            $arrFilter = array();

        $arNavParams = array(
            "nPageSize" => $this->arParams['COUNT_ON_PAGE']
        );
        $arNavigation = \CDBResult::GetNavParams($arNavParams);

        global $USER;
        return !($this->StartResultCache(false, [$arrFilter,$USER->GetGroups(),$arNavigation]));
    }

    protected function putDataToCache()
    {
        $this->endResultCache();
    }

    protected function abortDataCache()
    {
        $this->AbortResultCache();
    }

    protected function isAjax()
    {
        $context = Main\Context::getCurrent();
        return ($context->getRequest()->getQuery('ajax') == 'Y' && $context->getRequest()->getQuery('method') == 'load');
    }

    /**
     * Process incoming request.
     * @return void
     */
    protected function processRequest()
    {
        global $APPLICATION;

        $this->request = $_REQUEST;

        \CPageOption::SetOptionString("main", "nav_page_in_session", "N");

        /*if (isset($this->request['page'])) {
            $pageNavVar = 'PAGEN_' . ($GLOBALS['NavNum'] + 1);
            global $$pageNavVar;
            $$pageNavVar = $this->request['page'];
        }*/
    }


    /**
     * @return void
     */
    protected function prepareFilter()
    {
        global ${$this->arParams['FILTER_NAME']};
        $this->filter = array('ACTIVE' => 'Y');

        if (is_array(${$this->arParams['FILTER_NAME']})) {
         $this->filter = array_merge(${$this->arParams['FILTER_NAME']}, $this->filter);
        }

        //change numeric props to code
        $arMetaData = \nav\IblockOrm\ElementTable::getMetadata((int) $this->arParams['IBLOCK_ID']);
        $arTmp = [];
        foreach($this->filter as $code=>$value)
        {
            $arPropParts = [];
            if(strpos($code,'PROPERTY_'))
            {
                $arPropParts = explode('_',$code);
                if(is_numeric($arPropParts[1]))
                {
                    foreach($arMetaData['props'] as $arMetaD)
                    {
                        if($arMetaD['ID'] == $arPropParts[1])
                        {
                            $arPropParts[1] = $arMetaD['CODE'];
                            $code = implode('_',$arPropParts);
                        }
                    }
                }
            }

            $arTmp[$code] = $value;
        }
        $this->filter = $arTmp;

        if((int) $this->arParams['SECTION_ID']>0)
        {

            $arSec = [(int) $this->arParams['SECTION_ID']];
            $rsParentSection = \CIBlockSection::GetByID((int) $this->arParams['SECTION_ID']);
            if ($arParentSection = $rsParentSection->GetNext())
            {
                $this->ParentSection = $arParentSection;

               $arFilter = array('IBLOCK_ID' => $arParentSection['IBLOCK_ID'],'>LEFT_MARGIN' => $arParentSection['LEFT_MARGIN'],'<RIGHT_MARGIN' => $arParentSection['RIGHT_MARGIN'],'>DEPTH_LEVEL' => $arParentSection['DEPTH_LEVEL']); // выберет потомков без учета активности

               $rsSect = \CIBlockSection::GetList(array('left_margin' => 'asc'),$arFilter);
               while ($arSect = $rsSect->GetNext())
               {
                   if($arSect['SORT']<500){
                       $arSec[] = $arSect['ID'];
                   }
               }
            }
            $this->filter['IBLOCK_SECTION_ID'] = $arSec;
        }

    }

    protected function getItemsByBId($iblock_id, $arNavigation)
    {
            $entity = \nav\IblockOrm\ElementTable::createEntity((int) $iblock_id);
            $entityClass = $entity->getDataClass();
            $arSort = array($this->arParams['SORT_FIELD'] => $this->arParams['SORT_ORDER']);
            if(
                isset($this->arParams['SORT_FIELD2'])
                && isset($this->arParams['SORT_ORDER2'])
            )
                $arSort[$this->arParams['SORT_FIELD2']] = $this->arParams['SORT_ORDER2'];

            $arGetList = [
                'select' => array(
                    '*',
                    'DETAIL_PAGE_URL',
                    //'TEST1'
                ),
                'order' => $arSort,
                'filter' => $this->filter,
                'limit' => $arNavigation['SIZEN'],
                'offset' => $arNavigation['SIZEN'] * ($arNavigation['PAGEN'] - 1),
                /*'runtime' => array(
                    new \Bitrix\Main\Entity\ExpressionField('TEST1', 'COUNT(*)','ID')
                )*/
            ];

            //sort asc,nulls
            if(strpos($this->arParams['SORT_ORDER'],'nulls')!==false)
            {
                $arOrder = explode(',',$this->arParams['SORT_ORDER']);
                if($arOrder[1]=='nulls' && $arOrder[0]=='asc')
                {
                    array_push(
                        $arGetList['select'],
                        new \Bitrix\Main\Entity\ExpressionField(
                            'NULLS_SORT',
                            'IFNULL(%s, 9999999)',
                            array((string)$this->arParams['SORT_FIELD'])
                        )
                    );
                    unset($arGetList['order'][$this->arParams['SORT_FIELD']]);
                    $arGetList['order']['NULLS_SORT'] = $arOrder[0];

                } else {
                    $arGetList['order'][$this->arParams['SORT_FIELD']] = 'desc';
                }
            }

            if($this->arParams['GROUP_BY']!=''){
               if(!is_array($arGetList['group']))
                   $arGetList['group'] = [];

                $arGetList['group'] = array_merge($arGetList['group'],$this->arParams['GROUP_BY']);
            }


            $this->rs = $entityClass::getList($arGetList);

            if($this->rs->oldCDBResult->nSelectedCount==0) return false;
            return $entityClass::fetchAllWithProperties($this->rs);
    }

    /**
     * Get data.
     * @return array
     */
    protected function getItems()
    {

        if(CModule::IncludeModule('catalog'))
        {
            if(is_array($this->arParams['IBLOCK_ID']))
            {
                $arItems = [];
                foreach($this->arParams['IBLOCK_ID'] as $iblock_id)
                {
                    $arNavParams = array(
                        "nPageSize" => $this->arParams['COUNT_ON_PAGE']
                    );
                    $arNavigation = \CDBResult::GetNavParams($arNavParams);

                    $arItems = $this->getItemsByBId($iblock_id, $arNavigation);

                    if(is_array($arItems) && count($arItems)>0)
                        $this->items = array_merge($this->items,$arItems);
                }

            }   else {
                $arNavParams = array(
                    "nPageSize" => $this->arParams['COUNT_ON_PAGE']
                );
                $arNavigation = \CDBResult::GetNavParams($arNavParams);

                $this->items = $this->getItemsByBId($this->arParams['IBLOCK_ID'], $arNavigation);
            }

          /*  foreach($this->items as $rr){
                if(1==1){
                    $b = $rr;
                    unset($b['PROPERTIES']);

                    $r[] = $b;
                }
            }

            echo "<pre>";
               print_r($r);
            echo "</pre>";*/

            if($this->arParams['FST_ELEMENT']!=''){
                foreach ($this->items as $key=>$item){
                    if($item['ID']==$this->arParams['FST_ELEMENT']){
                        $id=$key;
                        $fst[$id] = $item;
                        unset($this->items[$key]);
                        break;
                    }
                }
                if(isset($fst[$id])){
                    $itemNew[$id] = $fst[$id];
                    foreach($this->items as $key=>$item){
                        $itemNew[$key] =$item;
                    }

                    $this->items = $itemNew;
                }
            }
            $arIds = [];
            $arTmp = [];

            if(is_array($this->items))
            {
                foreach($this->items as $item)
                {
                    $arIds[] = $item['ID'];
                    $arTmp[$item['ID']] = $item;
                }
            }
            $this->items = $arTmp;

            $arPrices = [];
            $rsPrice = \Bitrix\Catalog\PriceTable::getList([
                'filter' => ['PRODUCT_ID' => $arIds],
                'select' => ['PRODUCT_ID', 'CATALOG_GROUP_ID', 'PRICE'],
            ]);
            while($arPrice = $rsPrice->fetch())
            {
                //pre($arPrice);
                $arPrices =  $arPrice['PRICE'];
                $this->items[$arPrice['PRODUCT_ID']]['PRICES'][$arPrice['CATALOG_GROUP_ID']] =  $arPrice['PRICE'];
            }

            return $this->items;
        }

    }

    protected function prepareData()
    {
        $this->prepareFilter();
        $this->getItems();
    }

    /**
     * Prepare data to render.
     * @return void
     */
    protected function formatResult()
    {
        $navTemplate = '.default';
        if($this->arParams['PAGER_TEMPLATE']!='')
            $navTemplate = $this->arParams['PAGER_TEMPLATE'];

        $this->arResult['ITEMS'] = $this->items;
        $this->arResult['SECTION'] = $this->ParentSection;
        if (is_object($this->rs->oldCDBResult)) {
            $navComponentObject = null;
            $this->arResult['NAV_STRING'] = $this->rs->oldCDBResult->GetPageNavStringEx($navComponentObject, null, $navTemplate, 'N');
            $this->arResult['NAV_CACHED_DATA'] = $navComponentObject->GetTemplateCachedData();
            $this->arResult['NAV_RESULT'] = $this->rs->oldCDBResult;
        }
    }
}
