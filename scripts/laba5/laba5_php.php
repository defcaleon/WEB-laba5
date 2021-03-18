    <?php

        require_once('connectVars.php');
        echo "<link rel='stylesheet' href='styleCalendar.css' />";

        $date=$_POST["text"];
        $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

        $temp = new DateTime($date);
        echo "<h2>".$temp->format('Y-F')."</h2>";
        echo draw_calendar($temp->format('m'),$temp->format('y'));



    function draw_calendar($month,$year){
        /* Начало таблицы */
        $calendar = '<table cellpadding="0" cellspacing="0" class="calendar">';
        /* Заглавия в таблице */
        $headings = array('Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday');
        $calendar.= '<tr class="calendar-row"><td class="calendar-day-head">'.implode('</td><td class="calendar-day-head">',$headings).'</td></tr>';
        /* необходимые переменные дней и недель... */
        $running_day = date('w',mktime(0,0,0,$month,1,$year));
        $days_in_month = date('t',mktime(0,0,0,$month,1,$year));
        $days_in_this_week = 1;
        $day_counter = 0;
        $dates_array = array();
        /* первая строка календаря */
        $calendar.= '<tr class="calendar-row">';
        /* вывод пустых ячеек в сетке календаря */
        for($x = 0; $x < $running_day; $x++):
            $calendar.= '<td class="calendar-day-np"> </td>';
            $days_in_this_week++;
        endfor;
        /* дошли до чисел, будем их писать в первую строку */
        for($list_day = 1; $list_day <= $days_in_month; $list_day++):
            $calendar.= '<td class="calendar-day">';
            /* Пишем номер в ячейку */
            $calendar.= '<div class="day-number">'.$list_day.'</div>';
            /** ЗДЕСЬ МОЖНО СДЕЛАТЬ MySQL ЗАПРОС К БАЗЕ ДАННЫХ! ЕСЛИ НАЙДЕНО СОВПАДЕНИЕ ДАТЫ СОБЫТИЯ С ТЕКУЩЕЙ - ВЫВОДИМ! **/
            $calendar.= str_repeat('<p> </p>',2);

            $calendar.= '</td>';
            if($running_day == 6):
                $calendar.= '</tr>';
                if(($day_counter+1) != $days_in_month):
                    $calendar.= '<tr class="calendar-row">';
                endif;
                $running_day = -1;
                $days_in_this_week = 0;
            endif;
            $days_in_this_week++; $running_day++; $day_counter++;
        endfor;
        /* Выводим пустые ячейки в конце последней недели */
        if($days_in_this_week < 8):
            for($x = 1; $x <= (8 - $days_in_this_week); $x++):
                $calendar.= '<td class="calendar-day-np"> </td>';
            endfor;
        endif;
        /* Закрываем последнюю строку */
        $calendar.= '</tr>';
        /* Закрываем таблицу */
        $calendar.= '</table>';

        /* Все сделано, возвращаем результат */
        return $calendar;
    }

