
$(".methodSelectionLogin a").on('click', function(e){
    if(!$(this).hasClass('active')){ //Если кнопка не активна, что-бы не делать повторных вещей если нажать нескольео раз
        $(".methodSelectionLogin a").removeClass('active'); //Удаляем активную
        $(this).addClass('active'); //Делаем текущую активной
        let name = $(this).data('btn');  //Получаем название поля с которым нужно выполнить действие

        $(".blockFields input").val(""); //Очищаем поля от вводов

        $("#login-form input").removeClass('is-valid').removeClass('is-invalid'); //Убираем маркера указывающие на ошибку
        $("#login-form .invalid-feedback").text(''); //Очищаем ощибки

        $("#login-form .blockFields .pairUsernamePhone input").attr("disabled", "disabled");
        $("#login-form .blockFields .pairUsernamePhone #loginform-" + name).removeAttr('disabled');
        $("#login-form .blockFields .pairUsernamePhone").css('display', 'none');

        $("#login-form .blockFields .field-loginform-" + name).css('display', 'block');

    }
    return false;

});




$("#login-form").on('beforeSubmit', function(e){
    ShowLoadingAnimation();

    $(".errorBigMessage").text("").css("display", "none");
    $(".blockFields .validate_error").text("").css("display", "none");
    $.ajax({
        url:     '/auth/login', //url страницы (action_ajax_form.php)
        type:     "POST", //метод отправки
        dataType: "html", //формат данных
        data: $(this).serialize(),  // Сеарилизуем объект
        success: function(response) {
            HideLoadingAnimation();
            //Данные отправлены успешно
            let result = $.parseJSON(response);
            //Проверяем что ответ является обьектом - значит есть ошибки
            if(result['success'] === true){

                if(result['login_referer'])  window.location.replace(result['login_referer']);

                else window.location.replace('/');


            }else{
                $.each(result['errors'],function(key,data) {
                    if(key === "unknown"){
                        $(".centerBlockForm #bigErrorMessage").text(data);
                    }else{
                        $("#loginform-" + key).addClass("is-invalid").attr('aria-invalid', 'true');
                        $(".field-loginform-" + key + " .invalid-feedback").text(data);
                    }

                });

            }

        },
        error: function() { // Данные не отправлены
            $(".centerBlockForm #errorBigMessage").text("Ошибка, попрубуйте перезагрузить страницу.");
        }
    });
    return false;
});


