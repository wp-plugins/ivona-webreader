<?
class IVONAWebreaderConfig{
    static function getConfig(){
        return array(
            // IVONA Webreader customer ID
            // To configure this variable please use IVONA WebReader section in your Wordpress Settings
            'wr_userID'=>'',
            
            // ID representing TTS voice
            // See ivona_voicelist.php for details
            // To configure this variable please use IVONA WebReader section in your Wordpress Settings
            'wr_voiceID'=>'14',
            
            // GUI Language
            // To configure this variable please use IVONA WebReader section in your Wordpress Settings 
            'wr_lang'=>'en',
            
            // Background color of the player
            // Color values must be in format '#xxxxxx' where
            // 'xxxxxx' is hexadecimal value of the color
            'wr_bgColor'=>'#FFFFFF',
            
            // Controls color of the player
            // Color values must be in format '#xxxxxx' where
            // 'xxxxxx' is hexadecimal value of the color
            'wr_btnColor'=>'#666666',
            
            // Border color of the player
            // Color values must be in format '#xxxxxx' where
            // 'xxxxxx' is hexadecimal value of the color
            'wr_borColor'=>'#CCCCCC',
            
            // Appearance of the player
            // Allowed values are 0 (minified), 1 (text button) and 2 (full size)
            'wr_playerMode'=>'1',
            
            // Alignment of the player
            // Allowed values are 0 (left) and 1 (right)
            'wr_scrollMode'=>'0', 
            
            // Shadow of the player
            // Allowed values are 0 (disabled ) and 1 (enabled)
            'wr_shadow'=>'0',
            
            // Alpha channel of the player
            // Value must be an unsigned integer between 20 and 100 
            'wr_alpha'=>'100',
            
            // Autoembed - forces embedding flash instead of HTML button
            // Allowed values are 0 (disabled) or 1 (enabled)
            'wr_autoembed'=>'0',
            
            // Speech synthesis volume
            // Value must be an unsigned integer between 1 and 100
            'wr_speechVolume'=>'100',
            
            // Height of the player
            // Player's width is calculated from this value 
            'wr_playerHeight'=>'30',
            
            // Speech synthesis rate
            // Allowed values are 50 75 100 125 150 175 and 200
            'wr_speechRate'=>'120',
            
            // Skip HTML elements with id attribute 
            // You can specify multiple values separated by comma.
            'wr_skip_id'=>'',                                         
            
            // Skip HTML elements with class attribute
            // You can specify multiple values separated by comma. 
            'wr_skip_class'=>'',  
            
            // Position of the player
            // Currently only two values are possible:
            // 'top' - displays player above the article
            // 'bottom' - displays the player below the article
            'wr_position'=>'top',       
            
             // Margin top (CSS Property)
             // Margin above the each player
            'wr_marginTop'=>'', 
            
            // Margin bottom  (CSS Property)
            // Margin below each player
            'wr_marginBottom'=>'10px',
            
            // Excluded IDS
            // Articles, Post or pages to exclude from reading
            // You can specify multiple IDs sparated by comma
            // For exaple '1,42,834'
            'wr_excludeIDS'=>'',
            
            // Embed hidden link to iWebReader website on your blog
            // The link will only be visible to web robots 
            'wr_hiddenLink'=>'yes',
            
            // Read modification date
            // Should iWebReader read modification date of your posts?
            'wr_readModDate'=>'yes',
        ); 
    }
}