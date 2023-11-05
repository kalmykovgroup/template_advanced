
$(".methodSelectionLogin a").on('click', function(e){
    if(!$(this).hasClass('active')){ //Если кнопка не активна, что-бы не делать повторных вещей если нажать нескольео раз
        $(".methodSelectionLogin a").removeClass('active'); //Удаляем активную
        $(this).addClass('active'); //Делаем текущую активной
        let name = $(this).data('btn');  //Получаем название поля с которым нужно выполнить действие


        $("#login-form input").removeClass('is-valid').removeClass('is-invalid').val(""); //Убираем маркера указывающие на ошибку
        $("#login-form .help-block").text(''); //Очищаем ощибки

        $("#login-form .blockFields input").attr("disabled", "disabled");
         $("#login-form .blockFields #loginform-" + name).removeAttr('disabled');

        $("#login-form .blockFields .form-group").css('display', 'none');

        $("#login-form .blockFields  .field-loginform-" + name).css('display', 'block');

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

            if(result['success'] !== undefined){

             window.location.replace(result['success']);

            }else if(result['errors'] !== undefined){


                $.each(result['errors'],function(key,data) {
                    let model = $("#loginform-" + key);
                    if(model !== undefined){
                        model.addClass("is-invalid").attr('aria-invalid', 'true');
                        $(".field-loginform-" + key + " .invalid-feedback").text(data);
                    }else{
                        $(".centerBlockForm #bigErrorMessage").text(data);
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


