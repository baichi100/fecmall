    public function getVal($name, $column){
        if (is_object($this->_one) && property_exists($this->_one, $name) && $this->_one[$name]) {
            
            return $this->_one[$name];
        }
        $content = $this->_one['value'];
        if (is_array($content) && !empty($content) && isset($content[$name])) {
            // 对于carousel_items，直接返回数组而不是处理成字符串
            if ($name === 'carousel_items') {
                return $content[$name];
            }
            
            return $content[$name];
        }
        
        return '';
    }