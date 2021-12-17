{**
 * 2007-2020 PrestaShop SA and Contributors
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License 3.0 (AFL-3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/AFL-3.0
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to https://www.prestashop.com for more information.
 *
 * @author    PrestaShop SA <contact@prestashop.com>
 * @copyright 2007-2020 PrestaShop SA and Contributors
 * @license   https://opensource.org/licenses/AFL-3.0 Academic Free License 3.0 (AFL-3.0)
 * International Registered Trademark & Property of PrestaShop SA
 *}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tiny-slider/2.9.4/tiny-slider.css">
<!--[if (lt IE 9)]><script src="https://cdnjs.cloudflare.com/ajax/libs/tiny-slider/2.9.4/min/tiny-slider.helper.ie8.js"></script><![endif]-->


<section>
    <h1 class="text-center">{l s='New Products' d='Modules.Scnewproducts.Shop'}</h1>
    <div id="controls-tiny-slider">
        <button id="recule"><img src="https://img.icons8.com/ios/50/000000/back.png" /></button>
        <button id="avance"><img src="https://img.icons8.com/ios/50/000000/forward.png" /></button>
    </div>
    <div class="products tiny-slider">
        {foreach from=$products item="product"}
            {include file="catalog/_partials/miniatures/product.tpl" product=$product}
        {/foreach}
    </div>
</section>

<script src="https://cdnjs.cloudflare.com/ajax/libs/tiny-slider/2.9.2/min/tiny-slider.js"></script>
<!-- NOTE: prior to v2.2.1 tiny-slider.js need to be in <body> -->

<script>
    var slider = tns({
        container: '.tiny-slider',
        items: 2,
        "responsive": {
            "350": {
                "items": 2,
                "controls": true,
                "gutter": 90,
            },
            "900": {
                "items": 3,
                "gutter": 10,
            },
            "1200": {
                "items": 4,
                "gutter": 10,
            },
        },
        "gutter": 10,
        "swipeAngle": false,
        autoplay: true,
        autoplayButtonOutput: false,
        "nav": false, 
        "prevButton": "#recule",
        "nextButton": "#avance",
        "fixedWidth": 250,
    });
</script>