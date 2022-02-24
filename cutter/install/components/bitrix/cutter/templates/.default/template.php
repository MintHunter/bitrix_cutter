<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true)die();
?>


<form action="" type="POST" id="cutter_form">
    <input name="urlStr" id="urlStr">
    <button>Создать короткий урл</button>
</form>
<div id="new_str" style="display: none"></div>

<table style="display: none" id="cutterTable">
    <thead>
    <tr>
        <th>ID</th>
        <th>Сокращенная ссылка</th>
        <th>Полная ссылка</th>
        <th>Количество переходов</th>
    </tr>
    </thead>
    <tbody id="tbody_table">

    </tbody>
</table>

<script src="https://yastatic.net/jquery/3.3.1/jquery.min.js"></script>

<script type="text/javascript">
    $(document).ready(function() {
        $('#cutter_form').on('submit', function(e){
            e.preventDefault();
            if($('#urlStr').val() == ''){
                $('#urlStr').val(location.href)
            }else{
                $('#new_str').text('');
                $.ajax({
                    url: '/',
                    method: 'post',
                    dataType: 'text',
                    data: {
                        urlStr: $('#urlStr').val(),
                    },
                    success: function(data){

                        if(data ===''){
                            $('#new_str').css({'display':''});
                            $('#new_str').text('Ссылка не должна содержать только домен');
                        }else {
                            var parsedJson = JSON.parse(data);
                            $('#new_str').css({'display':''});
                            $('#cutterTable').css({'display':''});
                            $('#new_str').text('Ваша ссылка: '+ parsedJson.new_str);
                            console.log("форма успешно отправлена")
                            $(parsedJson.result).each(function (){
                                $('#tbody_table').prepend("<tr><td>"+this.ID+"</td><td><a href='"+this.URL_CODE+"' target='_blank' '>"+this.SHORT_URL_CODE+"</a></td><td>"+this.URL_CODE+"</td><td>"+this.NUMBER_OF_USED+"</td></tr>")
                            })
                        }



                    }
                });
            }
        });
        $('#tbody_table').on('click','a',function (e){
            $.ajax({
                url: '/',
                method: 'post',
                dataType: 'text',
                data: {
                    update: $( this ).text(),
                },
                success: function(data){
                    if(data ===''){
                        $('#new_str').css({'display':''});
                        $('#new_str').text('Что то пошло не так...');
                    }else {
                        var parsedJson = JSON.parse(data);
                        $('#tbody_table').find('tr').remove();
                        $(parsedJson.result).each(function (){
                            $('#tbody_table').prepend("<tr><td>"+this.ID+"</td><td><a href='"+this.URL_CODE+"' target='_blank' '>"+this.SHORT_URL_CODE+"</a></td><td>"+this.URL_CODE+"</td><td>"+this.NUMBER_OF_USED+"</td></tr>")
                        })
                    }
                }
            });
        })


    });
</script>
