<?php

namespace App\Libraries;

use App\Models\ModulePermission;

class UserRoleModules
{
    public static function getDynamicModule()
    {
        $modules = ModulePermission::where(['is_active' => 'active'])->get();

        $menus = [
            'items' => array(),
            'parents' => array()
        ];

        foreach ($modules as $module) {
            $menus['items'][$module->id] = $module;
            $menus['parents'][$module->parent][] = $module->id;
        }

        // Builds the array lists with data from the menu table
        return $output = self::buildMenu(0, $menus);
    }

    public function buildMenu($parent, $menu, $sub = NULL,$parentId = NULL,$parentClass = NULL){
        $html = "";

        

        if (isset($menu['parents'][$parent])) {

           $co = 1;
            foreach ($menu['parents'][$parent] as $itemId) {

                //echo form_input(array('type'=>'hidden','name'=>'module[]','value'=>$menu['items'][$itemId]->id));
                $html .= '<input type="hidden" name="module[]" value="'.$menu['items'][$itemId]->id.'">';

                if(!empty($sub)){
                    $html .= "<tr>\n";
                    $class="padding-left:30px !important;";
                    $inputClass ="onclick='subCheckbox(this,".$menu['items'][$itemId]->parent.")'";
                   // $id = $parentId.'.'.$co;
                    //$sub_id = $parentId.'.'.$co;
                    $sub_id = $co;
                    $id ="";
                    $subClass = "view_sub_checkbox";
                }else{
                    $html .= "<tr>\n";
                    $class="";
                    $id = $co;
                    $sub_id = '';
                    $subClass = '';
                }


                if($menu['items'][$itemId]->url==""){
                   // $parentClass2 = "onclick='parentCheckbox(".$menu['items'][$itemId]->id.")'";
                    $parentClass2 = "";
                }else{
                    $parentClass2 = "";
                }


                if (!isset($menu['parents'][$itemId])) { //if condition is false only view menu



                    $html .= "<td>\n ".$id." </td> \n";
                    $html .= "<td style='".$class."'>\n ".$sub_id.'&nbsp;&nbsp;'.$menu['items'][$itemId]->module_name." </td> \n";
                    $html .= "<td class='text-center'>\n
                                <div  class='checkbox checkbox-custom'>\n
                                    <input  class='".$subClass."' type='checkbox' id='view_".$menu['items'][$itemId]->id."' name='view_".$menu['items'][$itemId]->id."' value='1'>\n
                                         <label for='view_".$menu['items'][$itemId]->id."'></label>
                                </div>\n
                             </td> \n";

                    $html .= "<td class='text-center'>\n
                                <div class='checkbox checkbox-custom'>\n
                                    <input type='checkbox' id='insert_".$menu['items'][$itemId]->id."' name='insert_".$menu['items'][$itemId]->id."' value='1'>\n
                                         <label for='insert_".$menu['items'][$itemId]->id."'></label>
                                </div>\n
                             </td> \n";

                    $html .= "<td class='text-center'>\n
                                <div class='checkbox checkbox-custom'>\n
                                    <input type='checkbox' id='update_".$menu['items'][$itemId]->id."' name='update_".$menu['items'][$itemId]->id."' value='1'>\n
                                         <label for='update_".$menu['items'][$itemId]->id."'></label>
                                </div>\n
                             </td> \n";

                    $html .= "<td class='text-center'>\n
                                <div class='checkbox checkbox-custom'>\n
                                    <input type='checkbox' id='delete_".$menu['items'][$itemId]->id."' name='delete_".$menu['items'][$itemId]->id."' value='1'>\n
                                         <label for='delete_".$menu['items'][$itemId]->id."'></label>
                                </div>\n
                             </td> \n";

                }
                if (isset($menu['parents'][$itemId])) { //if condition is true show with submenu


                       // echo $parentId;
                       // echo "sub";


                    $html .= "<td>\n ".$co." </td> \n";
                    $html .= "<td>\n ".$menu['items'][$itemId]->module_name." </td> \n";
                    $html .= "<td class='text-center'>\n
                                <div class='checkbox checkbox-custom'>\n
                                    <input ".$parentClass2." type='checkbox' id='view_".$menu['items'][$itemId]->id."' name='view_".$menu['items'][$itemId]->id."' value='1'>\n
                                         <label for='view_".$menu['items'][$itemId]->id."'></label>
                                </div>\n
                             </td> \n";
                    $html .= "<td class='text-center'>\n </td> \n";
                    $html .= "<td class='text-center'>\n </td> \n";
                    $html .= "<td class='text-center'>\n </td> \n";

                    $html .= self::buildMenu($itemId, $menu, $menu['items'][$itemId]->module_name,$menu['items'][$itemId]->id,'parent_class');

                    $html .= "</td> \n";
                }
                $html .= "</tr> \n";
           $co++; }

        }
        return $html;
    }

}
