<?php
namespace Bp\Template;

class Sitemaper
{
    private $arLinks = array();
    private $arSavedFiles = array();
    private $mapFilename;

    private $postfix = 'links';
    private $filesCounter = 0;

    private $domen = 'http://hochucoffe.ru';

    public $linksCounter = 0;
    private $limit = 30000;

    public function __construct($newPostfix = '', $domen)
    {
        if(strlen($newPostfix) > 0) $this->postfix = $newPostfix;
        if(strlen($domen) > 0) $this->domen = $domen;
        $this->recalcFilename();
    }

    private function recalcFilename()
    {
        if($this->filesCounter==0)
            $this->mapFilename = "/sitemap_{$this->postfix}.xml";
        else
            $this->mapFilename = "/sitemap_{$this->postfix}_{$this->filesCounter}.xml";
    }

    public function put($path, $date = "")
    {
           if(strlen($path) <= 0) return false;

        $this->arLinks[] = array('LINK' => $path, 'DATE' => (strlen($date) > 0 ? $this->timeEncode($date) : $this->timeEncode(strtotime("now"))));
        $this->linksCounter++;

        $this->checkOverflow();
    }

    private function checkOverflow()
    {
        if ($this->linksCounter >= $this->limit)
        {
            $this->save();
        }
    }

    public function save()
    {
        if (!empty($this->arLinks))
        {
            $f = fopen($_SERVER['DOCUMENT_ROOT'].$this->mapFilename, 'w');
            fwrite($f, "<?xml version='1.0' encoding='UTF-8'?>\n<urlset xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\" xsi:schemaLocation=\"http://www.sitemaps.org/schemas/sitemap/0.9\" xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">\n");

            foreach ($this->arLinks as &$arUrl)
            {
                fwrite($f, "\t<url>\n\t\t<loc>".$arUrl["LINK"]."</loc>\n\t\t<lastmod>".$arUrl["DATE"]."</lastmod>\n\t</url>\n");
            }

            fwrite($f, "</urlset>\n");
            fclose($f);

            $this->arSavedFiles[] = $this->mapFilename;
            $this->arLinks = array();
            $this->linksCounter = 0;
            $this->filesCounter++;
            $this->recalcFilename();
        }
    }

    public function addNewFilesToMainSitemap($name = 'sitemap.xml')
    {
        $strMapBegin = '';
        $strMap = file_get_contents($_SERVER['DOCUMENT_ROOT']. '/'.$name);

        if($strMap == ''){
            echo 'yes';
            $strMapBegin = "<?xml version=\"1.0\" encoding=\"UTF-8\"?><sitemapindex xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">\n<sitemap></sitemap></sitemapindex>";
        }

        echo $this->postfix;

        # remove old files
        if (preg_match_all('/<sitemap><loc>\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]<\/loc><lastmod>\b[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]<\/lastmod><\/sitemap>/i', $strMap, $matches))
        {
            echo "<pre>";
               print_r($matches);
            echo "</pre>";
            foreach ($matches[0] as $url)
                if (strpos($url, "sitemap_{$this->postfix}") !== false)
                    $strMap = str_replace($url, '', $strMap);
        }

        echo "<pre>";
           print_r($this->arSavedFiles);
        echo "</pre>";
        # add new files
        if(!empty($this->arSavedFiles)){
            $strMap = str_replace(
                '</sitemap></sitemapindex>',
                '</sitemap><sitemap><loc>'.$this->domen.implode('</loc><lastmod>'.$this->timeEncode(strtotime("now")).'</lastmod></sitemap><sitemap><loc>'.$this->domen, $this->arSavedFiles).'</loc><lastmod>'.$this->timeEncode(strtotime("now")).'</lastmod></sitemap></sitemapindex>',
                $strMap
            );
        }


        file_put_contents($_SERVER['DOCUMENT_ROOT']. '/'.$name, $strMapBegin.$strMap);
    }

    private function timeEncode($iTime)
    {
        $iTZ = date("Z", $iTime);
        $iTZHour = intval(abs($iTZ)/3600);
        $iTZMinutes = intval((abs($iTZ)-$iTZHour*3600)/60);
        $strTZ = ($iTZ<0? "-": "+").sprintf("%02d:%02d", $iTZHour, $iTZMinutes);
        return date("Y-m-d",$iTime)."T".date("H:i:s",$iTime).$strTZ;
    }
}
