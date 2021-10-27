<?php
include_once 'vendor/autoload.php';
include_once 'src/Toolkit.php';
use Cregennan\PornhubToolkit\Toolkit as PHToolkit;

$viewkey = $_GET['key'];

$data = json_decode(PHToolkit::GetMediaData($viewkey));
?>
<script type="text/javascript" src="//cdn.embed.ly/player-0.1.0.min.js"></script>
<iframe width="500" height="400" src="<?=$data[0]->videoUrl ?>" ></iframe>
<script>
    document.addEventListener('ready', () => {
        let iframe = document.getElementsByTagName('iframe')[0];
        const player = new playerjs.Player(iframe);
        player.on('ready', () => player.play());
    });
</script>










