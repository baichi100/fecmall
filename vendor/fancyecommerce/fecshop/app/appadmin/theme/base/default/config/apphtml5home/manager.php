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
				<input type="hidden" id="carousel_items_input" value="" name="editFormData[carousel_items]" />

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
            <input type="file" name="carousel_image_` + carouselItemIndex + `" accept="image/*" onchange="handleFileSelect(this)">
            <div class="file-info" style="margin-top: 5px; font-size: 12px; color: #666;"></div>
        </div>
        <div class="media-input video-input" style="` + (mediaType !== 'video' ? 'display:none;' : '') + `">
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
        } else {
            // 自动触发文件上传
            uploadFile(fileInput, file, fileInfoDiv);
        }
    } else {
        fileInfoDiv.innerHTML = '';
    }
}

// 上传文件函数
function uploadFile(fileInput, file, fileInfoDiv) {
    var formData = new FormData();
    formData.append('file', file);
    formData.append('media_type', file.type.startsWith('image/') ? 'image' : 'video');
    formData.append('input_name', fileInput.name);
    formData.append("<?= Yii::$app->request->csrfParam ?>", "<?= Yii::$app->request->csrfToken ?>");
    
    // 显示上传进度
    fileInfoDiv.innerHTML = '<span style="color: #FF9800;">↑ 正在上传...</span>';
    
    // 发送AJAX请求上传文件
    $.ajax({
        url: '<?= Yii::$app->urlManager->createUrl("config/apphtml5home/fileupload") ?>',
        type: 'POST',
        data: formData,
        async: true,
        dataType: 'json',
        timeout: 80000,
        cache: false,
        contentType: false,
        processData: false,
        success: function(data, textStatus) {
            if (data.return_status == "success") {
                // 修改隐藏字段名称以符合后端解析规则
                var hiddenFieldName = data.input_name + '_url';
                fileInfoDiv.innerHTML = '<span style="color: #4CAF50;">✓ 上传成功</span>' +
                    '<input type="hidden" name="' + hiddenFieldName + '" value="' + data.file_url + '">' +
                    '<p style="margin: 5px 0; font-size: 12px; color: #666;">URL: ' + data.file_url + '</p>';
                
                // 如果是图片，显示预览
                if (data.media_type === 'image') {
                    fileInfoDiv.innerHTML += '<img src="' + data.file_url + '" style="max-width: 100px; max-height: 60px; margin-top: 5px; border: 1px solid #ddd; border-radius: 3px;" alt="预览">';
                } else if (data.media_type === 'video') {
                    fileInfoDiv.innerHTML += '<video style="max-width: 100px; max-height: 60px; margin-top: 5px; border: 1px solid #ddd; border-radius: 3px;" controls><source src="' + data.file_url + '" type="video/mp4">您的浏览器不支持视频播放</video>';
                }
            } else {
                fileInfoDiv.innerHTML = '<span style="color: #f44336;">✗ 上传失败: ' + data.error_message + '</span>';
            }
        },
        error: function() {
            fileInfoDiv.innerHTML = '<span style="color: #f44336;">✗ 上传出错</span>';
            alert('<?= Yii::$service->page->translate->__('Upload Error') ?>');
        }
    });
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
    // 修复：正确获取轮播图数据，不通过View::block属性
    <?php 
    // 确保carouselItems变量被正确传递
    if (isset($carouselItems) && !empty($carouselItems) && is_array($carouselItems)): ?>
        var existingItems = <?= json_encode($carouselItems) ?>;
        // 重置计数器以确保新添加的项目索引正确
        carouselItemIndex = 0;
        
        // 清空容器并重新添加所有项目
        var container = document.getElementById('carousel-items-container');
        container.innerHTML = '';
        
        // 按顺序添加每个轮播项
        existingItems.forEach(function(item, index) {
            // 添加轮播项
            addCarouselItem(item.mediaType || 'image');
            
            // 获取最新添加的轮播项
            var itemDiv = container.children[container.children.length - 1];
            
            // 设置链接值
            if (item.link) {
                var linkInput = itemDiv.querySelector('input[name="carousel_link_' + index + '"]');
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
                
                // 添加隐藏字段存储媒体URL
                var hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
                hiddenInput.name = 'carousel_' + item.mediaType + '_' + index + '_url';
                hiddenInput.value = item.mediaUrl;
                itemDiv.appendChild(hiddenInput);
            }
        });
    <?php endif; ?>
}

// 在表单提交前准备carousel_items数据
function prepareFormData() {
    // 收集轮播图数据
    var carouselData = collectCarouselData();
    
    // 将轮播图数据转换为JSON并存储在隐藏字段中
    document.getElementById('carousel_items_input').value = JSON.stringify(carouselData);
    
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
    
    return true;
}

// 收集轮播图数据
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
        
        // 获取链接
        var linkInput = item.querySelector('input[name="carousel_link_' + index + '"]');
        var link = linkInput ? linkInput.value : '';
        
        // 获取媒体URL（从隐藏字段中获取）
        var mediaUrl = '';
        var mediaUrlInput = item.querySelector('input[name="carousel_image_' + index + '_url"], input[name="carousel_video_' + index + '_url"]');
        if (mediaUrlInput) {
            mediaUrl = mediaUrlInput.value;
        }
        
        carouselItems.push({
            mediaType: mediaType,
            mediaUrl: mediaUrl,
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