<? $active = true;?>

<div class="webcomp-settings__tabs">
    <ul class="webcomp-settings__list">
        <? foreach ($arTabs as $id => $tab): ?>
            <li class="webcomp-settings__item <?=($active) ? "active" : ""?>" data-tab="<?=$id?>">
                <?=$tab["TITLE"]?>
            </li>
        <? $active = false;?>
        <? endforeach ?>
    </ul>
</div>
