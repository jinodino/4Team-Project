<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
/**
 * @description 
 * 
 * Mail Handler
 * 
 */

class RequestUserInfoController extends Controller
{
    protected function findId(Request $request){
        /** 
         * find User ID
         * 
         * @param  JSON > name , birth , mailAddr
         * @return String userID 
         */
       
    }

    protected function findPassword(Request $reuqest){
        /**
         *  @param JSON  > id, birth , mailAddr
         *  @return SendMail
         */

        
    }
}
