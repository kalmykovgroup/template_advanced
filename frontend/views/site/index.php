<?php

/** @var yii\web\View $this */

$this->title = 'Строй-Хоз-Маг';
?>
<style>

    .line{
        width: 100%;
        height: 5px;
        background: #218100;
        margin-bottom: 10px;
    }

    .categories{
        width: 100%;
        position: relative;
        display: flex;
        flex-direction: row;
        flex-wrap: wrap;
    }
    .category{
        color: black;
        height: 30px;
        padding: 5px 10px;
        border-radius: 5px;
        display: flex;
        justify-content: center;
        align-items: center;
        margin: 0 4px;
        font-weight: 500;
        border: 1px solid #9f9f9f;
        box-shadow: 0 0 5px rgb(33, 129, 0);
        background: #ffffff;
    }

    .category:hover{
        background: #ddffdd;
    }


</style>
<div class="line"></div>
<div class="container">
    <div class="site-index">
        <div class="body-content">

            <div class="categories">
                    <a href="#" class="category category_tool">Инструмент</a>
                    <a href="#" class="category category_chemistry">Бытовая химия</a>
                    <a href="#" class="category category_wash">Для стирки</a>
                    <a href="#" class="category category_interior">Предметы интерьера</a>
                    <a href="#" class="category category_tool">Услуги мастера</a>
                    <a href="#" class="category category_tool">Сантехника</a>
                    <a href="#" class="category category_tool">Электрика</a>
                    <a href="#" class="category category_tool">Краски</a>
                    <a href="#" class="category category_tool">Для детей</a>
            </div>
        </div>

    </div>
</div>

