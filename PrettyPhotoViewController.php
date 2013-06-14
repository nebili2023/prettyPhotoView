<?php

define('MODULE', 'prettyPhotoView/');
define('MODULE_HOME', _MODULE_DIR_ . MODULE);
define('ALL_CATEGORIES', 'All Galleries');

class PrettyPhotoViewControllerCore extends FrontController 
{

    public function setMedia() 
    {
        parent::setMedia();
        Tools::addCSS(MODULE_HOME . 'css/prettyPhoto.css');
        Tools::addJS(MODULE_HOME . 'js/jquery.prettyPhoto.js');
    }
    
    private function countItems( $file, $shouldFilterByCategory ) 
    {
        $itemsCount = 0;
        
        $xml = @simplexml_load_file($file);

        foreach ($xml->item AS $item) {
            $categoryIsSet = isset($item->category);

            $shouldDisplayCurrentItem = $categoryIsSet && $item->category == $_GET["categorySelector"];
            if (!$shouldFilterByCategory || $shouldFilterByCategory && $shouldDisplayCurrentItem)
            {
                $itemsCount++;
            }
        }
        
        return $itemsCount;
    }

    public function displayContent() 
    {
        parent::displayContent();
        $file = _PS_MODULE_DIR_ . MODULE . 'data.xml';

        $maxItemsPerPage = 40;
        $currentPage = isset($_GET['p']) && is_numeric($_GET['p']) && (int)$_GET['p'] > 0 ? $_GET['p'] : 1;
        $currentItem = 0;
        
        if (file_exists($file))
        {
            $xml = @simplexml_load_file($file);
            $selectedCategory = ALL_CATEGORIES;
            $categories = array(ALL_CATEGORIES => ALL_CATEGORIES);
            $shouldFilterByCategory = isset($_GET["categorySelector"]) && $_GET["categorySelector"] != ALL_CATEGORIES;
            if ($shouldFilterByCategory)
            {
                $selectedCategory = $_GET["categorySelector"];
            }
            
            $itemsCount = $this->countItems( $file, $shouldFilterByCategory );    
            $pagesTotal = ceil( $itemsCount / $maxItemsPerPage );
            $currentPage = $currentPage > $pagesTotal ? $pagesTotal : $currentPage;
            $nextPage = $currentPage == $pagesTotal ? 0 : $currentPage + 1;
            $previousPage = $currentPage == 1 ? 0 : $currentPage - 1;
            
            foreach ($xml->item AS $item) {
                $categoryIsSet = isset($item->category);
                if ($categoryIsSet)
                {
                    $categories[(string) $item->category] = (string) $item->category;
                }
                $shouldDisplayCurrentItem = $categoryIsSet && $item->category == $_GET["categorySelector"];
                if (!$shouldFilterByCategory || $shouldFilterByCategory && $shouldDisplayCurrentItem)
                {
                    $currentItem++;
                    if($currentItem > (($currentPage-1) * $maxItemsPerPage) && $currentItem <= (($currentPage-1) * $maxItemsPerPage + $maxItemsPerPage)){
                        $displayedItems[] = $item;
                    }
                }
            }

            global $smarty;
            $smarty->assign(array(
                'xml' => $displayedItems,
                'title' => 'title_1',
                'text' => 'text_1',
                'url' => 'url',
                'path' => MODULE_HOME,
                'categories' => $categories,
                'selectedCategory' => $selectedCategory,                
                'currentPage' => $currentPage,
                'pagesTotal' => $pagesTotal,
                'nextPage' => $nextPage,
                'previousPage' => $previousPage
            ));
            self::$smarty->display(_PS_MODULE_DIR_ . MODULE . 'PrettyPhotoView.tpl');
        }
    }
}

?>