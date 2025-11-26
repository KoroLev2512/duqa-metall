
<? if(!empty($arParams)):?>
    <tr>
        <th style="width: 100px;" class="productLine adm-list-table-cell adm-detail-valign-middle"><?=GetMessage("WEBCOMP_MARKET_TABLE_PHOTO")?></th>
        <th class="productLine adm-list-table-cell adm-detail-valign-middle"><?=GetMessage("WEBCOMP_MARKET_TABLE_NAME")?></th>
        <th class="productLine adm-list-table-cell adm-detail-valign-middle"><?=GetMessage("WEBCOMP_MARKET_TABLE_PRICE")?></th>
        <th class="productLine adm-list-table-cell adm-detail-valign-middle"><?=GetMessage("WEBCOMP_MARKET_TABLE_COUNT")?></th>
        <th class="productLine adm-list-table-cell adm-detail-valign-middle"><?=GetMessage("WEBCOMP_MARKET_TABLE_TOTAL")?></th>
        <th class="productLine adm-list-table-cell adm-detail-valign-middle"><?=GetMessage("WEBCOMP_MARKET_TABLE_DELETE")?></th>
    </tr>
    <?php
    $fullSum=0;
    ?>
    <? foreach ($arParams as $item): ?>
        <?
        $photoID = $item["UF_PHOTO"]["VALUE"][0]["ID"];
        $photo = CFile::ResizeImageGet($photoID, array('width'=> 80, 'height'=> 80), BX_RESIZE_IMAGE_PROPORTIONAL, false, false);
        $fullSum+=$item["UF_QUANTITY"]["VALUE"]*$item["UF_PRICE"]["VALUE"];
        ?>

        <tr class="orderList active" data-price="<?=$item["UF_PRICE"]["VALUE"]?>" data-lineid="<?=$item["ID"]?>" data-elementid="<?=$item["UF_ELEMENT_ID"]["VALUE"]?>" data-quantity="<?=$item["UF_QUANTITY"]["VALUE"]?>">
            <td class="productLine adm-list-table-cell adm-detail-valign-middle">
                <img src="<?=$photo["src"]?>" alt="<?=$item["UF_NAME"]?>">
            </td>
            <td class="productLine adm-list-table-cell adm-detail-valign-middle"><?=$item["UF_NAME"]["VALUE"]?></td>
            <td class="productLine adm-list-table-cell adm-detail-valign-middle"><?= CMarketCatalog::getPrice($item["UF_PRICE"]["VALUE"]) ?></td>
            <td class="productLine adm-list-table-cell adm-detail-valign-middle">
                <input type="number"
                       value="<?= $item["UF_QUANTITY"]["VALUE"] ?>"
                       style="width: 35px;" min="1" onkeyup="changeCount(this)"
                       oninput="changeCount(this)"></td>
            <td class="productLine adm-list-table-cell adm-detail-valign-middle">
                <?= CMarketCatalog::getPrice($item["UF_QUANTITY"]["VALUE"]
                    * $item["UF_PRICE"]["VALUE"]) ?>
            </td>
            <td class="productLine adm-list-table-cell adm-detail-valign-middle">
                <a class="removeLine" href="javascript:void(0)"
                   onclick="elementRemove(this)"><?=GetMessage("WEBCOMP_MARKET_SCRIPT_DELETE")?></a></td>
        </tr>
    <? endforeach ?>
    <tr id="buttonAddBlock">
        <td style="text-align: left">
            <input type="button" value="<?=GetMessage("WEBCOMP_MARKET_ADD_ELEMENT")?>"
                   onclick="jsUtils.OpenWindow('/bitrix/admin/iblock_element_search.php?lang=ru&amp;IBLOCK_ID=<?= $GLOBALS['WEBCOMP']['IBLOCKS']["catalog"]["catalog_webcomp"] ?>&amp;n=newProduct', 900, 700);">
        </td>
        <td>
            <input style="display: none" type="text" id="newProduct"
                   onchange="elementAdd(this.value)">
        </td>
        <td></td>
        <td></td>
        <td style="text-align: right"><?=GetMessage("WEBCOMP_MARKET_TOTAL")?><b
                    class="totalSum"><?= CMarketCatalog::getPrice($fullSum) ?></b>
        </td>
    </tr>
<? endif ?>
<style>
    .productLine{
        padding: 10px 0; text-align: center; outline: 1px solid #ccc;
    }
    #buttonAddBlock{
        /*display: none;*/
    }
    .removed{
        opacity: 0.5;
    }
