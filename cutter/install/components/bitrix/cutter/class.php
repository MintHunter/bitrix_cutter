<?php
CModule::IncludeModule("cutter");

use Bitrix\cutter\urlsCutterStatisticTable;


class cutterComponent extends \CBitrixComponent
{
    function generateRandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public function executeComponent()
    {
        $arResult = [];
        $urlsStatistic = new urlsCutterStatisticTable();
        global $APPLICATION;
        if($_POST['urlStr']!=''){

            $APPLICATION->RestartBuffer();
            $url = $_POST['urlStr'];
            $parsed_url =  parse_url($url, -1);
            $newUrl= $parsed_url['host'].'/'.self::generateRandomString();
            if(strlen($parsed_url['path'])>1){


                $newStat = $urlsStatistic->add([
                    'SHORT_URL_CODE'=>$newUrl,
                    'URL_CODE'=>$url,
                    'BROWSER_DATA'=>json_encode(getallheaders()['X-Real-IP'].';'.getallheaders()['User-Agent']),
                    'LAST_USED'=>new \Bitrix\Main\Type\DateTime(),
                    'NUMBER_OF_USED'=>'0',
                ]);

                $statObj = $urlsStatistic->getAllUrls();
                foreach ($statObj as $urlObj){
                    $arResult[] = $urlObj;
                }
                echo json_encode(['result'=>$arResult,'new_str'=>$newUrl]);
            }




            die();
        }
        if($_POST['update']!=''){
            $APPLICATION->RestartBuffer();
            $updatedShortUrl = $_POST['update'];
            $urlObj = $urlsStatistic->getByShortUrl($updatedShortUrl);
            $numOfUsed = (int)$urlObj['NUMBER_OF_USED'];
            $numOfUsed ++;
            $urlsStatistic->update($urlObj['ID'],[
                'NUMBER_OF_USED'=>$numOfUsed,
            ]);
            $statObj = $urlsStatistic->getAllUrls();
            foreach ($statObj as $urlObj){
                $arResult[] = $urlObj;
            }
            echo json_encode(['result'=>$arResult,'new_str'=>$updatedShortUrl]);
            die();
        }


        $this->includeComponentTemplate();
    }
}
