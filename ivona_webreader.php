<?php
/*
 IVONA WebReader WordPress Plugin
 ==============================================================================
 
 This plugin will add iWebReader players to each article on your website

 Info for WordPress:
 ==============================================================================
 Plugin Name: IVONA WebReader 
 Plugin URI: http://www.iwebreader.com/
 Description: Professional voiceover for your website 
 Author: IVONA WebReader Ltd.
 Version: 1.1
 Author URI: http://www.iwebreader.com
 
*/
require_once(dirname(__FILE__).'/ivona_configuration.php');
require_once(dirname(__FILE__).'/ivona_voicelist.php');

define('IVONA_WR_PLUGIN_NAME', 'IVONAWebreaderPlugin');
define('IVONA_WR_CONFIG_NAME', 'IVONAWebreaderConfig');
define('IVONA_WR_VOICELIST_NAME','IVONAWebreaderVoicelist');
define('IVONA_WR_READTHIS_PREFIX','WebReaderContent_'); 
define('IVONA_WR_CONTAINER_PREFIX','WebreaderPlayer_');
define('IVONA_WR_PLAYERINNER_CLASS','WebreaderInnerPlayer');


class IVONAWebreaderPlugin{
    // Config
    static $config = array();
    // Allowed post ids are stored here
    static $ids = array();
    // Array of ids to exclude
    static $excludedIDS = array();
    // Voicelist instance
    static $voicelist;
    // Is WR Libs loaded
    static $isLiblaryLoaded = FALSE;
    // Editable fields
    static $fields;
    // Field values
    static $values;
    // Javascript option keys 
    static $js_options = array(
        'autoembed',
        'autoplay',
        'download',
        'shadow',
        'alpha',
        'scrollMode',
        'playerMode',
        'bgColor',
        'borColor',
        'btnColor',
        
    );
    // Initialize
    static function init(){
        $voiceListClass = IVONA_WR_VOICELIST_NAME;
        self::$voicelist = new $voiceListClass();
        try {
            self::$config = call_user_func(array(IVONA_WR_CONFIG_NAME,'getConfig'));
        } catch (Exception $e) {
            self::$config = array();
        }
        // Set 0 values as -1 because Wordpress wont store 0 values in DB
        self::$fields = array(
            'wr_userID'=>array(
                'name'=>'Service ID',
                'type'=>'text',
                'default'=>'',
                'desc'=>'Unique ID of iWebReader service. You can grab it from <a href="https://secure.iwebreader.com/account/status.php">here</a></p>'
            ),
            'wr_voice_data'=>array(
                'name'=>'Voice',
                'type'=>'select',
                'desc'=>'Choose one of all available voices',
                'default'=>'',
                'choices'=>self::$voicelist->getVoiceChoices(),
            ),
            'wr_speechRate'=>array(
                'name'=>'Speech rate',
                'type'=>'select',
                'desc'=>'Speed of reading',
                'default'=>'', 
                'choices'=>array(
                    'Get from configuration file'=>null,
                    '50%'=>'50',
                    '75%'=>'75',
                    '100%'=>'100',
                    '125%'=>'125',
                    '150%'=>'150',
                    '175%'=>'175',
                    '200%'=>'200',
                ),
            ),
            'wr_speechVolume'=>array(
                'name'=>'Speech volume',
                'type'=>'text',
                'desc'=>'Volume of reading. Enter value between 20 and 100',
                'default'=>'100',
                
            ),
            'wr_readModDate'=>array(
                'name'=>'Read modification date',
                'type'=>'select',
                'desc'=>'Should iWebReader read modification date of your posts?',
                'default'=>'', 
                'choices'=>array(
                    'Get from configuration file'=>null,
                    'Yes'=>'yes',
                    'No'=>'no',
                ),
            ),
            'wr_position'=>array(
                'name'=>'Position',
                'type'=>'select',
                'default'=>'',
                'desc'=>'Choose position of player',
                'choices'=>array(
                    'Get from configuration file'=>null,
                    'Above the content'=>'top',
                    'Below the content'=>'bottom',
                ),
            ),
            'wr_scrollMode'=>array(
                'name'=>'Alignment',
                'type'=>'select',
                'desc'=>'Choose alignment of the player',
                'default'=>'',
                'choices'=>array(
                    'Get from configuration file'=>null,
                    'Left'=>'-1',
                    'Right'=>'1',
                ),
            ),
            'wr_shadow'=>array(
                'name'=>'Drop shadow',
                'type'=>'select',
                'desc'=>'Drop shadow under the player?',
                'default'=>'',
                'choices'=>array(
                    'Get from configuration file'=>null,
                    'No'=>'-1',
                    'Yes'=>'1',
                ),
            ),
            'wr_playerMode'=>array(
                'name'=>'Appearance',
                'type'=>'select',
                'desc'=>'Appearance of the activated Flash player',
                'default'=>'',
                'choices'=>array(
                    'Get from configuration file'=>null,
                    'Text button'=>'1',
                    'Minified'=>'-1',
                    'Full size'=>'2',
                ),
            ),
            
            'wr_autoembed'=>array(
                'name'=>'Start with',
                'type'=>'select',
                'default'=>'',
                'desc'=>'This option is ignored if visitor\'s browser does not support Flash',
                'choices'=>array(
                    'Get from configuration file'=>null,
                    'HTML button'=>'-1',
                    'Flash object'=>'1',
                ),
            ),
            'wr_bgColor'=>array(
                'name'=>'Background color',
                'type'=>'text',
                'default'=>'#FFFFFF',
                'desc'=>'Color code of player\'s background in hex format. For example #FFFFFF',

            ),
            'wr_btnColor'=>array(
                'name'=>'Controls color',
                'type'=>'text',
                'default'=>'#666666',
                'desc'=>'Color code of player\'s controls in hex format. For example #666666',

            ),
            'wr_borColor'=>array(
                'name'=>'Border color',
                'type'=>'text',
                'default'=>'#CCCCCC',
                'desc'=>'Color code of player\'s border in hex format. For example #CCCCCC',

            ),
            'wr_playerHeight'=>array(
                'name'=>'Height of the player.',
                'type'=>'text',
                'default'=>26,
                'desc'=>'Specify height of the player. This value must be greater than 20',

            ),
            'wr_marginTop'=>array(
                'name'=>'Margin top',
                'type'=>'text',
                'desc'=>'Margin above the player',
                'default'=>'0px',
                
            ),
            'wr_marginBottom'=>array(
                'name'=>'Margin bottom',
                'type'=>'text',
                'desc'=>'Margin below the player',
                'default'=>'0px',
                
            ),
            'wr_hiddenLink'=>array(
                'name'=>'Embed hidden link',
                'type'=>'select',
                'desc'=>'Embed hidden link to iWebReader website. It will only be visible to web robots',
                'default'=>'', 
                'choices'=>array(
                    'Get from configuration file'=>null,
                    'Yes'=>'yes',
                    'No'=>'no',
                ),
            ),
            'wr_excludeIDS'=>array(
                'name'=>'Excluded IDs',
                'type'=>'text',
                'desc'=>'Enter post or page IDs you wish to exclude from reading. Spearate multiple values by comma.',
                'default'=>'',
                
            ),
            'wr_skip_id'=>array(
                'name'=>'Skip by ID',
                'type'=>'text',
                'desc'=>'Skip reading HTML Elements with id atrribute. Spearate multiple values by comma.',
                'default'=>'',
                
            ),
            'wr_skip_class'=>array(
                'name'=>'Skip by Class',
                'type'=>'text',
                'desc'=>'Skip reading HTML Elements with class atrribute. Spearate multiple values by comma.',
                'default'=>'',
                
            ),
            
            
            
            
            
            
            
            
        );
        // Store DB values in self::values instance
        self::configureValues();
        // Bind values to config instance
        self::bindValuesToConfig();
        // Prepare Array of excluded IDs
        self::buildExcludedIDS(self::getVar('wr_excludeIDS',''));
        
        // Wordpress Hooks
        add_action('wp_head', array(IVONA_WR_PLUGIN_NAME,'append_wr_script'));
        add_filter('the_content', array(IVONA_WR_PLUGIN_NAME,'ivona_wr_content_filter'),20);
        add_filter('the_excerpt', array(IVONA_WR_PLUGIN_NAME,'ivona_wr_content_filter'));
        add_action('admin_menu', array(IVONA_WR_PLUGIN_NAME,'add_administartion_options'));
    }
    
