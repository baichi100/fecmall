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
    
}