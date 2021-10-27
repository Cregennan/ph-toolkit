<?php
include_once 'vendor/autoload.php';
use Cregennan\PornhubToolkit\Toolkit as PHToolkit;
$viewkey = "ph***********";

$data = json_decode(PHToolkit::GetMediaData($viewkey));
?>
<iframe width="500" height="400" src="<?=$data[0]->videoUrl ?>" ></iframe>

<script type="text/javascript" src="//cdn.embed.ly/player-0.1.0.min.js"></script>

<script>
    document.addEventListener('ready', () => {
        let iframe = document.getElementsByTagName('iframe')[0];
        const player = new playerjs.Player(iframe);
        player.on('ready', () => player.play());
    });
</script>










