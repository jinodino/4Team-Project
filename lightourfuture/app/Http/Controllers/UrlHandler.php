<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Http\Request;

class UrlHandler extends Controller
{   
    // 복호화
    public function decryptdate($secretdate){
        //$expire_date = decrypt($secretdate);
        $newEncrypter = new \Illuminate\Encryption\Encrypter(config('app.mkey'), config('app.cipher'));
        $expire_date = $newEncrypter->decrypt($secretdate);
        return $expire_date;
    }

    // 현 시각 날짜 
    public function setnowDate(){
        $date = date("Y-m-d H:i:s");
        return $date; 
    }
    
    /**
     * @return Boolean Url ExpireDate's validation 
     */
    public function confirmExpireDate($secretdate, $classification){
      
        $now_date    = $this->setnowDate();
        
        $expire_date = $this->decryptdate($secretdate);
        $expire_date = $this->decryptdate($expire_date);
        //return $this->decryptdate($expire_date);
        if($expire_date > $now_date) return 1;
        else return;
    }

    // 분실 비밀번호 찾기 vue
    public function pwExpireDate($secretdate){
      
        $now_date    = $this->setnowDate();
        
        $expire_date = $this->decryptdate($secretdate);

        if($expire_date > $now_date) return $classification;
        else return;
    }
}
