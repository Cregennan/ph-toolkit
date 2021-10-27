<?php

namespace Cregennan\PornhubToolkit;

use DiDom\Document;
use GuzzleHttp\Client;

/**
 * PHP Pornhub Toolkit
 *      A little toolkit for apps based on Pornhub.
 * Currently, there's only video mediadata extractor, but in the future different tools will be added.
 * @author Cregennan cregennandev@gmail.com
 * @package Cregennan\PornhubToolkit
 * @license MIT
 * @see Toolkit::GetMediaData()
 */
class Toolkit
{
    public const pornhubEmbedURL = "https://www.pornhub.com/embed/";

    /**Returns deobfuscated link to media data of the video. Uses GetMessyJavascript for retirieving obfuscated link.
     *
     * @param string $viewkey Viewkey of the video, like 'ph**********'
     * @return string Link to media data.
     */
    protected static function ParseMediaDataLink(string $viewkey){
        $string = Toolkit::GetMessyJavascript($viewkey);

        //Preparing MP4 string components
        $mp4pos = strpos($string, ToolkitDefinitions::pornhubMessyMp4) + strlen(ToolkitDefinitions::pornhubMessyMp4);
        $messedmp4 = substr($string, $mp4pos, strlen($string) - $mp4pos - 1);
        $messedmp4 = preg_replace( '/ \+ /', '', $messedmp4);
        $preparedmp4 = preg_split('/\/\*(.|\n)*?\*\//', $messedmp4, -1, PREG_SPLIT_NO_EMPTY);


        /*   Preparing array of variable definitions */
        $cutoffpos = strpos($string, ToolkitDefinitions::pornhubMessyMp4);
        $string = substr($string, 0, $cutoffpos);


        $variables = preg_split("/;/", $string, -1, PREG_SPLIT_NO_EMPTY);

        $parts = array();
        foreach ($variables as $var) {
            $t = $var;

            //remove 'var '
            $t = substr_replace($t, "", 0, 4);

            //find first '='
            $eqpos = strpos($t, '=');


            $variable_name = substr($t, 0, $eqpos);
            $value = substr($t, $eqpos + 1, strlen($t) - $eqpos);

            $patterns = ['/\ /', '/\"/', '/\+/'];
            $replacements = ['', '', ''];

            $value = preg_replace($patterns, $replacements, $value);
            $parts[$variable_name] = $value;
        }
        $link = "";

        foreach ($preparedmp4 as $key) {
            $link = $link . $parts[$key];
        }
        return $link;
    }

    /**
     * Returns js code of obfuscated mediadata link.
     * Needs to be parsed in Toolkit::ParseMediaDataLink()
     * @param string $viewkey Viewkey of the video, like 'ph**********'
     * @return string Javascript code
     */
    protected static function GetMessyJavascript(string $viewkey){

        $url = Toolkit::pornhubEmbedURL . $viewkey;
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($curl);
        curl_close($curl);


        //$client = new Client()
        $document = new Document();
        $document->loadHtml($response);

        $script = "";

        $dom = $document->find("script");
        foreach ($dom as $element){
            if (str_contains($element->text(), "var flashvars")){
                $script =  $element->text();
                break;
            }
        }

        //cut off trash after declarations
        $back = strpos($script, ToolkitDefinitions::pornhubMessyScriptBack);
        $script = substr($script, 0,  $back);
        //cut off trash before declarations
        $front = strpos($script, ToolkitDefinitions::pornhubMessyScriptFront);
        $script = substr($script, $front + strlen(ToolkitDefinitions::pornhubMessyScriptFront));

        return $script;
    }

    /**
     * Returns MediaData array for video by viewkey, e.g
     *      [{
     *          "defaultQuality":false,
     *          "format":"mp4",
     *          "videoUrl":"**link**",
     *          "quality":"2160"
     *      }]
     * @param string $viewkey Viewkey of the video, like 'ph**********'
     * @return string Json array of media data
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public static function GetMediaData(string $viewkey) : string{
        $datalink = self::ParseMediaDataLink($viewkey);

        $client = new Client([
            'base_uri' => ''
        ]);
        $response = $client->request('GET', $datalink);
        $body = $response->getBody()->getContents();

        return $body;
    }
}