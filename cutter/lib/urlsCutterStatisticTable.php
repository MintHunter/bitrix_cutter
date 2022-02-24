<?php


namespace Bitrix\cutter;

use Bitrix\Main\Entity;
use Bitrix\Main\ORM;
use Bitrix\Main\ORM\Fields\Relations\Reference as Reference;
use Bitrix\Main\Entity\ReferenceField;

class urlsCutterStatisticTable extends Entity\DataManager
{
    public static function getTableName()
    {
        return 'urlsCutterStatistic_table';
    }
    public static function getMap()
    {
        return array(
            new Entity\IntegerField('ID',[
                'primary' => true,
                'autocomplete' => true
            ]),
            new Entity\StringField('URL_CODE'),
            new Entity\StringField('SHORT_URL_CODE'),


            new Entity\StringField('BROWSER_DATA'),
            new Entity\DateField('LAST_USED'),
            new Entity\IntegerField('NUMBER_OF_USED'),
        );

    }
    public  function getByShortUrl($shortUrl)
    {
        return self::query()
            ->setFilter(['SHORT_URL_CODE'=>$shortUrl])
            ->setSelect(['ID','LAST_USED','NUMBER_OF_USED','BROWSER_DATA'])
            ->fetch();
    }
    public  function getAllUrls(){
        return self::query()
            ->setSelect(['*'])
            ->fetchAll();
    }

}