<?php
defined('_JEXEC') or die;
?>
<?php /*BEGIN_EDITOR_OPEN*/
$app = JFactory::getApplication('site');
$templateName = $app->getTemplate();

$ret = false;
$templateDir = JPATH_THEMES . '/' . $templateName;
$editorClass = $templateDir . '/app/' . 'Editor.php';

if (!$app->isAdmin() && file_exists($editorClass)) {
    require_once $templateDir . '/app/' . 'Editor.php';
    $ret = DesignerEditor::override($templateName, __FILE__);
}

if ($ret) {
    $editorDir = $templateName . '/editor';
    require($ret);
    return;
} else {
/*BEGIN_EDITOR_CLOSE*/ ?>

<?php
require_once dirname(dirname(dirname(__FILE__))) . DIRECTORY_SEPARATOR . 'functions.php';

$productsCount = count($products);
$i = 0;

$attribs['drstyle'] = '';

$caption = $module->showtitle != 0 ? $module->title : '';
$hasCaption = (null !== $caption && strlen(trim($caption)) > 0);

$themeParams = JFactory::getApplication()->getTemplate(true)->params;

$itemsInRow = $themeParams->get('itemsInRow', '');

$desktops =  ''; $laptops = ''; $tablets = ''; $phones = '';
$slidersOptions = $themeParams->get('slidersOptions', '');
if ('' !== $slidersOptions) {
    $slidersOptions = json_decode(base64_decode($slidersOptions), true);
    if (isset($slidersOptions[$module->id])) {
        $desktops =  $slidersOptions[$module->id]['desktops'];
        $laptops = $slidersOptions[$module->id]['laptops'];
        $tablets = $slidersOptions[$module->id]['tablets'];
        $phones = $slidersOptions[$module->id]['phones'];
    }
}

if ('' == $products_per_row) {
    $products_per_row = $itemsInRow;
}
$_itemsInRow = empty($products_per_row) ? '2' : intval($products_per_row);

$_itemClass = 'separated-item-2  grid';

$_widthLg = empty($desktops) ? '' : $desktops;
$_widthMd = empty($laptops) ? '' : $laptops;
$_widthSm = empty($tablets) ? '12' : $tablets;
$_widthXs = empty($phones) ? '' : $phones;

if ($_widthLg) {
    $_itemClass .= ' col-lg-' . $_widthLg;
}
if ($_widthMd) {
    $_itemClass .= ' col-md-' . $_widthMd;
}
if ($_widthSm) {
    $_itemClass .= ' col-sm-' . $_widthSm;
}
if ($_widthXs) {
    $_itemClass .= ' col-xs-' . $_widthXs;
}
?>

<div data-slider-id="product_slider" class=" bd-productsslider-1" data-elements-per-row="<?php echo $products_per_row; ?>">
    <div class="bd-container-inner">
        <div class=" bd-block <?php echo $params->get('moduleclass_sfx'); ?>">
            <?php if ($hasCaption) : ?>
            <div class=" bd-blockheader bd-tagstyles">
                <h4><?php echo $caption; ?></h4>
            </div>
            <?php endif; ?>
            <div class=" bd-blockcontent bd-tagstyles shape-only">
            <div class=" bd-grid-26">
              <div class="container-fluid">
                <div class="separated-grid row">
                    <div class="carousel slide<?php if ($productsCount <= $_itemsInRow): ?> single<?php endif; ?>
                    <?php echo $params->get ('moduleclass_sfx') ?> adjust-slides">
                <?php
                    if ($headerText) {
                        echo $headerText;
                    }
                ?>
                <div class="carousel-inner <?php echo $params->get ('moduleclass_sfx'); ?>">
                    <?php foreach ($products as $product) : ?>
                        <?php if ($i % $_itemsInRow == 0): ?>
                            <div class="item<?php if ($i == 0): ?> active<?php endif ?>">
                        <?php endif; ?>
                        <?php
                        //create product title decorator object
                        $productTitleDecorator = new stdClass();
                        $productTitleDecorator->link = $product->link;
                        $productTitleDecorator->name = $product->product_name;
                        //create product manufacturer decorator object
                        $productManufacturerDecorator = new stdClass();
                        $productManufacturerDecorator->name = $product->mf_name;
                        //create product price decorator object
                        $productPriceDecorator = new stdClass();
                        $productPriceDecorator->show_prices = $show_price;
                        $productPriceDecorator->currency = $currency;
                        $productPriceDecorator->prices = $product->prices;
                        $productPriceDecorator->imagesExists = isset($product->images) ? true : false;
                        $productPriceDecorator->image = $productPriceDecorator->imagesExists ? $product->images[0] : null;
                        //create product image decorator object
                        $productImageDecorator = new stdClass();
                        $productImageDecorator->imagesExists = isset($product->images) ? true : false;
                        $productImageDecorator->image = $productImageDecorator->imagesExists ? $product->images[0] : null;
                        $productImageDecorator->link = $product->link;
                        //create product buy decorator object
                        $productBuyDecorator = new stdClass();
                        $productBuyDecorator->prices = $product->prices;
                        $productBuyDecorator->min_order_level = isset($product->min_order_level) ? $product->min_order_level : null;
                        $productBuyDecorator->step_order_level = isset($product->step_order_level) ? $product->step_order_level : null;
                        $productBuyDecorator->product_in_stock = isset($product->product_in_stock) ? $product->product_in_stock : 0;
                        $productBuyDecorator->product_ordered = isset($product->product_ordered) ? $product->product_ordered : 0;
                        $productBuyDecorator->orderable = $product->orderable;
                        $productBuyDecorator->link = $product->link;
                        $productBuyDecorator->product_name = $product->product_name;
                        $productBuyDecorator->virtuemart_product_id = $product->virtuemart_product_id;
                        //create product sale decorator object
                        $productSaleDecorator = new stdClass();
                        $productSaleDecorator->prices = $product->prices;
                        $productSaleDecorator->currency = $currency;
                        //create product out of stock decorator object
                        $productOutOfStockDecorator = new stdClass();
                        if (isset($product->product_in_stock) && isset($product->product_ordered)) {
                            $productOutOfStockDecorator->product_in_stock = $product->product_in_stock;
                            $productOutOfStockDecorator->product_ordered = $product->product_ordered;
                        } else {
                            $productOutOfStockDecorator = null;
                        }
                        //create products items collection
                        $productItems = new stdClass();
                        $productItems->productTitle = $productTitleDecorator;
                        $productItems->productManufacturer = $productManufacturerDecorator;
                        $productItems->productPrice = $productPriceDecorator;
                        $productItems->productImage = $productImageDecorator;
                        $productItems->productBuy   = $productBuyDecorator;
                        $productItems->productSale = $productSaleDecorator;
                        $productItems->productOutOfStock = $productOutOfStockDecorator;
                        ?>
                        <div class="<?php echo $_itemClass; ?>">
                            <div class=" bd-griditem-2">
                                <?php if (isset($productItems->productImage)) : ?>
    <?php if ($productItems->productImage->imagesExists) : ?>
    <?php
        $offsetHeight = isset($productItems->productImage->offsetHeight) ? $productItems->productImage->offsetHeight : 0;
        $offsetWidth = isset($productItems->productImage->offsetWidth) ? $productItems->productImage->offsetWidth : 0;
        $height = 'height:' . (VmConfig::get ('img_height') + $offsetHeight) . 'px;';
        $width ='width:' . (VmConfig::get ('img_width') + $offsetWidth) . 'px;';
        if (is_object($productItems->productImage->image))
            $imgHtml = $productItems->productImage->image->displayMediaThumb('class=" bd-imagestyles-14"', false);
        else
            $imgHtml = str_replace('<img', '<img class=" bd-imagestyles-14" ', $productItems->productImage->image);
    ?>
    <a class=" bd-productimage-2" href="<?php echo $productItems->productImage->link; ?>">
        <?php echo $imgHtml; ?>
    </a>
    <?php endif; ?>
<?php endif; ?>
	
		<?php if (isset($productItems->productSale)) : ?>
<?php if ($productItems->productSale->prices['discountedPriceWithoutTax'] != $productItems->productSale->prices['priceWithoutTax']) : ?>
<div class=" bd-productsaleicon bd-productsale-1">
    <span>Sale!</span>
</div>
<?php endif; ?>
<?php endif; ?>
	
		<?php if (isset($productItems->productOutOfStock)) : ?>
<?php if (($productItems->productOutOfStock->product_in_stock - $productItems->productOutOfStock->product_ordered) < 1) : ?>
<div class="bd-productoutofstockicon-2  bd-productoutofstock-1">
    Out of stock
</div>
<?php endif; ?>
<?php endif; ?>
	
		<?php if (isset($productItems->productTitle)) : ?>
<div class=" bd-producttitle-4">
    <?php
    if ('' !== $productItems->productTitle->link)
        echo JHTML::link($productItems->productTitle->link, $productItems->productTitle->name);
    else 
        echo $productItems->productTitle->name;
    ?>
</div>
<?php endif; ?>
	
		<?php if (isset($productItems->productPrice)) : ?>
<div class=" bd-productprice-2 product-prices">
    <?php
        if ($productItems->productPrice->show_prices == '1') {
    if ($productItems->productPrice->prices['salesPrice']<=0 and VmConfig::get ('askprice', 1)
    and $productItems->productPrice->imagesExists && !$productItems->productPrice->image->file_is_downloadable) {
    echo JText::_ ('COM_VIRTUEMART_PRODUCT_ASKPRICE');
    }
    $oldPrice = false;
    $oldPriceProps = array('name' => 'basePrice', 'description' => 'COM_VIRTUEMART_PRODUCT_BASEPRICE', $productItems->productPrice->prices, true);
    $regularPriceProps = array('name' => 'salesPrice', 'description' => 'COM_VIRTUEMART_PRODUCT_SALESPRICE', $productItems->productPrice->prices, true);
    ?>
    
    <div class=" bd-pricetext-5">
    <?php
		$html = call_user_func_array(array(&$productItems->productPrice->currency, 'createPriceDiv'), $regularPriceProps);
    ?>
    
    <span class=" bd-container-7 bd-tagstyles bd-custom-blockquotes bd-custom-button bd-custom-image bd-custom-table salesPrice">
        <?php echo $html; ?>
    </span>

</div>
    
    <?php } ?>
</div>
<?php endif; ?>
	
		<?php if (isset($productItems->productBuy)) : ?>
<form method="post" class="product" action="<?php echo JRoute::_ ('index.php'); ?>">
    <?php // todo output customfields ?>
    <?php if (!VmConfig::get('use_as_catalog', 0)) : ?>
        <?php
            $quantity = 1;
            if (isset($productItems->productBuy->step_order_level) && (int)$productItems->productBuy->step_order_level > 0) {
                $quantity = $productItems->productBuy->step_order_level;
            } else if (!empty($productItems->productBuy->min_order_level)){
                $quantity = $productItems->productBuy->min_order_level;
            }
        ?>
        <?php $stockhandle = VmConfig::get ('stockhandle', 'none'); ?>
        <?php if (($stockhandle == 'disableit' or $stockhandle == 'disableadd') and ($productItems->productBuy->product_in_stock - $productItems->productBuy->product_ordered) < 1) : ?>
            <?php
                echo JHTML::link (JRoute::_ ('index.php?option=com_virtuemart&view=productdetails&layout=notify&virtuemart_product_id=' . $productItems->productBuy->virtuemart_product_id), vmText::_ ('COM_VIRTUEMART_CART_NOTIFY'), array('class' => ' bd-productbuy-1 bd-button notify'));
            ?>
        <?php else : ?>
            <?php
                $tmpPrice = (float)$productItems->productBuy->prices['costPrice'];
                if (!(VmConfig::get('askprice', true) and empty($tmpPrice))) {
                    if (isset($productItems->productBuy->orderable) && $productItems->productBuy->orderable) {
                        $vmLang = VmConfig::get ('vmlang_js', 1) ? '&lang=' . substr (VmConfig::$vmlang, 0, 2) : '';
                        $attributes = array(
                            'data-vmsiteurl' => JURI::root( ),
                            'data-vmlang' => $vmLang,
                            'data-vmsuccessmsg' => JText::_('COM_VIRTUEMART_CART_ADDED'),
                            'title' => $productItems->productBuy->product_name,
                            'class' => ' bd-productbuy-1 bd-button add_to_cart_button'
                        );
                        echo JHTML::link ('#', JText::_ ('COM_VIRTUEMART_CART_ADD_TO'), $attributes);
                    } else {
                        $button = JHTML::link ($productItems->productBuy->link, JText::_ ('COM_VIRTUEMART_CART_ADD_TO'),
                            array('title' => $productItems->productBuy->product_name, 'class' => ' bd-productbuy-1 bd-button'));
                        if (isset($productItems->productBuy->isOne))
                            $button = JText::_('COM_VIRTUEMART_ADDTOCART_CHOOSE_VARIANT');
                        echo $button;
                    }
                }
            ?>
        <?php endif; ?>
    <?php endif; ?>
    <input type="hidden" name="quantity[]" value="<?php echo $quantity; ?>"/>
    <noscript><input type="hidden" name="task" value="add"/></noscript>
    <input type="hidden" name="option" value="com_virtuemart"/>
    <input type="hidden" name="view" value="cart"/>
    <input type="hidden" name="virtuemart_product_id[]" value="<?php echo $productItems->productBuy->virtuemart_product_id ?>"/>
    <input type="hidden" class="pname" value="<?php echo htmlentities($productItems->productBuy->product_name) ?>"/>
</form>
<?php endif; ?>
                            </div>
                        </div>
                        <?php if (($i + 1) % $_itemsInRow == 0 || $i == $productsCount - 1): ?>
                            </div>
                        <?php endif ?>
                        <?php $i++ ?>
                    <?php endforeach; ?>
                </div>
                <?php
                if ($footerText) : ?>
                <div class="vmfooter<?php echo $params->get ('moduleclass_sfx') ?>">
                    <?php echo $footerText ?>
                </div>
                <?php endif; ?>
                <?php if ($productsCount > $_itemsInRow): ?>
                        
                            <div class="bd-left-button">
    <a class=" bd-carousel-5" href="#">
        <span></span>
    </a>
</div>

<div class="bd-right-button">
    <a class=" bd-carousel-5" href="#">
        <span></span>
    </a>
</div>
                <?php endif ?>
                    </div>
                </div>
              </div>
            </div>
        </div>
    </div>
    </div>
</div>
<?php /*END_EDITOR_OPEN*/ } /*END_EDITOR_CLOSE*/ ?>