</style>
<script>

    function createTr(name,urlPicture,price,priceInt,elementid){
        let tr = document.createElement('tr');
        tr.classList.add('orderList', 'active');
        tr.dataset.price = priceInt;
        tr.dataset.quantity = 1;
        tr.dataset.lineid = 0;
        tr.dataset.elementid = elementid;
        let nameTr = document.createElement('td');
        let urlPictureTr = document.createElement('td');
        let priceTr = document.createElement('td');
        let countTr = document.createElement('td');
        let totalPriceTr = document.createElement('td');
        let remove = document.createElement('td');
        nameTr.innerHTML = name;
        urlPictureTr.innerHTML = "<img height='100px' src='"+  urlPicture + "'>";
        priceTr.innerHTML = price;
        countTr.innerHTML = `<input type="number" value="1" style="width: 35px;" min="1"  onkeyup="changeCount(this)" oninput="changeCount(this)">`;
        totalPriceTr.innerHTML = price;
        remove.innerHTML = `<a class="removeLine" href="javascript:void(0)" onclick="elementRemove(this)">Удалить</a>`;

        urlPictureTr.className = "productLine adm-list-table-cell adm-detail-valign-middle";
        nameTr.className = "productLine adm-list-table-cell adm-detail-valign-middle";
        priceTr.className = "productLine adm-list-table-cell adm-detail-valign-middle";
        countTr.className = "productLine adm-list-table-cell adm-detail-valign-middle";
        totalPriceTr.className = "productLine adm-list-table-cell adm-detail-valign-middle";
        remove.className = "productLine adm-list-table-cell adm-detail-valign-middle";

        tr.appendChild(urlPictureTr);
        tr.appendChild(nameTr);
        tr.appendChild(priceTr);
        tr.appendChild(countTr);
        tr.appendChild(totalPriceTr);
        tr.appendChild(remove);
        document.querySelector('#buttonAddBlock').before(tr)
        changeTotalPrice()
    }
    function elementAdd(id) {
        BX.ajax.post("/bitrix/tools/webcomp_get_element_json.php",{
                id: id
            },
            function (data) {
                let json = JSON.parse(data);
                createTr(json.NAME,json.DETAIL_PICTURE_VALUE.SRC,json.PRICE_VAL,json.PROPERTIES.PRICE.VALUE,json.ID)
            }
        )
    }
    function elementRemove(el) {
        el.parentElement.parentElement.classList.toggle("removed")
        el.parentElement.parentElement.classList.toggle("active")
        if (el.innerHTML === "<?=GetMessage("WEBCOMP_MARKET_SCRIPT_DELETE")?>") {
            el.innerHTML = "<?=GetMessage("WEBCOMP_MARKET_SCRIPT_RESTORE")?>";
        } else {
            el.innerHTML = "<?=GetMessage("WEBCOMP_MARKET_SCRIPT_DELETE")?>";
        }
        changeTotalPrice();
    }

    function changeCount(el) {
        el.parentElement.parentElement.dataset.quantity = el.value
        changeTotalPrice()
    }

    function changeTotalPrice() {
        let sum = 0;
        document.querySelectorAll(".orderList.active").forEach((el)=>{
            sum += parseFloat(el.dataset.price)*parseFloat(el.dataset.quantity);
        });
        document.querySelector('.totalSum').innerHTML = sum.toLocaleString('ru-RU')+" руб.";
        getJSONProducts(sum);
    }

    function getJSONProducts(sum){
        let cart = {
            items : [],
            itemsRemoved: []
        };
        cart.items = [];
        cart.itemsRemoved = [];
        document.querySelectorAll(".orderList.active").forEach((el)=>{
            let item = {};
            item.elementid = parseFloat(el.dataset.elementid);
            item.lineid = parseFloat(el.dataset.lineid);
            item.price = parseFloat(el.dataset.price);
            item.quantity = parseFloat(el.dataset.quantity);
            cart.items.push(item);
        });
        document.querySelectorAll(".orderList.removed").forEach((el)=>{
            let item = {};
            item.lineid = parseFloat(el.dataset.lineid);
            cart.itemsRemoved.push(item);
        });
        cart.total = sum;
        document.querySelector('input[name="UF_PRODUCTS_LIST_JSON"]').value = JSON.stringify(cart);
        return cart;
    }

    function hidePropJSON(){
        document.querySelector('#table_UF_PRODUCTS_LIST_JSON').parentElement.parentElement.style.display = 'none';
    }
    document.addEventListener("DOMContentLoaded", hidePropJSON);
</script>
