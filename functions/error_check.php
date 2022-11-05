<?php 

function check_error($string, $arr){
	if(array_key_exists($string, $arr)) {
        print $arr[$string];
    }else {
        return;
    }
}
