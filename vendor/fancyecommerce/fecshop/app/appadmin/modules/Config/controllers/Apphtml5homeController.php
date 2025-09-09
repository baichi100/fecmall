<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\app\appadmin\modules\Config\controllers;

use fecshop\app\appadmin\modules\Config\ConfigController;
use Yii;
/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Apphtml5homeController extends ConfigController
{
    public $enableCsrfValidation = true;
    
    /**
     * @var array 白名单，无需权限验证的动作
     */
    public $whitelist = [
        'fileupload',
    ];
    
    public function actionManager()
    {
        $data = $this->getBlock('manager')->getLastData();
        return $this->render($this->action->id, $data);
    }
    
    
    public function actionManagersave()
    {
        // 明确指定使用manager block
        return $this->getBlock('manager')->save();
    }
    
    /**
     * 处理轮播图文件上传
     */
    public function actionFileupload()
    {
        if (Yii::$app->request->isPost && !empty($_FILES)) {
            try {
                // 获取上传的文件
                $file = $_FILES['file'];
                $inputName = Yii::$app->request->post('input_name');
                $mediaType = Yii::$app->request->post('media_type');
                
                if ($file['error'] !== UPLOAD_ERR_OK) {
                    echo json_encode([
                        'return_status' => 'error',
                        'error_message' => 'File upload error: ' . $file['error']
                    ]);
                    Yii::$app->end();
                }
                
                // 使用Block中的方法处理文件上传
                $managerBlock = $this->getBlock('manager');
                $uploadResult = $managerBlock->handleFileUpload($file, $mediaType);
                
                if ($uploadResult) {
                    echo json_encode([
                        'return_status' => 'success',
                        'file_url' => $uploadResult,
                        'input_name' => $inputName,
                        'media_type' => $mediaType
                    ]);
                } else {
                    $errors = Yii::$service->helper->errors->get();
                    $errorMessage = is_array($errors) ? implode('; ', $errors) : $errors;
                    
                    echo json_encode([
                        'return_status' => 'error',
                        'error_message' => $errorMessage ?: 'Upload failed'
                    ]);
                }
            } catch (\Exception $e) {
                echo json_encode([
                    'return_status' => 'error',
                    'error_message' => $e->getMessage()
                ]);
            }
        } else {
            echo json_encode([
                'return_status' => 'error',
                'error_message' => 'No file uploaded'
            ]);
        }
        
        Yii::$app->end();
    }
    
}