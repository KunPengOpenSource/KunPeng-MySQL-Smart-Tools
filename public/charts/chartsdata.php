 <?php
    $id = $_GET['id'];
    $tid = $_GET['tid'];
    include("data.php");
    date_default_timezone_set("Asia/Shanghai");
    $time = array();
    $option = array();
    $od = array();
    foreach ($options as $value) {
        $option[$value] = "[";
    }
    $i = 0;
    foreach ($options as $key => $value) {
        $od[$key] = 0;
    }
    $row = mysql_fetch_assoc($result);
    while ($row = mysql_fetch_assoc($result)) {
        if($i < $space){
            foreach ($options as $key => $value) {
                $od[$key] += $row[$value];
            }
        }
        if($i == $space){
            foreach ($options as $key => $value) {
                $option[$value] .= "[\"" . date('Y-m-d H:i', strtotime($row['create_time'])) . "\"," . round($od[$key]/$space,'2') ."],";
            }
            $i=0;
            foreach ($options as $key => $value) {
                $od[$key] = $row[$value];
            }
            $time[] = $row['create_time'];
        }
        $i++;
    }
    foreach ($options as $key => $value) {
        $option[$value] .= "]";
    }
    $str = "[";
    foreach ($options as $key => $value) {
        $str .= str_replace("],]", "]]", $option[$value]) . ",";
    }
    $str = $str . "]";
    $str = str_replace("]],]", "]]]", $str);