    // Lets assign values from Wordpress to self::values
    static function configureValues(){
        $option_values = array(); 
        foreach(self::$fields as $key => $val){
            $getOpt =  get_option($key);
            if($getOpt){
                $option_values[$key] = $getOpt;
            }else{
                $option_values[$key] = null;
            }
        }
        self::$values = $option_values;
    }
    // Bind values to config
    static function bindValuesToConfig(){
        foreach(self::$values as $k => $v){
            // Specific assignment to coice data
            if($k=='wr_voice_data' && $v!==null){
                $exp = explode(".",$v);
                if(is_array($exp) && count($exp)==2){
                    $voiceID = $exp[0];
                    $lang = $exp[1];
                    self::$config['lang']=$lang;
                    self::$config['voiceID']=$voiceID;
                }
            // Normal assignment    
            }else{
                if($v!=null){
                    self::$config[$k]= $v;
                }
            }
        }
    }
    // Function for retrieving values from config
    static function getVar($k,$d=null){
        if(isset(self::$config[$k]) && !empty(self::$config[$k])){
            return self::$config[$k];
        }else{
            if($d!==null){
                return $d;
            }
            trigger_error("Error: Key ".$k." not set");
        }
    }
    // Add iWebreader loading function 
    function append_wr_script() {
        self::$isLiblaryLoaded = TRUE;   
echo '
<script type="text/javascript">
<!--//--><![CDATA[//><!-- 
;(function(){
    var D=document,W=window;
    W.WebreaderAsyncCommands = []; 
    W.loadIVONAWebReaderLibs = function(){
         	ts=new Date().getTime();
         	W.WebreaderAutoCreate = 0;

         	a = D.createElement("div");
         	a.setAttribute("id","webreader-root");
         	if(!D.body) return;
         	D.body.appendChild(a);

         	e = D.createElement("script");
         	e.async = true;
         	if("https:" == document.location.protocol){
         		e.src = "https://secure.ivona.com/static/scripts/webreaderPlayer2.js?timestamp="+ts
         	}else{
         		e.src = "http://player.ivona.com/www/static/scripts/webreaderPlayer2.js?timestamp="+ts
         	}
         	a.appendChild(e);
    }
    W.IVONAWebreaderDomLoad = function(){
        a = arguments.callee; a.init = a.init||0;
        if(a.init==0){a.init=1;loadIVONAWebReaderLibs()}   
    } 
    if (W.addEventListener) {
        W.addEventListener("load", IVONAWebreaderDomLoad, false);return;
    } else if (D.addEventListener){
        D.addEventListener("DOMContentLoaded", IVONAWebreaderDomLoad, false);return;
    } else if (W.attachEvent ) {
        W.attachEvent("onload", IVONAWebreaderDomLoad);return;
    } else {
        if (typeof W.onload == "function") {
    		var fnOld = W.onload;
    		W.onload = function() {
    			fnOld();
    			setTimeout(W.IVONAWebreaderDomLoad, 0);
    		}
    	} else {
    		W.onload = function(){
    		    setTimeout(W.IVONAWebreaderDomLoad, 0);
    		}
    	} 
    }      

})();
//--><!]]>   
</script>
'; 

    }
    static function buildExcludedIDS($str){
        $arr = explode(",",$str);
        foreach ($arr as $k => $v) {
            if(is_int(intval($v)) ){
                self::$excludedIDS[] = $v;
            }
            
        }   
    }
    function addPostId($id){
        if(count(self::$ids) > 30 ) return false;
        if(in_array($id,self::$excludedIDS)) return false;
        if(!in_array($id,self::$ids)){
            self::$ids[] = $id;
            return $id;
        }else{
            return false;
        }
        
    }
    
