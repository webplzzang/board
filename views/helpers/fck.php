<?php
App::import('Vendor', 'board.fckeditor');

class FckHelper extends AppHelper {

    /**
    * creates an fckeditor textarea
    *
    * @param array $namepair - used to build textarea name for views, array('Model', 'fieldname')
    * @param stirng $basepath - base path of project/system
    * @param string $content
    */
    function fckeditor($namepair = array(), $basepath = '', $content = ''){
        $editor_name = 'data';
        foreach ($namepair as $name){
            $editor_name .= "[" . $name . "]";
        }

        $oFCKeditor = new FCKeditor($editor_name,$namepair['1']) ;
        $oFCKeditor->BasePath = $basepath . '/js/fckeditor/' ;
        $oFCKeditor->Height="400px";
        $oFCKeditor->Value = $content ;
        $oFCKeditor->Create() ;
    }
}

?>