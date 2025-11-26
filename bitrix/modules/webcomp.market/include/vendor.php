<? if($SHOW): ?>
    <?
        // подгужаем с нашего сайта контент, для того чтобы можно было если что поменять просто
        $arrContextOptions = [
            "ssl" => [
                "verify_peer"      => false,
                "verify_peer_name" => false,
            ],
        ];

        $content = file_get_contents("https://web-komp.ru/mc/index.php", false,
            stream_context_create($arrContextOptions));

        if ( ! empty($content)) {
            echo $content;
        } else {
            // В случае если подгрузка не удалась, то можно просто ставить текст обычный
            echo 'This solution is developed by the company WEBCOMP';
        }

        die();
    ?>
<? endif ?>

