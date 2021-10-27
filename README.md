<p align="center">
    Pornhub Toolbox
</p>

# About Pornhub Toolbox

Toolbox is a bunch of instruments for developers. We believe, everyone should have access to videos they want. Sometimes, standard Pornhub API is not good enough. Toolbox can help you develop your own apps, based on Pornhub.

##Installation
```composer
composer require cregennan/pornhub-toolkit
```
You can always download package directly from [here](https://github.com/Vasiliy-Makogon/Database/archive/refs/heads/master.zip). 
You'll need to manually include ToolBox and ToolboxDefinitions classes from `/src`


##Tools
 - [Video direct link extractor](#cdn-video-link-extractor)
 
 ###Video direct link extractor
 Pornhub Embed video is not enough for some situations. Here comes Link Extractor.
 
 Usage: `Toolkit::GetMediaData($viewkey)`
```php 
<?php
include_once 'vendor/autoload.php';
use Cregennan\PornhubToolkit\Toolkit as Toolkit;
$viewkey = "ph***********";

$data = json_decode(Toolkit::GetMediaData($viewkey));
?>
<iframe width="500" height="400" src="<?=$data[0]->videoUrl ?>" ></iframe>

```
`Toolkit::GetMediaData($viewkey)` returns json string of media definitions e.g. 
```json
[{
    "defaultQuality":false,
    "format":"mp4",
    "videoUrl":"https://de1.phncdn.com/videos/****",
    "quality":"2160"
},
{
    "defaultQuality":false,
    "format":"mp4",
    "videoUrl":"https://de1.phncdn.com/videos/****",
    "quality":"1080"
}]
```
##Issues
You can always send issue in its tab or make pull request with some fixes.

##License
Pornhub Toolbox is open sourced software licensed under [MIT license](https://opensource.org/licenses/MIT).


