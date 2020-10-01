<!DOCTYPE html>
<html lang="en">    
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Document</title>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <style type="text/css">
            @import url('https://fonts.googleapis.com/css2?family=Roboto:wght@500&display=swap');
            body{
                font-family: 'Roboto', sans-serif;
                background-color: #e2e2e2;
            }
            .container {
                width: 600px;
                margin: 0 auto;
                background-color: #efefef;
                padding: 15px;
            }
            table {
                border-spacing: 0px;
            }
            th {
                padding: 10px;
                background-color: #e2e2e2;
                border: gray solid 1px;
            }
            td {
                padding: 10px;
                background-color: #e2e2e2;
                text-align: center;
                border: gray solid 0.00005em;
            }
            td:focus {
                background-color: #30ad60;
            }
            td:hover, td:active {
                color: white;
                background-color: #30ad60;
            }
            .close {
                color: white;
                background-color: #ff371e;
            }
            .order {
                background-color: #30ad60;
            }
            input[type = "checkbox"]{
                display: none;
            }
            h1{
                text-align: center;
                text-transform: uppercase;
                font-weight: 100;
            }
            .red{
                background-color:#ff371e;
                width: 40px;
                height: 40px;
            }
            .green{
                background-color:#30ad60;
                width: 40px;
                height: 40px;
            }
            .description{
                display:flex;
                justify-content: center;
                align-items: center;
                margin-bottom: 20px;
            }
            .table{
                display: flex;
                justify-content: space-between;
                align-items: center;
            }
            button{
                display: none;
                padding: 10px;
                font-size: 15px;
                margin: 0 auto;
                /* background: linear-gradient(to top, #30ad60 60%, #fff); */
                border:none;
                border-radius: 5px;
                background-color: #30ad60;
                color:#fff;
                
            }
            span{
                display: none;
                color: #30ad60;
            }
            @media (max-width: 600px){
                .table{
                    flex-direction: column;
                }
            }
        </style>
    </head>
    <body>
        <?php
            $filename = 'array.txt';
            // массив с днями каллендаря
            $sep = [28,29,30,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,1];
            // Проверяем существует ли POST['order'] запрос, если нет то берем данные из файла
            if(isset($_POST['order'])){
                // Post order - ajax
                $data = $_POST['order'];
                $array = file_get_contents($filename); // Чтение файла на сервере 
                $data2 = unserialize($array); // Конвертируем в значение PHP
                empty($array) ? $result = $data : $result = array_merge($data,$data2); // проверка на пустой массив
                array_unique($result);
                echo print_r($result, 'true');
                $data2 = serialize($result);      // PHP формат сохраняемого значения.
                file_put_contents($filename, $data2); // Сохраняем на сервер
                $order = $result;
            } else {
                $data2 = file_get_contents($filename);
                $order = unserialize($data2);
            }
            
        ?>
        <div class="container">
        <h1>Система бронирования</h1>
        <div class="description">
            <div class="red"></div>&nbsp- забронирован&nbsp
            <div class="green"> </div>&nbsp- выбран&nbsp
        </div>
        <div class="table">
            <table>
                <tr>
                    <th>Пн</th>
                    <th>Вт</th>
                    <th>Ср</th>
                    <th>Чт</th>
                    <th>Пт</th>
                    <th>Сб</th>
                    <th>Вс</th>
                </tr>
                <?php
                // вывод календаря 
                    $i = 0;
                    foreach($sep as $day){
                        
                        if($i % 7 == 0){
                            $i = 0;
                            echo '<tr>';
                            if (!empty($order)){
                                if(in_array($day, $order)){
                                    echo '<td class="close">' . $day . '</td>';
                                } else {
                                    echo '<td>' . $day . '</td>';
                                }
                            } else {
                                echo '<td>' . $day . '</td>';
                            }
                        } else {
                            if(!empty($order)){
                                if(in_array($day, $order)){
                                    echo '<td class="close">' . $day . '</td>';
                                } else {
                                    echo '<td>' . $day . '</td>';
                                }
                            } else {
                                echo '<td>' . $day . '</td>';
                            }
                        }
                        $i++;
                    }
                ?>
            </table>
        <button id="ajax">Забронировать</button>
        <span>Бронировение успешно завершено<span>
        </div>
        </div>
        
    </body>
    <script type="text/javascript">
        let order = [];
        $("td").on("click", function(){
            var d = parseInt($(this).html());
            var index = $.inArray(d, order);
            if(!$(this).hasClass('close') && !$(this).hasClass('order')){
                $("#ajax").css({"display" : "block"});
                $(this).addClass('order');
                order.push(d);
            } else {
                if(index != -1){
                    order.splice(index, 1);
                    $(this).removeClass('order');
                }
            }
        });

        $("#ajax").on("click", function(){
            $.ajax({
                type: "post",
                cahce: false,             
                data:  {order},
                success: function(){
                    $("#ajax").css({"display" : "none"});
                    $("span").css({"display" : "block"});
                },
                error: function(){
                    console.log('Ошибка');
                }
            });
        });
    </script>
</html>