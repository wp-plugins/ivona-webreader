<?
/*  
    Name: IVONAWebreaderVoicelist Class
    Description: Contains voices data for CMS plugins
    License: Dual licensed under MIT and GPLv2 licenses
    Build date: 2011-12-29T13:16:31+01:00
    Copyrights: IVONA WebReader, LLC
    
*/
class IVONAWebreaderVoicelist {
    var $voices = array();
    
    function __construct(){
        $this->voices = array(
            array(
                'id'=>'1',
                'name'=>'Jacek',
                'is_delegate'=>0,
                'locale'=>'pl_PL',
                'gender'=>'m',
            ),
            array(
                'id'=>'2',
                'name'=>'Ewa',
                'is_delegate'=>1,
                'locale'=>'pl_PL',
                'gender'=>'f',
            ),
            array(
                'id'=>'3',
                'name'=>'Jennifer',
                'is_delegate'=>0,
                'locale'=>'en_US',
                'gender'=>'f',
            ),
            array(
                'id'=>'4',
                'name'=>'Eric',
                'is_delegate'=>0,
                'locale'=>'en_US',
                'gender'=>'m',
            ),
            array(
                'id'=>'5',
                'name'=>'Carmen',
                'is_delegate'=>1,
                'locale'=>'ro_RO',
                'gender'=>'f',
            ),
            array(
                'id'=>'6',
                'name'=>'Jan',
                'is_delegate'=>0,
                'locale'=>'pl_PL',
                'gender'=>'m',
            ),
            array(
                'id'=>'7',
                'name'=>'Maja',
                'is_delegate'=>0,
                'locale'=>'pl_PL',
                'gender'=>'f',
            ),
            array(
                'id'=>'8',
                'name'=>'Amy',
                'is_delegate'=>0,
                'locale'=>'en_GB',
                'gender'=>'f',
            ),
            array(
                'id'=>'9',
                'name'=>'Brian',
                'is_delegate'=>0,
                'locale'=>'en_GB',
                'gender'=>'m',
            ),
            array(
                'id'=>'10',
                'name'=>'Kendra',
                'is_delegate'=>0,
                'locale'=>'en_US',
                'gender'=>'f',
            ),
            array(
                'id'=>'11',
                'name'=>'Joey',
                'is_delegate'=>0,
                'locale'=>'en_US',
                'gender'=>'m',
            ),
            array(
                'id'=>'12',
                'name'=>'Geraint',
                'is_delegate'=>1,
                'locale'=>'cy_CY',
                'gender'=>'m',
            ),
            array(
                'id'=>'13',
                'name'=>'Emma',
                'is_delegate'=>1,
                'locale'=>'en_GB',
                'gender'=>'f',
            ),
            array(
                'id'=>'14',
                'name'=>'Kimberly',
                'is_delegate'=>1,
                'locale'=>'en_US',
                'gender'=>'f',
            ),
            array(
                'id'=>'15',
                'name'=>'Conchita',
                'is_delegate'=>1,
                'locale'=>'es_ES',
                'gender'=>'f',
            ),
            array(
                'id'=>'16',
                'name'=>'Miguel',
                'is_delegate'=>0,
                'locale'=>'es_ES',
                'gender'=>'m',
            ),
            array(
                'id'=>'17',
                'name'=>'Chipmunk',
                'is_delegate'=>0,
                'locale'=>'en_US',
                'gender'=>'chip',
            ),
            array(
                'id'=>'18',
                'name'=>'Penélope',
                'is_delegate'=>1,
                'locale'=>'es_US',
                'gender'=>'f',
            ),
            array(
                'id'=>'19',
                'name'=>'Enrique',
                'is_delegate'=>0,
                'locale'=>'es_US',
                'gender'=>'m',
            ),
            array(
                'id'=>'20',
                'name'=>'Hans',
                'is_delegate'=>0,
                'locale'=>'de_DE',
                'gender'=>'m',
            ),
            array(
                'id'=>'21',
                'name'=>'Marlene',
                'is_delegate'=>1,
                'locale'=>'de_DE',
                'gender'=>'f',
            ),
            array(
                'id'=>'22',
                'name'=>'Céline',
                'is_delegate'=>1,
                'locale'=>'fr_FR',
                'gender'=>'f',
            ),
            array(
                'id'=>'23',
                'name'=>'Mathieu',
                'is_delegate'=>0,
                'locale'=>'fr_FR',
                'gender'=>'m',
            ),
            array(
                'id'=>'24',
                'name'=>'Gwyneth',
                'is_delegate'=>0,
                'locale'=>'cy_CY',
                'gender'=>'f',
            ),
            array(
                'id'=>'25',
                'name'=>'Geraint',
                'is_delegate'=>0,
                'locale'=>'en_WLS',
                'gender'=>'m',
            ),
            array(
                'id'=>'26',
                'name'=>'Gwyneth',
                'is_delegate'=>1,
                'locale'=>'en_WLS',
                'gender'=>'f',
            ),
            array(
                'id'=>'27',
                'name'=>'Salli',
                'is_delegate'=>0,
                'locale'=>'en_US',
                'gender'=>'f',
            ),
            array(
                'id'=>'28',
                'name'=>'Ivy',
                'is_delegate'=>0,
                'locale'=>'en_US',
                'gender'=>'tf',
            ),
            array(
                'id'=>'29',
                'name'=>'Nicole',
                'is_delegate'=>1,
                'locale'=>'en_AU',
                'gender'=>'f',
            )
        );
    }
    function getSortedByLocale(){
        $ret = array();
        foreach($this->voices as $k => $v){
            if(!isset($ret[$v['locale']])){
                $ret[$v['locale']] = array();
                $ret[$v['locale']]['wrlang'] = $this->getLangCodeByLocale($v['locale']);
                $ret[$v['locale']]['items'] = array();
            }
            $ret[$v['locale']]['items'][]=$v;
        }
        return $ret;
    }
    public function getFlagCodeByLocale($l){
        if($l=='es_US') return 'es_us';
        if($l=='en_WLS') return 'wls';
        return strtolower(substr($l,3,4));
    }
    function getLangCodeByLocale($l){
        return strtolower(substr($l,0,2));
    }
    function getVoicesArray(){
        return $this->voices;
    }
    function getVoiceChoices(){
        $choices = array(
            'Get voice from configuration file'=>null,
        );
        foreach($this->getSortedByLocale() as $locale => $v){
            foreach($v['items'] as $key => $voice){
                $choicesVal = $voice['id'].".".$v['wrlang'];
                $choicesKey = "[".strtoupper($locale)."] ".$voice['name'];
                $choices[$choicesKey]=$choicesVal;
            }
        }
        return $choices;
    }
    
}