<?php
namespace Operation\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class LinuxStatusController extends AbstractActionController
{
    protected $operationTable;
    public function indexAction() {
        //检查文件系统的磁盘空间占用情况 df命令
        exec('/bin/df -Ph | awk \'BEGIN {OFS=","} {print $1,$2,$3,$4,$5,$6}\'', $result);
        $data1 = array();
        $x = 0;
        foreach ($result as $a) {
            if ($x==0) {
                $x++;
                continue;
            }
            $data1[] = explode(',', $result[$x]);
//          unset($result[$x], $a);
            $x++;
        }
        //查看已安装软件安装位置
         if (file_exists("monitor"))
        {
            $data = file_get_contents("monitor");
            $binaries = preg_split('~ ~', $data, NULL, PREG_SPLIT_NO_EMPTY);
        }
        // If file doesn't exist then use hard coded list
        else
        {
            $binaries = explode(" ", "mysql php python apache2 nginx ");
        }
        $path = 'PATH=/usr/local/sbin:/usr/sbin:/sbin:/usr/local/bin:/usr/bin:/bin:' . getenv('PATH');
        $data = array();
        foreach ($binaries as $b) {
            $which = array();
            exec($path . ' command -v ' . escapeshellarg($b), $which, $return_var);
            $data[] = array($b, $return_var ? "Not Installed" : $which[0]);
        }
        
        return new ViewModel(
                    array(
                        'data1' => $data1,
                        'data'=>$data,
                   )
                );
    }
    
    public function generalInfoAction(){
        $str = explode(" ", implode(" ",@file("/proc/uptime")));
        $str = trim($str[0]);
        $min = $str / 60;
        $hours = $min / 60;
        $days = floor($hours / 24);
        $hours = floor($hours - ($days * 24));
        $min = floor($min - ($days * 60 * 24) - ($hours * 60));
        if ($days !== 0) $res['uptime'] = $days." days ";
        if ($hours !== 0) $res['uptime'] .= $hours." hours ";
        $res['uptime'] .= $min." minutes";
        $uptime=$res['uptime'];
        return new ViewModel(
                array(
                    'uptime' => $uptime,
               )
        );
    }
    public function ramAction(){
        exec('/usr/bin/free -tmo | /usr/bin/awk \'BEGIN {OFS=","} {print $1,$2,$3-$6-$7,$4+$6+$7}\'', $result);
        $res = explode(',', $result[1]);
        $a=floor($res[2]/$res[1]*100);
        return new ViewModel(
                array(
                    'res' => $res,
                    'a'=>$a,
               )
        );
    }
    
    public function diskUsageAction(){
        exec('/bin/df -Ph | awk \'BEGIN {OFS=","} {print $1,$2,$3,$4,$5,$6}\'', $result);
        $data1 = array();
        $x = 0;
        foreach ($result as $a) {
            if ($x==0) {
                $x++;
                continue;
            }
            $data1[] = explode(',', $result[$x]);
            unset($result[$x], $a);
            $x++;
        }
        return new ViewModel(
                array(
                    'data1' => $data1,
               )
        );
    }
    
    public function softwareAction(){
        if (file_exists("monitor"))
        {
            $data = file_get_contents("monitor");
            $binaries = preg_split('~ ~', $data, NULL, PREG_SPLIT_NO_EMPTY);
        }
        // If file doesn't exist then use hard coded list
        else
        {
            $binaries = explode(" ", "php node mysql mongo vim python ruby java apache2 nginx openssl vsftpd make");
        }

        $path = 'PATH=/usr/local/sbin:/usr/sbin:/sbin:/usr/local/bin:/usr/bin:/bin:' . getenv('PATH');
        $data = array();
        foreach ($binaries as $b) {
            $which = array();
            exec($path . ' command -v ' . escapeshellarg($b), $which, $return_var);
            $data[] = array($b, $return_var ? "Not Installed" : $which[0]);
        }
        return new ViewModel(
                    array(
                        'data'=>$data,
                   )
       );
    }
    
    public function usersAction(){
        exec('/usr/bin/awk -F: \'{ if ($3<=499) print "system,"$1","$6;' .' else print "user,"$1","$6; }\' < /etc/passwd',$result);
//        print_r($result);
        $data = array();
        $x = 0;
        foreach ($result as $a) {
            $x++;
            $line = explode(',', $a);
            if ($line[0] == '#') {
                continue;
            }
            $data[] = $line;
        }
        return new ViewModel(
                    array(
                        'data'=>$data,
                   )
       );
    }
    
    public function onlineAction(){
        $users = array();
        // change username column length for w command
        putenv("PROCPS_USERLEN=20");
        exec('PROCPS_FROMLEN=40 /usr/bin/w -h |' .' /usr/bin/awk \'{print $1","$2","$3","$4","$5","$6","$7}\'',$users );
        $result = array();
        foreach ($users as $user) {
            $result[] = explode(",", $user);
        }
        return new ViewModel(
            array(
                'result'=>$result,
           )
       );
    }
    
    public function lastLoginAction(){
        exec( '/usr/bin/lastlog --time 365 |' . ' /usr/bin/awk \'{print $1","$3","$4" "$5" "$6" "$7" "$8}\'',$users);
//        print_r($users);echo "<br>";
        $result = array();
        # ignore the first line of column names
        for ($i = 1; $i < count($users); $i++) {
            $result[$i-1] = explode(",", $users[$i]);
        }
//        print_r($result);
        return new ViewModel(
            array(
                'result'=>$result,
           )
       );
    }
    
    public function processesAction(){
       exec( '/bin/ps aux | /usr/bin/awk ' ."'NR>1{print ".'$1","$2","$3","$4","$5","$6","$7","$8","$9","$10","$11'."}'", $result);
//       print_r($result);
       $data = array();
       $x = 0;
       foreach ($result as $a) {
           $data[] = explode(',', $result[$x]);
           unset($result[$x],$a);
           $x++;
       }
       return new ViewModel(
            array(
                'data'=>$data,
           )
       );
    }
}
?>