    // This function filters the content of current wordpress entry
    function ivona_wr_content_filter($content) {
        // If rendering RSS or ATOM do nothing 
        if(is_feed()) return $content;
        if(!self::$isLiblaryLoaded){
            self::append_wr_script();
        }
        global $post;
        $o = ''; 	
    	$id = $post->ID; 
    	
    	
    	$a = self::addPostId($id);
    	// Return regular contnent if is not page or not single 
    	if(!$a && !is_single() && !is_page()) return $content;
    	
    	
    	// Modified date
    	$modified = '';
    	if(self::getVar('wr_readModDate','yes')=='yes'){
    	    
        	$modified = '<div style="display:none">'.substr($post->post_modified,0,(strlen($post->post_modified)-3)).'


            </div>';
    	}
    	
    	// Title
    	$title = '<div style="display:none">'.$post->post_title.'
        
        
        </div>';
        
        $wr_hiddenLink = '';
    	if( self::getVar('wr_hiddenLink') == 'yes' ){
    	    $wr_hiddenLink = '<a href="http://www.iwebreader.com" class="'.IVONA_WR_PLAYERINNER_CLASS.'" style="display:none">iWebReader - Professional Voice Over for Webpages</a>';
    	}
        
        $wr_marginTop = self::getVar('wr_marginTop',0);
        $wr_marginBottom = self::getVar('wr_marginBottom',0);
        $alignmentStyle = ( (self::getVar('wr_scrollMode',0)==1) ? 'float:right;':'float:left;');
        $playerStyle='margin-top:'.$wr_marginTop.';margin-bottom:'.$wr_marginBottom.';'.$alignmentStyle;
        
        
    	
    	
    	
       
$o .= '
<script type="text/javascript">
<!--//--><![CDATA[//><!-- 
try{
WebreaderAsyncCommands.push(function(){
    Webreader.create({    
        ';
        foreach(self::$config as $k => $v){
            $k = substr($k,3,strlen($k));
            if(in_array($k,self::$js_options)){
                // Replace -1 to 0
                // Because not null values are stored in WP DB
                $v = ($v==-1?0:$v);
                // Print the option
                $o .= $k.':"'.$v.'",
        ';
            }else continue;
    
        }
        $o .= 'parentId:"'.IVONA_WR_CONTAINER_PREFIX.$id.'",
        lang:"'.self::getVar('lang','en').'",
        soundUrl:escape("http://www.ivona.com/online/fileWebRead.php"+
        "?v="+'.self::getVar('voiceID',14/*Kimberly*/).'+';
        $o .='"&dc="+"'.IVONA_WR_READTHIS_PREFIX.$id.'"+';
        // Skip ids
        if( is_string(self::getVar('wr_skip_id','')) && strlen(self::getVar('wr_skip_id','')) > 0  ){
            $o .='"&sdi="+"'.self::getVar('wr_skip_id','').'"+';
        }
        // Skip classes
        if( is_string(self::getVar('wr_skip_class','')) && strlen(self::getVar('wr_skip_class','')) > 0  ){
            $o .='"&sdc="+"'.self::getVar('wr_skip_class','').','.IVONA_WR_PLAYERINNER_CLASS.'"+';
        }else{
            $o .='"&sdc="+"'.IVONA_WR_PLAYERINNER_CLASS.'"+';
        }
        $o .= '"&pv="+"'.self::getVar('wr_speechVolume',100).'"+
        "&pr="+"'.self::getVar('wr_speechRate',130).'"+
        "&i="+"'.self::getVar('wr_userID').'"+
        "&u="+escape(document.location.href))  
    },'.self::getVar('wr_playerHeight',26).');
      
});
}catch(e){}
//--><!]]> 
</script>
';  
        
    	
    	if( is_string(self::getVar('wr_position',0)) && strlen(self::getVar('wr_position','')) > 0 && 'bottom'==self::getVar('wr_position','')  ){
    	   
	        $o .= '<div class="'.IVONA_WR_READTHIS_PREFIX.$id.'">'.$title.$modified.$content.'</div>';
        	$o .=  '<div style="float:left;width:100%"><div id="'.IVONA_WR_CONTAINER_PREFIX.$id.'" style="'.$playerStyle.'" ></div>'.$wr_hiddenLink.'</div>';
	    }else{
	        $o .=  '<div style="float:left;width:100%"><div id="'.IVONA_WR_CONTAINER_PREFIX.$id.'" style="'.$playerStyle.'" ></div>'.$wr_hiddenLink.'</div>';
        	$o .= '<div class="'.IVONA_WR_READTHIS_PREFIX.$id.'" >'.$title.$modified.$content.'</div>';
	    }
    	return $o;

    }
    function add_administartion_options() {
        // Add a new submenu under Options:
        add_options_page('IVONA WebReader', 'IVONA WebReader', 8, 'IVONAWebReader' /* slug */, array(IVONA_WR_PLUGIN_NAME,'get_webreader_options_page') );
    }
    
