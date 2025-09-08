<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\app\appadmin\modules\Config\block\apphtml5home;

use fec\helpers\CUrl;
use fec\helpers\CRequest;
use fecshop\app\appadmin\interfaces\base\AppadminbaseBlockEditInterface;
use fecshop\app\appadmin\modules\AppadminbaseBlockEdit;
use Yii;

/**
 * block cms\staticblock.
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Manager extends AppadminbaseBlockEdit implements AppadminbaseBlockEditInterface
{
    public $_saveUrl;
    // 需要配置
    public $_key = 'apphtml5_home';
    public $_type;
    protected $_attrArr = [
        'meta_title',
        'meta_keywords',
        'meta_description',
        'best_feature_sku',
        'carousel_items',  // 添加轮播图配置属性
    ];
    
    public function init()
    {
        
         // 需要配置
        $this->_saveUrl = CUrl::getUrl('config/apphtml5home/managersave');
        $this->_editFormData = 'editFormData';
        $this->setService();
        $this->_param = CRequest::param();
        $this->_one = $this->_service->getByKey([
            'key' => $this->_key,
        ]);
        if ($this->_one['value']) {
            $this->_one['value'] = unserialize($this->_one['value']);
        }
    }
    
    // 传递给前端的数据 显示编辑form
    public function getLastData()
    {
        $id = ''; 
        if (isset($this->_one['id'])) {
           $id = $this->_one['id'];
        }
        
        // 获取已保存的轮播图数据
        $carouselItems = [];
        if (isset($this->_one['value']['carousel_items']) && is_array($this->_one['value']['carousel_items'])) {
            $carouselItems = $this->_one['value']['carousel_items'];
        }
        
        return [
            'id'            =>   $id, 
            'editBar'      => $this->getEditBar(),
            'textareas'   => $this->_textareas,
            'lang_attr'   => $this->_lang_attr,
            'saveUrl'     => $this->_saveUrl,
            'carouselItems' => $carouselItems,
        ];
    }
    
    /**
     * 实现接口中的setService方法
     */
    public function setService()
    {
        $this->_service = Yii::$service->storeBaseConfig;
    }
    
    public function getEditArr()
    {
        $deleteStatus = Yii::$service->customer->getStatusDeleted();
        $activeStatus = Yii::$service->customer->getStatusActive();
        
        return [
            // 需要配置
            [
                'label'  => Yii::$service->page->translate->__('Meta Title'),
                'name' => 'meta_title',
                'display' => [
                    'type' => 'inputString',
                    'lang' => true,
                ],
                'remark' => '',
            ],
            [
                'label'  => Yii::$service->page->translate->__('Meta Keywords'),
                'name' => 'meta_keywords',
                'display' => [
                    'type' => 'inputString',
                    'lang' => true,
                ],
                'remark' => '',
            ],
            [
                'label'  => Yii::$service->page->translate->__('Meta Description'),
                'name' => 'meta_description',
                'display' => [
                    'type' => 'inputString',
                    'lang' => true,
                ],
                'remark' => '',
            ],
            
            [
                'label'  => Yii::$service->page->translate->__('Best Feature Sku'),
                'name' => 'best_feature_sku',
                'display' => [
                    'type' => 'inputString',
                ],
                'remark' => '',
            ],
            
            [
                'label'  => Yii::$service->page->translate->__('Carousel Items'),
                'name' => 'carousel_items',
                'display' => [
                    'type' => 'custom',
                    'custom' => 'carouselItems',
                ],
                'remark' => '',
            ],
        ];
    }
    
    public function getArrParam(){
        $request_param = CRequest::param();
        $this->_param = isset($request_param[$this->_editFormData]) ? $request_param[$this->_editFormData] : [];
        $param = [];
        $attrVals = [];
        foreach($this->_param as $attr => $val) {
            if (in_array($attr, $this->_attrArr)) {
                // 特殊处理carousel_items，将其转换为数组格式
                if ($attr === 'carousel_items') {
                    // 解析轮播图配置数据
                    $carouselItems = $this->parseCarouselItems($request_param);
                    $attrVals[$attr] = $carouselItems;
                } else {
                    $attrVals[$attr] = $val;
                }
            } else {
                $param[$attr] = $val;
            }
        }
        $param['value'] = $attrVals;
        $param['key'] = $this->_key;
        
        return $param;
    }
    
    /**
     * 解析轮播图配置数据
     */
    protected function parseCarouselItems($request_param)
    {
        // 从请求参数中收集轮播图数据
        $carouselItems = [];
        
        // 查找所有以carousel_开头的参数
        foreach ($request_param as $key => $value) {
            // 检查是否为轮播图链接参数
            if (preg_match('/^carousel_link_(\d+)$/', $key, $matches)) {
                $index = $matches[1];
                $carouselItems[$index]['link'] = $value;
                $carouselItems[$index]['mediaType'] = isset($request_param["carousel_media_type_$index"]) ? 
                    $request_param["carousel_media_type_$index"] : 'image';
            }
        }
        
        // 处理文件上传
        if (!empty($_FILES)) {
            foreach ($_FILES as $fileKey => $fileInfo) {
                if (preg_match('/^carousel_(image|video)_(\d+)$/', $fileKey, $matches)) {
                    $mediaType = $matches[1];
                    $index = $matches[2];
                    
                    // 检查文件是否上传成功
                    if ($fileInfo['error'] === UPLOAD_ERR_OK && !empty($fileInfo['tmp_name'])) {
                        // 处理文件上传
                        $uploadResult = $this->handleFileUpload($fileInfo, $mediaType);
                        if ($uploadResult) {
                            if (!isset($carouselItems[$index])) {
                                $carouselItems[$index] = [];
                            }
                            $carouselItems[$index]['mediaUrl'] = $uploadResult;
                            $carouselItems[$index]['mediaType'] = $mediaType;
                        }
                    }
                }
            }
        }
        
        // 按索引排序
        ksort($carouselItems);
        
        // 重新索引数组
        return array_values($carouselItems);
    }
    
    /**
     * 处理文件上传
     */
    protected function handleFileUpload($fileInfo, $mediaType)
    {
        try {
            // 设置上传目录到appimage
            $uploadDir = Yii::getAlias('@appimage/common/media/carousel/');
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            
            // 获取文件扩展名
            $extension = pathinfo($fileInfo['name'], PATHINFO_EXTENSION);
            
            // 验证文件类型
            $allowedTypes = [];
            if ($mediaType === 'image') {
                $allowedTypes = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
            } elseif ($mediaType === 'video') {
                $allowedTypes = ['mp4', 'webm', 'ogg', 'avi', 'mov'];
            }
            
            if (!in_array(strtolower($extension), $allowedTypes)) {
                Yii::$service->helper->errors->add('Invalid file type for ' . $mediaType);
                return false;
            }
            
            // 生成唯一文件名
            $fileName = 'carousel_' . time() . '_' . uniqid() . '.' . $extension;
            $filePath = $uploadDir . $fileName;
            
            // 移动上传的文件到appimage目录
            if (move_uploaded_file($fileInfo['tmp_name'], $filePath)) {
                // 返回完整的URL路径，用于存储到数据库
                $baseUrl = Yii::$app->request->hostInfo;
                $mediaUrl = $baseUrl . '/appimage/common/media/carousel/' . $fileName;
                
                // 记录上传成功的日志
                Yii::info('File uploaded successfully: ' . $mediaUrl, 'apphtml5home');
                
                return $mediaUrl;
            } else {
                Yii::$service->helper->errors->add('Failed to upload file');
                return false;
            }
        } catch (Exception $e) {
            Yii::$service->helper->errors->add('Upload error: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * save article data,  get rewrite url and save to article url key.
     */
    public function save()
    {
        /*
         * if attribute is date or date time , db storage format is int ,by frontend pass param is int ,
         * you must convert string datetime to time , use strtotime function.
         */
        // 设置 bdmin_user_id 为 当前的user_id
        $data = $this->getArrParam();
        
        // 调试信息：记录保存的数据
        Yii::info('Apphtml5home saving data: ' . print_r($data, true), 'apphtml5home');
        Yii::info('Files uploaded: ' . print_r($_FILES, true), 'apphtml5home');
        
        $result = $this->_service->saveConfig($data);
        $errors = Yii::$service->helper->errors->get();
        if (!$errors && $result) {
            echo  json_encode([
                'statusCode' => '200',
                'message'    => Yii::$service->page->translate->__('Save Success'),
            ]);
            exit;
        } else {
            // 提供更详细的错误信息
            $errorMessage = $errors;
            if (is_array($errors)) {
                $errorMessage = implode('; ', $errors);
            }
            
            echo  json_encode([
                'statusCode' => '300',
                'message'    => $errorMessage ?: 'Save failed - unknown error',
            ]);
            exit;
        }
    }
    
    public function getVal($name, $column){
        if (is_object($this->_one) && property_exists($this->_one, $name) && $this->_one[$name]) {
            
            return $this->_one[$name];
        }
        $content = $this->_one['value'];
        if (is_array($content) && !empty($content) && isset($content[$name])) {
            
            return $content[$name];
        }
        
        return '';
    }
}