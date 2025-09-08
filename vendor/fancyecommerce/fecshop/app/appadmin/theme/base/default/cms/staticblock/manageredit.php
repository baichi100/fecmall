<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

use fec\helpers\CRequest;

/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
?>
<style>
.checker{float:left;}
.dialog .pageContent {background:none;}
.dialog .pageContent .pageFormContent{background:none;}
.carousel-config-section {margin: 10px 0;}
.carousel-item {margin-bottom: 15px; padding: 10px; border: 1px solid #eee;}
.carousel-item label {display: block; margin-bottom: 5px; font-weight: bold;}
.carousel-item input[type="file"] {margin-bottom: 5px;}
.carousel-item input[type="text"] {width: 300px; padding: 5px;}
</style>

<div class="pageContent">
	<form  method="post" action="<?= $saveUrl ?>" class="pageForm required-validate" onsubmit="return validateCallback(this, dialogAjaxDoneCloseAndReflush);">
		<?php echo CRequest::getCsrfInputHtml();  ?>
		<div layouth="56" class="pageFormContent" style="height: 240px; overflow: auto;">
                <input type="hidden"  value="<?=  $product_id; ?>" size="30" name="product_id" class="textInput ">
				<fieldset id="fieldset_table_qbe">
					<legend style="color:#009688"><?= Yii::$service->page->translate->__('Edit Info') ?></legend>
					<div>
						<?= $editBar; ?>
					</div>
				</fieldset>
				<?= $lang_attr ?>
				<?= $textareas ?>
				
				<!-- 首页轮播图配置 -->
				<fieldset class="carousel-config-section">
					<legend style="color:#cc0000"><?= Yii::$service->page->translate->__('Apphtml5 Home Carousel Config') ?></legend>
					<div>
						<div class="carousel-item">
							<label for="carousel_image_1"><?= Yii::$service->page->translate->__('Carousel Image') ?> 1:</label>
							<input type="file" name="carousel_image_1" id="carousel_image_1" accept="image/*">
							<input type="text" name="carousel_link_1" placeholder="<?= Yii::$service->page->translate->__('Link URL (Optional)') ?>" style="margin-top: 5px;">
						</div>
						
						<div class="carousel-item">
							<label for="carousel_image_2"><?= Yii::$service->page->translate->__('Carousel Image') ?> 2:</label>
							<input type="file" name="carousel_image_2" id="carousel_image_2" accept="image/*">
							<input type="text" name="carousel_link_2" placeholder="<?= Yii::$service->page->translate->__('Link URL (Optional)') ?>" style="margin-top: 5px;">
						</div>
						
						<div class="carousel-item">
							<label for="carousel_image_3"><?= Yii::$service->page->translate->__('Carousel Image') ?> 3:</label>
							<input type="file" name="carousel_image_3" id="carousel_image_3" accept="image/*">
							<input type="text" name="carousel_link_3" placeholder="<?= Yii::$service->page->translate->__('Link URL (Optional)') ?>" style="margin-top: 5px;">
						</div>
					</div>
				</fieldset>
				
				<fieldset id="fieldset_table_qbe">
					<legend style="color:#cc0000"><?= Yii::$service->page->translate->__('Help') ?></legend>
					<div>
							{{homeUrl}}:  <?= Yii::$service->page->translate->__('Refer to home page url') ?><br/>
							{{imgBaseUrl}}:  <?= Yii::$service->page->translate->__('Refer to image base url') ?>
					</div>
				</fieldset>
		</div>

		<div class="formBar">
			<ul>
				<!--<li><a class="buttonActive" href="javascript:;"><span>保存</span></a></li>-->
				<li>
                    <div class="buttonActive"><div class="buttonContent"><button onclick="func('accept')"  value="accept" name="accept" type="submit"><?= Yii::$service->page->translate->__('Save') ?></button></div></div>
                </li>
				<li>
					<div class="button"><div class="buttonContent"><button type="button" class="close"><?= Yii::$service->page->translate->__('Cancel') ?></button></div></div>
				</li>
			</ul>
		</div>
	</form>
</div>