    function get_webreader_options_page() {
        $updateOptionsKey = 'updateWROptions';
        $options_fields = self::$fields;
        $option_values = self::$values;
        
        $inputWidth = ' style="width:300px" ';
        $o = '';
        // Submitted
        if(isset($_POST[$updateOptionsKey]) && $_POST[$updateOptionsKey]==1 ){
            //Update options
            foreach($options_fields as $ofk => $ofv){
                if(isset($_POST[$ofk])){
                    $option_values[$ofk] = $_POST[$ofk]; 
                    
                }else{
                    $option_values[$ofk] = null;
                }
                update_option( $ofk, $option_values[$ofk] );
                 
            }
            $o .= '<div class="updated"><p><strong>Your changes has been saved</strong></p></div>';
   
        }   
        $o .= '
            <div class="wrap">
            <h2>IVONA WebReader Configuration</h2>
            <form name="form1" method="post" action="'.$_SERVER['REQUEST_URI'].'">
            <input type="hidden" name="'.$updateOptionsKey.'" value="1" />
            <table class="form-table">
            ';
            foreach($options_fields as $ko => $vo){
                $fieldvalue = $option_values[$ko];
                
                
                
                $o .= '
                <tr valign="top">      
                <th scope="row"><label for="'.$ko.'">'.$vo['name'].'</label></th>
                <td>
                ';
                // Text inputs
                if($vo['type']=='text'){
                    $o .= '<input type="text" name="'.$ko.'" id="'.$ko.'" value="'.$fieldvalue.'" size="20" '.$inputWidth.'>';
                }
                // Select
                if($vo['type']=='select' && is_array($vo['choices'])){
                    $o .= '
                    <select name="'.$ko.'" id="'.$ko.'" '.$inputWidth.'>
                    ';
                    foreach($vo['choices'] as $selk=>$selv){
                        $isSel = ($fieldvalue==$selv?' selected="selected" ':'');
                        $o .= '<option value="'.$selv.'" '.$isSel.'>'.$selk.'</option>';
                    }
                    $o .='
                    </select>';
                }
                $o .='
                </td>
                </tr>
                ';              
               if(isset($vo['desc'])){
                    $o .='
                    <tr>
                        <td colspan="2">
                        <div style="margin-bottom:10px">
                            '.$vo['desc'].'
                        </div>
                        </td>
                    </tr>
                    ';
                }
                $o .='
                <tr>
                <td colspan="2"><div style="height:1px;border-bottom:1px solid #CCC">&nbsp;</div>
                </td>
                </tr>
                ';
                
                
            }
            $o .='
            </table>
            
            <p class="submit">
               <input type="submit" name="Submit" value="Save" />
            </p>
            </form>
            </div>
        ';
        echo $o;
        return;
       
    }

}
// If WP is loaded. Calling this php file directly will do nothing.
if(defined('ABSPATH') && defined('WPINC')) {

    add_action("init",array(IVONA_WR_PLUGIN_NAME,"init"));
}