<?php
/**
 * FecMall file.
 *
 * @link http://www.fecmall.com/
 * @copyright Copyright (c) 2016 FecMall Software LLC
 * @license http://www.fecmall.com/license
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
.carousel-config-section {margin: 15px 0; padding: 15px; border: 1px solid #e4e4e4; border-radius: 4px; background-color: #f9f9f9;}
.carousel-config-section legend {font-weight: bold; color: #cc0000; padding: 0 5px;}
.carousel-items-container {margin-top: 15px;}
.carousel-item {margin-bottom: 15px; padding: 15px; border: 1px solid #ddd; border-radius: 5px; background: #fff; box-shadow: 0 1px 3px rgba(0,0,0,0.1);}
.carousel-item-header {display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; padding-bottom: 10px; border-bottom: 1px solid #eee;}
.carousel-item-header strong {font-size: 14px; color: #333;}
.carousel-item label {display: block; margin: 10px 0 5px; font-weight: 600; color: #555; font-size: 13px;}
.carousel-item input[type="file"], 
.carousel-item input[type="text"] {width: 100%; margin-bottom: 10px; padding: 8px; border: 1px solid #ddd; border-radius: 3px; box-sizing: border-box;}
.carousel-item input[type="file"]:focus, 
.carousel-item input[type="text"]:focus {border-color: #4CAF50; outline: none; box-shadow: 0 0 5px rgba(76, 175, 80, 0.3);}
.add-carousel-item {margin-top: 15px; padding: 10px 15px; background: #4CAF50; color: white; border: none; cursor: pointer; border-radius: 3px; font-weight: 500; transition: background 0.3s;}
.add-carousel-item:hover {background: #45a049;}
.remove-carousel-item {background: #f44336; color: white; border: none; padding: 5px 10px; cursor: pointer; border-radius: 3px; font-size: 12px; transition: background 0.3s;}
.remove-carousel-item:hover {background: #d32f2f;}
.media-type-selector {margin: 10px 0;}
.media-type-selector label {margin-right: 20px; cursor: pointer; font-weight: 500; color: #555; display: inline-flex; align-items: center;}
.media-type-selector input[type="radio"] {margin-right: 5px;}
.media-input {margin: 10px 0;}
.pageFormContent .edit_p label {font-weight: 600; color: #333; display: block; margin-bottom: 8px;}
</style>

<div class="pageContent systemConfig">
	<form  method="post" action="<?= $saveUrl ?>" class="pageForm required-validate" enctype="multipart/form-data" onsubmit="return validateCallback(this, navTabAjaxDone);">
		<?php echo CRequest::getCsrfInputHtml();  ?>
		<div layouth="56" class="pageFormContent" style="height: 240px; overflow: auto;">

				<input type="hidden"  value="<?=  $_id; ?>" size="30" name="editFormData[_id]" class="textInput ">

				<fieldset id="fieldset_table_qbe">
					<legend style="color:#cc0000"><?= Yii::$service->page->translate->__('Apphtml5 Home Config') ?></legend>
					<div>
						<?= $editBar; ?>
					</div>
				</fieldset>
				
				<!-- 首页轮播图配置 -->
				<fieldset class="carousel-config-section">
					<legend style="color:#cc0000"><?= Yii::$service->page->translate->__('Apphtml5 Home Carousel Config') ?></legend>
					<div>
						<div class="pageFormContent">
							<div class="edit_p">
								<label><?= Yii::$service->page->translate->__('Carousel Items') ?>:</label>
								<div class="carousel-items-container" id="carousel-items-container">
									<!-- 轮播项将通过JavaScript动态添加 -->
								</div>
								<button type="button" class="add-carousel-item" onclick="addCarouselItem()"><?= Yii::$service->page->translate->__('Add Carousel Item') ?></button>
				<button type="button" class="add-carousel-item" onclick="testFileUpload()" style="background: #2196F3; margin-left: 10px;">测试文件上传</button>
							</div>
						</div>
					</div>
				</fieldset>
				
				<?= $lang_attr ?>
				<?= $textareas ?>
		</div>

		<div class="formBar">
			<ul>
				<!--<li><a class="buttonActive" href="javascript:;"><span>保存</span></a></li>-->
				<li>
                    <div class="buttonActive"><div class="buttonContent"><button onclick="prepareFormData()" value="accept" name="accept" type="submit"><?= Yii::$service->page->translate->__('Save') ?></button></div></div>
                </li>
				<li>
					<div class="button"><div class="buttonContent"><button type="button" class="close"><?= Yii::$service->page->translate->__('Cancel') ?></button></div></div>
				</li>
			</ul>
		</div>
	</form>
</div>

<script>
// 初始化轮播项计数器
var carouselItemIndex = 0;

// 添加轮播项
function addCarouselItem(mediaType = 'image') {
    var container = document.getElementById('carousel-items-container');
    var itemDiv = document.createElement('div');
    itemDiv.className = 'carousel-item';
    itemDiv.innerHTML = `
        <div class="carousel-item-header">
            <strong><?= Yii::$service->page->translate->__('Carousel Item') ?> ` + (carouselItemIndex + 1) + `</strong>
            <button type="button" class="remove-carousel-item" onclick="removeCarouselItem(this)"><?= Yii::$service->page->translate->__('Remove') ?></button>
        </div>
        <div class="media-type-selector">
            <label><input type="radio" name="carousel_media_type_` + carouselItemIndex + `" value="image" ` + (mediaType === 'image' ? 'checked' : '') + ` onchange="toggleMediaType(this)"> <?= Yii::$service->page->translate->__('Image') ?></label>
            <label><input type="radio" name="carousel_media_type_` + carouselItemIndex + `" value="video" ` + (mediaType === 'video' ? 'checked' : '') + ` onchange="toggleMediaType(this)"> <?= Yii::$service->page->translate->__('Video') ?></label>
        </div>
        <div class="media-input image-input" style="` + (mediaType !== 'image' ? 'display:none;' : '') + `">
            <label><?= Yii::$service->page->translate->__('Image') ?>:</label>
            <input type="file" name="carousel_image_` + carouselItemIndex + `" accept="image/*" onchange="handleFileSelect(this)">
            <div class="file-info" style="margin-top: 5px; font-size: 12px; color: #666;"></div>
        </div>
        <div class="media-input video-input" style="` + (mediaType !== 'video' ? 'display:none;' : '') + `">
            <label><?= Yii::$service->page->translate->__('Video') ?>:</label>
            <input type="file" name="carousel_video_` + carouselItemIndex + `" accept="video/*" onchange="handleFileSelect(this)">
            <div class="file-info" style="margin-top: 5px; font-size: 12px; color: #666;"></div>
        </div>
        <div>
            <label><?= Yii::$service->page->translate->__('Link URL (Optional)') ?>:</label>
            <input type="text" name="carousel_link_` + carouselItemIndex + `" placeholder="<?= Yii::$service->page->translate->__('Link URL') ?>">
        </div>
    `;
    container.appendChild(itemDiv);
    carouselItemIndex++;
}

// 删除轮播项
function removeCarouselItem(button) {
    var itemDiv = button.closest('.carousel-item');
    itemDiv.remove();
}

// 切换媒体类型
function toggleMediaType(radio) {
    var itemDiv = radio.closest('.carousel-item');
    var index = Array.from(itemDiv.parentNode.children).indexOf(itemDiv);
    
    var imageInput = itemDiv.querySelector('.image-input');
    var videoInput = itemDiv.querySelector('.video-input');
    
    if (radio.value === 'image') {
        imageInput.style.display = 'block';
        videoInput.style.display = 'none';
    } else if (radio.value === 'video') {
        imageInput.style.display = 'none';
        videoInput.style.display = 'block';
    }
}

// 处理文件选择事件
function handleFileSelect(fileInput) {
    var fileInfoDiv = fileInput.parentNode.querySelector('.file-info');
    
    if (fileInput.files && fileInput.files.length > 0) {
        var file = fileInput.files[0];
        var fileSize = (file.size / 1024 / 1024).toFixed(2); // MB
        var fileName = file.name;
        
        fileInfoDiv.innerHTML = '<span style="color: #4CAF50;">✓ 已选择文件: ' + fileName + ' (' + fileSize + ' MB)</span>';
        
        console.log('File selected:', {
            name: fileName,
            size: fileSize + ' MB',
            type: file.type,
            inputName: fileInput.name
        });
        
        // 验证文件类型
        var isImage = file.type.startsWith('image/');
        var isVideo = file.type.startsWith('video/');
        
        if (!isImage && !isVideo) {
            fileInfoDiv.innerHTML = '<span style="color: #f44336;">⚠ 不支持的文件类型</span>';
        }
    } else {
        fileInfoDiv.innerHTML = '';
    }
}

// 页面加载完成后初始化
document.addEventListener('DOMContentLoaded', function() {
    // 加载已保存的轮播图数据
    loadExistingCarouselItems();
    
    // 如果没有现有数据，添加一个默认的轮播项
    var container = document.getElementById('carousel-items-container');
    if (container.children.length === 0) {
        addCarouselItem();
    }
});

// 加载已保存的轮播图数据
 function loadExistingCarouselItems() {
     // 这里需要从后端获取已保存的轮播图数据
     // 由于这是在PHP模板中，我们可以通过PHP变量传递数据
     <?php if (!empty($carouselItems) && is_array($carouselItems)): ?>
         var existingItems = <?= json_encode($carouselItems) ?>;
         existingItems.forEach(function(item, index) {
            addCarouselItem(item.mediaType || 'image');
            var container = document.getElementById('carousel-items-container');
            var itemDiv = container.children[container.children.length - 1];
            
            // 设置链接值
            if (item.link) {
                var linkInput = itemDiv.querySelector('input[name="carousel_link_' + (carouselItemIndex - 1) + '"]');
                if (linkInput) linkInput.value = item.link;
            }
            
            // 显示已上传的媒体文件信息
             if (item.mediaUrl) {
                 var mediaInfo = document.createElement('div');
                 mediaInfo.className = 'existing-media-info';
                 
                 // 创建预览和信息显示
                 var infoHtml = '<p style="color: #4CAF50; font-size: 12px; margin: 5px 0;">已上传文件</p>';
                 
                 if (item.mediaType === 'image') {
                     infoHtml += '<img src="' + item.mediaUrl + '" style="max-width: 100px; max-height: 60px; margin: 5px 0; border: 1px solid #ddd; border-radius: 3px;" alt="轮播图预览">';
                 } else if (item.mediaType === 'video') {
                     infoHtml += '<video style="max-width: 100px; max-height: 60px; margin: 5px 0; border: 1px solid #ddd; border-radius: 3px;" controls><source src="' + item.mediaUrl + '" type="video/mp4">您的浏览器不支持视频播放</video>';
                 }
                 
                 infoHtml += '<p style="color: #666; font-size: 11px; margin: 2px 0; word-break: break-all;">URL: ' + item.mediaUrl + '</p>';
                 mediaInfo.innerHTML = infoHtml;
                 
                 if (item.mediaType === 'image') {
                     var imageInput = itemDiv.querySelector('.image-input');
                     if (imageInput) imageInput.appendChild(mediaInfo);
                 } else if (item.mediaType === 'video') {
                     var videoInput = itemDiv.querySelector('.video-input');
                     if (videoInput) videoInput.appendChild(mediaInfo);
                 }
             }
        });
    <?php endif; ?>
}

// 在表单提交前准备carousel_items数据
function prepareFormData() {
    // 检查是否有文件需要上传
    var hasFiles = false;
    var fileInputs = document.querySelectorAll('input[type="file"]');
    fileInputs.forEach(function(input) {
        if (input.files && input.files.length > 0) {
            hasFiles = true;
            console.log('Found file to upload:', input.name, input.files[0].name);
        }
    });
    
    if (hasFiles) {
        console.log('Files detected, form will be submitted with multipart/form-data');
    } else {
        console.log('No files detected');
    }
    
    // 注意：我们不再需要创建隐藏的carousel_items字段
    // 因为后端现在直接从carousel_link_0, carousel_link_1等字段中收集数据
    return true;
}

// 收集轮播图数据（保留此函数以防将来需要）
function collectCarouselData() {
    var carouselItems = [];
    var container = document.getElementById('carousel-items-container');
    var items = container.querySelectorAll('.carousel-item');
    
    items.forEach(function(item, index) {
        var mediaTypeInputs = item.querySelectorAll('input[name="carousel_media_type_' + index + '"]');
        var mediaType = 'image';
        mediaTypeInputs.forEach(function(input) {
            if (input.checked) {
                mediaType = input.value;
            }
        });
        
        var linkInput = item.querySelector('input[name="carousel_link_' + index + '"]');
        var link = linkInput ? linkInput.value : '';
        
        carouselItems.push({
            mediaType: mediaType,
            link: link
        });
    });
    
    return carouselItems;
}

// 测试文件上传功能
function testFileUpload() {
    console.log('=== 文件上传测试开始 ===');
    
    // 检查表单设置
    var form = document.querySelector('form');
    console.log('表单enctype:', form.enctype);
    console.log('表单method:', form.method);
    console.log('表单action:', form.action);
    
    // 检查文件输入框
    var fileInputs = document.querySelectorAll('input[type="file"]');
    console.log('找到文件输入框数量:', fileInputs.length);
    
    var hasFiles = false;
    fileInputs.forEach(function(input, index) {
        console.log('文件输入框 ' + (index + 1) + ':', {
            name: input.name,
            accept: input.accept,
            files: input.files.length
        });
        
        if (input.files && input.files.length > 0) {
            hasFiles = true;
            console.log('  - 选中的文件:', input.files[0].name, input.files[0].size + ' bytes');
        }
    });
    
    if (!hasFiles) {
        alert('请先选择要上传的文件，然后再点击测试按钮');
        return;
    }
    
    // 检查CSRF token
    var csrfInput = document.querySelector('input[name="_csrf"]');
    console.log('CSRF token存在:', !!csrfInput);
    
    console.log('=== 文件上传测试完成 ===');
    alert('测试完成，请查看浏览器控制台获取详细信息');
}
</script>

<style>
.pageForm  .pageFormContent .edit_p{
    width:100%;
    line-height:35px;
}
.pageForm  .pageFormContent .edit_p .remark-text{
    font-size: 11px;
    color: #777;
    margin-left: 20px;
}
.pageForm   .pageFormContent p.edit_p label{
        width: 240px;
    line-height: 30px;
    font-size: 13px;
    font-weight: 500;
}
.pageContent .combox {
        margin-left:5px;
}
</style>